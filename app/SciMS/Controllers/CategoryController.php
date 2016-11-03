<?php

namespace SciMS\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use SciMS\Models\Category;
use SciMS\Models\CategoryQuery;

class CategoryController {

  const INVALID_PARENT_CATEGORY = 'INVALID_PARENT_CATEGORY';

  /**
   * Endpoint to get all categories and their subcategories.
   * @param  ServerRequestInterface $request  a PSR-7 Request object.
   * @param  ResponseInterface      $response a PSR-7 Response object.
   * @return ResponseInterface a JSON containing all the categories and their subcategories.
   */
  public function getCategories(ServerRequestInterface $request, ResponseInterface $response) {
    $categories = CategoryQuery::create()->findAll();

    return $response->withJson($categories, 200);
  }

  /**
   * Endpoint to add a category.
   * @param  ServerRequestInterface $request  a PSR-7 Request object.
   * @param  ResponseInterface      $response a PSR-7 Response object.
   * @return ResponseInterface a JSON containing all the categories and their subcategories.
   */
  public function addCategory(ServerRequestInterface $request, ResponseInterface $response) {
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
   * Endpoint to add a subcategory.
   * @param  ServerRequestInterface $request  a PSR-7 Request object.
   * @param  ResponseInterface      $response a PSR-7 Response object.
   * @return ResponseInterface a JSON containing all the categories and their subcategories.
   */
  public function addSubcategory(ServerRequestInterface $request, ResponseInterface $response) {

  }

}
