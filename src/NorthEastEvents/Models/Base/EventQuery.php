<?php

namespace NorthEastEvents\Models\Base;

use \Exception;
use \PDO;
use NorthEastEvents\Models\Event as ChildEvent;
use NorthEastEvents\Models\EventQuery as ChildEventQuery;
use NorthEastEvents\Models\Map\EventTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'event' table.
 *
 *
 *
 * @method     ChildEventQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildEventQuery orderByCharityID($order = Criteria::ASC) Order by the charityID column
 * @method     ChildEventQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildEventQuery orderByDate($order = Criteria::ASC) Order by the date column
 * @method     ChildEventQuery orderByLocation($order = Criteria::ASC) Order by the location column
 * @method     ChildEventQuery orderByImageUrl($order = Criteria::ASC) Order by the image_url column
 * @method     ChildEventQuery orderByBody($order = Criteria::ASC) Order by the body column
 * @method     ChildEventQuery orderByBodyHTML($order = Criteria::ASC) Order by the bodyHTML column
 * @method     ChildEventQuery orderByTickets($order = Criteria::ASC) Order by the tickets column
 * @method     ChildEventQuery orderByVideoUrl($order = Criteria::ASC) Order by the video_url column
 * @method     ChildEventQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildEventQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method     ChildEventQuery orderByTicketsRemaining($order = Criteria::ASC) Order by the tickets_remaining column
 * @method     ChildEventQuery orderByAverageRating($order = Criteria::ASC) Order by the average_rating column
 *
 * @method     ChildEventQuery groupById() Group by the id column
 * @method     ChildEventQuery groupByCharityID() Group by the charityID column
 * @method     ChildEventQuery groupByTitle() Group by the title column
 * @method     ChildEventQuery groupByDate() Group by the date column
 * @method     ChildEventQuery groupByLocation() Group by the location column
 * @method     ChildEventQuery groupByImageUrl() Group by the image_url column
 * @method     ChildEventQuery groupByBody() Group by the body column
 * @method     ChildEventQuery groupByBodyHTML() Group by the bodyHTML column
 * @method     ChildEventQuery groupByTickets() Group by the tickets column
 * @method     ChildEventQuery groupByVideoUrl() Group by the video_url column
 * @method     ChildEventQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildEventQuery groupByUpdatedAt() Group by the updated_at column
 * @method     ChildEventQuery groupByTicketsRemaining() Group by the tickets_remaining column
 * @method     ChildEventQuery groupByAverageRating() Group by the average_rating column
 *
 * @method     ChildEventQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventQuery leftJoinCharity($relationAlias = null) Adds a LEFT JOIN clause to the query using the Charity relation
 * @method     ChildEventQuery rightJoinCharity($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Charity relation
 * @method     ChildEventQuery innerJoinCharity($relationAlias = null) Adds a INNER JOIN clause to the query using the Charity relation
 *
 * @method     ChildEventQuery joinWithCharity($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Charity relation
 *
 * @method     ChildEventQuery leftJoinWithCharity() Adds a LEFT JOIN clause and with to the query using the Charity relation
 * @method     ChildEventQuery rightJoinWithCharity() Adds a RIGHT JOIN clause and with to the query using the Charity relation
 * @method     ChildEventQuery innerJoinWithCharity() Adds a INNER JOIN clause and with to the query using the Charity relation
 *
 * @method     ChildEventQuery leftJoinEventUsers($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventUsers relation
 * @method     ChildEventQuery rightJoinEventUsers($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventUsers relation
 * @method     ChildEventQuery innerJoinEventUsers($relationAlias = null) Adds a INNER JOIN clause to the query using the EventUsers relation
 *
 * @method     ChildEventQuery joinWithEventUsers($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventUsers relation
 *
 * @method     ChildEventQuery leftJoinWithEventUsers() Adds a LEFT JOIN clause and with to the query using the EventUsers relation
 * @method     ChildEventQuery rightJoinWithEventUsers() Adds a RIGHT JOIN clause and with to the query using the EventUsers relation
 * @method     ChildEventQuery innerJoinWithEventUsers() Adds a INNER JOIN clause and with to the query using the EventUsers relation
 *
 * @method     ChildEventQuery leftJoinWaitingList($relationAlias = null) Adds a LEFT JOIN clause to the query using the WaitingList relation
 * @method     ChildEventQuery rightJoinWaitingList($relationAlias = null) Adds a RIGHT JOIN clause to the query using the WaitingList relation
 * @method     ChildEventQuery innerJoinWaitingList($relationAlias = null) Adds a INNER JOIN clause to the query using the WaitingList relation
 *
 * @method     ChildEventQuery joinWithWaitingList($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the WaitingList relation
 *
 * @method     ChildEventQuery leftJoinWithWaitingList() Adds a LEFT JOIN clause and with to the query using the WaitingList relation
 * @method     ChildEventQuery rightJoinWithWaitingList() Adds a RIGHT JOIN clause and with to the query using the WaitingList relation
 * @method     ChildEventQuery innerJoinWithWaitingList() Adds a INNER JOIN clause and with to the query using the WaitingList relation
 *
 * @method     ChildEventQuery leftJoinEventRating($relationAlias = null) Adds a LEFT JOIN clause to the query using the EventRating relation
 * @method     ChildEventQuery rightJoinEventRating($relationAlias = null) Adds a RIGHT JOIN clause to the query using the EventRating relation
 * @method     ChildEventQuery innerJoinEventRating($relationAlias = null) Adds a INNER JOIN clause to the query using the EventRating relation
 *
 * @method     ChildEventQuery joinWithEventRating($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the EventRating relation
 *
 * @method     ChildEventQuery leftJoinWithEventRating() Adds a LEFT JOIN clause and with to the query using the EventRating relation
 * @method     ChildEventQuery rightJoinWithEventRating() Adds a RIGHT JOIN clause and with to the query using the EventRating relation
 * @method     ChildEventQuery innerJoinWithEventRating() Adds a INNER JOIN clause and with to the query using the EventRating relation
 *
 * @method     ChildEventQuery leftJoinThread($relationAlias = null) Adds a LEFT JOIN clause to the query using the Thread relation
 * @method     ChildEventQuery rightJoinThread($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Thread relation
 * @method     ChildEventQuery innerJoinThread($relationAlias = null) Adds a INNER JOIN clause to the query using the Thread relation
 *
 * @method     ChildEventQuery joinWithThread($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Thread relation
 *
 * @method     ChildEventQuery leftJoinWithThread() Adds a LEFT JOIN clause and with to the query using the Thread relation
 * @method     ChildEventQuery rightJoinWithThread() Adds a RIGHT JOIN clause and with to the query using the Thread relation
 * @method     ChildEventQuery innerJoinWithThread() Adds a INNER JOIN clause and with to the query using the Thread relation
 *
 * @method     \NorthEastEvents\Models\CharityQuery|\NorthEastEvents\Models\EventUsersQuery|\NorthEastEvents\Models\WaitingListQuery|\NorthEastEvents\Models\EventRatingQuery|\NorthEastEvents\Models\ThreadQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEvent findOne(ConnectionInterface $con = null) Return the first ChildEvent matching the query
 * @method     ChildEvent findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEvent matching the query, or a new ChildEvent object populated from the query conditions when no match is found
 *
 * @method     ChildEvent findOneById(int $id) Return the first ChildEvent filtered by the id column
 * @method     ChildEvent findOneByCharityID(int $charityID) Return the first ChildEvent filtered by the charityID column
 * @method     ChildEvent findOneByTitle(string $title) Return the first ChildEvent filtered by the title column
 * @method     ChildEvent findOneByDate(string $date) Return the first ChildEvent filtered by the date column
 * @method     ChildEvent findOneByLocation(string $location) Return the first ChildEvent filtered by the location column
 * @method     ChildEvent findOneByImageUrl(string $image_url) Return the first ChildEvent filtered by the image_url column
 * @method     ChildEvent findOneByBody(string $body) Return the first ChildEvent filtered by the body column
 * @method     ChildEvent findOneByBodyHTML(string $bodyHTML) Return the first ChildEvent filtered by the bodyHTML column
 * @method     ChildEvent findOneByTickets(int $tickets) Return the first ChildEvent filtered by the tickets column
 * @method     ChildEvent findOneByVideoUrl(string $video_url) Return the first ChildEvent filtered by the video_url column
 * @method     ChildEvent findOneByCreatedAt(string $created_at) Return the first ChildEvent filtered by the created_at column
 * @method     ChildEvent findOneByUpdatedAt(string $updated_at) Return the first ChildEvent filtered by the updated_at column
 * @method     ChildEvent findOneByTicketsRemaining(int $tickets_remaining) Return the first ChildEvent filtered by the tickets_remaining column
 * @method     ChildEvent findOneByAverageRating(int $average_rating) Return the first ChildEvent filtered by the average_rating column *

 * @method     ChildEvent requirePk($key, ConnectionInterface $con = null) Return the ChildEvent by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOne(ConnectionInterface $con = null) Return the first ChildEvent matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent requireOneById(int $id) Return the first ChildEvent filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByCharityID(int $charityID) Return the first ChildEvent filtered by the charityID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByTitle(string $title) Return the first ChildEvent filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByDate(string $date) Return the first ChildEvent filtered by the date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByLocation(string $location) Return the first ChildEvent filtered by the location column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByImageUrl(string $image_url) Return the first ChildEvent filtered by the image_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByBody(string $body) Return the first ChildEvent filtered by the body column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByBodyHTML(string $bodyHTML) Return the first ChildEvent filtered by the bodyHTML column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByTickets(int $tickets) Return the first ChildEvent filtered by the tickets column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByVideoUrl(string $video_url) Return the first ChildEvent filtered by the video_url column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByCreatedAt(string $created_at) Return the first ChildEvent filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByUpdatedAt(string $updated_at) Return the first ChildEvent filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByTicketsRemaining(int $tickets_remaining) Return the first ChildEvent filtered by the tickets_remaining column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEvent requireOneByAverageRating(int $average_rating) Return the first ChildEvent filtered by the average_rating column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEvent[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEvent objects based on current ModelCriteria
 * @method     ChildEvent[]|ObjectCollection findById(int $id) Return ChildEvent objects filtered by the id column
 * @method     ChildEvent[]|ObjectCollection findByCharityID(int $charityID) Return ChildEvent objects filtered by the charityID column
 * @method     ChildEvent[]|ObjectCollection findByTitle(string $title) Return ChildEvent objects filtered by the title column
 * @method     ChildEvent[]|ObjectCollection findByDate(string $date) Return ChildEvent objects filtered by the date column
 * @method     ChildEvent[]|ObjectCollection findByLocation(string $location) Return ChildEvent objects filtered by the location column
 * @method     ChildEvent[]|ObjectCollection findByImageUrl(string $image_url) Return ChildEvent objects filtered by the image_url column
 * @method     ChildEvent[]|ObjectCollection findByBody(string $body) Return ChildEvent objects filtered by the body column
 * @method     ChildEvent[]|ObjectCollection findByBodyHTML(string $bodyHTML) Return ChildEvent objects filtered by the bodyHTML column
 * @method     ChildEvent[]|ObjectCollection findByTickets(int $tickets) Return ChildEvent objects filtered by the tickets column
 * @method     ChildEvent[]|ObjectCollection findByVideoUrl(string $video_url) Return ChildEvent objects filtered by the video_url column
 * @method     ChildEvent[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildEvent objects filtered by the created_at column
 * @method     ChildEvent[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildEvent objects filtered by the updated_at column
 * @method     ChildEvent[]|ObjectCollection findByTicketsRemaining(int $tickets_remaining) Return ChildEvent objects filtered by the tickets_remaining column
 * @method     ChildEvent[]|ObjectCollection findByAverageRating(int $average_rating) Return ChildEvent objects filtered by the average_rating column
 * @method     ChildEvent[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \NorthEastEvents\Models\Base\EventQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\NorthEastEvents\\Models\\Event', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventQuery) {
            return $criteria;
        }
        $query = new ChildEventQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
            // the object is already in the instance pool
            return $obj;
        }

        return $this->findPkSimple($key, $con);
    }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEvent A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, charityID, title, date, location, image_url, body, bodyHTML, tickets, video_url, created_at, updated_at, tickets_remaining, average_rating FROM event WHERE id = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildEvent $obj */
            $obj = new ChildEvent();
            $obj->hydrate($row);
            EventTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     ConnectionInterface $con A connection object
     *
     * @return ChildEvent|array|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, ConnectionInterface $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     ConnectionInterface $con an optional connection object
     *
     * @return ObjectCollection|array|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(EventTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(EventTableMap::COL_ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id > 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(EventTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(EventTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the charityID column
     *
     * Example usage:
     * <code>
     * $query->filterByCharityID(1234); // WHERE charityID = 1234
     * $query->filterByCharityID(array(12, 34)); // WHERE charityID IN (12, 34)
     * $query->filterByCharityID(array('min' => 12)); // WHERE charityID > 12
     * </code>
     *
     * @see       filterByCharity()
     *
     * @param     mixed $charityID The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByCharityID($charityID = null, $comparison = null)
    {
        if (is_array($charityID)) {
            $useMinMax = false;
            if (isset($charityID['min'])) {
                $this->addUsingAlias(EventTableMap::COL_CHARITYID, $charityID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($charityID['max'])) {
                $this->addUsingAlias(EventTableMap::COL_CHARITYID, $charityID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_CHARITYID, $charityID, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the date column
     *
     * Example usage:
     * <code>
     * $query->filterByDate('2011-03-14'); // WHERE date = '2011-03-14'
     * $query->filterByDate('now'); // WHERE date = '2011-03-14'
     * $query->filterByDate(array('max' => 'yesterday')); // WHERE date > '2011-03-13'
     * </code>
     *
     * @param     mixed $date The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByDate($date = null, $comparison = null)
    {
        if (is_array($date)) {
            $useMinMax = false;
            if (isset($date['min'])) {
                $this->addUsingAlias(EventTableMap::COL_DATE, $date['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($date['max'])) {
                $this->addUsingAlias(EventTableMap::COL_DATE, $date['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_DATE, $date, $comparison);
    }

    /**
     * Filter the query on the location column
     *
     * Example usage:
     * <code>
     * $query->filterByLocation('fooValue');   // WHERE location = 'fooValue'
     * $query->filterByLocation('%fooValue%'); // WHERE location LIKE '%fooValue%'
     * </code>
     *
     * @param     string $location The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByLocation($location = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($location)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $location)) {
                $location = str_replace('*', '%', $location);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_LOCATION, $location, $comparison);
    }

    /**
     * Filter the query on the image_url column
     *
     * Example usage:
     * <code>
     * $query->filterByImageUrl('fooValue');   // WHERE image_url = 'fooValue'
     * $query->filterByImageUrl('%fooValue%'); // WHERE image_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $imageUrl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByImageUrl($imageUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($imageUrl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $imageUrl)) {
                $imageUrl = str_replace('*', '%', $imageUrl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_IMAGE_URL, $imageUrl, $comparison);
    }

    /**
     * Filter the query on the body column
     *
     * Example usage:
     * <code>
     * $query->filterByBody('fooValue');   // WHERE body = 'fooValue'
     * $query->filterByBody('%fooValue%'); // WHERE body LIKE '%fooValue%'
     * </code>
     *
     * @param     string $body The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByBody($body = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($body)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $body)) {
                $body = str_replace('*', '%', $body);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_BODY, $body, $comparison);
    }

    /**
     * Filter the query on the bodyHTML column
     *
     * Example usage:
     * <code>
     * $query->filterByBodyHTML('fooValue');   // WHERE bodyHTML = 'fooValue'
     * $query->filterByBodyHTML('%fooValue%'); // WHERE bodyHTML LIKE '%fooValue%'
     * </code>
     *
     * @param     string $bodyHTML The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByBodyHTML($bodyHTML = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($bodyHTML)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $bodyHTML)) {
                $bodyHTML = str_replace('*', '%', $bodyHTML);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_BODYHTML, $bodyHTML, $comparison);
    }

    /**
     * Filter the query on the tickets column
     *
     * Example usage:
     * <code>
     * $query->filterByTickets(1234); // WHERE tickets = 1234
     * $query->filterByTickets(array(12, 34)); // WHERE tickets IN (12, 34)
     * $query->filterByTickets(array('min' => 12)); // WHERE tickets > 12
     * </code>
     *
     * @param     mixed $tickets The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByTickets($tickets = null, $comparison = null)
    {
        if (is_array($tickets)) {
            $useMinMax = false;
            if (isset($tickets['min'])) {
                $this->addUsingAlias(EventTableMap::COL_TICKETS, $tickets['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($tickets['max'])) {
                $this->addUsingAlias(EventTableMap::COL_TICKETS, $tickets['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_TICKETS, $tickets, $comparison);
    }

    /**
     * Filter the query on the video_url column
     *
     * Example usage:
     * <code>
     * $query->filterByVideoUrl('fooValue');   // WHERE video_url = 'fooValue'
     * $query->filterByVideoUrl('%fooValue%'); // WHERE video_url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $videoUrl The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByVideoUrl($videoUrl = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($videoUrl)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $videoUrl)) {
                $videoUrl = str_replace('*', '%', $videoUrl);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_VIDEO_URL, $videoUrl, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(EventTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EventTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(EventTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EventTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the tickets_remaining column
     *
     * Example usage:
     * <code>
     * $query->filterByTicketsRemaining(1234); // WHERE tickets_remaining = 1234
     * $query->filterByTicketsRemaining(array(12, 34)); // WHERE tickets_remaining IN (12, 34)
     * $query->filterByTicketsRemaining(array('min' => 12)); // WHERE tickets_remaining > 12
     * </code>
     *
     * @param     mixed $ticketsRemaining The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByTicketsRemaining($ticketsRemaining = null, $comparison = null)
    {
        if (is_array($ticketsRemaining)) {
            $useMinMax = false;
            if (isset($ticketsRemaining['min'])) {
                $this->addUsingAlias(EventTableMap::COL_TICKETS_REMAINING, $ticketsRemaining['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($ticketsRemaining['max'])) {
                $this->addUsingAlias(EventTableMap::COL_TICKETS_REMAINING, $ticketsRemaining['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_TICKETS_REMAINING, $ticketsRemaining, $comparison);
    }

    /**
     * Filter the query on the average_rating column
     *
     * Example usage:
     * <code>
     * $query->filterByAverageRating(1234); // WHERE average_rating = 1234
     * $query->filterByAverageRating(array(12, 34)); // WHERE average_rating IN (12, 34)
     * $query->filterByAverageRating(array('min' => 12)); // WHERE average_rating > 12
     * </code>
     *
     * @param     mixed $averageRating The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function filterByAverageRating($averageRating = null, $comparison = null)
    {
        if (is_array($averageRating)) {
            $useMinMax = false;
            if (isset($averageRating['min'])) {
                $this->addUsingAlias(EventTableMap::COL_AVERAGE_RATING, $averageRating['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($averageRating['max'])) {
                $this->addUsingAlias(EventTableMap::COL_AVERAGE_RATING, $averageRating['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventTableMap::COL_AVERAGE_RATING, $averageRating, $comparison);
    }

    /**
     * Filter the query by a related \NorthEastEvents\Models\Charity object
     *
     * @param \NorthEastEvents\Models\Charity|ObjectCollection $charity The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByCharity($charity, $comparison = null)
    {
        if ($charity instanceof \NorthEastEvents\Models\Charity) {
            return $this
                ->addUsingAlias(EventTableMap::COL_CHARITYID, $charity->getId(), $comparison);
        } elseif ($charity instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventTableMap::COL_CHARITYID, $charity->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCharity() only accepts arguments of type \NorthEastEvents\Models\Charity or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Charity relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinCharity($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Charity');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Charity');
        }

        return $this;
    }

    /**
     * Use the Charity relation Charity object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \NorthEastEvents\Models\CharityQuery A secondary query class using the current class as primary query
     */
    public function useCharityQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinCharity($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Charity', '\NorthEastEvents\Models\CharityQuery');
    }

    /**
     * Filter the query by a related \NorthEastEvents\Models\EventUsers object
     *
     * @param \NorthEastEvents\Models\EventUsers|ObjectCollection $eventUsers the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByEventUsers($eventUsers, $comparison = null)
    {
        if ($eventUsers instanceof \NorthEastEvents\Models\EventUsers) {
            return $this
                ->addUsingAlias(EventTableMap::COL_ID, $eventUsers->getEventID(), $comparison);
        } elseif ($eventUsers instanceof ObjectCollection) {
            return $this
                ->useEventUsersQuery()
                ->filterByPrimaryKeys($eventUsers->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventUsers() only accepts arguments of type \NorthEastEvents\Models\EventUsers or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventUsers relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinEventUsers($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventUsers');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'EventUsers');
        }

        return $this;
    }

    /**
     * Use the EventUsers relation EventUsers object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \NorthEastEvents\Models\EventUsersQuery A secondary query class using the current class as primary query
     */
    public function useEventUsersQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventUsers($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventUsers', '\NorthEastEvents\Models\EventUsersQuery');
    }

    /**
     * Filter the query by a related \NorthEastEvents\Models\WaitingList object
     *
     * @param \NorthEastEvents\Models\WaitingList|ObjectCollection $waitingList the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByWaitingList($waitingList, $comparison = null)
    {
        if ($waitingList instanceof \NorthEastEvents\Models\WaitingList) {
            return $this
                ->addUsingAlias(EventTableMap::COL_ID, $waitingList->getEventID(), $comparison);
        } elseif ($waitingList instanceof ObjectCollection) {
            return $this
                ->useWaitingListQuery()
                ->filterByPrimaryKeys($waitingList->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByWaitingList() only accepts arguments of type \NorthEastEvents\Models\WaitingList or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the WaitingList relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinWaitingList($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('WaitingList');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'WaitingList');
        }

        return $this;
    }

    /**
     * Use the WaitingList relation WaitingList object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \NorthEastEvents\Models\WaitingListQuery A secondary query class using the current class as primary query
     */
    public function useWaitingListQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinWaitingList($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'WaitingList', '\NorthEastEvents\Models\WaitingListQuery');
    }

    /**
     * Filter the query by a related \NorthEastEvents\Models\EventRating object
     *
     * @param \NorthEastEvents\Models\EventRating|ObjectCollection $eventRating the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByEventRating($eventRating, $comparison = null)
    {
        if ($eventRating instanceof \NorthEastEvents\Models\EventRating) {
            return $this
                ->addUsingAlias(EventTableMap::COL_ID, $eventRating->getEventID(), $comparison);
        } elseif ($eventRating instanceof ObjectCollection) {
            return $this
                ->useEventRatingQuery()
                ->filterByPrimaryKeys($eventRating->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByEventRating() only accepts arguments of type \NorthEastEvents\Models\EventRating or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the EventRating relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinEventRating($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('EventRating');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'EventRating');
        }

        return $this;
    }

    /**
     * Use the EventRating relation EventRating object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \NorthEastEvents\Models\EventRatingQuery A secondary query class using the current class as primary query
     */
    public function useEventRatingQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEventRating($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'EventRating', '\NorthEastEvents\Models\EventRatingQuery');
    }

    /**
     * Filter the query by a related \NorthEastEvents\Models\Thread object
     *
     * @param \NorthEastEvents\Models\Thread|ObjectCollection $thread the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByThread($thread, $comparison = null)
    {
        if ($thread instanceof \NorthEastEvents\Models\Thread) {
            return $this
                ->addUsingAlias(EventTableMap::COL_ID, $thread->getEventID(), $comparison);
        } elseif ($thread instanceof ObjectCollection) {
            return $this
                ->useThreadQuery()
                ->filterByPrimaryKeys($thread->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByThread() only accepts arguments of type \NorthEastEvents\Models\Thread or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Thread relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function joinThread($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Thread');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'Thread');
        }

        return $this;
    }

    /**
     * Use the Thread relation Thread object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \NorthEastEvents\Models\ThreadQuery A secondary query class using the current class as primary query
     */
    public function useThreadQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinThread($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Thread', '\NorthEastEvents\Models\ThreadQuery');
    }

    /**
     * Filter the query by a related User object
     * using the event_users table as cross reference
     *
     * @param User $user the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildEventQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useEventUsersQuery()
            ->filterByUser($user, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEvent $event Object to remove from the list of results
     *
     * @return $this|ChildEventQuery The current query, for fluid interface
     */
    public function prune($event = null)
    {
        if ($event) {
            $this->addUsingAlias(EventTableMap::COL_ID, $event->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the event table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventTableMap::clearInstancePool();
            EventTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    /**
     * Performs a DELETE on the database based on the current ModelCriteria
     *
     * @param ConnectionInterface $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *                         if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public function delete(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildEventQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(EventTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildEventQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(EventTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildEventQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(EventTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildEventQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(EventTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildEventQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(EventTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildEventQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(EventTableMap::COL_CREATED_AT);
    }

} // EventQuery
