<?php

namespace SciMS\Models;

use SciMS\Models\Base\Article as BaseArticle;

/**
 * Skeleton subclass for representing a row from the 'article' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Article extends BaseArticle implements \JsonSerializable {

  public function jsonSerialize() {
    $json = array(
      'id' => $this->id,
      'user_id' => $this->user_id,
      'title' => $this->title,
      'content' => $this->content
    );

    return $json;
  }

}
