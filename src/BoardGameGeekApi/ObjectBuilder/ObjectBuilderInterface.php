<?php

declare(strict_types=1);

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

interface ObjectBuilderInterface
{
    public const string ID = 'id';
    public const string THUMBNAIL = 'thumbnail';
    public const string IMAGE = 'image';
    public const string NAME = 'name';
    public const string TITLE = 'title';
    public const string DESCRIPTION = 'description';
    public const string TOTAL_ITEMS = 'totalitems';
    public const string TOTAL = 'total';
    public const string LAST_MODIFIED = 'lastmodified';

    public const string LINK = 'link';

    // These two differ in keys between collection API and game API
    public const string STATS = 'stats';
    public const string STATISTICS = 'statistics';
    public const string AVERAGE = 'average';
    public const string BAYESIAN_AVERAGE = 'bayesaverage';
    public const string USERS_RATED = 'usersrated';
    public const string STANDARD_DEVIATION = 'stddev';
    public const string MEDIAN = 'median';

    public const string OWNED = 'owned';
    public const string TRADING = 'trading';
    public const string WANTING = 'wanting';
    public const string WISHING = 'wishing';
    public const string NUMBER_OF_COMMENTS = 'numcomments';
    public const string NUMBER_OF_WEIGHTS = 'numweights';
    public const string AVERAGE_WEIGHT = 'averageweight';

    // These two differ in keys between collection API and game API
    public const string RATING = 'rating';
    public const string RATINGS = 'ratings';

    public const string TYPE = 'type';
    public const string VALUE = 'value';
    public const string ITEM = 'item';
    public const string ITEMS = 'items';
    public const string RESULT = 'result';
    public const string PUBLISH_DATE = 'pubdate';
    public const string OBJECT_ID = 'objectid';
    public const string OBJECT_TYPE = 'objecttype';
    public const string SUB_TYPE = 'subtype';
    public const string COLLECTION_ID = 'collid';

    public const string SORT_INDEX = 'sortindex';

    public const string YEAR_PUBLISHED = 'yearpublished';
    public const string MIN_PLAYERS = 'minplayers';
    public const string MAX_PLAYERS = 'maxplayers';
    public const string PLAYING_TIME = 'playingtime';
    public const string MIN_PLAY_TIME = 'minplaytime';
    public const string MAX_PLAY_TIME = 'maxplaytime';
    public const string MIN_AGE = 'minage';
    public const string RANKS = 'ranks';
    public const string RANK = 'rank';

    public const string POLL = 'poll';
    public const string TOTAL_VOTES = 'totalvotes';
    public const string NUMBER_OF_VOTES = 'numvotes';

    public const string COLLECTION_STATUS = 'status';
    public const string COLLECTION_OWN = 'own';
    public const string COLLECTION_PREVIOUSLY_OWN = 'prevowned';
    public const string COLLECTION_FOR_TRADE = 'fortrade';
    public const string COLLECTION_WANT = 'want';
    public const string COLLECTION_WANT_TO_PLAY = 'wanttoplay';
    public const string COLLECTION_WANT_TO_BUY = 'wanttobuy';
    public const string COLLECTION_WISHLIST = 'wishlist';
    public const string COLLECTION_PRE_ORDERED = 'preordered';
    public const string COLLECTION_WISHLIST_PRIORITY = 'wishlistpriority';
    public const string NUMBER_OF_PLAYS = 'numplays';
    public const string COMMENT = 'comment';
    public const string VERSION = 'version';

    public const string USER = 'user';
    public const string USER_FIRST_NAME = 'firstname';
    public const string USER_LAST_NAME = 'lastname';
    public const string USER_AVATAR_LINK = 'avatarlink';
    public const string USER_YEAR_REGISTERED = 'yearregistered';
    public const string USER_LAST_LOGIN = 'lastlogin';
    public const string USER_STATE_OR_PROVINCE = 'stateorprovince';
    public const string USER_COUNTRY = 'country';
    public const string USER_WEB_ADDRESS = 'webaddress';
    public const string USER_TRADE_RATING = 'traderating';
    public const string USER_MARKET_RATING = 'marketrating';
    public const string USER_ACCOUNT_XBOX = 'xboxaccount';
    public const string USER_ACCOUNT_WII = 'wiiaccount';
    public const string USER_ACCOUNT_PSN = 'psnaccount';
    public const string USER_ACCOUNT_BATTLE_NET = 'battlenetaccount';
    public const string USER_ACCOUNT_STEAM = 'steamaccount';
    public const string USER_BUDDIES = 'buddies';
    public const string USER_BUDDY = 'buddy';
    public const string USER_GUILDS = 'guilds';
    public const string USER_GUILD = 'guild';
    public const string USER_TOP = 'top';
    public const string USER_HOT = 'hot';

    public const string RANK_NAME = self::NAME;
    public const string RANK_FRIENDLY_NAME = 'friendlyname';
    public const string RANK_BAYESIAN_AVERAGE = 'bayesaverage';
    public const string RANK_TYPE = self::TYPE;

    public function supports(RequestInterface $request): bool;

    public function build(string $response, RequestInterface $request): DataTransferObjectInterface;
}
