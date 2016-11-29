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
            'is_draft' => $this->is_draft,
            'user' => [
                'uid' => $this->getaccount()->getUid(),
                'email' => $this->getaccount()->getEmail(),
                'last_name' => $this->getaccount()->getLastName(),
                'first_name' => $this->getaccount()->getFirstName(),
            ],
            'title' => $this->title,
            'content' => $this->content,
            'category_id' => $this->category_id,
            'subcategory_id' => $this->subcategory_id,
            'publication_date' => date_timestamp_set(new \DateTime(), $this->publication_date)->format(\DateTime::ISO8601),
            'comments_count' => $this->getComments()->count(),
        );

        return $json;
    }

}
