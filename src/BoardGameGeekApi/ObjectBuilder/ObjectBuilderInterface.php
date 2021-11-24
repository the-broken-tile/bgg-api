<?php

namespace TheBrokenTile\BoardGameGeekApi\ObjectBuilder;

use TheBrokenTile\BoardGameGeekApi\DataTransferObject\DataTransferObjectInterface;
use TheBrokenTile\BoardGameGeekApi\RequestInterface;

interface ObjectBuilderInterface
{
    public const ID = 'id';
    public const THUMBNAIL = 'thumbnail';
    public const IMAGE = 'image';
    public const NAME = 'name';
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';
    public const TOTAL_ITEMS = 'totalitems';
    public const TOTAL = 'total';
    public const LAST_MODIFIED = 'lastmodified';

    public const LINK = 'link';

    // These two differ in keys between collection API and game API
    public const STATS = 'stats';
    public const STATISTICS = 'statistics';
    public const AVERAGE = 'average';
    public const BAYESIAN_AVERAGE = 'bayesaverage';
    public const USERS_RATED = 'usersrated';
    public const STANDARD_DEVIATION = 'stddev';
    public const MEDIAN = 'median';

    public const OWNED = 'owned';
    public const TRADING = 'trading';
    public const WANTING = 'wanting';
    public const WISHING = 'wishing';
    public const NUMBER_OF_COMMENTS = 'numcomments';
    public const NUMBER_OF_WEIGHTS = 'numweights';
    public const AVERAGE_WEIGHT = 'averageweight';

    // These two differ in keys between collection API and game API
    public const RATING = 'rating';
    public const RATINGS = 'ratings';

    public const TYPE = 'type';
    public const VALUE = 'value';
    public const ITEM = 'item';
    public const ITEMS = 'items';
    public const RESULT = 'result';
    public const PUBLISH_DATE = 'pubdate';
    public const OBJECT_ID = 'objectid';
    public const OBJECT_TYPE = 'objecttype';
    public const SUB_TYPE = 'subtype';
    public const COLLECTION_ID = 'collid';

    public const SORT_INDEX = 'sortindex';

    public const YEAR_PUBLISHED = 'yearpublished';
    public const MIN_PLAYERS = 'minplayers';
    public const MAX_PLAYERS = 'maxplayers';
    public const PLAYING_TIME = 'playingtime';
    public const MIN_PLAY_TIME = 'minplaytime';
    public const MAX_PLAY_TIME = 'maxplaytime';
    public const MIN_AGE = 'minage';
    public const RANK = 'rank';

    public const POLL = 'poll';
    public const TOTAL_VOTES = 'totalvotes';
    public const NUMBER_OF_VOTES = 'numvotes';

    public const COLLECTION_STATUS = 'status';
    public const COLLECTION_OWN = 'own';
    public const COLLECTION_PREVIOUSLY_OWN = 'prevown';
    public const COLLECTION_FOR_TRADE = 'fortrade';
    public const COLLECTION_WANT = 'want';
    public const COLLECTION_WANT_TO_PLAY = 'wanttoplay';
    public const COLLECTION_WANT_TO_BUY = 'wanttobuy';
    public const COLLECTION_WISHLIST = 'wishlist';
    public const COLLECTION_PRE_ORDERED = 'preordered';
    public const COLLECTION_WISHLIST_PRIORITY = 'wishlistpriority';
    public const NUMBER_OF_PLAYS = 'numplays';
    public const COMMENT = 'comment';
    public const VERSION = 'version';

    public const USER = 'user';
    public const USER_FIRST_NAME = 'firstname';
    public const USER_LAST_NAME = 'lastname';
    public const USER_AVATAR_LINK = 'avatarlink';
    public const USER_YEAR_REGISTERED = 'yearregistered';
    public const USER_LAST_LOGIN = 'lastlogin';
    public const USER_STATE_OR_PROVINCE = 'stateorprovince';
    public const USER_COUNTRY = 'country';
    public const USER_WEB_ADDRESS = 'webaddress';
    public const USER_TRADE_RATING = 'traderating';
    public const USER_MARKET_RATING = 'marketrating';
    public const USER_ACCOUNT_XBOX = 'xboxaccount';
    public const USER_ACCOUNT_WII = 'wiiaccount';
    public const USER_ACCOUNT_PSN = 'psnaccount';
    public const USER_ACCOUNT_BATTLE_NET = 'battlenetaccount';
    public const USER_ACCOUNT_STEAM = 'steamaccount';
    public const USER_BUDDIES = 'buddies';
    public const USER_BUDDY = 'buddy';
    public const USER_GUILDS = 'guilds';
    public const USER_GUILD = 'guild';
    public const USER_TOP = 'top';

    public function supports(RequestInterface $request): bool;

    public function build(string $response): DataTransferObjectInterface;
}