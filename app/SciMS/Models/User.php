<?php

namespace SciMS\Models;

use SciMS\Models\Base\User as BaseUser;
use SciMS\Models\HighlightedArticle;
use SciMS\Models\HighlightedArticleQuery;
use SciMS\Models\Article;
use SciMS\Models\ArticleQuery;

/**
 * Skeleton subclass for representing a row from the 'user' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class User extends BaseUser implements \JsonSerializable {

  public function jsonSerialize() {

  }

}
