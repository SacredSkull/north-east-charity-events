<?php

namespace NorthEastEvents\Models\Map;

use NorthEastEvents\Models\Event;
use NorthEastEvents\Models\EventQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


/**
 * This class defines the structure of the 'event' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class EventTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'NorthEastEvents.Models.Map.EventTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'default';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'event';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\NorthEastEvents\\Models\\Event';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'NorthEastEvents.Models.Event';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 14;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 14;

    /**
     * the column name for the id field
     */
    const COL_ID = 'event.id';

    /**
     * the column name for the charityID field
     */
    const COL_CHARITYID = 'event.charityID';

    /**
     * the column name for the title field
     */
    const COL_TITLE = 'event.title';

    /**
     * the column name for the date field
     */
    const COL_DATE = 'event.date';

    /**
     * the column name for the location field
     */
    const COL_LOCATION = 'event.location';

    /**
     * the column name for the image_url field
     */
    const COL_IMAGE_URL = 'event.image_url';

    /**
     * the column name for the body field
     */
    const COL_BODY = 'event.body';

    /**
     * the column name for the bodyHTML field
     */
    const COL_BODYHTML = 'event.bodyHTML';

    /**
     * the column name for the tickets field
     */
    const COL_TICKETS = 'event.tickets';

    /**
     * the column name for the video_url field
     */
    const COL_VIDEO_URL = 'event.video_url';

    /**
     * the column name for the created_at field
     */
    const COL_CREATED_AT = 'event.created_at';

    /**
     * the column name for the updated_at field
     */
    const COL_UPDATED_AT = 'event.updated_at';

    /**
     * the column name for the tickets_remaining field
     */
    const COL_TICKETS_REMAINING = 'event.tickets_remaining';

    /**
     * the column name for the average_rating field
     */
    const COL_AVERAGE_RATING = 'event.average_rating';

    /**
     * The default string format for model objects of the related table
     */
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldNames[self::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'CharityID', 'Title', 'Date', 'Location', 'ImageUrl', 'Body', 'BodyHTML', 'Tickets', 'VideoUrl', 'CreatedAt', 'UpdatedAt', 'TicketsRemaining', 'AverageRating', ),
        self::TYPE_CAMELNAME     => array('id', 'charityID', 'title', 'date', 'location', 'imageUrl', 'body', 'bodyHTML', 'tickets', 'videoUrl', 'createdAt', 'updatedAt', 'ticketsRemaining', 'averageRating', ),
        self::TYPE_COLNAME       => array(EventTableMap::COL_ID, EventTableMap::COL_CHARITYID, EventTableMap::COL_TITLE, EventTableMap::COL_DATE, EventTableMap::COL_LOCATION, EventTableMap::COL_IMAGE_URL, EventTableMap::COL_BODY, EventTableMap::COL_BODYHTML, EventTableMap::COL_TICKETS, EventTableMap::COL_VIDEO_URL, EventTableMap::COL_CREATED_AT, EventTableMap::COL_UPDATED_AT, EventTableMap::COL_TICKETS_REMAINING, EventTableMap::COL_AVERAGE_RATING, ),
        self::TYPE_FIELDNAME     => array('id', 'charityID', 'title', 'date', 'location', 'image_url', 'body', 'bodyHTML', 'tickets', 'video_url', 'created_at', 'updated_at', 'tickets_remaining', 'average_rating', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'CharityID' => 1, 'Title' => 2, 'Date' => 3, 'Location' => 4, 'ImageUrl' => 5, 'Body' => 6, 'BodyHTML' => 7, 'Tickets' => 8, 'VideoUrl' => 9, 'CreatedAt' => 10, 'UpdatedAt' => 11, 'TicketsRemaining' => 12, 'AverageRating' => 13, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'charityID' => 1, 'title' => 2, 'date' => 3, 'location' => 4, 'imageUrl' => 5, 'body' => 6, 'bodyHTML' => 7, 'tickets' => 8, 'videoUrl' => 9, 'createdAt' => 10, 'updatedAt' => 11, 'ticketsRemaining' => 12, 'averageRating' => 13, ),
        self::TYPE_COLNAME       => array(EventTableMap::COL_ID => 0, EventTableMap::COL_CHARITYID => 1, EventTableMap::COL_TITLE => 2, EventTableMap::COL_DATE => 3, EventTableMap::COL_LOCATION => 4, EventTableMap::COL_IMAGE_URL => 5, EventTableMap::COL_BODY => 6, EventTableMap::COL_BODYHTML => 7, EventTableMap::COL_TICKETS => 8, EventTableMap::COL_VIDEO_URL => 9, EventTableMap::COL_CREATED_AT => 10, EventTableMap::COL_UPDATED_AT => 11, EventTableMap::COL_TICKETS_REMAINING => 12, EventTableMap::COL_AVERAGE_RATING => 13, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'charityID' => 1, 'title' => 2, 'date' => 3, 'location' => 4, 'image_url' => 5, 'body' => 6, 'bodyHTML' => 7, 'tickets' => 8, 'video_url' => 9, 'created_at' => 10, 'updated_at' => 11, 'tickets_remaining' => 12, 'average_rating' => 13, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, )
    );

    /**
     * Initialize the table attributes and columns
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('event');
        $this->setPhpName('Event');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\NorthEastEvents\\Models\\Event');
        $this->setPackage('NorthEastEvents.Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('charityID', 'CharityID', 'INTEGER', 'charity', 'id', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', true, 50, null);
        $this->addColumn('date', 'Date', 'TIMESTAMP', true, null, null);
        $this->addColumn('location', 'Location', 'VARCHAR', true, 50, null);
        $this->addColumn('image_url', 'ImageUrl', 'VARCHAR', false, 128, '/images/events/default.png');
        $this->addColumn('body', 'Body', 'LONGVARCHAR', false, null, null);
        $this->addColumn('bodyHTML', 'BodyHTML', 'LONGVARCHAR', false, null, null);
        $this->addColumn('tickets', 'Tickets', 'INTEGER', false, null, 0);
        $this->addColumn('video_url', 'VideoUrl', 'VARCHAR', false, 128, 'https://www.youtube.com/embed/d5gRPCJPIak');
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('tickets_remaining', 'TicketsRemaining', 'INTEGER', false, null, null);
        $this->addColumn('average_rating', 'AverageRating', 'INTEGER', false, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Charity', '\\NorthEastEvents\\Models\\Charity', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':charityID',
    1 => ':id',
  ),
), 'CASCADE', null, null, false);
        $this->addRelation('EventUsers', '\\NorthEastEvents\\Models\\EventUsers', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':eventID',
    1 => ':id',
  ),
), 'CASCADE', null, 'EventUserss', false);
        $this->addRelation('WaitingList', '\\NorthEastEvents\\Models\\WaitingList', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':eventID',
    1 => ':id',
  ),
), 'CASCADE', null, 'WaitingLists', false);
        $this->addRelation('EventRating', '\\NorthEastEvents\\Models\\EventRating', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':eventID',
    1 => ':id',
  ),
), 'CASCADE', null, 'EventRatings', false);
        $this->addRelation('Thread', '\\NorthEastEvents\\Models\\Thread', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':eventID',
    1 => ':id',
  ),
), 'CASCADE', null, 'Threads', false);
        $this->addRelation('User', '\\NorthEastEvents\\Models\\User', RelationMap::MANY_TO_MANY, array(), 'CASCADE', null, 'Users');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' => array('create_column' => 'created_at', 'update_column' => 'updated_at', 'disable_created_at' => 'false', 'disable_updated_at' => 'false', ),
            'aggregate_column' => array('name' => 'tickets_remaining', 'expression' => 'COUNT(userID)', 'condition' => '', 'foreign_table' => 'event_users', 'foreign_schema' => '', ),
            '2' => array('name' => 'average_rating', 'expression' => 'AVG(rating)', 'condition' => '', 'foreign_table' => 'event_rating', 'foreign_schema' => '', ),
        );
    } // getBehaviors()
    /**
     * Method to invalidate the instance pool of all tables related to event     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        EventUsersTableMap::clearInstancePool();
        WaitingListTableMap::clearInstancePool();
        EventRatingTableMap::clearInstancePool();
        ThreadTableMap::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return string The primary key hash of the row
     */
    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param array  $row       resultset row.
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM
     *
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        return (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
    }

    /**
     * The class that the tableMap will make instances of.
     *
     * If $withPrefix is true, the returned path
     * uses a dot-path notation which is translated into a path
     * relative to a location on the PHP include_path.
     * (e.g. path.to.MyClass -> 'path/to/MyClass.php')
     *
     * @param boolean $withPrefix Whether or not to return the path with the class name
     * @return string path.to.ClassName
     */
    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? EventTableMap::CLASS_DEFAULT : EventTableMap::OM_CLASS;
    }

    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param array  $row       row returned by DataFetcher->fetch().
     * @param int    $offset    The 0-based offset for reading from the resultset row.
     * @param string $indexType The index type of $row. Mostly DataFetcher->getIndexType().
                                 One of the class type constants TableMap::TYPE_PHPNAME, TableMap::TYPE_CAMELNAME
     *                           TableMap::TYPE_COLNAME, TableMap::TYPE_FIELDNAME, TableMap::TYPE_NUM.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     * @return array           (Event object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = EventTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + EventTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = EventTableMap::OM_CLASS;
            /** @var Event $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            EventTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @param DataFetcherInterface $dataFetcher
     * @return array
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = static::getOMClass(false);
        // populate the object(s)
        while ($row = $dataFetcher->fetch()) {
            $key = EventTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = EventTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Event $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                EventTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }
    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param Criteria $criteria object containing the columns to add.
     * @param string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(EventTableMap::COL_ID);
            $criteria->addSelectColumn(EventTableMap::COL_CHARITYID);
            $criteria->addSelectColumn(EventTableMap::COL_TITLE);
            $criteria->addSelectColumn(EventTableMap::COL_DATE);
            $criteria->addSelectColumn(EventTableMap::COL_LOCATION);
            $criteria->addSelectColumn(EventTableMap::COL_IMAGE_URL);
            $criteria->addSelectColumn(EventTableMap::COL_BODY);
            $criteria->addSelectColumn(EventTableMap::COL_BODYHTML);
            $criteria->addSelectColumn(EventTableMap::COL_TICKETS);
            $criteria->addSelectColumn(EventTableMap::COL_VIDEO_URL);
            $criteria->addSelectColumn(EventTableMap::COL_CREATED_AT);
            $criteria->addSelectColumn(EventTableMap::COL_UPDATED_AT);
            $criteria->addSelectColumn(EventTableMap::COL_TICKETS_REMAINING);
            $criteria->addSelectColumn(EventTableMap::COL_AVERAGE_RATING);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.charityID');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.date');
            $criteria->addSelectColumn($alias . '.location');
            $criteria->addSelectColumn($alias . '.image_url');
            $criteria->addSelectColumn($alias . '.body');
            $criteria->addSelectColumn($alias . '.bodyHTML');
            $criteria->addSelectColumn($alias . '.tickets');
            $criteria->addSelectColumn($alias . '.video_url');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.tickets_remaining');
            $criteria->addSelectColumn($alias . '.average_rating');
        }
    }

    /**
     * Returns the TableMap related to this object.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME)->getTable(EventTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(EventTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(EventTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new EventTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Event or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Event object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param  ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \NorthEastEvents\Models\Event) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(EventTableMap::DATABASE_NAME);
            $criteria->add(EventTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = EventQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            EventTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                EventTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the event table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return EventQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Event or Criteria object.
     *
     * @param mixed               $criteria Criteria or Event object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Event object
        }

        if ($criteria->containsKey(EventTableMap::COL_ID) && $criteria->keyContainsValue(EventTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.EventTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = EventQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // EventTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
EventTableMap::buildTableMap();
