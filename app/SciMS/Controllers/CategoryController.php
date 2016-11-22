<?php

namespace SciMS\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use SciMS\Models\Category;
use SciMS\Models\CategoryQuery;
use SciMS\Models\Article;
use SciMS\Models\ArticleQuery;

class CategoryController {

  const INVALID_PARENT_CATEGORY = 'INVALID_PARENT_CATEGORY';
  const CATEGORY_NOT_FOUND = 'CATEGORY_NOT_FOUND';

  /**
   * Endpoint to get all categories and their subcategories.
   * @param  Request $request  a PSR-7 Request object.
   * @param  Response      $response a PSR-7 Response object.
   * @return Response a JSON containing all the categories and their subcategories.
   */
  public function getCategories(Request $request, Response $response) {
    // Retreives all the categories.
    $categories = CategoryQuery::create()->find();

    $json = [
      'categories' => []
    ];
    foreach ($categories as $category) {
      $json['categories'][] = $category;
    }

    return $response->withJson($json, 200);
  }

  /**
   * Endpoint to get informations about a category given by its id.
   * Returns an error if the category is not found.
   */
  public function getCategory(Request $request, Response $response, array $args) {
    // Retreives the category given by its id or returns an error if the category is not found.
    $category = CategoryQuery::create()->findPK($args['id']);
    if (!$category) {
      return $response->withJson([
        'errors' => [
          self::CATEGORY_NOT_FOUND
        ]
      ], 400);
    }

    // Returns the category informations
    return $response->withJson($category, 200);
  }

  /**
   * Endpoint to add a category.
   * @param  Request $request  a PSR-7 Request object.
   * @param  Response      $response a PSR-7 Response object.
   * @return Response a JSON containing all the categories and their subcategories.
   */
  public function addCategory(Request $request, Response $response) {
    $name = $request->getParsedBodyParam('name');
    $parentCategoryId = $request->getParsedBodyParam('parent_category_id', NULL);

    $category = new Category();
    $category->setName($name);

    // Retreives the subcategory given by its id
    if ($parentCategoryId) {
      $parentCategory = CategoryQuery::create()->findPK($parentCategoryId);
      if (!$parentCategory) {
        return $response->withJson([
          'errors' => [
            INVALID_PARENT_CATEGORY
          ]
        ], 400);
      }
      $category->setParentCategoryId($parentCategoryId);
    }

    if (!$category->validate()) {
      $errors = [];
      foreach ($category->getValidationFailures() as $failure) {
        $errors[] = $failure->getMessage();
      }

      return $response->withJson([
        'errors' => $errors
      ], 400);
    }

    $category->save();
  }

  /**
   * Endpoint to delete a category given by its id.
   * Returns an HTTP 200 status or a JSON containing errors.
   */
  public function delete(Request $request, Response $response, array $args) {
    // Gets the category id from the url.
    $categoryId = $args['id'];

    // Retreives the category by the given id or returns an error if the category is not found.
    $category = CategoryQuery::create()->findPK($categoryId);
    if (!$category) {
      return $response->withJson([
        'errors' => [
          self::CATEGORY_NOT_FOUND
        ]
      ], 400);
    }

    // Deletes the category (automatically deletes all its subcategories).
    $category->delete();

    // Returns an http 200 status
    return $response->withStatus(200);
  }

  /**
   * Edit a category given by its id.
   * Returns an http 200 status or a JSON containg errors.
   */
  public function edit(Request $request, Response $response, array $args) {
    // Retreives the category id from the url.
    $categoryId = $args['id'];

    // Retreives the parameters
    $categoryName = $request->getParsedBodyParam('name', '');
    $parentCategoryId = $request->getParsedBodyParam('parent_category_id', NULL);

    // Retreives the category by its id or returns an error if the category is not found.
    $category = CategoryQuery::create()->findPK($categoryId);
    if (!$category) {
      return $response->withJson([
        'errors' => [
          self::CATEGORY_NOT_FOUND
        ]
      ], 400);
    }

    // Updates the category informations
    $category->setName($categoryName);
    $category->setParentCategoryId($parentCategoryId);

    // Returns errors if the newly informations are not valid
    if (!$category->validate()) {
      $errors = [];
      foreach ($category->getValidationFailures() as $failure) {
        $errors[] = $failure->getMessage();
      }
      return $response->withJson([
        'errors' => $errors
      ], 400);
    }

    $category->save();

    // Save the category informations and returns http 200 status
    return $response->withStatus(200);
  }

}
