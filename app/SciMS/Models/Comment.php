<?php

namespace SciMS\Models;

use SciMS\Models\Base\Comment as BaseComment;

/**
 * Skeleton subclass for representing a row from the 'comment' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class Comment extends BaseComment implements \JsonSerializable {
    public function jsonSerialize() {
        return [
            'id' => $this->getId(),
            'parent_comment_id' => $this->getParentCommentId(),
            'user' => $this->getAuthor(),
            'publication_date' => date_timestamp_set(new \DateTime, $this->getPublicationDate())->format(\DateTime::ISO8601),
            'content' => $this->getContent(),
        ];
    }
}
