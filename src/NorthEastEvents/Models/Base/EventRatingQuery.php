<?php

namespace NorthEastEvents\Models\Base;

use \Exception;
use \PDO;
use NorthEastEvents\Models\EventRating as ChildEventRating;
use NorthEastEvents\Models\EventRatingQuery as ChildEventRatingQuery;
use NorthEastEvents\Models\Map\EventRatingTableMap;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

/**
 * Base class that represents a query for the 'event_rating' table.
 *
 *
 *
 * @method     ChildEventRatingQuery orderByEventID($order = Criteria::ASC) Order by the eventID column
 * @method     ChildEventRatingQuery orderByUserID($order = Criteria::ASC) Order by the userID column
 * @method     ChildEventRatingQuery orderByRating($order = Criteria::ASC) Order by the rating column
 * @method     ChildEventRatingQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method     ChildEventRatingQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method     ChildEventRatingQuery groupByEventID() Group by the eventID column
 * @method     ChildEventRatingQuery groupByUserID() Group by the userID column
 * @method     ChildEventRatingQuery groupByRating() Group by the rating column
 * @method     ChildEventRatingQuery groupByCreatedAt() Group by the created_at column
 * @method     ChildEventRatingQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method     ChildEventRatingQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildEventRatingQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildEventRatingQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildEventRatingQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildEventRatingQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildEventRatingQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildEventRating findOne(ConnectionInterface $con = null) Return the first ChildEventRating matching the query
 * @method     ChildEventRating findOneOrCreate(ConnectionInterface $con = null) Return the first ChildEventRating matching the query, or a new ChildEventRating object populated from the query conditions when no match is found
 *
 * @method     ChildEventRating findOneByEventID(int $eventID) Return the first ChildEventRating filtered by the eventID column
 * @method     ChildEventRating findOneByUserID(int $userID) Return the first ChildEventRating filtered by the userID column
 * @method     ChildEventRating findOneByRating(int $rating) Return the first ChildEventRating filtered by the rating column
 * @method     ChildEventRating findOneByCreatedAt(string $created_at) Return the first ChildEventRating filtered by the created_at column
 * @method     ChildEventRating findOneByUpdatedAt(string $updated_at) Return the first ChildEventRating filtered by the updated_at column *

 * @method     ChildEventRating requirePk($key, ConnectionInterface $con = null) Return the ChildEventRating by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventRating requireOne(ConnectionInterface $con = null) Return the first ChildEventRating matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventRating requireOneByEventID(int $eventID) Return the first ChildEventRating filtered by the eventID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventRating requireOneByUserID(int $userID) Return the first ChildEventRating filtered by the userID column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventRating requireOneByRating(int $rating) Return the first ChildEventRating filtered by the rating column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventRating requireOneByCreatedAt(string $created_at) Return the first ChildEventRating filtered by the created_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildEventRating requireOneByUpdatedAt(string $updated_at) Return the first ChildEventRating filtered by the updated_at column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildEventRating[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildEventRating objects based on current ModelCriteria
 * @method     ChildEventRating[]|ObjectCollection findByEventID(int $eventID) Return ChildEventRating objects filtered by the eventID column
 * @method     ChildEventRating[]|ObjectCollection findByUserID(int $userID) Return ChildEventRating objects filtered by the userID column
 * @method     ChildEventRating[]|ObjectCollection findByRating(int $rating) Return ChildEventRating objects filtered by the rating column
 * @method     ChildEventRating[]|ObjectCollection findByCreatedAt(string $created_at) Return ChildEventRating objects filtered by the created_at column
 * @method     ChildEventRating[]|ObjectCollection findByUpdatedAt(string $updated_at) Return ChildEventRating objects filtered by the updated_at column
 * @method     ChildEventRating[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class EventRatingQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \NorthEastEvents\Models\Base\EventRatingQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = '\\NorthEastEvents\\Models\\EventRating', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildEventRatingQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildEventRatingQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildEventRatingQuery) {
            return $criteria;
        }
        $query = new ChildEventRatingQuery();
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
     * @return ChildEventRating|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(EventRatingTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = EventRatingTableMap::getInstanceFromPool(serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]))))) {
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
     * @return ChildEventRating A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT eventID, userID, rating, created_at, updated_at FROM event_rating WHERE eventID = :p0 AND userID = :p1';
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
            /** @var ChildEventRating $obj */
            $obj = new ChildEventRating();
            $obj->hydrate($row);
            EventRatingTableMap::addInstanceToPool($obj, serialize([(null === $key[0] || is_scalar($key[0]) || is_callable([$key[0], '__toString']) ? (string) $key[0] : $key[0]), (null === $key[1] || is_scalar($key[1]) || is_callable([$key[1], '__toString']) ? (string) $key[1] : $key[1])]));
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
     * @return ChildEventRating|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(EventRatingTableMap::COL_EVENTID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(EventRatingTableMap::COL_USERID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(EventRatingTableMap::COL_EVENTID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(EventRatingTableMap::COL_USERID, $key[1], Criteria::EQUAL);
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
     * @param     mixed $eventID The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function filterByEventID($eventID = null, $comparison = null)
    {
        if (is_array($eventID)) {
            $useMinMax = false;
            if (isset($eventID['min'])) {
                $this->addUsingAlias(EventRatingTableMap::COL_EVENTID, $eventID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($eventID['max'])) {
                $this->addUsingAlias(EventRatingTableMap::COL_EVENTID, $eventID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventRatingTableMap::COL_EVENTID, $eventID, $comparison);
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
     * @param     mixed $userID The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function filterByUserID($userID = null, $comparison = null)
    {
        if (is_array($userID)) {
            $useMinMax = false;
            if (isset($userID['min'])) {
                $this->addUsingAlias(EventRatingTableMap::COL_USERID, $userID['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userID['max'])) {
                $this->addUsingAlias(EventRatingTableMap::COL_USERID, $userID['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventRatingTableMap::COL_USERID, $userID, $comparison);
    }

    /**
     * Filter the query on the rating column
     *
     * @param     mixed $rating The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function filterByRating($rating = null, $comparison = null)
    {
        $valueSet = EventRatingTableMap::getValueSet(EventRatingTableMap::COL_RATING);
        if (is_scalar($rating)) {
            if (!in_array($rating, $valueSet)) {
                throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $rating));
            }
            $rating = array_search($rating, $valueSet);
        } elseif (is_array($rating)) {
            $convertedValues = array();
            foreach ($rating as $value) {
                if (!in_array($value, $valueSet)) {
                    throw new PropelException(sprintf('Value "%s" is not accepted in this enumerated column', $value));
                }
                $convertedValues []= array_search($value, $valueSet);
            }
            $rating = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventRatingTableMap::COL_RATING, $rating, $comparison);
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
     * @return $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(EventRatingTableMap::COL_CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(EventRatingTableMap::COL_CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventRatingTableMap::COL_CREATED_AT, $createdAt, $comparison);
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
     * @return $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(EventRatingTableMap::COL_UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(EventRatingTableMap::COL_UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(EventRatingTableMap::COL_UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   ChildEventRating $eventRating Object to remove from the list of results
     *
     * @return $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function prune($eventRating = null)
    {
        if ($eventRating) {
            $this->addCond('pruneCond0', $this->getAliasedColName(EventRatingTableMap::COL_EVENTID), $eventRating->getEventID(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(EventRatingTableMap::COL_USERID), $eventRating->getUserID(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    /**
     * Deletes all rows from the event_rating table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(EventRatingTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            EventRatingTableMap::clearInstancePool();
            EventRatingTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(EventRatingTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(EventRatingTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            EventRatingTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            EventRatingTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(EventRatingTableMap::COL_UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(EventRatingTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(EventRatingTableMap::COL_UPDATED_AT);
    }

    /**
     * Order by create date desc
     *
     * @return     $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(EventRatingTableMap::COL_CREATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(EventRatingTableMap::COL_CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date asc
     *
     * @return     $this|ChildEventRatingQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(EventRatingTableMap::COL_CREATED_AT);
    }

} // EventRatingQuery
