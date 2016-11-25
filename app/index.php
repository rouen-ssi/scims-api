<?php

require '../vendor/autoload.php';
include '../config/config.php';

$app = new \Slim\App();

// Add CorsMiddleware to app
$app->add('SciMS\Middlewares\CorsMiddleware');

/***********
 * Article *
 ***********/
$app->get('/articles', 'SciMS\Controllers\ArticleController:getPage');

$app->group('/article', function() {
    $this->post('', 'SciMS\Controllers\ArticleController:create')
        ->add('SciMS\Middlewares\TokenMiddleware');
    $this->group('/{id}', function() {
        $this->get('', 'SciMS\Controllers\ArticleController:getById');
        $this->put('', 'SciMS\Controllers\ArticleController:edit')
            ->add('SciMS\Middlewares\TokenMiddleware');
        $this->delete('', 'SciMS\Controllers\ArticleController:delete')
            ->add('SciMS\Middlewares\TokenMiddleware');

        /********************
         * Article comments *
         ********************/
        $this->get('/comments', 'SciMS\Controllers\CommentController:index');

        $this->group('/comment', function() {
            $this->post('', 'SciMS\Controllers\CommentController:post')
                ->add('SciMS\Middlewares\TokenMiddleware');
        });
    });
});

/***********
 * Comment *
 ***********/
$app->group('/comment/{comment_id}', function() {
    $this->put('', 'SciMS\Controllers\CommentController:edit');
    $this->delete('', 'SciMS\Controllers\CommentController:delete');
})->add('SciMS\Middlewares\TokenMiddleware');

/***********
 * Account *
 ***********/
$app->group('/account', function() {
    $this->put('', 'SciMS\Controllers\AccountController:updateInformations')
        ->add('SciMS\Middlewares\TokenMiddleware');
    $this->put('/email', 'SciMS\Controllers\AccountController:updateEmail')
        ->add('SciMS\Middlewares\TokenMiddleware');
    $this->get('/{uid}', 'SciMS\Controllers\AccountController:get')
        ->add('SciMS\Middlewares\TokenMiddleware');
    $this->post('/create', 'SciMS\Controllers\AccountController:create');
    $this->post('/login', 'SciMS\Controllers\AccountController:login');
    $this->put('/password', 'SciMS\Controllers\AccountController:changePassword')
        ->add('SciMS\Middlewares\TokenMiddleware');
});

/************
 * Category *
 ************/
$app->get('/categories', 'SciMS\Controllers\CategoryController:getCategories');

$app->group('/category', function() {
    $this->post('', 'SciMS\Controllers\CategoryController:addCategory')
        ->add('SciMS\Middlewares\TokenMiddleware');
    $this->group('/{id}', function() {
        $this->get('', 'SciMS\Controllers\CategoryController:getCategory');
        $this->delete('', 'SciMS\Controllers\CategoryController:delete')
            ->add('SciMS\Middlewares\TokenMiddleware');
        $this->put('', 'SciMS\Controllers\CategoryController:edit')
            ->add('SciMS\Middlewares\TokenMiddleware');
    });
});

/**********
 * Avatar *
 **********/
$app->group('/avatar', function() {
    $this->get('/{uid}', 'SciMS\Controllers\AvatarController:getByUid');
    $this->post('', 'SciMS\Controllers\AvatarController:create')
        ->add('SciMS\Middlewares\TokenMiddleware');
});

$app->run();
