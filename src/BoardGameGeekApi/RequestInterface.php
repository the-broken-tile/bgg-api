<?php

namespace TheBrokenTile\BoardGameGeekApi;

interface RequestInterface
{
    public const TYPE_THING = 'thing';
    public const TYPE_SEARCH = 'search';
    public const TYPE_USER = 'user';
    public const TYPE_COLLECTION = 'collection';

    public const PARAM_PAGE = 'page';
    /**
     * For things (games):
     *  Specifies the id of the thing(s) to retrieve. To request multiple things with a single query,
     *  NNN can specify a comma-delimited list of ids.
     * For collection:
     *  Filter collection to specifically listed item(s). NNN may be a comma-delimited list of item ids.
     */
    public const PARAM_ID = 'id';
    public const PARAM_STATS = 'stats';
    public const PARAM_QUERY = 'query';
    public const PARAM_EXACT = 'exact';
    public const PARAM_TYPE = 'type';

    public const PARAM_USER_NAME = 'name';
    public const PARAM_USER_BUDDIES = 'buddies';
    public const PARAM_USER_GUILDS = 'guilds';
    public const PARAM_USER_TOP = 'top';
    public const PARAM_USER_HOT = 'hot';

    public const PARAM_BOARD_GAME = 'boardgame';

    public const PARAM_COLLECTION_USERNAME = 'username';
    public const PARAM_COLLECTION_VERSION = 'version';
    public const PARAM_COLLECTION_BRIEF = 'brief';
    public const PARAM_COLLECTION_FILTER_OWN = 'own';
    public const PARAM_COLLECTION_FILTER_RATED = 'rated';
    public const PARAM_COLLECTION_FILTER_PLAYED = 'played';
    public const PARAM_COLLECTION_FILTER_COMMENT = 'comment';
    public const PARAM_COLLECTION_FILTER_FOR_TRADE = 'trade';
    public const PARAM_COLLECTION_FILTER_WANT = 'want';
    public const PARAM_COLLECTION_FILTER_WISHLIST = 'whishlist';
    public const PARAM_COLLECTION_FILTER_WISHLIST_PRIORITY = 'wishlistpriority';
    public const PARAM_COLLECTION_FILTER_PRE_ORDERED = 'preordered';
    public const PARAM_COLLECTION_FILTER_WANT_TO_PLAY = 'wanttoplay';
    public const PARAM_COLLECTION_FILTER_WANT_TO_BUY = 'wanttobuy';
    public const PARAM_COLLECTION_FILTER_PREVIOUSLY_OWNED = 'prevowned';
    public const PARAM_COLLECTION_FILTER_HAS_PARTS = 'hasparts';
    public const PARAM_COLLECTION_FILTER_WANTS_PARTS = 'wantsparts';
    /**
     * ?minrating=[1-10]
     * Filter on minimum personal rating assigned for that item in the collection.
     */
    public const PARAM_COLLECTION_FILTER_MIN_RATING = 'minrating';

    /**
     * ?rating=[1-10]
     * Filter on maximum personal rating assigned for that item in the collection. [Note: Although you'd expect it to be maxrating, it's rating.]
     */
    public const PARAM_COLLECTION_FILTER_RATING = 'rating';

    /**
     * ?minbggrating=[1-10]
     * Filter on minimum BGG rating for that item in the collection. Note: 0 is ignored... you can use -1 though,
     * for example min -1 and max 1 to get items w/no bgg rating.
     */
    public const PARAM_COLLECTION_FILTER_MIN_BGG_RATING = 'minbggrating';

    /**
     * ?bggrating=[1-10]
     * Filter on maximum BGG rating for that item in the collection. [Note: Although you'd expect it to be maxbggrating, it's bggrating.]
     */
    public const PARAM_COLLECTION_FILTER_BGG_RATING = 'bggrating';

    /**
     * ?minplays=NNN
     * Filter by minimum number of recorded plays.
     */
    public const PARAM_COLLECTION_MIN_PLAYS = 'minplays';

    /**
     * ?maxplays=NNN
     * Filter by maximum number of recorded plays.
     * [Note: Although the two maxima parameters above lack the max part, this one really is maxplays.]
     */
    public const PARAM_COLLECTION_MAX_PLAYS = 'maxplays';

    /**
     * Filter to show private collection info. Only works when viewing your own collection and you are logged in.
     * This will probably never be implemented here
     */
    public const PARAM_COLLECTION_SHOW_PRIVATE = 'showprivate';

    /**
     * ?collid=NNN
     * Restrict the collection results to the single specified collection id. Collid is returned in the results of normal queries as well.
     */
    public const PARAM_COLLECTION_COLLECTION_ID = 'collid';

    /**
     * ?modifiedsince=YY-MM-DD
     * Restricts the collection results to only those whose status (own, want, fortrade, etc.)
     * has changed or been added since the date specified (does not return results for deletions).
     * Time may be added as well: modifiedsince=YY-MM-DD%20HH:MM:SS
     */
    public const PARAM_COLLECTION_MODIFIED_SINCE = 'modifiedsince';

    public function getType(): string;

    /** @return array<string, bool|float|int|string|null> */
    public function getParams(): array;
}