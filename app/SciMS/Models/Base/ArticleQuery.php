<?php

namespace SciMS\Models\Base;

use \Exception;
use \PDO;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;
use SciMS\Models\Article as ChildArticle;
use SciMS\Models\ArticleQuery as ChildArticleQuery;
use SciMS\Models\Map\ArticleTableMap;

/**
 * Base class that represents a query for the 'article' table.
 *
 *
 *
 * @method     ChildArticleQuery orderById($order = Criteria::ASC) Order by the id column
 * @method     ChildArticleQuery orderByUserId($order = Criteria::ASC) Order by the user_id column
 * @method     ChildArticleQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method     ChildArticleQuery orderByContent($order = Criteria::ASC) Order by the content column
 * @method     ChildArticleQuery orderByPublicationDate($order = Criteria::ASC) Order by the publication_date column
 * @method     ChildArticleQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method     ChildArticleQuery orderBySubcategoryId($order = Criteria::ASC) Order by the subcategory_id column
 *
 * @method     ChildArticleQuery groupById() Group by the id column
 * @method     ChildArticleQuery groupByUserId() Group by the user_id column
 * @method     ChildArticleQuery groupByTitle() Group by the title column
 * @method     ChildArticleQuery groupByContent() Group by the content column
 * @method     ChildArticleQuery groupByPublicationDate() Group by the publication_date column
 * @method     ChildArticleQuery groupByCategoryId() Group by the category_id column
 * @method     ChildArticleQuery groupBySubcategoryId() Group by the subcategory_id column
 *
 * @method     ChildArticleQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method     ChildArticleQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method     ChildArticleQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method     ChildArticleQuery leftJoinWith($relation) Adds a LEFT JOIN clause and with to the query
 * @method     ChildArticleQuery rightJoinWith($relation) Adds a RIGHT JOIN clause and with to the query
 * @method     ChildArticleQuery innerJoinWith($relation) Adds a INNER JOIN clause and with to the query
 *
 * @method     ChildArticleQuery leftJoinUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the User relation
 * @method     ChildArticleQuery rightJoinUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the User relation
 * @method     ChildArticleQuery innerJoinUser($relationAlias = null) Adds a INNER JOIN clause to the query using the User relation
 *
 * @method     ChildArticleQuery joinWithUser($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the User relation
 *
 * @method     ChildArticleQuery leftJoinWithUser() Adds a LEFT JOIN clause and with to the query using the User relation
 * @method     ChildArticleQuery rightJoinWithUser() Adds a RIGHT JOIN clause and with to the query using the User relation
 * @method     ChildArticleQuery innerJoinWithUser() Adds a INNER JOIN clause and with to the query using the User relation
 *
 * @method     ChildArticleQuery leftJoinCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Category relation
 * @method     ChildArticleQuery rightJoinCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Category relation
 * @method     ChildArticleQuery innerJoinCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Category relation
 *
 * @method     ChildArticleQuery joinWithCategory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Category relation
 *
 * @method     ChildArticleQuery leftJoinWithCategory() Adds a LEFT JOIN clause and with to the query using the Category relation
 * @method     ChildArticleQuery rightJoinWithCategory() Adds a RIGHT JOIN clause and with to the query using the Category relation
 * @method     ChildArticleQuery innerJoinWithCategory() Adds a INNER JOIN clause and with to the query using the Category relation
 *
 * @method     ChildArticleQuery leftJoinSubcategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the Subcategory relation
 * @method     ChildArticleQuery rightJoinSubcategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Subcategory relation
 * @method     ChildArticleQuery innerJoinSubcategory($relationAlias = null) Adds a INNER JOIN clause to the query using the Subcategory relation
 *
 * @method     ChildArticleQuery joinWithSubcategory($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Subcategory relation
 *
 * @method     ChildArticleQuery leftJoinWithSubcategory() Adds a LEFT JOIN clause and with to the query using the Subcategory relation
 * @method     ChildArticleQuery rightJoinWithSubcategory() Adds a RIGHT JOIN clause and with to the query using the Subcategory relation
 * @method     ChildArticleQuery innerJoinWithSubcategory() Adds a INNER JOIN clause and with to the query using the Subcategory relation
 *
 * @method     ChildArticleQuery leftJoinHighlightedArticle($relationAlias = null) Adds a LEFT JOIN clause to the query using the HighlightedArticle relation
 * @method     ChildArticleQuery rightJoinHighlightedArticle($relationAlias = null) Adds a RIGHT JOIN clause to the query using the HighlightedArticle relation
 * @method     ChildArticleQuery innerJoinHighlightedArticle($relationAlias = null) Adds a INNER JOIN clause to the query using the HighlightedArticle relation
 *
 * @method     ChildArticleQuery joinWithHighlightedArticle($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the HighlightedArticle relation
 *
 * @method     ChildArticleQuery leftJoinWithHighlightedArticle() Adds a LEFT JOIN clause and with to the query using the HighlightedArticle relation
 * @method     ChildArticleQuery rightJoinWithHighlightedArticle() Adds a RIGHT JOIN clause and with to the query using the HighlightedArticle relation
 * @method     ChildArticleQuery innerJoinWithHighlightedArticle() Adds a INNER JOIN clause and with to the query using the HighlightedArticle relation
 *
 * @method     ChildArticleQuery leftJoinComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the Comment relation
 * @method     ChildArticleQuery rightJoinComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the Comment relation
 * @method     ChildArticleQuery innerJoinComment($relationAlias = null) Adds a INNER JOIN clause to the query using the Comment relation
 *
 * @method     ChildArticleQuery joinWithComment($joinType = Criteria::INNER_JOIN) Adds a join clause and with to the query using the Comment relation
 *
 * @method     ChildArticleQuery leftJoinWithComment() Adds a LEFT JOIN clause and with to the query using the Comment relation
 * @method     ChildArticleQuery rightJoinWithComment() Adds a RIGHT JOIN clause and with to the query using the Comment relation
 * @method     ChildArticleQuery innerJoinWithComment() Adds a INNER JOIN clause and with to the query using the Comment relation
 *
 * @method     \SciMS\Models\UserQuery|\SciMS\Models\CategoryQuery|\SciMS\Models\HighlightedArticleQuery|\SciMS\Models\CommentQuery endUse() Finalizes a secondary criteria and merges it with its primary Criteria
 *
 * @method     ChildArticle findOne(ConnectionInterface $con = null) Return the first ChildArticle matching the query
 * @method     ChildArticle findOneOrCreate(ConnectionInterface $con = null) Return the first ChildArticle matching the query, or a new ChildArticle object populated from the query conditions when no match is found
 *
 * @method     ChildArticle findOneById(int $id) Return the first ChildArticle filtered by the id column
 * @method     ChildArticle findOneByUserId(int $user_id) Return the first ChildArticle filtered by the user_id column
 * @method     ChildArticle findOneByTitle(string $title) Return the first ChildArticle filtered by the title column
 * @method     ChildArticle findOneByContent(string $content) Return the first ChildArticle filtered by the content column
 * @method     ChildArticle findOneByPublicationDate(int $publication_date) Return the first ChildArticle filtered by the publication_date column
 * @method     ChildArticle findOneByCategoryId(int $category_id) Return the first ChildArticle filtered by the category_id column
 * @method     ChildArticle findOneBySubcategoryId(int $subcategory_id) Return the first ChildArticle filtered by the subcategory_id column *

 * @method     ChildArticle requirePk($key, ConnectionInterface $con = null) Return the ChildArticle by primary key and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOne(ConnectionInterface $con = null) Return the first ChildArticle matching the query and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildArticle requireOneById(int $id) Return the first ChildArticle filtered by the id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByUserId(int $user_id) Return the first ChildArticle filtered by the user_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByTitle(string $title) Return the first ChildArticle filtered by the title column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByContent(string $content) Return the first ChildArticle filtered by the content column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByPublicationDate(int $publication_date) Return the first ChildArticle filtered by the publication_date column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneByCategoryId(int $category_id) Return the first ChildArticle filtered by the category_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 * @method     ChildArticle requireOneBySubcategoryId(int $subcategory_id) Return the first ChildArticle filtered by the subcategory_id column and throws \Propel\Runtime\Exception\EntityNotFoundException when not found
 *
 * @method     ChildArticle[]|ObjectCollection find(ConnectionInterface $con = null) Return ChildArticle objects based on current ModelCriteria
 * @method     ChildArticle[]|ObjectCollection findById(int $id) Return ChildArticle objects filtered by the id column
 * @method     ChildArticle[]|ObjectCollection findByUserId(int $user_id) Return ChildArticle objects filtered by the user_id column
 * @method     ChildArticle[]|ObjectCollection findByTitle(string $title) Return ChildArticle objects filtered by the title column
 * @method     ChildArticle[]|ObjectCollection findByContent(string $content) Return ChildArticle objects filtered by the content column
 * @method     ChildArticle[]|ObjectCollection findByPublicationDate(int $publication_date) Return ChildArticle objects filtered by the publication_date column
 * @method     ChildArticle[]|ObjectCollection findByCategoryId(int $category_id) Return ChildArticle objects filtered by the category_id column
 * @method     ChildArticle[]|ObjectCollection findBySubcategoryId(int $subcategory_id) Return ChildArticle objects filtered by the subcategory_id column
 * @method     ChildArticle[]|\Propel\Runtime\Util\PropelModelPager paginate($page = 1, $maxPerPage = 10, ConnectionInterface $con = null) Issue a SELECT query based on the current ModelCriteria and uses a page and a maximum number of results per page to compute an offset and a limit
 *
 */
abstract class ArticleQuery extends ModelCriteria
{
    protected $entityNotFoundExceptionClass = '\\Propel\\Runtime\\Exception\\EntityNotFoundException';

    /**
     * Initializes internal state of \SciMS\Models\Base\ArticleQuery object.
     *
     * @param     string $dbName The database name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'scims', $modelName = '\\SciMS\\Models\\Article', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new ChildArticleQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param     Criteria $criteria Optional Criteria to build the query from
     *
     * @return ChildArticleQuery
     */
    public static function create($modelAlias = null, Criteria $criteria = null)
    {
        if ($criteria instanceof ChildArticleQuery) {
            return $criteria;
        }
        $query = new ChildArticleQuery();
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
     * @return ChildArticle|array|mixed the result, formatted by the current formatter
     */
    public function findPk($key, ConnectionInterface $con = null)
    {
        if ($key === null) {
            return null;
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(ArticleTableMap::DATABASE_NAME);
        }

        $this->basePreSelect($con);

        if (
            $this->formatter || $this->modelAlias || $this->with || $this->select
            || $this->selectColumns || $this->asColumns || $this->selectModifiers
            || $this->map || $this->having || $this->joins
        ) {
            return $this->findPkComplex($key, $con);
        }

        if ((null !== ($obj = ArticleTableMap::getInstanceFromPool(null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key)))) {
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
     * @return ChildArticle A model object, or null if the key is not found
     */
    protected function findPkSimple($key, ConnectionInterface $con)
    {
        $sql = 'SELECT id, user_id, title, content, publication_date, category_id, subcategory_id FROM article WHERE id = :p0';
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
            /** @var ChildArticle $obj */
            $obj = new ChildArticle();
            $obj->hydrate($row);
            ArticleTableMap::addInstanceToPool($obj, null === $key || is_scalar($key) || is_callable([$key, '__toString']) ? (string) $key : $key);
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
     * @return ChildArticle|array|mixed the result, formatted by the current formatter
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
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(ArticleTableMap::COL_ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(ArticleTableMap::COL_ID, $keys, Criteria::IN);
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
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_ID, $id, $comparison);
    }

    /**
     * Filter the query on the user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByUserId(1234); // WHERE user_id = 1234
     * $query->filterByUserId(array(12, 34)); // WHERE user_id IN (12, 34)
     * $query->filterByUserId(array('min' => 12)); // WHERE user_id > 12
     * </code>
     *
     * @see       filterByUser()
     *
     * @param     mixed $userId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByUserId($userId = null, $comparison = null)
    {
        if (is_array($userId)) {
            $useMinMax = false;
            if (isset($userId['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_USER_ID, $userId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($userId['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_USER_ID, $userId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_USER_ID, $userId, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%', Criteria::LIKE); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the content column
     *
     * Example usage:
     * <code>
     * $query->filterByContent('fooValue');   // WHERE content = 'fooValue'
     * $query->filterByContent('%fooValue%', Criteria::LIKE); // WHERE content LIKE '%fooValue%'
     * </code>
     *
     * @param     string $content The value to use as filter.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByContent($content = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($content)) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_CONTENT, $content, $comparison);
    }

    /**
     * Filter the query on the publication_date column
     *
     * Example usage:
     * <code>
     * $query->filterByPublicationDate(1234); // WHERE publication_date = 1234
     * $query->filterByPublicationDate(array(12, 34)); // WHERE publication_date IN (12, 34)
     * $query->filterByPublicationDate(array('min' => 12)); // WHERE publication_date > 12
     * </code>
     *
     * @param     mixed $publicationDate The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByPublicationDate($publicationDate = null, $comparison = null)
    {
        if (is_array($publicationDate)) {
            $useMinMax = false;
            if (isset($publicationDate['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_PUBLICATION_DATE, $publicationDate['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publicationDate['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_PUBLICATION_DATE, $publicationDate['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_PUBLICATION_DATE, $publicationDate, $comparison);
    }

    /**
     * Filter the query on the category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryId(1234); // WHERE category_id = 1234
     * $query->filterByCategoryId(array(12, 34)); // WHERE category_id IN (12, 34)
     * $query->filterByCategoryId(array('min' => 12)); // WHERE category_id > 12
     * </code>
     *
     * @see       filterByCategory()
     *
     * @param     mixed $categoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCategoryId($categoryId = null, $comparison = null)
    {
        if (is_array($categoryId)) {
            $useMinMax = false;
            if (isset($categoryId['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_CATEGORY_ID, $categoryId, $comparison);
    }

    /**
     * Filter the query on the subcategory_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySubcategoryId(1234); // WHERE subcategory_id = 1234
     * $query->filterBySubcategoryId(array(12, 34)); // WHERE subcategory_id IN (12, 34)
     * $query->filterBySubcategoryId(array('min' => 12)); // WHERE subcategory_id > 12
     * </code>
     *
     * @see       filterBySubcategory()
     *
     * @param     mixed $subcategoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function filterBySubcategoryId($subcategoryId = null, $comparison = null)
    {
        if (is_array($subcategoryId)) {
            $useMinMax = false;
            if (isset($subcategoryId['min'])) {
                $this->addUsingAlias(ArticleTableMap::COL_SUBCATEGORY_ID, $subcategoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($subcategoryId['max'])) {
                $this->addUsingAlias(ArticleTableMap::COL_SUBCATEGORY_ID, $subcategoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(ArticleTableMap::COL_SUBCATEGORY_ID, $subcategoryId, $comparison);
    }

    /**
     * Filter the query by a related \SciMS\Models\User object
     *
     * @param \SciMS\Models\User|ObjectCollection $user The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildArticleQuery The current query, for fluid interface
     */
    public function filterByUser($user, $comparison = null)
    {
        if ($user instanceof \SciMS\Models\User) {
            return $this
                ->addUsingAlias(ArticleTableMap::COL_USER_ID, $user->getId(), $comparison);
        } elseif ($user instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ArticleTableMap::COL_USER_ID, $user->toKeyValue('Id', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByUser() only accepts arguments of type \SciMS\Models\User or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the User relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
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
     * @return \SciMS\Models\UserQuery A secondary query class using the current class as primary query
     */
    public function useUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'User', '\SciMS\Models\UserQuery');
    }

    /**
     * Filter the query by a related \SciMS\Models\Category object
     *
     * @param \SciMS\Models\Category|ObjectCollection $category The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildArticleQuery The current query, for fluid interface
     */
    public function filterByCategory($category, $comparison = null)
    {
        if ($category instanceof \SciMS\Models\Category) {
            return $this
                ->addUsingAlias(ArticleTableMap::COL_CATEGORY_ID, $category->getId(), $comparison);
        } elseif ($category instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ArticleTableMap::COL_CATEGORY_ID, $category->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCategory() only accepts arguments of type \SciMS\Models\Category or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Category relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function joinCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Category');

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
            $this->addJoinObject($join, 'Category');
        }

        return $this;
    }

    /**
     * Use the Category relation Category object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SciMS\Models\CategoryQuery A secondary query class using the current class as primary query
     */
    public function useCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Category', '\SciMS\Models\CategoryQuery');
    }

    /**
     * Filter the query by a related \SciMS\Models\Category object
     *
     * @param \SciMS\Models\Category|ObjectCollection $category The related object(s) to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return ChildArticleQuery The current query, for fluid interface
     */
    public function filterBySubcategory($category, $comparison = null)
    {
        if ($category instanceof \SciMS\Models\Category) {
            return $this
                ->addUsingAlias(ArticleTableMap::COL_SUBCATEGORY_ID, $category->getId(), $comparison);
        } elseif ($category instanceof ObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(ArticleTableMap::COL_SUBCATEGORY_ID, $category->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySubcategory() only accepts arguments of type \SciMS\Models\Category or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Subcategory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function joinSubcategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('Subcategory');

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
            $this->addJoinObject($join, 'Subcategory');
        }

        return $this;
    }

    /**
     * Use the Subcategory relation Category object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SciMS\Models\CategoryQuery A secondary query class using the current class as primary query
     */
    public function useSubcategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinSubcategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Subcategory', '\SciMS\Models\CategoryQuery');
    }

    /**
     * Filter the query by a related \SciMS\Models\HighlightedArticle object
     *
     * @param \SciMS\Models\HighlightedArticle|ObjectCollection $highlightedArticle the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildArticleQuery The current query, for fluid interface
     */
    public function filterByHighlightedArticle($highlightedArticle, $comparison = null)
    {
        if ($highlightedArticle instanceof \SciMS\Models\HighlightedArticle) {
            return $this
                ->addUsingAlias(ArticleTableMap::COL_ID, $highlightedArticle->getArticleId(), $comparison);
        } elseif ($highlightedArticle instanceof ObjectCollection) {
            return $this
                ->useHighlightedArticleQuery()
                ->filterByPrimaryKeys($highlightedArticle->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByHighlightedArticle() only accepts arguments of type \SciMS\Models\HighlightedArticle or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the HighlightedArticle relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function joinHighlightedArticle($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('HighlightedArticle');

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
            $this->addJoinObject($join, 'HighlightedArticle');
        }

        return $this;
    }

    /**
     * Use the HighlightedArticle relation HighlightedArticle object
     *
     * @see useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return \SciMS\Models\HighlightedArticleQuery A secondary query class using the current class as primary query
     */
    public function useHighlightedArticleQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinHighlightedArticle($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'HighlightedArticle', '\SciMS\Models\HighlightedArticleQuery');
    }

    /**
     * Filter the query by a related \SciMS\Models\Comment object
     *
     * @param \SciMS\Models\Comment|ObjectCollection $comment the related object to use as filter
     * @param string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return ChildArticleQuery The current query, for fluid interface
     */
    public function filterByComment($comment, $comparison = null)
    {
        if ($comment instanceof \SciMS\Models\Comment) {
            return $this
                ->addUsingAlias(ArticleTableMap::COL_ID, $comment->getArticleId(), $comparison);
        } elseif ($comment instanceof ObjectCollection) {
            return $this
                ->useCommentQuery()
                ->filterByPrimaryKeys($comment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByComment() only accepts arguments of type \SciMS\Models\Comment or Collection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the Comment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
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
     * @return \SciMS\Models\CommentQuery A secondary query class using the current class as primary query
     */
    public function useCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'Comment', '\SciMS\Models\CommentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   ChildArticle $article Object to remove from the list of results
     *
     * @return $this|ChildArticleQuery The current query, for fluid interface
     */
    public function prune($article = null)
    {
        if ($article) {
            $this->addUsingAlias(ArticleTableMap::COL_ID, $article->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Deletes all rows from the article table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTableMap::DATABASE_NAME);
        }

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con) {
            $affectedRows = 0; // initialize var to track total num of affected rows
            $affectedRows += parent::doDeleteAll($con);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            ArticleTableMap::clearInstancePool();
            ArticleTableMap::clearRelatedInstancePool();

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
            $con = Propel::getServiceContainer()->getWriteConnection(ArticleTableMap::DATABASE_NAME);
        }

        $criteria = $this;

        // Set the correct dbName
        $criteria->setDbName(ArticleTableMap::DATABASE_NAME);

        // use transaction because $criteria could contain info
        // for more than one table or we could emulating ON DELETE CASCADE, etc.
        return $con->transaction(function () use ($con, $criteria) {
            $affectedRows = 0; // initialize var to track total num of affected rows

            ArticleTableMap::removeInstanceFromPool($criteria);

            $affectedRows += ModelCriteria::delete($con);
            ArticleTableMap::clearRelatedInstancePool();

            return $affectedRows;
        });
    }

} // ArticleQuery
