<?php

namespace SciMS\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class AvatarController {

  const UID_NOT_FOUND = 'UID_NOT_FOUND';
  const AVATAR_NOT_FOUND = 'AVATAR_NOT_FOUND';
  const INVALID_FILE = 'INVALID_FILE';

  const VALID_MIME = [
    'image/png',
    'image/jpeg',
    'image/bmp'
  ];

  public function getByUid(Request $request, Response $response, array $args) {
    $filename = './avatars/' . $args['uid'];

    // Checks if the file exists
    if (!file_exists($filename)) {
      return $response->withJson([
        'errors' => [
          self::UID_NOT_FOUND
        ]
      ], 400);
    }

    // Opens the avatar file given by its uid.
    $file = fopen($filename, 'r');
    if (!$file) {
      return $response->withJson([
        'errors' => [
          self::AVATAR_NOT_FOUND
        ]
      ], 400);
    }

    // Reads the file
    $fileContent = fread($file, filesize($filename));

    // Sends the file
    $response = $response->withHeader('Content-Type', mime_content_type($filename));
    $response = $response->withHeader('Content-Disposition', 'attachment;filename="'. $filename .'"');
    $response = $response->withHeader('Content-Length', filesize($filename));
    $body = $response->getBody();
    $body->write($fileContent);
    return $response;
  }

  public function create(Request $request, Response $response) {
    // Retreives the user given by TokenMiddleware
    $user = $request->getAttribute('user');

    // Checks if a file has been given.
    if ($_FILES['avatar']['error'] != UPLOAD_ERR_OK) {
      return $response->withJson([
        'errors' => [
          self::INVALID_FILE
        ]
      ], 400);
    }

    // Checks the file MIME type.
    $finfo = new \finfo(FILEINFO_MIME_TYPE);
    if (!in_array($finfo->file($_FILES['avatar']['tmp_name']), self::VALID_MIME)) {
      return $response->withJson([
        'errors' => [
          self::INVALID_FILE
        ]
      ], 400);
    }

    // Create the avatar dir if not exists.
    if (!is_dir('./avatars')) {
      mkdir('./avatars');
    }

    // Moves the file to the avatars dir.);
    move_uploaded_file($_FILES['avatar']['tmp_name'], './avatars/' . $user->getUid());

    // Returns an http 200 status.
    return $response->withStatus(200);
  }

}
