<?php

require_once 'User.php';

class HelpTicketFilter
{
    /**
     * URL_PARAM is the HTTP GET parameter key that shall specify the filter
     * name to use.
     */
    const URL_PARAM = "ticket_filter";

    /**
     * MINE surfaces tickets where you are the submitter and the ticket is
     * currently open.
     *
     * This filter may be accessed by non-administrative users because it only
     * reveals the details of their own tickets.
     */
    const MINE = "mine_open";
    /**
     * MINE_CLOSED surfaces tickets where you are the submitter and the ticket
     * has already been closed.
     *
     * This filter may be accessed by non-administrative users because it only
     * reveals the details of their own tickets.
     */
    const MINE_CLOSED = "mine_closed";

    /**
     * UNASSIGNED surfaces tickets that do not currently have an assignee.
     */
    const UNASSIGNED = "unassigned";

    /**
     * ASSIGNED surfaces tickets where you are the assignee and the ticket is
     * currently open.
     */
    const ASSIGNED = "assigned";
    /**
     * ASSIGNED_ALL surfaces tickets where you are the assignee, regardless of
     * the state.
     */
    const ALL_ASSIGNED = "assigned_all";

    /**
     * ACTION_REQUIRED surfaces tickets where you are the assignee but you were
     * not the last person to reply to the ticket.
     */
    const ACTION_REQUIRED = "action_required";

    /**
     * ALL surfaces every ticket in the database.
     */
    const ALL = "all";

    /**
     * $options holds a list of all filter options.
     *
     * @var string[]
     */
    static $options = [
        self::MINE,
        self::MINE_CLOSED,
        self::UNASSIGNED,
        self::ASSIGNED,
        self::ALL_ASSIGNED,
        self::ACTION_REQUIRED,
        self::ALL,
    ];

    /**
     * $option_names holds the mapping between a filter name and its human
     * readable description.
     *
     * @var array[string]string
     */
    static $option_names = [
        self::MINE => "My Open Tickets",
        self::MINE_CLOSED => "My Closed Tickets",
        self::UNASSIGNED => "Unassigned Tickets",
        self::ASSIGNED => "Open Tickets Assigned To Me",
        self::ALL_ASSIGNED => "All Tickets Assigned To Me",
        self::ACTION_REQUIRED => "Tickets Awaiting Reply",
        self::ALL => "All Tickets",
    ];

    /**
     * $user_options holds a list of all filters that a regular user can use
     * without administrative privileges.
     *
     * This list ensures that new filters default to administrators only unless
     * they are explicitly added to this list.
     *
     * @var string[]
     */
    static $user_options = [
        self::MINE,
        self::MINE_CLOSED,
    ];

    private static function filterExists($name)
    {
        return in_array($name, static::$options);
    }

    public static function filterRequiresAdmin($name)
    {
        return !in_array($name, static::$user_options);
    }

    public static function filterOptionNamesForUser(User $user)
    {
        if ($user->is_admin) {
            return static::$option_names;
        }

        $options = [];
        foreach (static::$user_options as $name) {
            $options[$name] = static::$option_names[$name];
        }
        return $options;
    }

    /**
     * constructFromURL builds a new HelpTicketFilter from the HTTP GET
     * parameter. In the event that the parameter is not defined or contains
     * an invalid filter value, the MINE filter will be used.
     *
     * @param User // TODO
     *
     * @return HelpTicketFilter the newly constructed object.
     */
    public static function constructFromURL(User $user)
    {
        // Attempt to fetch the filter name from the URL. If the filter is not
        // set, default to the MINE filter.
        $name = self::MINE;

        if (
            array_key_exists(self::URL_PARAM, $_GET)
            && self::filterExists($_GET[self::URL_PARAM])
        ) {
            $name = $_GET[self::URL_PARAM];
        }

        return new HelpTicketFilter($name, $user);
    }

    // --- INSTANCE VARIABLES AND METHODS ---

    /** @var string */
    private $name;
    /** @var User */
    private $user;


    public function __construct($name, User $user)
    {
        if (!static::filterExists($name)) {
            throw new InvalidArgumentException("no such filter '$name'");
        }

        $this->name = $name;
        $this->user = $user;
    }

    public function getName()
    {
        return $this->name;
    }

    public function checkUserAccess()
    {
        return $this->user->is_admin || !self::filterRequiresAdmin($this->name);
    }

    public function toSQL()
    {
        $query = (object) [
            "sql" => "
                WHERE
            ",
            "params" => [],
        ];

        switch ($this->name) {
            case self::ALL:
                // No WHERE for fetching all, so clear the query buffer.
                $query->sql = "";
                break;
            case self::MINE:
            case self::MINE_CLOSED:
                $query->sql .= "
                    ht.submitter = :user_id
                    AND ht.is_closed = :is_closed
                ";

                $query->params[":user_id"] = $this->user->user_id;
                $query->params[":is_closed"] =
                    $this->name === self::MINE_CLOSED;
                break;
            case self::UNASSIGNED:
                $query->sql .= "
                    ht.assignee IS NULL
                    AND ht.submitter != :user_id
                ";

                $query->params[":user_id"] = $this->user->user_id;
                break;
            case self::ASSIGNED:
                $query->sql .= "
                    ht.assignee = :user_id
                    AND ht.is_closed = FALSE
                ";

                $query->params[":user_id"] = $this->user->user_id;
                break;
            case self::ALL_ASSIGNED:
                $query->sql .= "
                    ht.assignee = :user_id
                ";

                $query->params[":user_id"] = $this->user->user_id;
                break;
            case self::ACTION_REQUIRED:
                $query->sql .= "
                    ht.is_closed = FALSE
                    AND (
                        ht.assignee = :assignee_id
                        OR ht.submitter = :submitter_id
                    )
                    AND COALESCE(
                        (
                            SELECT c.author
                            FROM help_ticket_comment htc
                            INNER JOIN comment c
                                ON c.comment_id = htc.comment_id
                            WHERE htc.help_ticket_id = ht.help_ticket_id
                                AND c.is_reply = TRUE
                            ORDER BY c.posted_at DESC
                            LIMIT 1
                        ),
                        ht.submitter
                    ) != :user_id
                ";
                $query->params[":assignee_id"] = $this->user->user_id;
                $query->params[":submitter_id"] = $this->user->user_id;
                $query->params[":user_id"] = $this->user->user_id;

                break;
            default:
                throw new RuntimeException("unknown filter name");
        }

        $query->sql .= "
            ORDER BY ht.updated_at DESC
        ";

        return $query;
    }
}
