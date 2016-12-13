<?php

namespace SciMS\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use SciMS\Models\Attachment;
use SciMS\Models\AttachmentQuery;

class AttachmentController {
    const NO_FILE_GIVEN = 'NO_FILE_GIVEN';
    const INVALID_FILE_SIZE = 'INVALID_FILE_SIZE';
    const ATTACHMENT_NOT_FOUND = 'ATTACHMENT_NOT_FOUND';

    public function post(Request $request, Response $response) {
        $files = $request->getUploadedFiles();

        // Return an error if no file given.
        if (!array_key_exists('file', $files)) {
            return $response->withJson(['errors' => [self::NO_FILE_GIVEN]], 400);
        }

        $file = $files['file'];

        // Check the file size.
        $fileSize = $file->getSize();
        if ($fileSize != null && $fileSize > MAX_ATTACHMENT_SIZE) {
            return $response->withJson(['errors' => [self::INVALID_FILE_SIZE]], 400);
        }

        // Create the attachment.
        $attachment = new Attachment();
        $attachment->setFile($file);
        $attachment->setDate(new \DateTime());
        $attachment->save();

        return $response->withJson(['id' => $attachment->getId()]);
    }

    public function get(Request $request, Response $response, array $args) {
        // Retreive the attachment by its id.
        $attachment = AttachmentQuery::create()->findPk($args['attachment_id']);

        if (!$attachment) {
            return $response->withJson(['errors' => [self::ATTACHMENT_NOT_FOUND]], 400);
        }

        return $response->getBody()->write(stream_get_contents($attachment->getFile()));
    }
}