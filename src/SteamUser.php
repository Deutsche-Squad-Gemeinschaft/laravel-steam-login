<?php

namespace skyraptor\LaravelSteamLogin;

use Illuminate\Support\Fluent;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Response;
use SteamID;

/**
 * @property string steamId
 * @property string steamId2
 * @property string steamId3
 * @property string accountUrl
 * @property string profileDataUrl
 * @property int accountId
 * @property string name
 * @property string avatar
 */
class SteamUser extends Fluent
{
    /**
     * Steam Community URL using 64bit steamId.
     *
     * @var string
     */
    const STEAM_PROFILE = 'https://steamcommunity.com/profiles/%s';

    /**
     * Steam Community URL using custom id.
     *
     * @var string
     */
    const STEAM_PROFILE_ID = 'https://steamcommunity.com/id/%s';

    /**
     * Steam API GetPlayerSummaries URL.
     *
     * @var string
     */
    const STEAM_PLAYER_API = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=%s&steamids=%s';

    /**
     * personaStates.
     */
    protected static array $personaStates = [
        'Offline',
        'Online',
        'Busy',
        'Away',
        'Snooze',
        'Looking to trade',
        'Looking to play',
    ];

    /**
     * Profile data retrieval method to use.
     *
     * @var string
     */
    protected string $method = 'xml';

    /**
     * URL to use when retrieving a user's profile.
     *
     * @var string
     */
    protected string $profileDataUrl;

    /**
     * xPaw instance.
     *
     * @var \SteamID
     */
    protected SteamID $xPawSteamId;

    /**
     * Guzzle instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected GuzzleClient $guzzle;

    /**
     * Guzzle response.
     *
     * @var \GuzzleHttp\Psr7\Response
     */
    protected Response $response;

    /**
     * SteamUser constructor. Extends SteamID and constructs that first.
     *
     * @param string|int        $steamId
     * @param GuzzleClient|null $guzzle
     */
    public function __construct($steamId, GuzzleClient $guzzle = null)
    {
        if (PHP_INT_SIZE !== 8) {
            trigger_error('64 bit PHP is required to handle SteamID conversions', E_USER_ERROR);
        }

        $xPawSteamId = new SteamID($steamId);

        $this->attributes['steamId'] = $xPawSteamId->ConvertToUInt64();
        $this->attributes['steamId2'] = $xPawSteamId->RenderSteam2();
        $this->attributes['steamId3'] = $xPawSteamId->RenderSteam3();
        $this->attributes['accountId'] = $xPawSteamId->GetAccountID();
        $this->attributes['accountUrl'] = sprintf(self::STEAM_PROFILE, $this->attributes['steamId']);
        $this->attributes['profileDataUrl'] = sprintf(self::STEAM_PROFILE.'/?xml=1', $this->attributes['steamId']);

        unset($xPawSteamId);

        parent::__construct($this->attributes);

        $this->guzzle = $guzzle ?? new GuzzleClient();
        $this->method = config('steam-login.method', 'xml') === 'api' ? 'api' : 'xml';
        $this->profileDataUrl = $this->method === 'xml' ? $this->attributes['profileDataUrl'] : sprintf(self::STEAM_PLAYER_API, config('steam-login.api_key'), $this->attributes['steamId']);
    }

    /**
     * magic method __toString using Fluent toJson().
     *
     * @return string
     */
    public function __toString() : string
    {
        return $this->toJson();
    }

    /**
     * Retrieve a user's steam info set its attributes.
     *
     * @return $this
     */
    public function getUserInfo() : self
    {
        $this->userInfo();

        return $this;
    }

    /**
     * Retrieve a user's profile info from Steam via API or XML data.
     */
    private function userInfo() : void
    {
        $this->response = $this->guzzle->get($this->profileDataUrl, ['connect_timeout' => config('steam-login.timeout')]);
        $body = $this->response->getBody()->getContents();

        switch ($this->method) {
            case 'api':
                $data = $this->parseApiProfileData($body);
                break;
            case 'xml':
                $data = $this->parseXmlProfileData($body);
                break;
            default:
                $data = [];
                break;
        }

        $this->attributes = array_merge($this->attributes, $data);
    }

    
    public static function userInfoBulk(array $steamIds) : void
    {
        /* Initialize new GuzzleClient */
        $guzzle = new GuzzleClient();

        /* Chunk the provided steamUsers, maximum of 100 */
        $chunks = array_chunk($steamIds, 100);

        foreach ($chunks as $chunk) {
            $url = sprintf(self::STEAM_PLAYER_API, config('steam-login.api_key'), implode(',', $chunk));
            $response = $guzzle->get($url);
        }
    }

    /**
     * Return Guzzle response of retrieving player's profile data.
     *
     * @return Response
     */
    public function getResponse() : Response
    {
        return $this->response;
    }

    /**
     * Parse API response data.
     *
     * @param string $body
     * @return array
     */
    protected static function parseApiProfileData(string $body) : array
    {
        $json = @json_decode($body, true);
        $json = isset($json['response']['players'][0]) ? $json['response']['players'][0] : null;

        if (empty($body) || $json === null || json_last_error() !== JSON_ERROR_NONE) {
            return [];
        }

        return [
            'name'            => $json['personaname'],
            'realName'        => $json['realname'] ?? null,
            'profileUrl'      => $json['profileurl'],
            'isPublic'        => $json['communityvisibilitystate'] === 3,
            'privacyState'    => $json['communityvisibilitystate'] === 3 ? 'Public' : 'Private',
            'visibilityState' => $json['communityvisibilitystate'],
            'isOnline'        => !in_array($json['personastate'], [0, 4]),
            'onlineState'     => isset($json['gameid']) ? 'In-Game' : (!in_array($json['personastate'], [0, 4]) ? 'Online' : 'Offline'),
            'joined'          => $json['timecreated'] ?? null,
            'avatarIcon'      => $json['avatar'],
            'avatarSmall'     => $json['avatar'],
            'avatarMedium'    => $json['avatarmedium'],
            'avatarFull'      => $json['avatarfull'],
            'avatarLarge'     => $json['avatarfull'],
            'avatar'          => $json['avatarfull'],
        ];
    }

    /**
     * Parse XML response data
     *
     * @param string $body
     * @return array
     */
    protected static function parseXmlProfileData(string $body) : array
    {
        $xml = simplexml_load_string($body, 'SimpleXMLElement', LIBXML_NOCDATA);

        if (empty($body) || $xml === false || isset($xml->error)) {
            return [];
        }

        return [
            'name'            => (string) $xml->steamID,
            'realName'        => isset($xml->realName) ? (string) $xml->realName : null,
            'profileUrl'      => 'https://steamcommunity.com/'.(isset($xml->customURL) ? 'id' : 'profiles').'/'.(isset($xml->customURL) ? $xml->customURL : $xml->steamID64),
            'isPublic'        => $xml->privacyState === 'public',
            'privacyState'    => $xml->privacyState === 'public' ? 'Public' : 'Private',
            'visibilityState' => (int) $xml->visibilityState,
            'isOnline'        => $xml->onlineState !== 'offline',
            'onlineState'     => $xml->onlineState === 'in-game' ? 'In-Game' : ucfirst($xml->onlineState),
            'joined'          => isset($xml->memberSince) ? strtotime($xml->memberSince) : null,
            'avatarIcon'      => (string) $xml->avatarIcon,
            'avatarSmall'     => (string) $xml->avatarIcon,
            'avatarMedium'    => (string) $xml->avatarMedium,
            'avatarFull'      => (string) $xml->avatarFull,
            'avatarLarge'     => (string) $xml->avatarFull,
            'avatar'          => (string) $xml->avatarFull,
        ];
    }
}
