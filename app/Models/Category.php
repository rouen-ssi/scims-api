<?php

namespace SciMS\Models;

use SciMS\Models\Base\Category as BaseCategory;
use SciMS\Models\Article;
use SciMS\Models\ArticleQuery;

/**
 * Skeleton subclass for representing a row from the 'category' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Category extends BaseCategory implements \JsonSerializable {

  public function jsonSerialize() {
    // Retreives the number of articles of the category.
    $articleCount = ArticleQuery::create()
      ->findByCategoryId($this->getId())
      ->count();

    return [
      'id' => $this->id,
      'name' => $this->name,
      'parent_categories' => $this->parent_category_id,
      'article_count' => $articleCount
    ];
  }

}
