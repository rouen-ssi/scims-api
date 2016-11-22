<?php

namespace SciMS\Models\Map;

use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;
use SciMS\Models\Comment;
use SciMS\Models\CommentQuery;


/**
 * This class defines the structure of the 'comment' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class CommentTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'SciMS.Models.Map.CommentTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'scims';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'comment';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SciMS\\Models\\Comment';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'SciMS.Models.Comment';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 6;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 6;

    /**
     * the column name for the id field
     */
    const COL_ID = 'comment.id';

    /**
     * the column name for the parent_comment_id field
     */
    const COL_PARENT_COMMENT_ID = 'comment.parent_comment_id';

    /**
     * the column name for the author_id field
     */
    const COL_AUTHOR_ID = 'comment.author_id';

    /**
     * the column name for the article_id field
     */
    const COL_ARTICLE_ID = 'comment.article_id';

    /**
     * the column name for the publication_date field
     */
    const COL_PUBLICATION_DATE = 'comment.publication_date';

    /**
     * the column name for the content field
     */
    const COL_CONTENT = 'comment.content';

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
        self::TYPE_PHPNAME       => array('Id', 'ParentCommentId', 'AuthorId', 'ArticleId', 'PublicationDate', 'Content', ),
        self::TYPE_CAMELNAME     => array('id', 'parentCommentId', 'authorId', 'articleId', 'publicationDate', 'content', ),
        self::TYPE_COLNAME       => array(CommentTableMap::COL_ID, CommentTableMap::COL_PARENT_COMMENT_ID, CommentTableMap::COL_AUTHOR_ID, CommentTableMap::COL_ARTICLE_ID, CommentTableMap::COL_PUBLICATION_DATE, CommentTableMap::COL_CONTENT, ),
        self::TYPE_FIELDNAME     => array('id', 'parent_comment_id', 'author_id', 'article_id', 'publication_date', 'content', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ParentCommentId' => 1, 'AuthorId' => 2, 'ArticleId' => 3, 'PublicationDate' => 4, 'Content' => 5, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'parentCommentId' => 1, 'authorId' => 2, 'articleId' => 3, 'publicationDate' => 4, 'content' => 5, ),
        self::TYPE_COLNAME       => array(CommentTableMap::COL_ID => 0, CommentTableMap::COL_PARENT_COMMENT_ID => 1, CommentTableMap::COL_AUTHOR_ID => 2, CommentTableMap::COL_ARTICLE_ID => 3, CommentTableMap::COL_PUBLICATION_DATE => 4, CommentTableMap::COL_CONTENT => 5, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'parent_comment_id' => 1, 'author_id' => 2, 'article_id' => 3, 'publication_date' => 4, 'content' => 5, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, )
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
        $this->setName('comment');
        $this->setPhpName('Comment');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\SciMS\\Models\\Comment');
        $this->setPackage('SciMS.Models');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('parent_comment_id', 'ParentCommentId', 'INTEGER', 'comment', 'id', false, null, null);
        $this->addForeignKey('author_id', 'AuthorId', 'INTEGER', 'user', 'id', true, null, null);
        $this->addForeignKey('article_id', 'ArticleId', 'INTEGER', 'article', 'id', true, null, null);
        $this->addColumn('publication_date', 'PublicationDate', 'INTEGER', true, null, null);
        $this->addColumn('content', 'Content', 'LONGVARCHAR', true, null, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('ParentComment', '\\SciMS\\Models\\Comment', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':parent_comment_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Author', '\\SciMS\\Models\\User', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':author_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('Article', '\\SciMS\\Models\\Article', RelationMap::MANY_TO_ONE, array (
  0 =>
  array (
    0 => ':article_id',
    1 => ':id',
  ),
), null, null, null, false);
        $this->addRelation('CommentRelatedById', '\\SciMS\\Models\\Comment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':parent_comment_id',
    1 => ':id',
  ),
), null, null, 'CommentsRelatedById', false);
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
            'validate' => array('content_not_blank' => array ('column' => 'content','validator' => 'NotBlank','options' => array ('message' => 'INVALID_CONTENT',),), ),
        );
    } // getBehaviors()

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
        return $withPrefix ? CommentTableMap::CLASS_DEFAULT : CommentTableMap::OM_CLASS;
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
     * @return array           (Comment object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = CommentTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = CommentTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + CommentTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = CommentTableMap::OM_CLASS;
            /** @var Comment $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            CommentTableMap::addInstanceToPool($obj, $key);
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
            $key = CommentTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = CommentTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Comment $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                CommentTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(CommentTableMap::COL_ID);
            $criteria->addSelectColumn(CommentTableMap::COL_PARENT_COMMENT_ID);
            $criteria->addSelectColumn(CommentTableMap::COL_AUTHOR_ID);
            $criteria->addSelectColumn(CommentTableMap::COL_ARTICLE_ID);
            $criteria->addSelectColumn(CommentTableMap::COL_PUBLICATION_DATE);
            $criteria->addSelectColumn(CommentTableMap::COL_CONTENT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.parent_comment_id');
            $criteria->addSelectColumn($alias . '.author_id');
            $criteria->addSelectColumn($alias . '.article_id');
            $criteria->addSelectColumn($alias . '.publication_date');
            $criteria->addSelectColumn($alias . '.content');
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
        return Propel::getServiceContainer()->getDatabaseMap(CommentTableMap::DATABASE_NAME)->getTable(CommentTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(CommentTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(CommentTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new CommentTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Comment or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Comment object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SciMS\Models\Comment) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(CommentTableMap::DATABASE_NAME);
            $criteria->add(CommentTableMap::COL_ID, (array) $values, Criteria::IN);
        }

        $query = CommentQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            CommentTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                CommentTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the comment table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return CommentQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Comment or Criteria object.
     *
     * @param mixed               $criteria Criteria or Comment object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(CommentTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Comment object
        }

        if ($criteria->containsKey(CommentTableMap::COL_ID) && $criteria->keyContainsValue(CommentTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.CommentTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = CommentQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // CommentTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
CommentTableMap::buildTableMap();
