<?php

namespace SciMS\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class AttachmentController {
    public function post(Request $request, Response $response) {
        $files = $request->getUploadedFiles();

        if (array_key_exists('file', $files)) {
            $file = $files['file'];
            return $response->getBody()->write($file->getClientFileName());
        } else {
            return $response->getBody()->write('no file given');
        }
    }
}