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
use SciMS\Models\Account;
use SciMS\Models\AccountQuery;


/**
 * This class defines the structure of the 'account' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 */
class AccountTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'SciMS.Models.Map.AccountTableMap';

    /**
     * The default database name for this class
     */
    const DATABASE_NAME = 'scims';

    /**
     * The table name for this class
     */
    const TABLE_NAME = 'account';

    /**
     * The related Propel class for this table
     */
    const OM_CLASS = '\\SciMS\\Models\\Account';

    /**
     * A class that can be returned by this tableMap
     */
    const CLASS_DEFAULT = 'SciMS.Models.Account';

    /**
     * The total number of columns
     */
    const NUM_COLUMNS = 9;

    /**
     * The number of lazy-loaded columns
     */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /**
     * The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS)
     */
    const NUM_HYDRATE_COLUMNS = 9;

    /**
     * the column name for the id field
     */
    const COL_ID = 'account.id';

    /**
     * the column name for the uid field
     */
    const COL_UID = 'account.uid';

    /**
     * the column name for the email field
     */
    const COL_EMAIL = 'account.email';

    /**
     * the column name for the first_name field
     */
    const COL_FIRST_NAME = 'account.first_name';

    /**
     * the column name for the last_name field
     */
    const COL_LAST_NAME = 'account.last_name';

    /**
     * the column name for the biography field
     */
    const COL_BIOGRAPHY = 'account.biography';

    /**
     * the column name for the password field
     */
    const COL_PASSWORD = 'account.password';

    /**
     * the column name for the token field
     */
    const COL_TOKEN = 'account.token';

    /**
     * the column name for the token_expiration field
     */
    const COL_TOKEN_EXPIRATION = 'account.token_expiration';

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
        self::TYPE_PHPNAME       => array('Id', 'Uid', 'Email', 'FirstName', 'LastName', 'Biography', 'Password', 'Token', 'TokenExpiration', ),
        self::TYPE_CAMELNAME     => array('id', 'uid', 'email', 'firstName', 'lastName', 'biography', 'password', 'token', 'tokenExpiration', ),
        self::TYPE_COLNAME       => array(AccountTableMap::COL_ID, AccountTableMap::COL_UID, AccountTableMap::COL_EMAIL, AccountTableMap::COL_FIRST_NAME, AccountTableMap::COL_LAST_NAME, AccountTableMap::COL_BIOGRAPHY, AccountTableMap::COL_PASSWORD, AccountTableMap::COL_TOKEN, AccountTableMap::COL_TOKEN_EXPIRATION, ),
        self::TYPE_FIELDNAME     => array('id', 'uid', 'email', 'first_name', 'last_name', 'biography', 'password', 'token', 'token_expiration', ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. self::$fieldKeys[self::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'Uid' => 1, 'Email' => 2, 'FirstName' => 3, 'LastName' => 4, 'Biography' => 5, 'Password' => 6, 'Token' => 7, 'TokenExpiration' => 8, ),
        self::TYPE_CAMELNAME     => array('id' => 0, 'uid' => 1, 'email' => 2, 'firstName' => 3, 'lastName' => 4, 'biography' => 5, 'password' => 6, 'token' => 7, 'tokenExpiration' => 8, ),
        self::TYPE_COLNAME       => array(AccountTableMap::COL_ID => 0, AccountTableMap::COL_UID => 1, AccountTableMap::COL_EMAIL => 2, AccountTableMap::COL_FIRST_NAME => 3, AccountTableMap::COL_LAST_NAME => 4, AccountTableMap::COL_BIOGRAPHY => 5, AccountTableMap::COL_PASSWORD => 6, AccountTableMap::COL_TOKEN => 7, AccountTableMap::COL_TOKEN_EXPIRATION => 8, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'uid' => 1, 'email' => 2, 'first_name' => 3, 'last_name' => 4, 'biography' => 5, 'password' => 6, 'token' => 7, 'token_expiration' => 8, ),
        self::TYPE_NUM           => array(0, 1, 2, 3, 4, 5, 6, 7, 8, )
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
        $this->setName('account');
        $this->setPhpName('Account');
        $this->setIdentifierQuoting(false);
        $this->setClassName('\\SciMS\\Models\\Account');
        $this->setPackage('SciMS.Models');
        $this->setUseIdGenerator(true);
        $this->setPrimaryKeyMethodInfo('account_id_seq');
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uid', 'Uid', 'VARCHAR', true, 16, null);
        $this->addPrimaryKey('email', 'Email', 'VARCHAR', true, 254, null);
        $this->addColumn('first_name', 'FirstName', 'VARCHAR', true, 128, null);
        $this->addColumn('last_name', 'LastName', 'VARCHAR', true, 128, null);
        $this->addColumn('biography', 'Biography', 'LONGVARCHAR', false, null, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 255, null);
        $this->addColumn('token', 'Token', 'VARCHAR', false, 255, null);
        $this->addColumn('token_expiration', 'TokenExpiration', 'INTEGER', false, 8, null);
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('Article', '\\SciMS\\Models\\Article', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':account_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'Articles', false);
        $this->addRelation('HighlightedArticle', '\\SciMS\\Models\\HighlightedArticle', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':account_id',
    1 => ':id',
  ),
), null, null, 'HighlightedArticles', false);
        $this->addRelation('Comment', '\\SciMS\\Models\\Comment', RelationMap::ONE_TO_MANY, array (
  0 =>
  array (
    0 => ':author_id',
    1 => ':id',
  ),
), 'CASCADE', null, 'Comments', false);
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
            'validate' => array('email_blank' => array ('column' => 'email','validator' => 'NotBlank','options' => array ('message' => 'INVALID_EMAIL',),), 'email_already_exists' => array ('column' => 'email','validator' => 'Unique','options' => array ('message' => 'EMAIL_ALREADY_EXISTS',),), 'first_name' => array ('column' => 'first_name','validator' => 'NotBlank','options' => array ('message' => 'INVALID_FIRST_NAME',),), 'last_name' => array ('column' => 'last_name','validator' => 'NotBlank','options' => array ('message' => 'INVALID_LAST_NAME',),), 'password' => array ('column' => 'password','validator' => 'NotNull','options' => array ('message' => 'INVALID_PASSWORD',),), ),
        );
    } // getBehaviors()

    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database. In some cases you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by find*()
     * and findPk*() calls.
     *
     * @param \SciMS\Models\Account $obj A \SciMS\Models\Account object.
     * @param string $key             (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (null === $key) {
                $key = serialize([(null === $obj->getId() || is_scalar($obj->getId()) || is_callable([$obj->getId(), '__toString']) ? (string) $obj->getId() : $obj->getId()), (null === $obj->getEmail() || is_scalar($obj->getEmail()) || is_callable([$obj->getEmail(), '__toString']) ? (string) $obj->getEmail() : $obj->getEmail())]);
            } // if key === null
            self::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param mixed $value A \SciMS\Models\Account object or a primary key value.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && null !== $value) {
            if (is_object($value) && $value instanceof \SciMS\Models\Account) {
                $key = serialize([(null === $value->getId() || is_scalar($value->getId()) || is_callable([$value->getId(), '__toString']) ? (string) $value->getId() : $value->getId()), (null === $value->getEmail() || is_scalar($value->getEmail()) || is_callable([$value->getEmail(), '__toString']) ? (string) $value->getEmail() : $value->getEmail())]);

            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key";
                $key = serialize([(null === $value[0] || is_scalar($value[0]) || is_callable([$value[0], '__toString']) ? (string) $value[0] : $value[0]), (null === $value[1] || is_scalar($value[1]) || is_callable([$value[1], '__toString']) ? (string) $value[1] : $value[1])]);
            } elseif ($value instanceof Criteria) {
                self::$instances = [];

                return;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or \SciMS\Models\Account object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value, true)));
                throw $e;
            }

            unset(self::$instances[$key]);
        }
    }
    /**
     * Method to invalidate the instance pool of all tables related to account     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in related instance pools,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        ArticleTableMap::clearInstancePool();
        CommentTableMap::clearInstancePool();
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
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null && $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return serialize([(null === $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)]), (null === $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)] || is_scalar($row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)]) || is_callable([$row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)], '__toString']) ? (string) $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)] : $row[TableMap::TYPE_NUM == $indexType ? 2 + $offset : static::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)])]);
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
            $pks = [];

        $pks[] = (int) $row[
            $indexType == TableMap::TYPE_NUM
                ? 0 + $offset
                : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
        ];
        $pks[] = (string) $row[
            $indexType == TableMap::TYPE_NUM
                ? 2 + $offset
                : self::translateFieldName('Email', TableMap::TYPE_PHPNAME, $indexType)
        ];

        return $pks;
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
        return $withPrefix ? AccountTableMap::CLASS_DEFAULT : AccountTableMap::OM_CLASS;
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
     * @return array           (Account object, last column rank)
     */
    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = AccountTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = AccountTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + AccountTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = AccountTableMap::OM_CLASS;
            /** @var Account $obj */
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            AccountTableMap::addInstanceToPool($obj, $key);
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
            $key = AccountTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = AccountTableMap::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                /** @var Account $obj */
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                AccountTableMap::addInstanceToPool($obj, $key);
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
            $criteria->addSelectColumn(AccountTableMap::COL_ID);
            $criteria->addSelectColumn(AccountTableMap::COL_UID);
            $criteria->addSelectColumn(AccountTableMap::COL_EMAIL);
            $criteria->addSelectColumn(AccountTableMap::COL_FIRST_NAME);
            $criteria->addSelectColumn(AccountTableMap::COL_LAST_NAME);
            $criteria->addSelectColumn(AccountTableMap::COL_BIOGRAPHY);
            $criteria->addSelectColumn(AccountTableMap::COL_PASSWORD);
            $criteria->addSelectColumn(AccountTableMap::COL_TOKEN);
            $criteria->addSelectColumn(AccountTableMap::COL_TOKEN_EXPIRATION);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uid');
            $criteria->addSelectColumn($alias . '.email');
            $criteria->addSelectColumn($alias . '.first_name');
            $criteria->addSelectColumn($alias . '.last_name');
            $criteria->addSelectColumn($alias . '.biography');
            $criteria->addSelectColumn($alias . '.password');
            $criteria->addSelectColumn($alias . '.token');
            $criteria->addSelectColumn($alias . '.token_expiration');
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
        return Propel::getServiceContainer()->getDatabaseMap(AccountTableMap::DATABASE_NAME)->getTable(AccountTableMap::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this tableMap class.
     */
    public static function buildTableMap()
    {
        $dbMap = Propel::getServiceContainer()->getDatabaseMap(AccountTableMap::DATABASE_NAME);
        if (!$dbMap->hasTable(AccountTableMap::TABLE_NAME)) {
            $dbMap->addTableObject(new AccountTableMap());
        }
    }

    /**
     * Performs a DELETE on the database, given a Account or Criteria object OR a primary key value.
     *
     * @param mixed               $values Criteria or Account object or primary key or array of primary keys
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
            $con = Propel::getServiceContainer()->getWriteConnection(AccountTableMap::DATABASE_NAME);
        }

        if ($values instanceof Criteria) {
            // rename for clarity
            $criteria = $values;
        } elseif ($values instanceof \SciMS\Models\Account) { // it's a model object
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(AccountTableMap::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(AccountTableMap::COL_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(AccountTableMap::COL_EMAIL, $value[1]));
                $criteria->addOr($criterion);
            }
        }

        $query = AccountQuery::create()->mergeWith($criteria);

        if ($values instanceof Criteria) {
            AccountTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) {
                AccountTableMap::removeInstanceFromPool($singleval);
            }
        }

        return $query->delete($con);
    }

    /**
     * Deletes all rows from the account table.
     *
     * @param ConnectionInterface $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).
     */
    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return AccountQuery::create()->doDeleteAll($con);
    }

    /**
     * Performs an INSERT on the database, given a Account or Criteria object.
     *
     * @param mixed               $criteria Criteria or Account object containing data that is used to create the INSERT statement.
     * @param ConnectionInterface $con the ConnectionInterface connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *                         rethrown wrapped into a PropelException.
     */
    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(AccountTableMap::DATABASE_NAME);
        }

        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from Account object
        }

        if ($criteria->containsKey(AccountTableMap::COL_ID) && $criteria->keyContainsValue(AccountTableMap::COL_ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.AccountTableMap::COL_ID.')');
        }


        // Set the correct dbName
        $query = AccountQuery::create()->mergeWith($criteria);

        // use transaction because $criteria could contain info
        // for more than one table (I guess, conceivably)
        return $con->transaction(function () use ($con, $query) {
            return $query->doInsert($con);
        });
    }

} // AccountTableMap
// This is the static code needed to register the TableMap for this table with the main Propel class.
//
AccountTableMap::buildTableMap();
