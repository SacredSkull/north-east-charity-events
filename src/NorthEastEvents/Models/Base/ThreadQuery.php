<?php

namespace NorthEastEvents\Models\Base;

use \Exception;
use \PDO;
use NorthEastEvents\Models\Thread as ChildThread;
use NorthEastEvents\Models\ThreadQuery as ChildThreadQuery;
use NorthEastEvents\Models\Map\ThreadTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'thread' table.
 *
 *
 *
 * @method     ChildThreadQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildThreadQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildThreadQuery orderByEventID($order = Criteria::ASC) Order by the eventID column
 * @method     ChildThreadQuery orderByUserID($order = Criteria::ASC) Order by the userID column
 * @method     ChildThreadQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildThreadQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildThreadQuery groupById() Group by the id column
 * @method     ChildThreadQuery groupByTitle() Group by the title column
 * @method     ChildThreadQuery groupByEventID() Group by the eventID column
 * @method     ChildThreadQuery groupByUserID() Group by the userID column
 * @method     ChildThreadQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildThreadQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildThreadQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildThreadQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildThreadQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildThreadQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildThreadQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildThreadQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildThreadQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildThreadQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildThreadQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildThreadQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildThreadQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildThreadQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildThreadQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildThreadQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildThreadQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildThreadQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildThreadQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildThreadQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildThreadQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildThreadQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildThreadQuery leftJoinComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Comment relation
 * @method     ChildThreadQuery rightJoinComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Comment relation
 * @method     ChildThreadQuery innerJoinComment($relationAlias = null) Adds a INNER JOIN clause to the query using the Comment relation
 *
 * @method     ChildThreadQuery joinWithComment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Comment relation
 *
 * @method     ChildThreadQuery leftJoinWithComment() Adds a LEFT JOIN clause and with to the query using the Comment relation
 * @method     ChildThreadQuery rightJoinWithComment() Adds a RIGHT JOIN clause and with to the query using the Comment relation
 * @method     ChildThreadQuery innerJoinWithComment() Adds a INNER JOIN clause and with to the query using the Comment relation
 *
 * @method     \NorthEastEvents\Models\EventQuery|\NorthEastEvents\Models\UserQuery|\NorthEastEvents\Models\CommentQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildThread findOne(ConnectionInterface $con = null) Return the first ChildThread matching the query
 * @method     ChildThread findOneOrCreate(ConnectionInterface $con = null) Return the first ChildThread matching the query, or a new ChildThread object populated from the query conditions when no match is found
 *
 * @method     ChildThread findOneById(int $id) Return the first ChildThread filtered by the id column
 * @method     ChildThread findOneByTitle(string $title) Return the first ChildThread filtered by the title column
 * @method     ChildThread findOneByEventID(int $eventID) Return the first ChildThread filtered by the eventID column
 * @method     ChildThread findOneByUserID(int $userID) Return the first ChildThread filtered by the userID column
 * @method     ChildThread findOneByCreatedAt(string $created_at) Return the first ChildThread filtered by the created_at column
 * @method     ChildThread findOneByUpdatedAt(string $updated_at) Return the first ChildThread filtered by the updated_at column *

 * @method     ChildThread requirePk($key, ConnectionInterface $con = null) Return the ChildThread by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThread requireOne(ConnectionInterface $con = null) Return the first ChildThread matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildThread requireOneById(int $id) Return the first ChildThread filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThread requireOneByTitle(string $title) Return the first ChildThread filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThread requireOneByEventID(int $eventID) Return the first ChildThread filtered by the eventID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThread requireOneByUserID(int $userID) Return the first ChildThread filtered by the userID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThread requireOneByCreatedAt(string $created_at) Return the first ChildThread filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildThread requireOneByUpdatedAt(string $updated_at) Return the first ChildThread filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildThread[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildThread objects based on current ModelCriteria
 * @method     ChildThread[]|ObjectCollection findById(int $id) Return ChildThread objects filtered by the id column
 * @method     ChildThread[]|ObjectCollection findByTitle(string $title) Return ChildThread objects filtered by the title column
 * @method     ChildThread[]|ObjectCollection findByEventID(int $eventID) Return ChildThread objects filtered by the eventID column
 * @method     ChildThread[]|ObjectCollection findByUserID(int $userID) Return ChildThread objects filtered by the userID column
 * @method     ChildThread[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildThread objects filtered by the created_at column
 * @method     ChildThread[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildThread objects filtered by the updated_at column
 * @method     ChildThread[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ThreadQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \NorthEastEvents\Models\Base\ThreadQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\NorthEastEvents\\Models\\Thread', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildThreadQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildThreadQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildThreadQuery) {
            return $criteria;
        }
        $query = new ChildThreadQuery();
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
     * @return ChildThread|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ThreadTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ThreadTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildThread A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, title, eventID, userID, created_at, updated_at FROM thread WHERE id = :p0';
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
            /** @var ChildThread $obj */
            $obj = new ChildThread();
            $obj->hydrate($row);
            ThreadTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildThread|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ThreadTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ThreadTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ThreadTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ThreadTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ThreadTableMap::COL_ID, $id, $comparison);
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
     * @return $this|ChildThreadQuery The current query, for fluid interface
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

        return $this->addUsingAlias(ThreadTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the eventID column
     *
     * Example usage:
     * <code>
     * $query->filterByEventID(1234); // WHERE eventID = 1234
     * $query->filterByEventID(array(12, 34)); // WHERE eventID IN (12, 34)
     * $query->filterByEventID(array('min' => 12)); // WHERE eventID > 12
     * </code>
     *
     * @see       filterByEvent()
     *
     * @param     mixed $eventID The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function filterByEventID($eventID = null, $comparison = null)
    {
        if (is_array($eventID)) {
            $useMinMax = false;
            if (isset($eventID['min'])) {
                $this->addUsingAlias(ThreadTableMap::COL_EVENTID, $eventID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventID['max'])) {
                $this->addUsingAlias(ThreadTableMap::COL_EVENTID, $eventID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ThreadTableMap::COL_EVENTID, $eventID, $comparison);
    }

    /**
     * Filter the query on the userID column
     *
     * Example usage:
     * <code>
     * $query->filterByUserID(1234); // WHERE userID = 1234
     * $query->filterByUserID(array(12, 34)); // WHERE userID IN (12, 34)
     * $query->filterByUserID(array('min' => 12)); // WHERE userID > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userID The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function filterByUserID($userID = null, $comparison = null)
    {
        if (is_array($userID)) {
            $useMinMax = false;
            if (isset($userID['min'])) {
                $this->addUsingAlias(ThreadTableMap::COL_USERID, $userID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userID['max'])) {
                $this->addUsingAlias(ThreadTableMap::COL_USERID, $userID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ThreadTableMap::COL_USERID, $userID, $comparison);
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
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(ThreadTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(ThreadTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ThreadTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(ThreadTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(ThreadTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ThreadTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \NorthEastEvents\Models\Event object
     *
     * @param \NorthEastEvents\Models\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildThreadQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \NorthEastEvents\Models\Event) {
            return $this
                ->addUsingAlias(ThreadTableMap::COL_EVENTID, $event->getId(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ThreadTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByEvent() only accepts arguments of type \NorthEastEvents\Models\Event or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Event relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function joinEvent($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Event');

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
            $this->addJoinObject($join, 'Event');
        }

        return $this;
    }

    /**
     * Use the Event relation Event object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \NorthEastEvents\Models\EventQuery A secondary query class using the current class as primary query
     */
    public function useEventQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinEvent($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Event', '\NorthEastEvents\Models\EventQuery');
    }

    /**
     * Filter the query by a related \NorthEastEvents\Models\User object
     *
     * @param \NorthEastEvents\Models\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildThreadQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \NorthEastEvents\Models\User) {
            return $this
                ->addUsingAlias(ThreadTableMap::COL_USERID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ThreadTableMap::COL_USERID, $user->toKeyValue('Id', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \NorthEastEvents\Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function joinUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('User');

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
            $this->addJoinObject($join, 'User');
        }

        return $this;
    }

    /**
     * Use the User relation User object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \NorthEastEvents\Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\NorthEastEvents\Models\UserQuery');
    }

    /**
     * Filter the query by a related \NorthEastEvents\Models\Comment object
     *
     * @param \NorthEastEvents\Models\Comment|ObjectCollection $comment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildThreadQuery The current query, for fluid interface
     */
    public function filterByComment($comment, $comparison = null)
    {
        if ($comment instanceof \NorthEastEvents\Models\Comment) {
            return $this
                ->addUsingAlias(ThreadTableMap::COL_ID, $comment->getThreadID(), $comparison);
        } elseif ($comment instanceof ObjectCollection) {
            return $this
                ->useCommentQuery()
                ->filterByPrimaryKeys($comment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByComment() only accepts arguments of type \NorthEastEvents\Models\Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Comment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function joinComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Comment');

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
            $this->addJoinObject($join, 'Comment');
        }

        return $this;
    }

    /**
     * Use the Comment relation Comment object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \NorthEastEvents\Models\CommentQuery A secondary query class using the current class as primary query
     */
    public function useCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Comment', '\NorthEastEvents\Models\CommentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildThread $thread Object to remove from the list of results
     *
     * @return $this|ChildThreadQuery The current query, for fluid interface
     */
    public function prune($thread = null)
    {
        if ($thread) {
            $this->addUsingAlias(ThreadTableMap::COL_ID, $thread->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the thread table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ThreadTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ThreadTableMap::clearInstancePool();
            ThreadTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ThreadTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ThreadTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ThreadTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ThreadTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildThreadQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(ThreadTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildThreadQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(ThreadTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildThreadQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(ThreadTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildThreadQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(ThreadTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildThreadQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(ThreadTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildThreadQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(ThreadTableMap::COL_CREATED_AT);
    }

} // ThreadQuery
