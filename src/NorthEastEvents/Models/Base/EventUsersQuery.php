<?php

namespace NorthEastEvents\Models\Base;

use \Exception;
use \PDO;
use NorthEastEvents\Models\EventUsers as ChildEventUsers;
use NorthEastEvents\Models\EventUsersQuery as ChildEventUsersQuery;
use NorthEastEvents\Models\Map\EventUsersTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'event_users' table.
 *
 *
 *
 * @method     ChildEventUsersQuery orderByEventID($order = Criteria::ASC) Order by the eventID column
 * @method     ChildEventUsersQuery orderByUserID($order = Criteria::ASC) Order by the userID column
 * @method     ChildEventUsersQuery orderByPrivate($order = Criteria::ASC) Order by the private column
 * @method     ChildEventUsersQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildEventUsersQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildEventUsersQuery groupByEventID() Group by the eventID column
 * @method     ChildEventUsersQuery groupByUserID() Group by the userID column
 * @method     ChildEventUsersQuery groupByPrivate() Group by the private column
 * @method     ChildEventUsersQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildEventUsersQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildEventUsersQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventUsersQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventUsersQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventUsersQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventUsersQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventUsersQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventUsersQuery leftJoinEvent($relationAlias = null) Adds a LEFT JOIN clause to the query using the Event relation
 * @method     ChildEventUsersQuery rightJoinEvent($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Event relation
 * @method     ChildEventUsersQuery innerJoinEvent($relationAlias = null) Adds a INNER JOIN clause to the query using the Event relation
 *
 * @method     ChildEventUsersQuery joinWithEvent($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Event relation
 *
 * @method     ChildEventUsersQuery leftJoinWithEvent() Adds a LEFT JOIN clause and with to the query using the Event relation
 * @method     ChildEventUsersQuery rightJoinWithEvent() Adds a RIGHT JOIN clause and with to the query using the Event relation
 * @method     ChildEventUsersQuery innerJoinWithEvent() Adds a INNER JOIN clause and with to the query using the Event relation
 *
 * @method     ChildEventUsersQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildEventUsersQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildEventUsersQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildEventUsersQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildEventUsersQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildEventUsersQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildEventUsersQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     \NorthEastEvents\Models\EventQuery|\NorthEastEvents\Models\UserQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildEventUsers findOne(ConnectionInterface $con = null) Return the first ChildEventUsers matching the query
 * @method     ChildEventUsers findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEventUsers matching the query, or a new ChildEventUsers object populated from the query conditions when no match is found
 *
 * @method     ChildEventUsers findOneByEventID(int $eventID) Return the first ChildEventUsers filtered by the eventID column
 * @method     ChildEventUsers findOneByUserID(int $userID) Return the first ChildEventUsers filtered by the userID column
 * @method     ChildEventUsers findOneByPrivate(boolean $private) Return the first ChildEventUsers filtered by the private column
 * @method     ChildEventUsers findOneByCreatedAt(string $created_at) Return the first ChildEventUsers filtered by the created_at column
 * @method     ChildEventUsers findOneByUpdatedAt(string $updated_at) Return the first ChildEventUsers filtered by the updated_at column *

 * @method     ChildEventUsers requirePk($key, ConnectionInterface $con = null) Return the ChildEventUsers by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUsers requireOne(ConnectionInterface $con = null) Return the first ChildEventUsers matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventUsers requireOneByEventID(int $eventID) Return the first ChildEventUsers filtered by the eventID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUsers requireOneByUserID(int $userID) Return the first ChildEventUsers filtered by the userID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUsers requireOneByPrivate(boolean $private) Return the first ChildEventUsers filtered by the private column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUsers requireOneByCreatedAt(string $created_at) Return the first ChildEventUsers filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventUsers requireOneByUpdatedAt(string $updated_at) Return the first ChildEventUsers filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventUsers[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEventUsers objects based on current ModelCriteria
 * @method     ChildEventUsers[]|ObjectCollection findByEventID(int $eventID) Return ChildEventUsers objects filtered by the eventID column
 * @method     ChildEventUsers[]|ObjectCollection findByUserID(int $userID) Return ChildEventUsers objects filtered by the userID column
 * @method     ChildEventUsers[]|ObjectCollection findByPrivate(boolean $private) Return ChildEventUsers objects filtered by the private column
 * @method     ChildEventUsers[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildEventUsers objects filtered by the created_at column
 * @method     ChildEventUsers[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildEventUsers objects filtered by the updated_at column
 * @method     ChildEventUsers[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventUsersQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \NorthEastEvents\Models\Base\EventUsersQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\NorthEastEvents\\Models\\EventUsers', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventUsersQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventUsersQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventUsersQuery) {
            return $criteria;
        }
        $query = new ChildEventUsersQuery();
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array[$eventID, $userID] $key Primary key to use for the query
     * @param ConnectionInterface $con an optional connection object
     *
     * @return ChildEventUsers|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventUsersTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventUsersTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildEventUsers A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT eventID, userID, private, created_at, updated_at FROM event_users WHERE eventID = :p0 AND userID = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            /** @var ChildEventUsers $obj */
            $obj = new ChildEventUsers();
            $obj->hydrate($row);
            EventUsersTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildEventUsers|array|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
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
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(EventUsersTableMap::COL_EVENTID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(EventUsersTableMap::COL_USERID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(EventUsersTableMap::COL_EVENTID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(EventUsersTableMap::COL_USERID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
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
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function filterByEventID($eventID = null, $comparison = null)
    {
        if (is_array($eventID)) {
            $useMinMax = false;
            if (isset($eventID['min'])) {
                $this->addUsingAlias(EventUsersTableMap::COL_EVENTID, $eventID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventID['max'])) {
                $this->addUsingAlias(EventUsersTableMap::COL_EVENTID, $eventID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventUsersTableMap::COL_EVENTID, $eventID, $comparison);
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
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function filterByUserID($userID = null, $comparison = null)
    {
        if (is_array($userID)) {
            $useMinMax = false;
            if (isset($userID['min'])) {
                $this->addUsingAlias(EventUsersTableMap::COL_USERID, $userID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userID['max'])) {
                $this->addUsingAlias(EventUsersTableMap::COL_USERID, $userID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventUsersTableMap::COL_USERID, $userID, $comparison);
    }

    /**
     * Filter the query on the private column
     *
     * Example usage:
     * <code>
     * $query->filterByPrivate(true); // WHERE private = true
     * $query->filterByPrivate('yes'); // WHERE private = true
     * </code>
     *
     * @param     boolean|string $private The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function filterByPrivate($private = null, $comparison = null)
    {
        if (is_string($private)) {
            $private = in_array(strtolower($private), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(EventUsersTableMap::COL_PRIVATE, $private, $comparison);
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
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(EventUsersTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EventUsersTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventUsersTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(EventUsersTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EventUsersTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventUsersTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related \NorthEastEvents\Models\Event object
     *
     * @param \NorthEastEvents\Models\Event|ObjectCollection $event The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildEventUsersQuery The current query, for fluid interface
     */
    public function filterByEvent($event, $comparison = null)
    {
        if ($event instanceof \NorthEastEvents\Models\Event) {
            return $this
                ->addUsingAlias(EventUsersTableMap::COL_EVENTID, $event->getId(), $comparison);
        } elseif ($event instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventUsersTableMap::COL_EVENTID, $event->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
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
     * @return ChildEventUsersQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \NorthEastEvents\Models\User) {
            return $this
                ->addUsingAlias(EventUsersTableMap::COL_USERID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(EventUsersTableMap::COL_USERID, $user->toKeyValue('Id', 'Id'), $comparison);
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
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
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
     * Exclude object from result
     *
     * @param   ChildEventUsers $eventUsers Object to remove from the list of results
     *
     * @return $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function prune($eventUsers = null)
    {
        if ($eventUsers) {
            $this->addCond('pruneCond0', $this->getAliasedColName(EventUsersTableMap::COL_EVENTID), $eventUsers->getEventID(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(EventUsersTableMap::COL_USERID), $eventUsers->getUserID(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the event_users table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventUsersTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventUsersTableMap::clearInstancePool();
            EventUsersTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventUsersTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventUsersTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventUsersTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventUsersTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(EventUsersTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(EventUsersTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(EventUsersTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(EventUsersTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(EventUsersTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildEventUsersQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(EventUsersTableMap::COL_CREATED_AT);
    }

} // EventUsersQuery
