<?php

namespace SciMS\Models\Base;

use \Exception;
use \PDO;
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
use SciMS\Models\Article as ChildArticle;
use SciMS\Models\ArticleQuery as ChildArticleQuery;
use SciMS\Models\Category as ChildCategory;
use SciMS\Models\CategoryQuery as ChildCategoryQuery;
use SciMS\Models\Map\ArticleTableMap;
use SciMS\Models\Map\CategoryTableMap;
use Symfony\Component\Translation\IdentityTranslator;
use Symfony\Component\Validator\ConstraintValidatorFactory;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextFactory;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Mapping\Factory\LazyLoadingMetadataFactory;
use Symfony\Component\Validator\Mapping\Loader\StaticMethodLoader;
use Symfony\Component\Validator\Validator\RecursiveValidator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Base class that represents a row from the 'category' table.
 *
 *
 *
 * @package    propel.generator.SciMS.Models.Base
 */
abstract class Category implements ActiveRecordInterface
{
    /**
     * TableMap class name
     */
    const TABLE_MAP = '\\SciMS\\Models\\Map\\CategoryTableMap';


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
     * The value for the name field.
     *
     * @var        string
     */
    protected $name;

    /**
     * The value for the parent_category_id field.
     *
     * Note: this column has a database default value of: -1
     * @var        int
     */
    protected $parent_category_id;

    /**
     * @var        ChildCategory
     */
    protected $aparentCategory;

    /**
     * @var        ObjectCollection|ChildArticle[] Collection to store aggregation of ChildArticle objects.
     */
    protected $collArticlesRelatedByCategoryId;
    protected $collArticlesRelatedByCategoryIdPartial;

    /**
     * @var        ObjectCollection|ChildArticle[] Collection to store aggregation of ChildArticle objects.
     */
    protected $collArticlesRelatedBySubcategoryId;
    protected $collArticlesRelatedBySubcategoryIdPartial;

    /**
     * @var        ObjectCollection|ChildCategory[] Collection to store aggregation of ChildCategory objects.
     */
    protected $collCategoriesRelatedById;
    protected $collCategoriesRelatedByIdPartial;

    /**
     * Flag to prevent endless save loop, if this object is referenced
     * by another object which falls in this transaction.
     *
     * @var boolean
     */
    protected $alreadyInSave = false;

    // validate behavior

    /**
     * Flag to prevent endless validation loop, if this object is referenced
     * by another object which falls in this transaction.
     * @var        boolean
     */
    protected $alreadyInValidation = false;

    /**
     * ConstraintViolationList object
     *
     * @see     http://api.symfony.com/2.0/Symfony/Component/Validator/ConstraintViolationList.html
     * @var     ConstraintViolationList
     */
    protected $validationFailures;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildArticle[]
     */
    protected $articlesRelatedByCategoryIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildArticle[]
     */
    protected $articlesRelatedBySubcategoryIdScheduledForDeletion = null;

    /**
     * An array of objects scheduled for deletion.
     * @var ObjectCollection|ChildCategory[]
     */
    protected $categoriesRelatedByIdScheduledForDeletion = null;

    /**
     * Applies default values to this object.
     * This method should be called from the object's constructor (or
     * equivalent initialization method).
     * @see __construct()
     */
    public function applyDefaultValues()
    {
        $this->parent_category_id = -1;
    }

    /**
     * Initializes internal state of SciMS\Models\Base\Category object.
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
     * Compares this with another <code>Category</code> instance.  If
     * <code>obj</code> is an instance of <code>Category</code>, delegates to
     * <code>equals(Category)</code>.  Otherwise, returns <code>false</code>.
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
     * @return $this|Category The current object, for fluid interface
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
     * Get the [name] column value.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the [parent_category_id] column value.
     *
     * @return int
     */
    public function getParentCategoryId()
    {
        return $this->parent_category_id;
    }

    /**
     * Set the value of [id] column.
     *
     * @param int $v new value
     * @return $this|\SciMS\Models\Category The current object (for fluent API support)
     */
    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[CategoryTableMap::COL_ID] = true;
        }

        return $this;
    } // setId()

    /**
     * Set the value of [name] column.
     *
     * @param string $v new value
     * @return $this|\SciMS\Models\Category The current object (for fluent API support)
     */
    public function setName($v)
    {
        if ($v !== null) {
            $v = (string) $v;
        }

        if ($this->name !== $v) {
            $this->name = $v;
            $this->modifiedColumns[CategoryTableMap::COL_NAME] = true;
        }

        return $this;
    } // setName()

    /**
     * Set the value of [parent_category_id] column.
     *
     * @param int $v new value
     * @return $this|\SciMS\Models\Category The current object (for fluent API support)
     */
    public function setParentCategoryId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }

        if ($this->parent_category_id !== $v) {
            $this->parent_category_id = $v;
            $this->modifiedColumns[CategoryTableMap::COL_PARENT_CATEGORY_ID] = true;
        }

        if ($this->aparentCategory !== null && $this->aparentCategory->getId() !== $v) {
            $this->aparentCategory = null;
        }

        return $this;
    } // setParentCategoryId()

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
            if ($this->parent_category_id !== -1) {
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

            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : CategoryTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : CategoryTableMap::translateFieldName('Name', TableMap::TYPE_PHPNAME, $indexType)];
            $this->name = (null !== $col) ? (string) $col : null;

            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : CategoryTableMap::translateFieldName('ParentCategoryId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->parent_category_id = (null !== $col) ? (int) $col : null;
            $this->resetModified();

            $this->setNew(false);

            if ($rehydrate) {
                $this->ensureConsistency();
            }

            return $startcol + 3; // 3 = CategoryTableMap::NUM_HYDRATE_COLUMNS.

        } catch (Exception $e) {
            throw new PropelException(sprintf('Error populating %s object', '\\SciMS\\Models\\Category'), 0, $e);
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
        if ($this->aparentCategory !== null && $this->parent_category_id !== $this->aparentCategory->getId()) {
            $this->aparentCategory = null;
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
            $con = Propel::getServiceContainer()->getReadConnection(CategoryTableMap::DATABASE_NAME);
        }

        // We don't need to alter the object instance pool; we're just modifying this instance
        // already in the pool.

        $dataFetcher = ChildCategoryQuery::create(null, $this->buildPkeyCriteria())->setFormatter(ModelCriteria::FORMAT_STATEMENT)->find($con);
        $row = $dataFetcher->fetch();
        $dataFetcher->close();
        if (!$row) {
            throw new PropelException('Cannot find matching row in the database to reload object values.');
        }
        $this->hydrate($row, 0, true, $dataFetcher->getIndexType()); // rehydrate

        if ($deep) {  // also de-associate any related objects?

            $this->aparentCategory = null;
            $this->collArticlesRelatedByCategoryId = null;

            $this->collArticlesRelatedBySubcategoryId = null;

            $this->collCategoriesRelatedById = null;

        } // if (deep)
    }

    /**
     * Removes this object from datastore and sets delete attribute.
     *
     * @param      ConnectionInterface $con
     * @return void
     * @throws PropelException
     * @see Category::setDeleted()
     * @see Category::isDeleted()
     */
    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryTableMap::DATABASE_NAME);
        }

        $con->transaction(function () use ($con) {
            $deleteQuery = ChildCategoryQuery::create()
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
            $con = Propel::getServiceContainer()->getWriteConnection(CategoryTableMap::DATABASE_NAME);
        }

        return $con->transaction(function () use ($con) {
            $ret = $this->preSave($con);
            $isInsert = $this->isNew();
            if ($isInsert) {
                $ret = $ret && $this->preInsert($con);
            } else {
                $ret = $ret && $this->preUpdate($con);
            }
            if ($ret) {
                $affectedRows = $this->doSave($con);
                if ($isInsert) {
                    $this->postInsert($con);
                } else {
                    $this->postUpdate($con);
                }
                $this->postSave($con);
                CategoryTableMap::addInstanceToPool($this);
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

            if ($this->aparentCategory !== null) {
                if ($this->aparentCategory->isModified() || $this->aparentCategory->isNew()) {
                    $affectedRows += $this->aparentCategory->save($con);
                }
                $this->setparentCategory($this->aparentCategory);
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

            if ($this->articlesRelatedByCategoryIdScheduledForDeletion !== null) {
                if (!$this->articlesRelatedByCategoryIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->articlesRelatedByCategoryIdScheduledForDeletion as $articleRelatedByCategoryId) {
                        // need to save related object because we set the relation to null
                        $articleRelatedByCategoryId->save($con);
                    }
                    $this->articlesRelatedByCategoryIdScheduledForDeletion = null;
                }
            }

            if ($this->collArticlesRelatedByCategoryId !== null) {
                foreach ($this->collArticlesRelatedByCategoryId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->articlesRelatedBySubcategoryIdScheduledForDeletion !== null) {
                if (!$this->articlesRelatedBySubcategoryIdScheduledForDeletion->isEmpty()) {
                    foreach ($this->articlesRelatedBySubcategoryIdScheduledForDeletion as $articleRelatedBySubcategoryId) {
                        // need to save related object because we set the relation to null
                        $articleRelatedBySubcategoryId->save($con);
                    }
                    $this->articlesRelatedBySubcategoryIdScheduledForDeletion = null;
                }
            }

            if ($this->collArticlesRelatedBySubcategoryId !== null) {
                foreach ($this->collArticlesRelatedBySubcategoryId as $referrerFK) {
                    if (!$referrerFK->isDeleted() && ($referrerFK->isNew() || $referrerFK->isModified())) {
                        $affectedRows += $referrerFK->save($con);
                    }
                }
            }

            if ($this->categoriesRelatedByIdScheduledForDeletion !== null) {
                if (!$this->categoriesRelatedByIdScheduledForDeletion->isEmpty()) {
                    \SciMS\Models\CategoryQuery::create()
                        ->filterByPrimaryKeys($this->categoriesRelatedByIdScheduledForDeletion->getPrimaryKeys(false))
                        ->delete($con);
                    $this->categoriesRelatedByIdScheduledForDeletion = null;
                }
            }

            if ($this->collCategoriesRelatedById !== null) {
                foreach ($this->collCategoriesRelatedById as $referrerFK) {
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

        $this->modifiedColumns[CategoryTableMap::COL_ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . CategoryTableMap::COL_ID . ')');
        }
        if (null === $this->id) {
            try {
                $dataFetcher = $con->query("SELECT nextval('category_id_seq')");
                $this->id = (int) $dataFetcher->fetchColumn();
            } catch (Exception $e) {
                throw new PropelException('Unable to get sequence id.', 0, $e);
            }
        }


         // check the columns in natural order for more readable SQL queries
        if ($this->isColumnModified(CategoryTableMap::COL_ID)) {
            $modifiedColumns[':p' . $index++]  = 'id';
        }
        if ($this->isColumnModified(CategoryTableMap::COL_NAME)) {
            $modifiedColumns[':p' . $index++]  = 'name';
        }
        if ($this->isColumnModified(CategoryTableMap::COL_PARENT_CATEGORY_ID)) {
            $modifiedColumns[':p' . $index++]  = 'parent_category_id';
        }

        $sql = sprintf(
            'INSERT INTO category (%s) VALUES (%s)',
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
                    case 'name':
                        $stmt->bindValue($identifier, $this->name, PDO::PARAM_STR);
                        break;
                    case 'parent_category_id':
                        $stmt->bindValue($identifier, $this->parent_category_id, PDO::PARAM_INT);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }

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
        $pos = CategoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);
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
                return $this->getName();
                break;
            case 2:
                return $this->getParentCategoryId();
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

        if (isset($alreadyDumpedObjects['Category'][$this->hashCode()])) {
            return '*RECURSION*';
        }
        $alreadyDumpedObjects['Category'][$this->hashCode()] = true;
        $keys = CategoryTableMap::getFieldNames($keyType);
        $result = array(
            $keys[0] => $this->getId(),
            $keys[1] => $this->getName(),
            $keys[2] => $this->getParentCategoryId(),
        );
        $virtualColumns = $this->virtualColumns;
        foreach ($virtualColumns as $key => $virtualColumn) {
            $result[$key] = $virtualColumn;
        }

        if ($includeForeignObjects) {
            if (null !== $this->aparentCategory) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'category';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'category';
                        break;
                    default:
                        $key = 'parentCategory';
                }

                $result[$key] = $this->aparentCategory->toArray($keyType, $includeLazyLoadColumns,  $alreadyDumpedObjects, true);
            }
            if (null !== $this->collArticlesRelatedByCategoryId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'articles';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'articles';
                        break;
                    default:
                        $key = 'Articles';
                }

                $result[$key] = $this->collArticlesRelatedByCategoryId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collArticlesRelatedBySubcategoryId) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'articles';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'articles';
                        break;
                    default:
                        $key = 'Articles';
                }

                $result[$key] = $this->collArticlesRelatedBySubcategoryId->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
            }
            if (null !== $this->collCategoriesRelatedById) {

                switch ($keyType) {
                    case TableMap::TYPE_CAMELNAME:
                        $key = 'categories';
                        break;
                    case TableMap::TYPE_FIELDNAME:
                        $key = 'categories';
                        break;
                    default:
                        $key = 'Categories';
                }

                $result[$key] = $this->collCategoriesRelatedById->toArray(null, false, $keyType, $includeLazyLoadColumns, $alreadyDumpedObjects);
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
     * @return $this|\SciMS\Models\Category
     */
    public function setByName($name, $value, $type = TableMap::TYPE_PHPNAME)
    {
        $pos = CategoryTableMap::translateFieldName($name, $type, TableMap::TYPE_NUM);

        return $this->setByPosition($pos, $value);
    }

    /**
     * Sets a field from the object by Position as specified in the xml schema.
     * Zero-based.
     *
     * @param  int $pos position in xml schema
     * @param  mixed $value field value
     * @return $this|\SciMS\Models\Category
     */
    public function setByPosition($pos, $value)
    {
        switch ($pos) {
            case 0:
                $this->setId($value);
                break;
            case 1:
                $this->setName($value);
                break;
            case 2:
                $this->setParentCategoryId($value);
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
        $keys = CategoryTableMap::getFieldNames($keyType);

        if (array_key_exists($keys[0], $arr)) {
            $this->setId($arr[$keys[0]]);
        }
        if (array_key_exists($keys[1], $arr)) {
            $this->setName($arr[$keys[1]]);
        }
        if (array_key_exists($keys[2], $arr)) {
            $this->setParentCategoryId($arr[$keys[2]]);
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
     * @return $this|\SciMS\Models\Category The current object, for fluid interface
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
        $criteria = new Criteria(CategoryTableMap::DATABASE_NAME);

        if ($this->isColumnModified(CategoryTableMap::COL_ID)) {
            $criteria->add(CategoryTableMap::COL_ID, $this->id);
        }
        if ($this->isColumnModified(CategoryTableMap::COL_NAME)) {
            $criteria->add(CategoryTableMap::COL_NAME, $this->name);
        }
        if ($this->isColumnModified(CategoryTableMap::COL_PARENT_CATEGORY_ID)) {
            $criteria->add(CategoryTableMap::COL_PARENT_CATEGORY_ID, $this->parent_category_id);
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
        $criteria = ChildCategoryQuery::create();
        $criteria->add(CategoryTableMap::COL_ID, $this->id);

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
     * @param      object $copyObj An object of \SciMS\Models\Category (or compatible) type.
     * @param      boolean $deepCopy Whether to also copy all rows that refer (by fkey) to the current row.
     * @param      boolean $makeNew Whether to reset autoincrement PKs and make the object new.
     * @throws PropelException
     */
    public function copyInto($copyObj, $deepCopy = false, $makeNew = true)
    {
        $copyObj->setName($this->getName());
        $copyObj->setParentCategoryId($this->getParentCategoryId());

        if ($deepCopy) {
            // important: temporarily setNew(false) because this affects the behavior of
            // the getter/setter methods for fkey referrer objects.
            $copyObj->setNew(false);

            foreach ($this->getArticlesRelatedByCategoryId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArticleRelatedByCategoryId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getArticlesRelatedBySubcategoryId() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addArticleRelatedBySubcategoryId($relObj->copy($deepCopy));
                }
            }

            foreach ($this->getCategoriesRelatedById() as $relObj) {
                if ($relObj !== $this) {  // ensure that we don't try to copy a reference to ourselves
                    $copyObj->addCategoryRelatedById($relObj->copy($deepCopy));
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
     * @return \SciMS\Models\Category Clone of current object.
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
     * Declares an association between this object and a ChildCategory object.
     *
     * @param  ChildCategory $v
     * @return $this|\SciMS\Models\Category The current object (for fluent API support)
     * @throws PropelException
     */
    public function setparentCategory(ChildCategory $v = null)
    {
        if ($v === null) {
            $this->setParentCategoryId(-1);
        } else {
            $this->setParentCategoryId($v->getId());
        }

        $this->aparentCategory = $v;

        // Add binding for other direction of this n:n relationship.
        // If this object has already been added to the ChildCategory object, it will not be re-added.
        if ($v !== null) {
            $v->addCategoryRelatedById($this);
        }


        return $this;
    }


    /**
     * Get the associated ChildCategory object
     *
     * @param  ConnectionInterface $con Optional Connection object.
     * @return ChildCategory The associated ChildCategory object.
     * @throws PropelException
     */
    public function getparentCategory(ConnectionInterface $con = null)
    {
        if ($this->aparentCategory === null && ($this->parent_category_id !== null)) {
            $this->aparentCategory = ChildCategoryQuery::create()->findPk($this->parent_category_id, $con);
            /* The following can be used additionally to
                guarantee the related object contains a reference
                to this object.  This level of coupling may, however, be
                undesirable since it could result in an only partially populated collection
                in the referenced object.
                $this->aparentCategory->addCategoriesRelatedById($this);
             */
        }

        return $this->aparentCategory;
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
        if ('ArticleRelatedByCategoryId' == $relationName) {
            return $this->initArticlesRelatedByCategoryId();
        }
        if ('ArticleRelatedBySubcategoryId' == $relationName) {
            return $this->initArticlesRelatedBySubcategoryId();
        }
        if ('CategoryRelatedById' == $relationName) {
            return $this->initCategoriesRelatedById();
        }
    }

    /**
     * Clears out the collArticlesRelatedByCategoryId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addArticlesRelatedByCategoryId()
     */
    public function clearArticlesRelatedByCategoryId()
    {
        $this->collArticlesRelatedByCategoryId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collArticlesRelatedByCategoryId collection loaded partially.
     */
    public function resetPartialArticlesRelatedByCategoryId($v = true)
    {
        $this->collArticlesRelatedByCategoryIdPartial = $v;
    }

    /**
     * Initializes the collArticlesRelatedByCategoryId collection.
     *
     * By default this just sets the collArticlesRelatedByCategoryId collection to an empty array (like clearcollArticlesRelatedByCategoryId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArticlesRelatedByCategoryId($overrideExisting = true)
    {
        if (null !== $this->collArticlesRelatedByCategoryId && !$overrideExisting) {
            return;
        }

        $collectionClassName = ArticleTableMap::getTableMap()->getCollectionClassName();

        $this->collArticlesRelatedByCategoryId = new $collectionClassName;
        $this->collArticlesRelatedByCategoryId->setModel('\SciMS\Models\Article');
    }

    /**
     * Gets an array of ChildArticle objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildArticle[] List of ChildArticle objects
     * @throws PropelException
     */
    public function getArticlesRelatedByCategoryId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collArticlesRelatedByCategoryIdPartial && !$this->isNew();
        if (null === $this->collArticlesRelatedByCategoryId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collArticlesRelatedByCategoryId) {
                // return empty collection
                $this->initArticlesRelatedByCategoryId();
            } else {
                $collArticlesRelatedByCategoryId = ChildArticleQuery::create(null, $criteria)
                    ->filterBycategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collArticlesRelatedByCategoryIdPartial && count($collArticlesRelatedByCategoryId)) {
                        $this->initArticlesRelatedByCategoryId(false);

                        foreach ($collArticlesRelatedByCategoryId as $obj) {
                            if (false == $this->collArticlesRelatedByCategoryId->contains($obj)) {
                                $this->collArticlesRelatedByCategoryId->append($obj);
                            }
                        }

                        $this->collArticlesRelatedByCategoryIdPartial = true;
                    }

                    return $collArticlesRelatedByCategoryId;
                }

                if ($partial && $this->collArticlesRelatedByCategoryId) {
                    foreach ($this->collArticlesRelatedByCategoryId as $obj) {
                        if ($obj->isNew()) {
                            $collArticlesRelatedByCategoryId[] = $obj;
                        }
                    }
                }

                $this->collArticlesRelatedByCategoryId = $collArticlesRelatedByCategoryId;
                $this->collArticlesRelatedByCategoryIdPartial = false;
            }
        }

        return $this->collArticlesRelatedByCategoryId;
    }

    /**
     * Sets a collection of ChildArticle objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $articlesRelatedByCategoryId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCategory The current object (for fluent API support)
     */
    public function setArticlesRelatedByCategoryId(Collection $articlesRelatedByCategoryId, ConnectionInterface $con = null)
    {
        /** @var ChildArticle[] $articlesRelatedByCategoryIdToDelete */
        $articlesRelatedByCategoryIdToDelete = $this->getArticlesRelatedByCategoryId(new Criteria(), $con)->diff($articlesRelatedByCategoryId);


        $this->articlesRelatedByCategoryIdScheduledForDeletion = $articlesRelatedByCategoryIdToDelete;

        foreach ($articlesRelatedByCategoryIdToDelete as $articleRelatedByCategoryIdRemoved) {
            $articleRelatedByCategoryIdRemoved->setcategory(null);
        }

        $this->collArticlesRelatedByCategoryId = null;
        foreach ($articlesRelatedByCategoryId as $articleRelatedByCategoryId) {
            $this->addArticleRelatedByCategoryId($articleRelatedByCategoryId);
        }

        $this->collArticlesRelatedByCategoryId = $articlesRelatedByCategoryId;
        $this->collArticlesRelatedByCategoryIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Article objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Article objects.
     * @throws PropelException
     */
    public function countArticlesRelatedByCategoryId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collArticlesRelatedByCategoryIdPartial && !$this->isNew();
        if (null === $this->collArticlesRelatedByCategoryId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArticlesRelatedByCategoryId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArticlesRelatedByCategoryId());
            }

            $query = ChildArticleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBycategory($this)
                ->count($con);
        }

        return count($this->collArticlesRelatedByCategoryId);
    }

    /**
     * Method called to associate a ChildArticle object to this object
     * through the ChildArticle foreign key attribute.
     *
     * @param  ChildArticle $l ChildArticle
     * @return $this|\SciMS\Models\Category The current object (for fluent API support)
     */
    public function addArticleRelatedByCategoryId(ChildArticle $l)
    {
        if ($this->collArticlesRelatedByCategoryId === null) {
            $this->initArticlesRelatedByCategoryId();
            $this->collArticlesRelatedByCategoryIdPartial = true;
        }

        if (!$this->collArticlesRelatedByCategoryId->contains($l)) {
            $this->doAddArticleRelatedByCategoryId($l);

            if ($this->articlesRelatedByCategoryIdScheduledForDeletion and $this->articlesRelatedByCategoryIdScheduledForDeletion->contains($l)) {
                $this->articlesRelatedByCategoryIdScheduledForDeletion->remove($this->articlesRelatedByCategoryIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildArticle $articleRelatedByCategoryId The ChildArticle object to add.
     */
    protected function doAddArticleRelatedByCategoryId(ChildArticle $articleRelatedByCategoryId)
    {
        $this->collArticlesRelatedByCategoryId[]= $articleRelatedByCategoryId;
        $articleRelatedByCategoryId->setcategory($this);
    }

    /**
     * @param  ChildArticle $articleRelatedByCategoryId The ChildArticle object to remove.
     * @return $this|ChildCategory The current object (for fluent API support)
     */
    public function removeArticleRelatedByCategoryId(ChildArticle $articleRelatedByCategoryId)
    {
        if ($this->getArticlesRelatedByCategoryId()->contains($articleRelatedByCategoryId)) {
            $pos = $this->collArticlesRelatedByCategoryId->search($articleRelatedByCategoryId);
            $this->collArticlesRelatedByCategoryId->remove($pos);
            if (null === $this->articlesRelatedByCategoryIdScheduledForDeletion) {
                $this->articlesRelatedByCategoryIdScheduledForDeletion = clone $this->collArticlesRelatedByCategoryId;
                $this->articlesRelatedByCategoryIdScheduledForDeletion->clear();
            }
            $this->articlesRelatedByCategoryIdScheduledForDeletion[]= $articleRelatedByCategoryId;
            $articleRelatedByCategoryId->setcategory(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Category is new, it will return
     * an empty collection; or if this Category has previously
     * been saved, it will retrieve related ArticlesRelatedByCategoryId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Category.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildArticle[] List of ChildArticle objects
     */
    public function getArticlesRelatedByCategoryIdJoinaccount(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildArticleQuery::create(null, $criteria);
        $query->joinWith('account', $joinBehavior);

        return $this->getArticlesRelatedByCategoryId($query, $con);
    }

    /**
     * Clears out the collArticlesRelatedBySubcategoryId collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addArticlesRelatedBySubcategoryId()
     */
    public function clearArticlesRelatedBySubcategoryId()
    {
        $this->collArticlesRelatedBySubcategoryId = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collArticlesRelatedBySubcategoryId collection loaded partially.
     */
    public function resetPartialArticlesRelatedBySubcategoryId($v = true)
    {
        $this->collArticlesRelatedBySubcategoryIdPartial = $v;
    }

    /**
     * Initializes the collArticlesRelatedBySubcategoryId collection.
     *
     * By default this just sets the collArticlesRelatedBySubcategoryId collection to an empty array (like clearcollArticlesRelatedBySubcategoryId());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initArticlesRelatedBySubcategoryId($overrideExisting = true)
    {
        if (null !== $this->collArticlesRelatedBySubcategoryId && !$overrideExisting) {
            return;
        }

        $collectionClassName = ArticleTableMap::getTableMap()->getCollectionClassName();

        $this->collArticlesRelatedBySubcategoryId = new $collectionClassName;
        $this->collArticlesRelatedBySubcategoryId->setModel('\SciMS\Models\Article');
    }

    /**
     * Gets an array of ChildArticle objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildArticle[] List of ChildArticle objects
     * @throws PropelException
     */
    public function getArticlesRelatedBySubcategoryId(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collArticlesRelatedBySubcategoryIdPartial && !$this->isNew();
        if (null === $this->collArticlesRelatedBySubcategoryId || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collArticlesRelatedBySubcategoryId) {
                // return empty collection
                $this->initArticlesRelatedBySubcategoryId();
            } else {
                $collArticlesRelatedBySubcategoryId = ChildArticleQuery::create(null, $criteria)
                    ->filterBysubcategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collArticlesRelatedBySubcategoryIdPartial && count($collArticlesRelatedBySubcategoryId)) {
                        $this->initArticlesRelatedBySubcategoryId(false);

                        foreach ($collArticlesRelatedBySubcategoryId as $obj) {
                            if (false == $this->collArticlesRelatedBySubcategoryId->contains($obj)) {
                                $this->collArticlesRelatedBySubcategoryId->append($obj);
                            }
                        }

                        $this->collArticlesRelatedBySubcategoryIdPartial = true;
                    }

                    return $collArticlesRelatedBySubcategoryId;
                }

                if ($partial && $this->collArticlesRelatedBySubcategoryId) {
                    foreach ($this->collArticlesRelatedBySubcategoryId as $obj) {
                        if ($obj->isNew()) {
                            $collArticlesRelatedBySubcategoryId[] = $obj;
                        }
                    }
                }

                $this->collArticlesRelatedBySubcategoryId = $collArticlesRelatedBySubcategoryId;
                $this->collArticlesRelatedBySubcategoryIdPartial = false;
            }
        }

        return $this->collArticlesRelatedBySubcategoryId;
    }

    /**
     * Sets a collection of ChildArticle objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $articlesRelatedBySubcategoryId A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCategory The current object (for fluent API support)
     */
    public function setArticlesRelatedBySubcategoryId(Collection $articlesRelatedBySubcategoryId, ConnectionInterface $con = null)
    {
        /** @var ChildArticle[] $articlesRelatedBySubcategoryIdToDelete */
        $articlesRelatedBySubcategoryIdToDelete = $this->getArticlesRelatedBySubcategoryId(new Criteria(), $con)->diff($articlesRelatedBySubcategoryId);


        $this->articlesRelatedBySubcategoryIdScheduledForDeletion = $articlesRelatedBySubcategoryIdToDelete;

        foreach ($articlesRelatedBySubcategoryIdToDelete as $articleRelatedBySubcategoryIdRemoved) {
            $articleRelatedBySubcategoryIdRemoved->setsubcategory(null);
        }

        $this->collArticlesRelatedBySubcategoryId = null;
        foreach ($articlesRelatedBySubcategoryId as $articleRelatedBySubcategoryId) {
            $this->addArticleRelatedBySubcategoryId($articleRelatedBySubcategoryId);
        }

        $this->collArticlesRelatedBySubcategoryId = $articlesRelatedBySubcategoryId;
        $this->collArticlesRelatedBySubcategoryIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Article objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Article objects.
     * @throws PropelException
     */
    public function countArticlesRelatedBySubcategoryId(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collArticlesRelatedBySubcategoryIdPartial && !$this->isNew();
        if (null === $this->collArticlesRelatedBySubcategoryId || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collArticlesRelatedBySubcategoryId) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getArticlesRelatedBySubcategoryId());
            }

            $query = ChildArticleQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterBysubcategory($this)
                ->count($con);
        }

        return count($this->collArticlesRelatedBySubcategoryId);
    }

    /**
     * Method called to associate a ChildArticle object to this object
     * through the ChildArticle foreign key attribute.
     *
     * @param  ChildArticle $l ChildArticle
     * @return $this|\SciMS\Models\Category The current object (for fluent API support)
     */
    public function addArticleRelatedBySubcategoryId(ChildArticle $l)
    {
        if ($this->collArticlesRelatedBySubcategoryId === null) {
            $this->initArticlesRelatedBySubcategoryId();
            $this->collArticlesRelatedBySubcategoryIdPartial = true;
        }

        if (!$this->collArticlesRelatedBySubcategoryId->contains($l)) {
            $this->doAddArticleRelatedBySubcategoryId($l);

            if ($this->articlesRelatedBySubcategoryIdScheduledForDeletion and $this->articlesRelatedBySubcategoryIdScheduledForDeletion->contains($l)) {
                $this->articlesRelatedBySubcategoryIdScheduledForDeletion->remove($this->articlesRelatedBySubcategoryIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildArticle $articleRelatedBySubcategoryId The ChildArticle object to add.
     */
    protected function doAddArticleRelatedBySubcategoryId(ChildArticle $articleRelatedBySubcategoryId)
    {
        $this->collArticlesRelatedBySubcategoryId[]= $articleRelatedBySubcategoryId;
        $articleRelatedBySubcategoryId->setsubcategory($this);
    }

    /**
     * @param  ChildArticle $articleRelatedBySubcategoryId The ChildArticle object to remove.
     * @return $this|ChildCategory The current object (for fluent API support)
     */
    public function removeArticleRelatedBySubcategoryId(ChildArticle $articleRelatedBySubcategoryId)
    {
        if ($this->getArticlesRelatedBySubcategoryId()->contains($articleRelatedBySubcategoryId)) {
            $pos = $this->collArticlesRelatedBySubcategoryId->search($articleRelatedBySubcategoryId);
            $this->collArticlesRelatedBySubcategoryId->remove($pos);
            if (null === $this->articlesRelatedBySubcategoryIdScheduledForDeletion) {
                $this->articlesRelatedBySubcategoryIdScheduledForDeletion = clone $this->collArticlesRelatedBySubcategoryId;
                $this->articlesRelatedBySubcategoryIdScheduledForDeletion->clear();
            }
            $this->articlesRelatedBySubcategoryIdScheduledForDeletion[]= $articleRelatedBySubcategoryId;
            $articleRelatedBySubcategoryId->setsubcategory(null);
        }

        return $this;
    }


    /**
     * If this collection has already been initialized with
     * an identical criteria, it returns the collection.
     * Otherwise if this Category is new, it will return
     * an empty collection; or if this Category has previously
     * been saved, it will retrieve related ArticlesRelatedBySubcategoryId from storage.
     *
     * This method is protected by default in order to keep the public
     * api reasonable.  You can provide public methods for those you
     * actually need in Category.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @param      string $joinBehavior optional join type to use (defaults to Criteria::LEFT_JOIN)
     * @return ObjectCollection|ChildArticle[] List of ChildArticle objects
     */
    public function getArticlesRelatedBySubcategoryIdJoinaccount(Criteria $criteria = null, ConnectionInterface $con = null, $joinBehavior = Criteria::LEFT_JOIN)
    {
        $query = ChildArticleQuery::create(null, $criteria);
        $query->joinWith('account', $joinBehavior);

        return $this->getArticlesRelatedBySubcategoryId($query, $con);
    }

    /**
     * Clears out the collCategoriesRelatedById collection
     *
     * This does not modify the database; however, it will remove any associated objects, causing
     * them to be refetched by subsequent calls to accessor method.
     *
     * @return void
     * @see        addCategoriesRelatedById()
     */
    public function clearCategoriesRelatedById()
    {
        $this->collCategoriesRelatedById = null; // important to set this to NULL since that means it is uninitialized
    }

    /**
     * Reset is the collCategoriesRelatedById collection loaded partially.
     */
    public function resetPartialCategoriesRelatedById($v = true)
    {
        $this->collCategoriesRelatedByIdPartial = $v;
    }

    /**
     * Initializes the collCategoriesRelatedById collection.
     *
     * By default this just sets the collCategoriesRelatedById collection to an empty array (like clearcollCategoriesRelatedById());
     * however, you may wish to override this method in your stub class to provide setting appropriate
     * to your application -- for example, setting the initial array to the values stored in database.
     *
     * @param      boolean $overrideExisting If set to true, the method call initializes
     *                                        the collection even if it is not empty
     *
     * @return void
     */
    public function initCategoriesRelatedById($overrideExisting = true)
    {
        if (null !== $this->collCategoriesRelatedById && !$overrideExisting) {
            return;
        }

        $collectionClassName = CategoryTableMap::getTableMap()->getCollectionClassName();

        $this->collCategoriesRelatedById = new $collectionClassName;
        $this->collCategoriesRelatedById->setModel('\SciMS\Models\Category');
    }

    /**
     * Gets an array of ChildCategory objects which contain a foreign key that references this object.
     *
     * If the $criteria is not null, it is used to always fetch the results from the database.
     * Otherwise the results are fetched from the database the first time, then cached.
     * Next time the same method is called without $criteria, the cached collection is returned.
     * If this ChildCategory is new, it will return
     * an empty collection or the current collection; the criteria is ignored on a new object.
     *
     * @param      Criteria $criteria optional Criteria object to narrow the query
     * @param      ConnectionInterface $con optional connection object
     * @return ObjectCollection|ChildCategory[] List of ChildCategory objects
     * @throws PropelException
     */
    public function getCategoriesRelatedById(Criteria $criteria = null, ConnectionInterface $con = null)
    {
        $partial = $this->collCategoriesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collCategoriesRelatedById || null !== $criteria  || $partial) {
            if ($this->isNew() && null === $this->collCategoriesRelatedById) {
                // return empty collection
                $this->initCategoriesRelatedById();
            } else {
                $collCategoriesRelatedById = ChildCategoryQuery::create(null, $criteria)
                    ->filterByparentCategory($this)
                    ->find($con);

                if (null !== $criteria) {
                    if (false !== $this->collCategoriesRelatedByIdPartial && count($collCategoriesRelatedById)) {
                        $this->initCategoriesRelatedById(false);

                        foreach ($collCategoriesRelatedById as $obj) {
                            if (false == $this->collCategoriesRelatedById->contains($obj)) {
                                $this->collCategoriesRelatedById->append($obj);
                            }
                        }

                        $this->collCategoriesRelatedByIdPartial = true;
                    }

                    return $collCategoriesRelatedById;
                }

                if ($partial && $this->collCategoriesRelatedById) {
                    foreach ($this->collCategoriesRelatedById as $obj) {
                        if ($obj->isNew()) {
                            $collCategoriesRelatedById[] = $obj;
                        }
                    }
                }

                $this->collCategoriesRelatedById = $collCategoriesRelatedById;
                $this->collCategoriesRelatedByIdPartial = false;
            }
        }

        return $this->collCategoriesRelatedById;
    }

    /**
     * Sets a collection of ChildCategory objects related by a one-to-many relationship
     * to the current object.
     * It will also schedule objects for deletion based on a diff between old objects (aka persisted)
     * and new objects from the given Propel collection.
     *
     * @param      Collection $categoriesRelatedById A Propel collection.
     * @param      ConnectionInterface $con Optional connection object
     * @return $this|ChildCategory The current object (for fluent API support)
     */
    public function setCategoriesRelatedById(Collection $categoriesRelatedById, ConnectionInterface $con = null)
    {
        /** @var ChildCategory[] $categoriesRelatedByIdToDelete */
        $categoriesRelatedByIdToDelete = $this->getCategoriesRelatedById(new Criteria(), $con)->diff($categoriesRelatedById);


        $this->categoriesRelatedByIdScheduledForDeletion = $categoriesRelatedByIdToDelete;

        foreach ($categoriesRelatedByIdToDelete as $categoryRelatedByIdRemoved) {
            $categoryRelatedByIdRemoved->setparentCategory(null);
        }

        $this->collCategoriesRelatedById = null;
        foreach ($categoriesRelatedById as $categoryRelatedById) {
            $this->addCategoryRelatedById($categoryRelatedById);
        }

        $this->collCategoriesRelatedById = $categoriesRelatedById;
        $this->collCategoriesRelatedByIdPartial = false;

        return $this;
    }

    /**
     * Returns the number of related Category objects.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct
     * @param      ConnectionInterface $con
     * @return int             Count of related Category objects.
     * @throws PropelException
     */
    public function countCategoriesRelatedById(Criteria $criteria = null, $distinct = false, ConnectionInterface $con = null)
    {
        $partial = $this->collCategoriesRelatedByIdPartial && !$this->isNew();
        if (null === $this->collCategoriesRelatedById || null !== $criteria || $partial) {
            if ($this->isNew() && null === $this->collCategoriesRelatedById) {
                return 0;
            }

            if ($partial && !$criteria) {
                return count($this->getCategoriesRelatedById());
            }

            $query = ChildCategoryQuery::create(null, $criteria);
            if ($distinct) {
                $query->distinct();
            }

            return $query
                ->filterByparentCategory($this)
                ->count($con);
        }

        return count($this->collCategoriesRelatedById);
    }

    /**
     * Method called to associate a ChildCategory object to this object
     * through the ChildCategory foreign key attribute.
     *
     * @param  ChildCategory $l ChildCategory
     * @return $this|\SciMS\Models\Category The current object (for fluent API support)
     */
    public function addCategoryRelatedById(ChildCategory $l)
    {
        if ($this->collCategoriesRelatedById === null) {
            $this->initCategoriesRelatedById();
            $this->collCategoriesRelatedByIdPartial = true;
        }

        if (!$this->collCategoriesRelatedById->contains($l)) {
            $this->doAddCategoryRelatedById($l);

            if ($this->categoriesRelatedByIdScheduledForDeletion and $this->categoriesRelatedByIdScheduledForDeletion->contains($l)) {
                $this->categoriesRelatedByIdScheduledForDeletion->remove($this->categoriesRelatedByIdScheduledForDeletion->search($l));
            }
        }

        return $this;
    }

    /**
     * @param ChildCategory $categoryRelatedById The ChildCategory object to add.
     */
    protected function doAddCategoryRelatedById(ChildCategory $categoryRelatedById)
    {
        $this->collCategoriesRelatedById[]= $categoryRelatedById;
        $categoryRelatedById->setparentCategory($this);
    }

    /**
     * @param  ChildCategory $categoryRelatedById The ChildCategory object to remove.
     * @return $this|ChildCategory The current object (for fluent API support)
     */
    public function removeCategoryRelatedById(ChildCategory $categoryRelatedById)
    {
        if ($this->getCategoriesRelatedById()->contains($categoryRelatedById)) {
            $pos = $this->collCategoriesRelatedById->search($categoryRelatedById);
            $this->collCategoriesRelatedById->remove($pos);
            if (null === $this->categoriesRelatedByIdScheduledForDeletion) {
                $this->categoriesRelatedByIdScheduledForDeletion = clone $this->collCategoriesRelatedById;
                $this->categoriesRelatedByIdScheduledForDeletion->clear();
            }
            $this->categoriesRelatedByIdScheduledForDeletion[]= $categoryRelatedById;
            $categoryRelatedById->setparentCategory(null);
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
        if (null !== $this->aparentCategory) {
            $this->aparentCategory->removeCategoryRelatedById($this);
        }
        $this->id = null;
        $this->name = null;
        $this->parent_category_id = null;
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
            if ($this->collArticlesRelatedByCategoryId) {
                foreach ($this->collArticlesRelatedByCategoryId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collArticlesRelatedBySubcategoryId) {
                foreach ($this->collArticlesRelatedBySubcategoryId as $o) {
                    $o->clearAllReferences($deep);
                }
            }
            if ($this->collCategoriesRelatedById) {
                foreach ($this->collCategoriesRelatedById as $o) {
                    $o->clearAllReferences($deep);
                }
            }
        } // if ($deep)

        $this->collArticlesRelatedByCategoryId = null;
        $this->collArticlesRelatedBySubcategoryId = null;
        $this->collCategoriesRelatedById = null;
        $this->aparentCategory = null;
    }

    /**
     * Return the string representation of this object
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->exportTo(CategoryTableMap::DEFAULT_STRING_FORMAT);
    }

    // validate behavior

    /**
     * Configure validators constraints. The Validator object uses this method
     * to perform object validation.
     *
     * @param ClassMetadata $metadata
     */
    static public function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new NotBlank(array ('message' => 'INVALID_NAME',)));
    }

    /**
     * Validates the object and all objects related to this table.
     *
     * @see        getValidationFailures()
     * @param      ValidatorInterface|null $validator A Validator class instance
     * @return     boolean Whether all objects pass validation.
     */
    public function validate(ValidatorInterface $validator = null)
    {
        if (null === $validator) {
            $validator = new RecursiveValidator(
                new ExecutionContextFactory(new IdentityTranslator()),
                new LazyLoadingMetadataFactory(new StaticMethodLoader()),
                new ConstraintValidatorFactory()
            );
        }

        $failureMap = new ConstraintViolationList();

        if (!$this->alreadyInValidation) {
            $this->alreadyInValidation = true;
            $retval = null;

            // We call the validate method on the following object(s) if they
            // were passed to this object by their corresponding set
            // method.  This object relates to these object(s) by a
            // foreign key reference.

            // If validate() method exists, the validate-behavior is configured for related object
            if (method_exists($this->aparentCategory, 'validate')) {
                if (!$this->aparentCategory->validate($validator)) {
                    $failureMap->addAll($this->aparentCategory->getValidationFailures());
                }
            }

            $retval = $validator->validate($this);
            if (count($retval) > 0) {
                $failureMap->addAll($retval);
            }

            if (null !== $this->collArticlesRelatedByCategoryId) {
                foreach ($this->collArticlesRelatedByCategoryId as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collArticlesRelatedBySubcategoryId) {
                foreach ($this->collArticlesRelatedBySubcategoryId as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }
            if (null !== $this->collCategoriesRelatedById) {
                foreach ($this->collCategoriesRelatedById as $referrerFK) {
                    if (method_exists($referrerFK, 'validate')) {
                        if (!$referrerFK->validate($validator)) {
                            $failureMap->addAll($referrerFK->getValidationFailures());
                        }
                    }
                }
            }

            $this->alreadyInValidation = false;
        }

        $this->validationFailures = $failureMap;

        return (Boolean) (!(count($this->validationFailures) > 0));

    }

    /**
     * Gets any ConstraintViolation objects that resulted from last call to validate().
     *
     *
     * @return     object ConstraintViolationList
     * @see        validate()
     */
    public function getValidationFailures()
    {
        return $this->validationFailures;
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
