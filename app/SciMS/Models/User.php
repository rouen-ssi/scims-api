<?php

namespace SciMS\Models;

use SciMS\Models\Base\User as BaseUser;
use SciMS\Models\HighlightedArticle;
use SciMS\Models\HighlightedArticleQuery;

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
    // Retreives the user's highlighted articles.
    $highlightedArticleIds = [];
    foreach ($this->getHighlightedArticles() as $highlightedArticle) {
      $highlightedArticleIds[] = $highlightedArticle->getArticleId();
    }

    $json = array(
      'uid' => $this->uid,
      'email' => $this->email,
      'last_name' => $this->last_name,
      'first_name' => $this->first_name,
      'biography' => $this->biography,
      'highlighted_article' => $highlightedArticleIds
    );

    return $json;
  }

}
