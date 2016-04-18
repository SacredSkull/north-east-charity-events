<?php

namespace NorthEastEvents\Models\Base;

use \DateTime;
use \Exception;
use \PDO;
use NorthEastEvents\Models\Charity as ChildCharity;
use NorthEastEvents\Models\CharityQuery as ChildCharityQuery;
use NorthEastEvents\Models\Event as ChildEvent;
use NorthEastEvents\Models\EventQuery as ChildEventQuery;
use NorthEastEvents\Models\EventRating as ChildEventRating;
use NorthEastEvents\Models\EventRatingQuery as ChildEventRatingQuery;
use NorthEastEvents\Models\EventUsers as ChildEventUsers;
use NorthEastEvents\Models\EventUsersQuery as ChildEventUsersQuery;
use NorthEastEvents\Models\Thread as ChildThread;
use NorthEastEvents\Models\ThreadQuery as ChildThreadQuery;
use NorthEastEvents\Models\User as ChildUser;
use NorthEastEvents\Models\UserQuery as ChildUserQuery;
use NorthEastEvents\Models\WaitingList as ChildWaitingList;
use NorthEastEvents\Models\WaitingListQuery as ChildWaitingListQuery;
use NorthEastEvents\Models\Map\EventRatingTableMap;
use NorthEastEvents\Models\Map\EventTableMap;
use NorthEastEvents\Models\Map\EventUsersTableMap;
use NorthEastEvents\Models\Map\ThreadTableMap;
use NorthEastEvents\Models\Map\WaitingListTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\LogicException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;
use Propel\Runtime\Util\PropelDateTime;

/**
 * Base class that represents a row from the 'event' table.
 *
 *
 *
* @package    propel.generator.NorthEastEvents.Models.Base
*/
abstract class Event implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\NorthEastEvents\\Models\\Map\\EventTableMap';


    /**
     * attribute to determine if this object has previously been saved.
     * @var boolean
     */
    protected $new = true;

    /**
     * attribute to determine whether this object has been deleted.
     * @var boolean
     */
    protected $deleted = false;

    /**
     * The columns that have been modified in current object.
     * Tracking modified columns allows us to only update modified columns.
     * @var array
     */
    protected $modifiedColumns = array();

    /**
     * The (virtual) columns that are added at runtime
     * The formatters can add supplementary columns based on a resultset
     * @var array
     */
    protected $virtualColumns = array();

    /**
     * The value for the id field.
     *
     * @var        int
     */
    protected $id;

    /**
     * The value for the charityid field.
     *
     * @var        int
     */
    protected $charityid;

    /**
     * The value for the title field.
     *
     * @var        string
     */
    protected $title;

    /**
     * The value for the date field.
     *
     * @var        DateTime
     */
    protected $date;

    /**
     * The value for the location field.
     *
     * @var        string
     */
    protected $location;

    /**
     * The value for the image_url field.
     *
     * Note: this column has a database default value of: '/images/events/default.png'
     * @var        string
     */
    protected $image_url;

    /**
     * The value for the body field.
     *
     * @var        string
     */
    protected $body;

    /**
     * The value for the bodyhtml field.
     *
     * @var        string
     */
    protected $bodyhtml;

    /**
     * The value for the tickets field.
     *
     * Note: this column has a database default value of: 0
     * @var        int
     */
    protected $tickets;

    /**
     * The value for the video_url field.
     *
     * Note: this column has a database default value of: 'https://www.youtube.com/embed/d5gRPCJPIak'
     * @var        string
     */
    protected $video_url;

    /**
     * The value for the created_at field.
     *
     * @var        DateTime
     */
    protected $created_at;

    /**
     * The value for the updated_at field.
     *
     * @var        DateTime
     */
    protected $updated_at;

    /**
     * The value for the tickets_remaining field.
     *
     * @var        int
     */
    protected $tickets_remaining;

    /**
     * The value for the average_rating field.
     *
     * @var        int
     */
    protected $average_rating;

    /**
     * @var        ChildCharity
     */
    protected $aCharity;

    /**
     * @var        ObjectCollection|ChildEventUsers[] Collection to store aggregation of ChildEventUsers objects.
     */
    protected $collEventUserss;
    protected $collEventUserssPartial;

    /**
     * @var        ObjectCollection|ChildWaitingList[] Collection to store aggregation of ChildWaitingList objects.
     */
    protected $collWaitingLists;
    protected $collWaitingListsPartial;

    /**
     * @var        ObjectCollection|ChildEventRating[] Collection to store aggregation of ChildEventRating objects.
     */
    protected $collEventRatings;
    protected $collEventRatingsPartial;

    /**
     * @var        ObjectCollection|ChildThread[] Collection to store aggregation of ChildThread objects.
     */
    protected $collThreads;
    protected $collThreadsPartial;

    /**
     * @var        ObjectCollection|ChildUser[] Cross Collection to store aggregation of ChildUser objects.
     */
    protected $collUsers;

    /**
     * @var bool
     */
    protected $collUsersPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildUser[]
     */
    protected $usersScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventUsers[]
     */
    protected $eventUserssScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildWaitingList[]
     */
    protected $waitingListsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildEventRating[]
     */
    protected $eventRatingsScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildThread[]
     */
    protected $threadsScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->image_url = '/images/events/default.png';
        $this->tickets = 0;
        $this->video_url = 'https://www.youtube.com/embed/d5gRPCJPIak';
    }

    /**
     * Initializes internal state of NorthEastEvents\Models\Base\Event object.
     * @see applyDefaults()
     */
    public function __construct()
    {
        $this->applyDefaultValues();
    }

    /**
     * Returns whether the object has been modified.
     *
     * @return boolean True if the object has been modified.
     */
    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    /**
     * Has specified column been modified?
     *
     * @param  string  $col column fully qualified name (TableMap::TYPE_COLNAME), e.g. Book::AUTHOR_ID
     * @return boolean True if $col has been modified.
     */
    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    /**
     * Get the columns that have been modified in this object.
     * @return array A unique list of the modified column names for this object.
     */
    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    /**
     * Returns whether the object has ever been saved.  This will
     * be false, if the object was retrieved from storage or was created
     * and then saved.
     *
     * @return boolean true, if the object has never been persisted.
     */
    public function isNew()
    {
        return $this->new;
    }

    /**
     * Setter for the isNew attribute.  This method will be called
     * by Propel-generated children and objects.
     *
     * @param boolean $b the state of the object.
     */
    public function setNew($b)
    {
        $this->new = (boolean) $b;
    }

    /**
     * Whether this object has been deleted.
     * @return boolean The deleted state of this object.
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * Specify whether this object has been deleted.
     * @param  boolean $b The deleted state of this object.
     * @return void
     */
    public function setDeleted($b)
    {
        $this->deleted = (boolean) $b;
    }

    /**
     * Sets the modified state for the object to be false.
     * @param  string $col If supplied, only the specified column is reset.
     * @return void
     */
    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    /**
     * Compares this with another <code>Event</code> instance.  If
     * <code>obj</code> is an instance of <code>Event</code>, delegates to
     * <code>equals(Event)</code>.  Otherwise, returns <code>false</code>.
     *
     * @param  mixed   $obj The object to compare to.
     * @return boolean Whether equal to the object specified.
     */
    public function equals($obj)
    {
        if (!$obj instanceof static) {
            return false;
        }

        if ($this === $obj) {
            return true;
        }

        if (null === $this->getPrimaryKey() || null === $obj->getPrimaryKey()) {
            return false;
        }

        return $this->getPrimaryKey() === $obj->getPrimaryKey();
    }

    /**
     * Get the associative array of the virtual columns in this object
     *
     * @return array
     */
    public function getVirtualColumns()
    {
        return $this->virtualColumns;
    }

    /**
     * Checks the existence of a virtual column in this object
     *
     * @param  string  $name The virtual column name
     * @return boolean
     */
    public function hasVirtualColumn($name)
    {
        return array_key_exists($name, $this->virtualColumns);
    }

    /**
     * Get the value of a virtual column in this object
     *
     * @param  string $name The virtual column name
     * @return mixed
     *
     * @throws PropelException
     */
    public function getVirtualColumn($name)
    {
        if (!$this->hasVirtualColumn($name)) {
            throw new PropelException(sprintf('Cannot get value of inexistent virtual column %s.', $name));
        }

        return $this->virtualColumns[$name];
    }

    /**
     * Set the value of a virtual column in this object
     *
     * @param string $name  The virtual column name
     * @param mixed  $value The value to give to the virtual column
     *
     * @return $this|Event The current object, for fluid interface
     */
    public function setVirtualColumn($name, $value)
    {
        $this->virtualColumns[$name] = $value;

        return $this;
    }

    /**
     * Logs a message using Propel::log().
     *
     * @param  string  $msg
     * @param  int     $priority One of the Propel::LOG_* logging levels
     * @return boolean
     */
    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    /**
     * Export the current object properties to a string, using a given parser format
     * <code>
     * $book = BookQuery::create()->findPk(9012);
     * echo $book->exportTo('JSON');
     *  => {"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * @param  mixed   $parser                 A AbstractParser instance, or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param  boolean $includeLazyLoadColumns (optional) Whether to include lazy load(ed) columns. Defaults to TRUE.
     * @return string  The exported data
     */
    public function exportTo($parser, $includeLazyLoadColumns = true)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        return $parser->fromArray($this->toArray(TableMap::TYPE_PHPNAME, $includeLazyLoadColumns, array(), true));
    }

    /**
     * Clean up internal collections prior to serializing
     * Avoids recursive loops that turn into segmentation faults when serializing
     */
    public function __sleep()
    {
        $this->clearAllReferences();

        $cls = new \ReflectionClass($this);
        $propertyNames = [];
        $serializableProperties = array_diff($cls->getProperties(), $cls->getProperties(\ReflectionProperty::IS_STATIC));

        foreach($serializableProperties as $property) {
            $propertyNames[] = $property->getName();
        }

        return $propertyNames;
    }

    /**
     * Get the [id] column value.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the [charityid] column value.
     *
     * @return int
     */
    public function getCharityID()
    {
        return $this->charityid;
    }

    /**
     * Get the [title] column value.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get the [optionally formatted] temporal [date] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getDate($format = NULL)
    {
        if ($format === null) {
            return $this->date;
        } else {
            return $this->date instanceof \DateTimeInterface ? $this->date->format($format) : null;
        }
    }

    /**
     * Get the [location] column value.
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Get the [image_url] column value.
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->image_url;
    }

    /**
     * Get the [body] column value.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Get the [bodyhtml] column value.
     *
     * @return string
     */
    public function getBodyHTML()
    {
        return $this->bodyhtml;
    }

    /**
     * Get the [tickets] column value.
     *
     * @return int
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Get the [video_url] column value.
     *
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->video_url;
    }

    /**
     * Get the [optionally formatted] temporal [created_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getCreatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->created_at;
        } else {
            return $this->created_at instanceof \DateTimeInterface ? $this->created_at->format($format) : null;
        }
    }

    /**
     * Get the [optionally formatted] temporal [updated_at] column value.
     *
     *
     * @param      string $format The date/time format string (either date()-style or strftime()-style).
     *                            If format is NULL, then the raw DateTime object will be returned.
     *
     * @return string|DateTime Formatted date/time value as string or DateTime object (if format is NULL), NULL if column is NULL, and 0 if column value is 0000-00-00 00:00:00
     *
     * @throws PropelException - if unable to parse/validate the date/time value.
     */
    public function getUpdatedAt($format = NULL)
    {
        if ($format === null) {
            return $this->updated_at;
        } else {
            return $this->updated_at instanceof \DateTimeInterface ? $this->updated_at->format($format) : null;
        }
    }

    /**
     * Get the [tickets_remaining] column value.
     *
     * @return int
     */
    public function getTicketsRemaining()
    {
        return $this->tickets_remaining;
    }

    /**
     * Get the [average_rating] column value.
     *
     * @return int
     */
    public function getAverageRating()
    {
        return $this->average_rating;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[EventTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [charityid] column.
     *
     * @param int $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setCharityID($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->charityid !== $v) {
            $this->charityid = $v;
            $this->modifiedColumns[EventTableMap::COL_CHARITYID] = true;
        }

        if ($this->aCharity !== null && $this->aCharity->getId() !== $v) {
            $this->aCharity = null;
        }

        return $this;
    } // setCharityID()

    /**
     * Set the value of [title] column.
     *
     * @param string $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setTitle($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->title !== $v) {
            $this->title = $v;
            $this->modifiedColumns[EventTableMap::COL_TITLE] = true;
        }

        return $this;
    } // setTitle()

    /**
     * Sets the value of [date] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setDate($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->date !== null || $dt !== null) {
            if ($this->date === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->date->format("Y-m-d H:i:s")) {
                $this->date = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_DATE] = true;
            }
        } // if either are not null

        return $this;
    } // setDate()

    /**
     * Set the value of [location] column.
     *
     * @param string $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setLocation($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->location !== $v) {
            $this->location = $v;
            $this->modifiedColumns[EventTableMap::COL_LOCATION] = true;
        }

        return $this;
    } // setLocation()

    /**
     * Set the value of [image_url] column.
     *
     * @param string $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setImageUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->image_url !== $v) {
            $this->image_url = $v;
            $this->modifiedColumns[EventTableMap::COL_IMAGE_URL] = true;
        }

        return $this;
    } // setImageUrl()

    /**
     * Set the value of [body] column.
     *
     * @param string $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setBody($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->body !== $v) {
            $this->body = $v;
            $this->modifiedColumns[EventTableMap::COL_BODY] = true;
        }

        return $this;
    } // setBody()

    /**
     * Set the value of [bodyhtml] column.
     *
     * @param string $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setBodyHTML($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->bodyhtml !== $v) {
            $this->bodyhtml = $v;
            $this->modifiedColumns[EventTableMap::COL_BODYHTML] = true;
        }

        return $this;
    } // setBodyHTML()

    /**
     * Set the value of [tickets] column.
     *
     * @param int $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setTickets($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tickets !== $v) {
            $this->tickets = $v;
            $this->modifiedColumns[EventTableMap::COL_TICKETS] = true;
        }

        return $this;
    } // setTickets()

    /**
     * Set the value of [video_url] column.
     *
     * @param string $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setVideoUrl($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->video_url !== $v) {
            $this->video_url = $v;
            $this->modifiedColumns[EventTableMap::COL_VIDEO_URL] = true;
        }

        return $this;
    } // setVideoUrl()

    /**
     * Sets the value of [created_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setCreatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->created_at !== null || $dt !== null) {
            if ($this->created_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->created_at->format("Y-m-d H:i:s")) {
                $this->created_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_CREATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setCreatedAt()

    /**
     * Sets the value of [updated_at] column to a normalized version of the date/time value specified.
     *
     * @param  mixed $v string, integer (timestamp), or \DateTimeInterface value.
     *               Empty strings are treated as NULL.
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setUpdatedAt($v)
    {
        $dt = PropelDateTime::newInstance($v, null, 'DateTime');
        if ($this->updated_at !== null || $dt !== null) {
            if ($this->updated_at === null || $dt === null || $dt->format("Y-m-d H:i:s") !== $this->updated_at->format("Y-m-d H:i:s")) {
                $this->updated_at = $dt === null ? null : clone $dt;
                $this->modifiedColumns[EventTableMap::COL_UPDATED_AT] = true;
            }
        } // if either are not null

        return $this;
    } // setUpdatedAt()

    /**
     * Set the value of [tickets_remaining] column.
     *
     * @param int $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setTicketsRemaining($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->tickets_remaining !== $v) {
            $this->tickets_remaining = $v;
            $this->modifiedColumns[EventTableMap::COL_TICKETS_REMAINING] = true;
        }

        return $this;
    } // setTicketsRemaining()

    /**
     * Set the value of [average_rating] column.
     *
     * @param int $v new value
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function setAverageRating($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->average_rating !== $v) {
            $this->average_rating = $v;
            $this->modifiedColumns[EventTableMap::COL_AVERAGE_RATING] = true;
        }

        return $this;
    } // setAverageRating()

    /**
     * Indicates whether the columns in this object are only set to default values.
     *
     * This method can be used in conjunction with isModified() to indicate whether an object is both
     * modified _and_ has some values set which are non-default.
     *
     * @return boolean Whether the columns in this object are only been set with default values.
     */
    public function hasOnlyDefaultValues()
    {
            if ($this->image_url !== '/images/events/default.png') {
                return false;
            }

            if ($this->tickets !== 0) {
                return false;
            }

            if ($this->video_url !== 'https://www.youtube.com/embed/d5gRPCJPIak') {
                return false;
            }

        // otherwise, everything was equal, so return TRUE
        return true;
    } // hasOnlyDefaultValues()

    /**
     * Hydrates (populates) the object variables with values from the database resultset.
     *
     * An offset (0-based "start column") is specified so that objects can be hydrated
     * with a subset of the columns in the resultset rows.  This is needed, for example,
     * for results of JOIN queries where the resultset row includes columns from two or
     * more tables.
     *
     * @param array   $row       The row returned by DataFetcher->fetch().
     * @param int     $startcol  0-based offset column which indicates which restultset column to start with.
     * @param boolean $rehydrate Whether this object is being re-hydrated from the database.
     * @param string  $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                  One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                            TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @return int             next starting column
     * @throws PropelException - Any caught Exception will be rewrapped as a PropelException.
     */
    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : EventTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : EventTableMap::translateFieldName('CharityID', TableMap::TYPE_PHPNAME, $indexType)];
            $this->charityid = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : EventTableMap::translateFieldName('Title', TableMap::TYPE_PHPNAME, $indexType)];
            $this->title = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 3 + $startcol : EventTableMap::translateFieldName('Date', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->date = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 4 + $startcol : EventTableMap::translateFieldName('Location', TableMap::TYPE_PHPNAME, $indexType)];
            $this->location = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 5 + $startcol : EventTableMap::translateFieldName('ImageUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->image_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 6 + $startcol : EventTableMap::translateFieldName('Body', TableMap::TYPE_PHPNAME, $indexType)];
            $this->body = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 7 + $startcol : EventTableMap::translateFieldName('BodyHTML', TableMap::TYPE_PHPNAME, $indexType)];
            $this->bodyhtml = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 8 + $startcol : EventTableMap::translateFieldName('Tickets', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tickets = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 9 + $startcol : EventTableMap::translateFieldName('VideoUrl', TableMap::TYPE_PHPNAME, $indexType)];
            $this->video_url = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 10 + $startcol : EventTableMap::translateFieldName('CreatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->created_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 11 + $startcol : EventTableMap::translateFieldName('UpdatedAt', TableMap::TYPE_PHPNAME, $indexType)];
            if ($col === '0000-00-00 00:00:00') {
                $col = null;
            }
            $this->updated_at = (null !== $col) ? PropelDateTime::newInstance($col, null, 'DateTime') : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 12 + $startcol : EventTableMap::translateFieldName('TicketsRemaining', TableMap::TYPE_PHPNAME, $indexType)];
            $this->tickets_remaining = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 13 + $startcol : EventTableMap::translateFieldName('AverageRating', TableMap::TYPE_PHPNAME, $indexType)];
            $this->average_rating = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 14; // 14 = EventTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\NorthEastEvents\\Models\\Event'), 0, $e);
        }
    }

    /**
     * Checks and repairs the internal consistency of the object.
     *
     * This method is executed after an already-instantiated object is re-hydrated
     * from the database.  It exists to check any foreign keys to make sure that
     * the objects related to the current object are correct based on foreign key.
     *
     * You can override this method in the stub class, but you should always invoke
     * the base method from the overridden method (i.e. parent::ensureConsistency()),
     * in case your model changes.
     *
     * @throws PropelException
     */
    public function ensureConsistency()
    {
        if ($this->aCharity !== null && $this->charityid !== $this->aCharity->getId()) {
            $this->aCharity = null;
        }
    } // ensureConsistency

    /**
     * Reloads this object from datastore based on primary key and (optionally) resets all associated objects.
     *
     * This will only work if the object has been saved and has a valid primary key set.
     *
     * @param      boolean $deep (optional) Whether to also de-associated any related objects.
     * @param      ConnectionInterface $con (optional) The ConnectionInterface connection to use.
     * @return void
     * @throws PropelException - if this object is deleted, unsaved or doesn't have pk match in db
     */
    public function reload($deep = false, ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("Cannot reload a deleted object.");
        }

        if ($this->isNew()) {
            throw new PropelException("Cannot reload an unsaved object.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildEventQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aCharity = null;
            $this->collEventUserss = null;

            $this->collWaitingLists = null;

            $this->collEventRatings = null;

            $this->collThreads = null;

            $this->collUsers = null;
        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Event::setDeleted()
     * @see Event::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildEventQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
            $ret = $this->preDelete($con);
            if ($ret) {
                $deleteQuery->delete($con);
                $this->postDelete($con);
                $this->setDeleted(true);
            }
        });
    }

    /**
     * Persists this object to the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All modified related objects will also be persisted in the doSave()
     * method.  This method wraps all precipitate database operations in a
     * single transaction.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see doSave()
     */
    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
                // timestampable behavior

                if (!$this->isColumnModified(EventTableMap::COL_CREATED_AT)) {
                    $this->setCreatedAt(time());
                }
                if (!$this->isColumnModified(EventTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            } else {
                $ret = $ret && $this->preUpdate($con);
                // timestampable behavior
                if ($this->isModified() && !$this->isColumnModified(EventTableMap::COL_UPDATED_AT)) {
                    $this->setUpdatedAt(time());
                }
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                EventTableMap::addInstanceToPool($this);
            } else {
                $affectedRows = 0;
            }

            return $affectedRows;
        });
    }

    /**
     * Performs the work of inserting or updating the row in the database.
     *
     * If the object is new, it inserts it; otherwise an update is performed.
     * All related objects are also updated in this method.
     *
     * @param      ConnectionInterface $con
     * @return int             The number of rows affected by this insert/update and any referring fk objects' save() operations.
     * @throws PropelException
     * @see save()
     */
    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;

            // We call the save method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            if ($this->aCharity !== null) {
                if ($this->aCharity->isModified() || $this->aCharity->isNew()) {
                    $affectedRows += $this->aCharity->save($con);
                }
                $this->setCharity($this->aCharity);
            }

            if ($this->isNew() || $this->isModified()) {
                // persist changes
                if ($this->isNew()) {
                    $this->doInsert($con);
                    $affectedRows += 1;
                } else {
                    $affectedRows += $this->doUpdate($con);
                }
                $this->resetModified();
            }

            if ($this->usersScheduledForDeletion !== null) {
                if (!$this->usersScheduledForDeletion->isEmpty()) {
                    $pks = array();
                    foreach ($this->usersScheduledForDeletion as $entry) {
                        $entryPk = [];

                        $entryPk[0] = $this->getId();
                        $entryPk[1] = $entry->getId();
                        $pks[] = $entryPk;
                    }

                    \NorthEastEvents\Models\EventUsersQuery::create()
                        ->filterByPrimaryKeys($pks)
                        ->delete($con);

                    $this->usersScheduledForDeletion = null;
                }

            }

            if ($this->collUsers) {
                foreach ($this->collUsers as $user) {
                    if (!$user->isDeleted() && ($user->isNew() || $user->isModified())) {
                        $user->save($con);
                    }
                }
            }


            if ($this->eventUserssScheduledForDeletion !== null) {
                if (!$this->eventUserssScheduledForDeletion->isEmpty()) {
                    \NorthEastEvents\Models\EventUsersQuery::create()
                        ->filterByPrimaryKeys($this->eventUserssScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventUserssScheduledForDeletion = null;
                }
            }

            if ($this->collEventUserss !== null) {
                foreach ($this->collEventUserss as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->waitingListsScheduledForDeletion !== null) {
                if (!$this->waitingListsScheduledForDeletion->isEmpty()) {
                    \NorthEastEvents\Models\WaitingListQuery::create()
                        ->filterByPrimaryKeys($this->waitingListsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->waitingListsScheduledForDeletion = null;
                }
            }

            if ($this->collWaitingLists !== null) {
                foreach ($this->collWaitingLists as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->eventRatingsScheduledForDeletion !== null) {
                if (!$this->eventRatingsScheduledForDeletion->isEmpty()) {
                    \NorthEastEvents\Models\EventRatingQuery::create()
                        ->filterByPrimaryKeys($this->eventRatingsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->eventRatingsScheduledForDeletion = null;
                }
            }

            if ($this->collEventRatings !== null) {
                foreach ($this->collEventRatings as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->threadsScheduledForDeletion !== null) {
                if (!$this->threadsScheduledForDeletion->isEmpty()) {
                    \NorthEastEvents\Models\ThreadQuery::create()
                        ->filterByPrimaryKeys($this->threadsScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->threadsScheduledForDeletion = null;
                }
            }

            if ($this->collThreads !== null) {
                foreach ($this->collThreads as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            $this->alreadyInSave = false;

        }

        return $affectedRows;
    } // doSave()

    /**
     * Insert the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @throws PropelException
     * @see doSave()
     */
    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;

        $this->modifiedColumns[EventTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . EventTableMap::COL_ID . ')');
        }

         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(EventTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(EventTableMap::COL_CHARITYID)) {
            $modifiedColumns[':p' . $index++]  = 'charityID';
        }
        if ($this->isColumnModified(EventTableMap::COL_TITLE)) {
            $modifiedColumns[':p' . $index++]  = 'title';
        }
        if ($this->isColumnModified(EventTableMap::COL_DATE)) {
            $modifiedColumns[':p' . $index++]  = 'date';
        }
        if ($this->isColumnModified(EventTableMap::COL_LOCATION)) {
            $modifiedColumns[':p' . $index++]  = 'location';
        }
        if ($this->isColumnModified(EventTableMap::COL_IMAGE_URL)) {
            $modifiedColumns[':p' . $index++]  = 'image_url';
        }
        if ($this->isColumnModified(EventTableMap::COL_BODY)) {
            $modifiedColumns[':p' . $index++]  = 'body';
        }
        if ($this->isColumnModified(EventTableMap::COL_BODYHTML)) {
            $modifiedColumns[':p' . $index++]  = 'bodyHTML';
        }
        if ($this->isColumnModified(EventTableMap::COL_TICKETS)) {
            $modifiedColumns[':p' . $index++]  = 'tickets';
        }
        if ($this->isColumnModified(EventTableMap::COL_VIDEO_URL)) {
            $modifiedColumns[':p' . $index++]  = 'video_url';
        }
        if ($this->isColumnModified(EventTableMap::COL_CREATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'created_at';
        }
        if ($this->isColumnModified(EventTableMap::COL_UPDATED_AT)) {
            $modifiedColumns[':p' . $index++]  = 'updated_at';
        }
        if ($this->isColumnModified(EventTableMap::COL_TICKETS_REMAINING)) {
            $modifiedColumns[':p' . $index++]  = 'tickets_remaining';
        }
        if ($this->isColumnModified(EventTableMap::COL_AVERAGE_RATING)) {
            $modifiedColumns[':p' . $index++]  = 'average_rating';
        }

        $sql = sprintf(
            'INSERT INTO event (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );

        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'id':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'charityID':
                        $stmt->bindValue($identifier, $this->charityid, PDO::PARAM_INT);
                        break;
                    case 'title':
                        $stmt->bindValue($identifier, $this->title, PDO::PARAM_STR);
                        break;
                    case 'date':
                        $stmt->bindValue($identifier, $this->date ? $this->date->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'location':
                        $stmt->bindValue($identifier, $this->location, PDO::PARAM_STR);
                        break;
                    case 'image_url':
                        $stmt->bindValue($identifier, $this->image_url, PDO::PARAM_STR);
                        break;
                    case 'body':
                        $stmt->bindValue($identifier, $this->body, PDO::PARAM_STR);
                        break;
                    case 'bodyHTML':
                        $stmt->bindValue($identifier, $this->bodyhtml, PDO::PARAM_STR);
                        break;
                    case 'tickets':
                        $stmt->bindValue($identifier, $this->tickets, PDO::PARAM_INT);
                        break;
                    case 'video_url':
                        $stmt->bindValue($identifier, $this->video_url, PDO::PARAM_STR);
                        break;
                    case 'created_at':
                        $stmt->bindValue($identifier, $this->created_at ? $this->created_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'updated_at':
                        $stmt->bindValue($identifier, $this->updated_at ? $this->updated_at->format("Y-m-d H:i:s") : null, PDO::PARAM_STR);
                        break;
                    case 'tickets_remaining':
                        $stmt->bindValue($identifier, $this->tickets_remaining, PDO::PARAM_INT);
                        break;
                    case 'average_rating':
                        $stmt->bindValue($identifier, $this->average_rating, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);

        $this->setNew(false);
    }

    /**
     * Update the row in the database.
     *
     * @param      ConnectionInterface $con
     *
     * @return Integer Number of updated rows
     * @see doSave()
     */
    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();

        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    /**
     * Retrieves a field from the object by name passed in as a string.
     *
     * @param      string $name name
     * @param      string $type The type of fieldname the $name is of:
     *                     one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                     TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                     Defaults to TableMap::TYPE_PHPNAME.
     * @return mixed Value of field.
     */
    public function getByName($name, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
        $field = $this->getByPosition($pos);

        return $field;
    }

    /**
     * Retrieves a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param      int $pos position in xml schema
     * @return mixed Value of field at $pos
     */
    public function getByPosition($pos)
    {
        switch ($pos) {
            case 0:
                return $this->getId();
                break;
            case 1:
                return $this->getCharityID();
                break;
            case 2:
                return $this->getTitle();
                break;
            case 3:
                return $this->getDate();
                break;
            case 4:
                return $this->getLocation();
                break;
            case 5:
                return $this->getImageUrl();
                break;
            case 6:
                return $this->getBody();
                break;
            case 7:
                return $this->getBodyHTML();
                break;
            case 8:
                return $this->getTickets();
                break;
            case 9:
                return $this->getVideoUrl();
                break;
            case 10:
                return $this->getCreatedAt();
                break;
            case 11:
                return $this->getUpdatedAt();
                break;
            case 12:
                return $this->getTicketsRemaining();
                break;
            case 13:
                return $this->getAverageRating();
                break;
            default:
                return null;
                break;
        } // switch()
    }

    /**
     * Exports the object as an array.
     *
     * You can specify the key type of the array by passing one of the class
     * type constants.
     *
     * @param     string  $keyType (optional) One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     *                    TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                    Defaults to TableMap::TYPE_PHPNAME.
     * @param     boolean $includeLazyLoadColumns (optional) Whether to include lazy loaded columns. Defaults to TRUE.
     * @param     array $alreadyDumpedObjects List of objects to skip to avoid recursion
     * @param     boolean $includeForeignObjects (optional) Whether to include hydrated related objects. Default to FALSE.
     *
     * @return array an associative array containing the field names (as keys) and field values
     */
    public function toArray($keyType = TableMap::TYPE_PHPNAME, $includeLazyLoadColumns = true, $alreadyDumpedObjects = array(), $includeForeignObjects = false)
    {

        if (isset($alreadyDumpedObjects['Event'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Event'][$this->hashCode()] = true;
        $keys = EventTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getCharityID(),
            $keys[2] => $this->getTitle(),
            $keys[3] => $this->getDate(),
            $keys[4] => $this->getLocation(),
            $keys[5] => $this->getImageUrl(),
            $keys[6] => $this->getBody(),
            $keys[7] => $this->getBodyHTML(),
            $keys[8] => $this->getTickets(),
            $keys[9] => $this->getVideoUrl(),
            $keys[10] => $this->getCreatedAt(),
            $keys[11] => $this->getUpdatedAt(),
            $keys[12] => $this->getTicketsRemaining(),
            $keys[13] => $this->getAverageRating(),
        );
        if ($result[$keys[3]] instanceof \DateTime) {
            $result[$keys[3]] = $result[$keys[3]]->format('c');
        }

        if ($result[$keys[10]] instanceof \DateTime) {
            $result[$keys[10]] = $result[$keys[10]]->format('c');
        }

        if ($result[$keys[11]] instanceof \DateTime) {
            $result[$keys[11]] = $result[$keys[11]]->format('c');
        }

        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aCharity) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'charity';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'charity';
                        break;
                    default:
                        $key = 'Charity';
                }

                $result[$key] = $this->aCharity->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collEventUserss) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventUserss';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_userss';
                        break;
                    default:
                        $key = 'EventUserss';
                }

                $result[$key] = $this->collEventUserss->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collWaitingLists) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'waitingLists';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'waiting_lists';
                        break;
                    default:
                        $key = 'WaitingLists';
                }

                $result[$key] = $this->collWaitingLists->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collEventRatings) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'eventRatings';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'event_ratings';
                        break;
                    default:
                        $key = 'EventRatings';
                }

                $result[$key] = $this->collEventRatings->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collThreads) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'threads';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'threads';
                        break;
                    default:
                        $key = 'Threads';
                }

                $result[$key] = $this->collThreads->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
        }

        return $result;
    }

    /**
     * Sets a field from the object by name passed in as a string.
     *
     * @param  string $name
     * @param  mixed  $value field value
     * @param  string $type The type of fieldname the $name is of:
     *                one of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *                Defaults to TableMap::TYPE_PHPNAME.
     * @return $this|\NorthEastEvents\Models\Event
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = EventTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\NorthEastEvents\Models\Event
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setCharityID($value);
                break;
            case 2:
                $this->setTitle($value);
                break;
            case 3:
                $this->setDate($value);
                break;
            case 4:
                $this->setLocation($value);
                break;
            case 5:
                $this->setImageUrl($value);
                break;
            case 6:
                $this->setBody($value);
                break;
            case 7:
                $this->setBodyHTML($value);
                break;
            case 8:
                $this->setTickets($value);
                break;
            case 9:
                $this->setVideoUrl($value);
                break;
            case 10:
                $this->setCreatedAt($value);
                break;
            case 11:
                $this->setUpdatedAt($value);
                break;
            case 12:
                $this->setTicketsRemaining($value);
                break;
            case 13:
                $this->setAverageRating($value);
                break;
        } // switch()

        return $this;
    }

    /**
     * Populates the object using an array.
     *
     * This is particularly useful when populating an object from one of the
     * request arrays (e.g. $_POST).  This method goes through the column
     * names, checking to see whether a matching key exists in populated
     * array. If so the setByName() method is called for that column.
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param      array  $arr     An array to populate the object from.
     * @param      string $keyType The type of keys the array uses.
     * @return void
     */
    public function fromArray($arr, $keyType = TableMap::TYPE_PHPNAME)
    {
        $keys = EventTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setCharityID($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setTitle($arr[$keys[2]]);
        }
        if (array_key_exists($keys[3], $arr)) {
            $this->setDate($arr[$keys[3]]);
        }
        if (array_key_exists($keys[4], $arr)) {
            $this->setLocation($arr[$keys[4]]);
        }
        if (array_key_exists($keys[5], $arr)) {
            $this->setImageUrl($arr[$keys[5]]);
        }
        if (array_key_exists($keys[6], $arr)) {
            $this->setBody($arr[$keys[6]]);
        }
        if (array_key_exists($keys[7], $arr)) {
            $this->setBodyHTML($arr[$keys[7]]);
        }
        if (array_key_exists($keys[8], $arr)) {
            $this->setTickets($arr[$keys[8]]);
        }
        if (array_key_exists($keys[9], $arr)) {
            $this->setVideoUrl($arr[$keys[9]]);
        }
        if (array_key_exists($keys[10], $arr)) {
            $this->setCreatedAt($arr[$keys[10]]);
        }
        if (array_key_exists($keys[11], $arr)) {
            $this->setUpdatedAt($arr[$keys[11]]);
        }
        if (array_key_exists($keys[12], $arr)) {
            $this->setTicketsRemaining($arr[$keys[12]]);
        }
        if (array_key_exists($keys[13], $arr)) {
            $this->setAverageRating($arr[$keys[13]]);
        }
    }

     /**
     * Populate the current object from a string, using a given parser format
     * <code>
     * $book = new Book();
     * $book->importFrom('JSON', '{"Id":9012,"Title":"Don Juan","ISBN":"0140422161","Price":12.99,"PublisherId":1234,"AuthorId":5678}');
     * </code>
     *
     * You can specify the key type of the array by additionally passing one
     * of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME,
     * TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     * The default key type is the column's TableMap::TYPE_PHPNAME.
     *
     * @param mixed $parser A AbstractParser instance,
     *                       or a format name ('XML', 'YAML', 'JSON', 'CSV')
     * @param string $data The source data to import from
     * @param string $keyType The type of keys the array uses.
     *
     * @return $this|\NorthEastEvents\Models\Event The current object, for fluid interface
     */
    public function importFrom($parser, $data, $keyType = TableMap::TYPE_PHPNAME)
    {
        if (!$parser instanceof AbstractParser) {
            $parser = AbstractParser::getParser($parser);
        }

        $this->fromArray($parser->toArray($data), $keyType);

        return $this;
    }

    /**
     * Build a Criteria object containing the values of all modified columns in this object.
     *
     * @return Criteria The Criteria object containing all modified values.
     */
    public function buildCriteria()
    {
        $criteria = new Criteria(EventTableMap::DATABASE_NAME);

        if ($this->isColumnModified(EventTableMap::COL_ID)) {
            $criteria->add(EventTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(EventTableMap::COL_CHARITYID)) {
            $criteria->add(EventTableMap::COL_CHARITYID, $this->charityid);
        }
        if ($this->isColumnModified(EventTableMap::COL_TITLE)) {
            $criteria->add(EventTableMap::COL_TITLE, $this->title);
        }
        if ($this->isColumnModified(EventTableMap::COL_DATE)) {
            $criteria->add(EventTableMap::COL_DATE, $this->date);
        }
        if ($this->isColumnModified(EventTableMap::COL_LOCATION)) {
            $criteria->add(EventTableMap::COL_LOCATION, $this->location);
        }
        if ($this->isColumnModified(EventTableMap::COL_IMAGE_URL)) {
            $criteria->add(EventTableMap::COL_IMAGE_URL, $this->image_url);
        }
        if ($this->isColumnModified(EventTableMap::COL_BODY)) {
            $criteria->add(EventTableMap::COL_BODY, $this->body);
        }
        if ($this->isColumnModified(EventTableMap::COL_BODYHTML)) {
            $criteria->add(EventTableMap::COL_BODYHTML, $this->bodyhtml);
        }
        if ($this->isColumnModified(EventTableMap::COL_TICKETS)) {
            $criteria->add(EventTableMap::COL_TICKETS, $this->tickets);
        }
        if ($this->isColumnModified(EventTableMap::COL_VIDEO_URL)) {
            $criteria->add(EventTableMap::COL_VIDEO_URL, $this->video_url);
        }
        if ($this->isColumnModified(EventTableMap::COL_CREATED_AT)) {
            $criteria->add(EventTableMap::COL_CREATED_AT, $this->created_at);
        }
        if ($this->isColumnModified(EventTableMap::COL_UPDATED_AT)) {
            $criteria->add(EventTableMap::COL_UPDATED_AT, $this->updated_at);
        }
        if ($this->isColumnModified(EventTableMap::COL_TICKETS_REMAINING)) {
            $criteria->add(EventTableMap::COL_TICKETS_REMAINING, $this->tickets_remaining);
        }
        if ($this->isColumnModified(EventTableMap::COL_AVERAGE_RATING)) {
            $criteria->add(EventTableMap::COL_AVERAGE_RATING, $this->average_rating);
        }

        return $criteria;
    }

    /**
     * Builds a Criteria object containing the primary key for this object.
     *
     * Unlike buildCriteria() this method includes the primary key values regardless
     * of whether or not they have been modified.
     *
     * @throws LogicException if no primary key is defined
     *
     * @return Criteria The Criteria object containing value(s) for primary key(s).
     */
    public function buildPkeyCriteria()
    {
        $criteria = ChildEventQuery::create();
        $criteria->add(EventTableMap::COL_ID, $this->id);

        return $criteria;
    }

    /**
     * If the primary key is not null, return the hashcode of the
     * primary key. Otherwise, return the hash code of the object.
     *
     * @return int Hashcode
     */
    public function hashCode()
    {
        $validPk = null !== $this->getId();

        $validPrimaryKeyFKs = 0;
        $primaryKeyFKs = [];

        if ($validPk) {
            return crc32(json_encode($this->getPrimaryKey(), JSON_UNESCAPED_UNICODE));
        } elseif ($validPrimaryKeyFKs) {
            return crc32(json_encode($primaryKeyFKs, JSON_UNESCAPED_UNICODE));
        }

        return spl_object_hash($this);
    }

    /**
     * Returns the primary key for this object (row).
     * @return int
     */
    public function getPrimaryKey()
    {
        return $this->getId();
    }

    /**
     * Generic method to set the primary key (id column).
     *
     * @param       int $key Primary key.
     * @return void
     */
    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    /**
     * Returns true if the primary key for this object is null.
     * @return boolean
     */
    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }

    /**
     * Sets contents of passed object to values from current object.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param      object $copyObj An object of \NorthEastEvents\Models\Event (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setCharityID($this->getCharityID());
        $copyObj->setTitle($this->getTitle());
        $copyObj->setDate($this->getDate());
        $copyObj->setLocation($this->getLocation());
        $copyObj->setImageUrl($this->getImageUrl());
        $copyObj->setBody($this->getBody());
        $copyObj->setBodyHTML($this->getBodyHTML());
        $copyObj->setTickets($this->getTickets());
        $copyObj->setVideoUrl($this->getVideoUrl());
        $copyObj->setCreatedAt($this->getCreatedAt());
        $copyObj->setUpdatedAt($this->getUpdatedAt());
        $copyObj->setTicketsRemaining($this->getTicketsRemaining());
        $copyObj->setAverageRating($this->getAverageRating());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getEventUserss() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventUsers($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getWaitingLists() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addWaitingList($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getEventRatings() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addEventRating($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getThreads() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addThread($relObj->copy($deepCopy));
                }
            }

        } // if ($deepCopy)

        if ($makeNew) {
            $copyObj->setNew(true);
            $copyObj->setId(NULL); // this is a auto-increment column, so set to default value
        }
    }

    /**
     * Makes a copy of this object that will be inserted as a new row in table when saved.
     * It creates a new object filling in the simple attributes, but skipping any primary
     * keys that are defined for the table.
     *
     * If desired, this method can also make copies of all associated (fkey referrers)
     * objects.
     *
     * @param  boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @return \NorthEastEvents\Models\Event Clone of current object.
     * @throws PropelException
     */
    public function copy($deepCopy = false)
    {
        // we use get_class(), because this might be a subclass
        $clazz = get_class($this);
        $copyObj = new $clazz();
        $this->copyInto($copyObj, $deepCopy);

        return $copyObj;
    }

    /**
     * Declares an association between this object and a ChildCharity object.
     *
     * @param  ChildCharity $v
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     * @throws PropelException
     */
    public function setCharity(ChildCharity $v = null)
    {
        if ($v === null) {
            $this->setCharityID(NULL);
        } else {
            $this->setCharityID($v->getId());
        }

        $this->aCharity = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCharity object, it will not be re-added.
        if ($v !== null) {
            $v->addEvent($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCharity object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCharity The associated ChildCharity object.
     * @throws PropelException
     */
    public function getCharity(ConnectionInterface $con = null)
    {
        if ($this->aCharity === null && ($this->charityid !== null)) {
            $this->aCharity = ChildCharityQuery::create()->findPk($this->charityid, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aCharity->addEvents($this);
             */
        }

        return $this->aCharity;
    }


    /**
     * Initializes a collection based on the name of a relation.
     * Avoids crafting an 'init[$relationName]s' method name
     * that wouldn't work when StandardEnglishPluralizer is used.
     *
     * @param      string $relationName The name of the relation to initialize
     * @return void
     */
    public function initRelation($relationName)
    {
        if ('EventUsers' == $relationName) {
            return $this->initEventUserss();
        }
        if ('WaitingList' == $relationName) {
            return $this->initWaitingLists();
        }
        if ('EventRating' == $relationName) {
            return $this->initEventRatings();
        }
        if ('Thread' == $relationName) {
            return $this->initThreads();
        }
    }

    /**
     * Clears out the collEventUserss collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventUserss()
     */
    public function clearEventUserss()
    {
        $this->collEventUserss = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventUserss collection loaded partially.
     */
    public function resetPartialEventUserss($v = true)
    {
        $this->collEventUserssPartial = $v;
    }

    /**
     * Initializes the collEventUserss collection.
     *
     * By default this just sets the collEventUserss collection to an empty array (like clearcollEventUserss());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventUserss($overrideExisting = true)
    {
        if (null !== $this->collEventUserss && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventUsersTableMap::getTableMap()->getCollectionClassName();

        $this->collEventUserss = new $collectionClassName;
        $this->collEventUserss->setModel('\NorthEastEvents\Models\EventUsers');
    }

    /**
     * Gets an array of ChildEventUsers objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventUsers[] List of ChildEventUsers objects
     * @throws PropelException
     */
    public function getEventUserss(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventUserssPartial && !$this->isNew();
        if (null === $this->collEventUserss || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventUserss) {
                // return empty collection
                $this->initEventUserss();
            } else {
                $collEventUserss = ChildEventUsersQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventUserssPartial && count($collEventUserss)) {
                        $this->initEventUserss(false);

                        foreach ($collEventUserss as $obj) {
                            if (false == $this->collEventUserss->contains($obj)) {
                                $this->collEventUserss->append($obj);
                            }
                        }

                        $this->collEventUserssPartial = true;
                    }

                    return $collEventUserss;
                }

                if ($partial && $this->collEventUserss) {
                    foreach ($this->collEventUserss as $obj) {
                        if ($obj->isNew()) {
                            $collEventUserss[] = $obj;
                        }
                    }
                }

                $this->collEventUserss = $collEventUserss;
                $this->collEventUserssPartial = false;
            }
        }

        return $this->collEventUserss;
    }

    /**
     * Sets a collection of ChildEventUsers objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventUserss A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setEventUserss(Collection $eventUserss, ConnectionInterface $con = null)
    {
        /** @var ChildEventUsers[] $eventUserssToDelete */
        $eventUserssToDelete = $this->getEventUserss(new Criteria(), $con)->diff($eventUserss);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->eventUserssScheduledForDeletion = clone $eventUserssToDelete;

        foreach ($eventUserssToDelete as $eventUsersRemoved) {
            $eventUsersRemoved->setEvent(null);
        }

        $this->collEventUserss = null;
        foreach ($eventUserss as $eventUsers) {
            $this->addEventUsers($eventUsers);
        }

        $this->collEventUserss = $eventUserss;
        $this->collEventUserssPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventUsers objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventUsers objects.
     * @throws PropelException
     */
    public function countEventUserss(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventUserssPartial && !$this->isNew();
        if (null === $this->collEventUserss || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventUserss) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventUserss());
            }

            $query = ChildEventUsersQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collEventUserss);
    }

    /**
     * Method called to associate a ChildEventUsers object to this object
     * through the ChildEventUsers foreign key attribute.
     *
     * @param  ChildEventUsers $l ChildEventUsers
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function addEventUsers(ChildEventUsers $l)
    {
        if ($this->collEventUserss === null) {
            $this->initEventUserss();
            $this->collEventUserssPartial = true;
        }

        if (!$this->collEventUserss->contains($l)) {
            $this->doAddEventUsers($l);

            if ($this->eventUserssScheduledForDeletion and $this->eventUserssScheduledForDeletion->contains($l)) {
                $this->eventUserssScheduledForDeletion->remove($this->eventUserssScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventUsers $eventUsers The ChildEventUsers object to add.
     */
    protected function doAddEventUsers(ChildEventUsers $eventUsers)
    {
        $this->collEventUserss[]= $eventUsers;
        $eventUsers->setEvent($this);
    }

    /**
     * @param  ChildEventUsers $eventUsers The ChildEventUsers object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeEventUsers(ChildEventUsers $eventUsers)
    {
        if ($this->getEventUserss()->contains($eventUsers)) {
            $pos = $this->collEventUserss->search($eventUsers);
            $this->collEventUserss->remove($pos);
            if (null === $this->eventUserssScheduledForDeletion) {
                $this->eventUserssScheduledForDeletion = clone $this->collEventUserss;
                $this->eventUserssScheduledForDeletion->clear();
            }
            $this->eventUserssScheduledForDeletion[]= clone $eventUsers;
            $eventUsers->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related EventUserss from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEventUsers[] List of ChildEventUsers objects
     */
    public function getEventUserssJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventUsersQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getEventUserss($query, $con);
    }

    /**
     * Clears out the collWaitingLists collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addWaitingLists()
     */
    public function clearWaitingLists()
    {
        $this->collWaitingLists = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collWaitingLists collection loaded partially.
     */
    public function resetPartialWaitingLists($v = true)
    {
        $this->collWaitingListsPartial = $v;
    }

    /**
     * Initializes the collWaitingLists collection.
     *
     * By default this just sets the collWaitingLists collection to an empty array (like clearcollWaitingLists());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initWaitingLists($overrideExisting = true)
    {
        if (null !== $this->collWaitingLists && !$overrideExisting) {
            return;
        }

        $collectionClassName = WaitingListTableMap::getTableMap()->getCollectionClassName();

        $this->collWaitingLists = new $collectionClassName;
        $this->collWaitingLists->setModel('\NorthEastEvents\Models\WaitingList');
    }

    /**
     * Gets an array of ChildWaitingList objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildWaitingList[] List of ChildWaitingList objects
     * @throws PropelException
     */
    public function getWaitingLists(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collWaitingListsPartial && !$this->isNew();
        if (null === $this->collWaitingLists || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collWaitingLists) {
                // return empty collection
                $this->initWaitingLists();
            } else {
                $collWaitingLists = ChildWaitingListQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collWaitingListsPartial && count($collWaitingLists)) {
                        $this->initWaitingLists(false);

                        foreach ($collWaitingLists as $obj) {
                            if (false == $this->collWaitingLists->contains($obj)) {
                                $this->collWaitingLists->append($obj);
                            }
                        }

                        $this->collWaitingListsPartial = true;
                    }

                    return $collWaitingLists;
                }

                if ($partial && $this->collWaitingLists) {
                    foreach ($this->collWaitingLists as $obj) {
                        if ($obj->isNew()) {
                            $collWaitingLists[] = $obj;
                        }
                    }
                }

                $this->collWaitingLists = $collWaitingLists;
                $this->collWaitingListsPartial = false;
            }
        }

        return $this->collWaitingLists;
    }

    /**
     * Sets a collection of ChildWaitingList objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $waitingLists A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setWaitingLists(Collection $waitingLists, ConnectionInterface $con = null)
    {
        /** @var ChildWaitingList[] $waitingListsToDelete */
        $waitingListsToDelete = $this->getWaitingLists(new Criteria(), $con)->diff($waitingLists);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->waitingListsScheduledForDeletion = clone $waitingListsToDelete;

        foreach ($waitingListsToDelete as $waitingListRemoved) {
            $waitingListRemoved->setEvent(null);
        }

        $this->collWaitingLists = null;
        foreach ($waitingLists as $waitingList) {
            $this->addWaitingList($waitingList);
        }

        $this->collWaitingLists = $waitingLists;
        $this->collWaitingListsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related WaitingList objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related WaitingList objects.
     * @throws PropelException
     */
    public function countWaitingLists(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collWaitingListsPartial && !$this->isNew();
        if (null === $this->collWaitingLists || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collWaitingLists) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getWaitingLists());
            }

            $query = ChildWaitingListQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collWaitingLists);
    }

    /**
     * Method called to associate a ChildWaitingList object to this object
     * through the ChildWaitingList foreign key attribute.
     *
     * @param  ChildWaitingList $l ChildWaitingList
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function addWaitingList(ChildWaitingList $l)
    {
        if ($this->collWaitingLists === null) {
            $this->initWaitingLists();
            $this->collWaitingListsPartial = true;
        }

        if (!$this->collWaitingLists->contains($l)) {
            $this->doAddWaitingList($l);

            if ($this->waitingListsScheduledForDeletion and $this->waitingListsScheduledForDeletion->contains($l)) {
                $this->waitingListsScheduledForDeletion->remove($this->waitingListsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildWaitingList $waitingList The ChildWaitingList object to add.
     */
    protected function doAddWaitingList(ChildWaitingList $waitingList)
    {
        $this->collWaitingLists[]= $waitingList;
        $waitingList->setEvent($this);
    }

    /**
     * @param  ChildWaitingList $waitingList The ChildWaitingList object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeWaitingList(ChildWaitingList $waitingList)
    {
        if ($this->getWaitingLists()->contains($waitingList)) {
            $pos = $this->collWaitingLists->search($waitingList);
            $this->collWaitingLists->remove($pos);
            if (null === $this->waitingListsScheduledForDeletion) {
                $this->waitingListsScheduledForDeletion = clone $this->collWaitingLists;
                $this->waitingListsScheduledForDeletion->clear();
            }
            $this->waitingListsScheduledForDeletion[]= clone $waitingList;
            $waitingList->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related WaitingLists from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildWaitingList[] List of ChildWaitingList objects
     */
    public function getWaitingListsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildWaitingListQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getWaitingLists($query, $con);
    }

    /**
     * Clears out the collEventRatings collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addEventRatings()
     */
    public function clearEventRatings()
    {
        $this->collEventRatings = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collEventRatings collection loaded partially.
     */
    public function resetPartialEventRatings($v = true)
    {
        $this->collEventRatingsPartial = $v;
    }

    /**
     * Initializes the collEventRatings collection.
     *
     * By default this just sets the collEventRatings collection to an empty array (like clearcollEventRatings());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initEventRatings($overrideExisting = true)
    {
        if (null !== $this->collEventRatings && !$overrideExisting) {
            return;
        }

        $collectionClassName = EventRatingTableMap::getTableMap()->getCollectionClassName();

        $this->collEventRatings = new $collectionClassName;
        $this->collEventRatings->setModel('\NorthEastEvents\Models\EventRating');
    }

    /**
     * Gets an array of ChildEventRating objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildEventRating[] List of ChildEventRating objects
     * @throws PropelException
     */
    public function getEventRatings(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collEventRatingsPartial && !$this->isNew();
        if (null === $this->collEventRatings || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collEventRatings) {
                // return empty collection
                $this->initEventRatings();
            } else {
                $collEventRatings = ChildEventRatingQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collEventRatingsPartial && count($collEventRatings)) {
                        $this->initEventRatings(false);

                        foreach ($collEventRatings as $obj) {
                            if (false == $this->collEventRatings->contains($obj)) {
                                $this->collEventRatings->append($obj);
                            }
                        }

                        $this->collEventRatingsPartial = true;
                    }

                    return $collEventRatings;
                }

                if ($partial && $this->collEventRatings) {
                    foreach ($this->collEventRatings as $obj) {
                        if ($obj->isNew()) {
                            $collEventRatings[] = $obj;
                        }
                    }
                }

                $this->collEventRatings = $collEventRatings;
                $this->collEventRatingsPartial = false;
            }
        }

        return $this->collEventRatings;
    }

    /**
     * Sets a collection of ChildEventRating objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $eventRatings A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setEventRatings(Collection $eventRatings, ConnectionInterface $con = null)
    {
        /** @var ChildEventRating[] $eventRatingsToDelete */
        $eventRatingsToDelete = $this->getEventRatings(new Criteria(), $con)->diff($eventRatings);


        //since at least one column in the foreign key is at the same time a PK
        //we can not just set a PK to NULL in the lines below. We have to store
        //a backup of all values, so we are able to manipulate these items based on the onDelete value later.
        $this->eventRatingsScheduledForDeletion = clone $eventRatingsToDelete;

        foreach ($eventRatingsToDelete as $eventRatingRemoved) {
            $eventRatingRemoved->setEvent(null);
        }

        $this->collEventRatings = null;
        foreach ($eventRatings as $eventRating) {
            $this->addEventRating($eventRating);
        }

        $this->collEventRatings = $eventRatings;
        $this->collEventRatingsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related EventRating objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related EventRating objects.
     * @throws PropelException
     */
    public function countEventRatings(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collEventRatingsPartial && !$this->isNew();
        if (null === $this->collEventRatings || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collEventRatings) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getEventRatings());
            }

            $query = ChildEventRatingQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collEventRatings);
    }

    /**
     * Method called to associate a ChildEventRating object to this object
     * through the ChildEventRating foreign key attribute.
     *
     * @param  ChildEventRating $l ChildEventRating
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function addEventRating(ChildEventRating $l)
    {
        if ($this->collEventRatings === null) {
            $this->initEventRatings();
            $this->collEventRatingsPartial = true;
        }

        if (!$this->collEventRatings->contains($l)) {
            $this->doAddEventRating($l);

            if ($this->eventRatingsScheduledForDeletion and $this->eventRatingsScheduledForDeletion->contains($l)) {
                $this->eventRatingsScheduledForDeletion->remove($this->eventRatingsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildEventRating $eventRating The ChildEventRating object to add.
     */
    protected function doAddEventRating(ChildEventRating $eventRating)
    {
        $this->collEventRatings[]= $eventRating;
        $eventRating->setEvent($this);
    }

    /**
     * @param  ChildEventRating $eventRating The ChildEventRating object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeEventRating(ChildEventRating $eventRating)
    {
        if ($this->getEventRatings()->contains($eventRating)) {
            $pos = $this->collEventRatings->search($eventRating);
            $this->collEventRatings->remove($pos);
            if (null === $this->eventRatingsScheduledForDeletion) {
                $this->eventRatingsScheduledForDeletion = clone $this->collEventRatings;
                $this->eventRatingsScheduledForDeletion->clear();
            }
            $this->eventRatingsScheduledForDeletion[]= clone $eventRating;
            $eventRating->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related EventRatings from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildEventRating[] List of ChildEventRating objects
     */
    public function getEventRatingsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildEventRatingQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getEventRatings($query, $con);
    }

    /**
     * Clears out the collThreads collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addThreads()
     */
    public function clearThreads()
    {
        $this->collThreads = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collThreads collection loaded partially.
     */
    public function resetPartialThreads($v = true)
    {
        $this->collThreadsPartial = $v;
    }

    /**
     * Initializes the collThreads collection.
     *
     * By default this just sets the collThreads collection to an empty array (like clearcollThreads());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initThreads($overrideExisting = true)
    {
        if (null !== $this->collThreads && !$overrideExisting) {
            return;
        }

        $collectionClassName = ThreadTableMap::getTableMap()->getCollectionClassName();

        $this->collThreads = new $collectionClassName;
        $this->collThreads->setModel('\NorthEastEvents\Models\Thread');
    }

    /**
     * Gets an array of ChildThread objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildThread[] List of ChildThread objects
     * @throws PropelException
     */
    public function getThreads(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collThreadsPartial && !$this->isNew();
        if (null === $this->collThreads || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collThreads) {
                // return empty collection
                $this->initThreads();
            } else {
                $collThreads = ChildThreadQuery::create(null, $criteria)
                    ->filterByEvent($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collThreadsPartial && count($collThreads)) {
                        $this->initThreads(false);

                        foreach ($collThreads as $obj) {
                            if (false == $this->collThreads->contains($obj)) {
                                $this->collThreads->append($obj);
                            }
                        }

                        $this->collThreadsPartial = true;
                    }

                    return $collThreads;
                }

                if ($partial && $this->collThreads) {
                    foreach ($this->collThreads as $obj) {
                        if ($obj->isNew()) {
                            $collThreads[] = $obj;
                        }
                    }
                }

                $this->collThreads = $collThreads;
                $this->collThreadsPartial = false;
            }
        }

        return $this->collThreads;
    }

    /**
     * Sets a collection of ChildThread objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $threads A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setThreads(Collection $threads, ConnectionInterface $con = null)
    {
        /** @var ChildThread[] $threadsToDelete */
        $threadsToDelete = $this->getThreads(new Criteria(), $con)->diff($threads);


        $this->threadsScheduledForDeletion = $threadsToDelete;

        foreach ($threadsToDelete as $threadRemoved) {
            $threadRemoved->setEvent(null);
        }

        $this->collThreads = null;
        foreach ($threads as $thread) {
            $this->addThread($thread);
        }

        $this->collThreads = $threads;
        $this->collThreadsPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Thread objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Thread objects.
     * @throws PropelException
     */
    public function countThreads(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collThreadsPartial && !$this->isNew();
        if (null === $this->collThreads || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collThreads) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getThreads());
            }

            $query = ChildThreadQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByEvent($this)
                ->count($con);
        }

        return count($this->collThreads);
    }

    /**
     * Method called to associate a ChildThread object to this object
     * through the ChildThread foreign key attribute.
     *
     * @param  ChildThread $l ChildThread
     * @return $this|\NorthEastEvents\Models\Event The current object (for fluent API support)
     */
    public function addThread(ChildThread $l)
    {
        if ($this->collThreads === null) {
            $this->initThreads();
            $this->collThreadsPartial = true;
        }

        if (!$this->collThreads->contains($l)) {
            $this->doAddThread($l);

            if ($this->threadsScheduledForDeletion and $this->threadsScheduledForDeletion->contains($l)) {
                $this->threadsScheduledForDeletion->remove($this->threadsScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildThread $thread The ChildThread object to add.
     */
    protected function doAddThread(ChildThread $thread)
    {
        $this->collThreads[]= $thread;
        $thread->setEvent($this);
    }

    /**
     * @param  ChildThread $thread The ChildThread object to remove.
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function removeThread(ChildThread $thread)
    {
        if ($this->getThreads()->contains($thread)) {
            $pos = $this->collThreads->search($thread);
            $this->collThreads->remove($pos);
            if (null === $this->threadsScheduledForDeletion) {
                $this->threadsScheduledForDeletion = clone $this->collThreads;
                $this->threadsScheduledForDeletion->clear();
            }
            $this->threadsScheduledForDeletion[]= clone $thread;
            $thread->setEvent(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Event is new, it will return
     * an empty collection; or if this Event has previously
     * been saved, it will retrieve related Threads from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Event.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildThread[] List of ChildThread objects
     */
    public function getThreadsJoinUser(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildThreadQuery::create(null, $criteria);
        $query->joinWith('User', $joinBehavior);

        return $this->getThreads($query, $con);
    }

    /**
     * Clears out the collUsers collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addUsers()
     */
    public function clearUsers()
    {
        $this->collUsers = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Initializes the collUsers crossRef collection.
     *
     * By default this just sets the collUsers collection to an empty collection (like clearUsers());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @return void
     */
    public function initUsers()
    {
        $collectionClassName = EventUsersTableMap::getTableMap()->getCollectionClassName();

        $this->collUsers = new $collectionClassName;
        $this->collUsersPartial = true;
        $this->collUsers->setModel('\NorthEastEvents\Models\User');
    }

    /**
     * Checks if the collUsers collection is loaded.
     *
     * @return bool
     */
    public function isUsersLoaded()
    {
        return null !== $this->collUsers;
    }

    /**
     * Gets a collection of ChildUser objects related by a many-to-many relationship
     * to the current object by way of the event_users cross-reference table.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildEvent is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return ObjectCollection|ChildUser[] List of ChildUser objects
     */
    public function getUsers(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew()) {
                // return empty collection
                if (null === $this->collUsers) {
                    $this->initUsers();
                }
            } else {

                $query = ChildUserQuery::create(null, $criteria)
                    ->filterByEvent($this);
                $collUsers = $query->find($con);
                if (null !== $criteria) {
                    return $collUsers;
                }

                if ($partial && $this->collUsers) {
                    //make sure that already added objects gets added to the list of the database.
                    foreach ($this->collUsers as $obj) {
                        if (!$collUsers->contains($obj)) {
                            $collUsers[] = $obj;
                        }
                    }
                }

                $this->collUsers = $collUsers;
                $this->collUsersPartial = false;
            }
        }

        return $this->collUsers;
    }

    /**
     * Sets a collection of User objects related by a many-to-many relationship
     * to the current object by way of the event_users cross-reference table.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param  Collection $users A Propel collection.
     * @param  ConnectionInterface $con Optional connection object
     * @return $this|ChildEvent The current object (for fluent API support)
     */
    public function setUsers(Collection $users, ConnectionInterface $con = null)
    {
        $this->clearUsers();
        $currentUsers = $this->getUsers();

        $usersScheduledForDeletion = $currentUsers->diff($users);

        foreach ($usersScheduledForDeletion as $toDelete) {
            $this->removeUser($toDelete);
        }

        foreach ($users as $user) {
            if (!$currentUsers->contains($user)) {
                $this->doAddUser($user);
            }
        }

        $this->collUsersPartial = false;
        $this->collUsers = $users;

        return $this;
    }

    /**
     * Gets the number of User objects related by a many-to-many relationship
     * to the current object by way of the event_users cross-reference table.
     *
     * @param      Criteria $criteria Optional query object to filter the query
     * @param      boolean $distinct Set to true to force count distinct
     * @param      ConnectionInterface $con Optional connection object
     *
     * @return int the number of related User objects
     */
    public function countUsers(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collUsersPartial && !$this->isNew();
        if (null === $this->collUsers || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collUsers) {
                return 0;
            } else {

                if ($partial && !$criteria) {
                    return count($this->getUsers());
                }

                $query = ChildUserQuery::create(null, $criteria);
                if ($distinct) {
                    $query->distinct();
                }

                return $query
                    ->filterByEvent($this)
                    ->count($con);
            }
        } else {
            return count($this->collUsers);
        }
    }

    /**
     * Associate a ChildUser to this object
     * through the event_users cross reference table.
     *
     * @param ChildUser $user
     * @return ChildEvent The current object (for fluent API support)
     */
    public function addUser(ChildUser $user)
    {
        if ($this->collUsers === null) {
            $this->initUsers();
        }

        if (!$this->getUsers()->contains($user)) {
            // only add it if the **same** object is not already associated
            $this->collUsers->push($user);
            $this->doAddUser($user);
        }

        return $this;
    }

    /**
     *
     * @param ChildUser $user
     */
    protected function doAddUser(ChildUser $user)
    {
        $eventUsers = new ChildEventUsers();

        $eventUsers->setUser($user);

        $eventUsers->setEvent($this);

        $this->addEventUsers($eventUsers);

        // set the back reference to this object directly as using provided method either results
        // in endless loop or in multiple relations
        if (!$user->isEventsLoaded()) {
            $user->initEvents();
            $user->getEvents()->push($this);
        } elseif (!$user->getEvents()->contains($this)) {
            $user->getEvents()->push($this);
        }

    }

    /**
     * Remove user of this object
     * through the event_users cross reference table.
     *
     * @param ChildUser $user
     * @return ChildEvent The current object (for fluent API support)
     */
    public function removeUser(ChildUser $user)
    {
        if ($this->getUsers()->contains($user)) { $eventUsers = new ChildEventUsers();

            $eventUsers->setUser($user);
            if ($user->isEventsLoaded()) {
                //remove the back reference if available
                $user->getEvents()->removeObject($this);
            }

            $eventUsers->setEvent($this);
            $this->removeEventUsers(clone $eventUsers);
            $eventUsers->clear();

            $this->collUsers->remove($this->collUsers->search($user));

            if (null === $this->usersScheduledForDeletion) {
                $this->usersScheduledForDeletion = clone $this->collUsers;
                $this->usersScheduledForDeletion->clear();
            }

            $this->usersScheduledForDeletion->push($user);
        }


        return $this;
    }

    /**
     * Clears the current object, sets all attributes to their default values and removes
     * outgoing references as well as back-references (from other objects to this one. Results probably in a database
     * change of those foreign objects when you call `save` there).
     */
    public function clear()
    {
        if (null !== $this->aCharity) {
            $this->aCharity->removeEvent($this);
        }
        $this->id = null;
        $this->charityid = null;
        $this->title = null;
        $this->date = null;
        $this->location = null;
        $this->image_url = null;
        $this->body = null;
        $this->bodyhtml = null;
        $this->tickets = null;
        $this->video_url = null;
        $this->created_at = null;
        $this->updated_at = null;
        $this->tickets_remaining = null;
        $this->average_rating = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->applyDefaultValues();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    /**
     * Resets all references and back-references to other model objects or collections of model objects.
     *
     * This method is used to reset all php object references (not the actual reference in the database).
     * Necessary for object serialisation.
     *
     * @param      boolean $deep Whether to also clear the references on all referrer objects.
     */
    public function clearAllReferences($deep = false)
    {
        if ($deep) {
            if ($this->collEventUserss) {
                foreach ($this->collEventUserss as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collWaitingLists) {
                foreach ($this->collWaitingLists as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collEventRatings) {
                foreach ($this->collEventRatings as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collThreads) {
                foreach ($this->collThreads as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collUsers) {
                foreach ($this->collUsers as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collEventUserss = null;
        $this->collWaitingLists = null;
        $this->collEventRatings = null;
        $this->collThreads = null;
        $this->collUsers = null;
        $this->aCharity = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(EventTableMap::DEFAULT_STRING_FORMAT);
    }

    // timestampable behavior

    /**
     * Mark the current object so that the update date doesn't get updated during next save
     *
     * @return     $this|ChildEvent The current object (for fluent API support)
     */
    public function keepUpdateDateUnchanged()
    {
        $this->modifiedColumns[EventTableMap::COL_UPDATED_AT] = true;

        return $this;
    }

    // aggregate_column behavior

    /**
     * Computes the value of the aggregate column tickets_remaining *
     * @param ConnectionInterface $con A connection object
     *
     * @return mixed The scalar result from the aggregate query
     */
    public function computeTicketsRemaining(ConnectionInterface $con)
    {
        $stmt = $con->prepare('SELECT COUNT(userID) FROM event_users WHERE event_users.EVENTID = :p1');
        $stmt->bindValue(':p1', $this->getId());
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Updates the aggregate column tickets_remaining *
     * @param ConnectionInterface $con A connection object
     */
    public function updateTicketsRemaining(ConnectionInterface $con)
    {
        $this->setTicketsRemaining($this->computeTicketsRemaining($con));
        $this->save($con);
    }

    // 2 behavior

    /**
     * Computes the value of the aggregate column average_rating *
     * @param ConnectionInterface $con A connection object
     *
     * @return mixed The scalar result from the aggregate query
     */
    public function computeAverageRating(ConnectionInterface $con)
    {
        $stmt = $con->prepare('SELECT AVG(rating) FROM event_rating WHERE event_rating.EVENTID = :p1');
        $stmt->bindValue(':p1', $this->getId());
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Updates the aggregate column average_rating *
     * @param ConnectionInterface $con A connection object
     */
    public function updateAverageRating(ConnectionInterface $con)
    {
        $this->setAverageRating($this->computeAverageRating($con));
        $this->save($con);
    }

    /**
     * Code to be run before persisting the object
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preSave')) {
            return parent::preSave($con);
        }
        return true;
    }

    /**
     * Code to be run after persisting the object
     * @param ConnectionInterface $con
     */
    public function postSave(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postSave')) {
            parent::postSave($con);
        }
    }

    /**
     * Code to be run before inserting to database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preInsert')) {
            return parent::preInsert($con);
        }
        return true;
    }

    /**
     * Code to be run after inserting to database
     * @param ConnectionInterface $con
     */
    public function postInsert(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postInsert')) {
            parent::postInsert($con);
        }
    }

    /**
     * Code to be run before updating the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preUpdate')) {
            return parent::preUpdate($con);
        }
        return true;
    }

    /**
     * Code to be run after updating the object in database
     * @param ConnectionInterface $con
     */
    public function postUpdate(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postUpdate')) {
            parent::postUpdate($con);
        }
    }

    /**
     * Code to be run before deleting the object in database
     * @param  ConnectionInterface $con
     * @return boolean
     */
    public function preDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::preDelete')) {
            return parent::preDelete($con);
        }
        return true;
    }

    /**
     * Code to be run after deleting the object in database
     * @param ConnectionInterface $con
     */
    public function postDelete(ConnectionInterface $con = null)
    {
        if (is_callable('parent::postDelete')) {
            parent::postDelete($con);
        }
    }


    /**
     * Derived method to catches calls to undefined methods.
     *
     * Provides magic import/export method support (fromXML()/toXML(), fromYAML()/toYAML(), etc.).
     * Allows to define default __call() behavior if you overwrite __call()
     *
     * @param string $name
     * @param mixed  $params
     *
     * @return array|string
     */
    public function __call($name, $params)
    {
        if (0 === strpos($name, 'get')) {
            $virtualColumn = substr($name, 3);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }

            $virtualColumn = lcfirst($virtualColumn);
            if ($this->hasVirtualColumn($virtualColumn)) {
                return $this->getVirtualColumn($virtualColumn);
            }
        }

        if (0 === strpos($name, 'from')) {
            $format = substr($name, 4);

            return $this->importFrom($format, reset($params));
        }

        if (0 === strpos($name, 'to')) {
            $format = substr($name, 2);
            $includeLazyLoadColumns = isset($params[0]) ? $params[0] : true;

            return $this->exportTo($format, $includeLazyLoadColumns);
        }

        throw new BadMethodCallException(sprintf('Call to undefined method: %s.', $name));
    }

}
