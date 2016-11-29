<?php

namespace SciMS\Scripts;

require_once 'config/config.php';

use Composer\Script\Event;
use SciMS\Models\User;
use SciMS\Models\Article;

class ProductionFixture {
    public static function create(Event $event) {
        if ($event->isDevMode()) {
            $event->getIO()->write('Dev mode, ignore production fixtures...');
            return;
        }

        $adminUser = self::createAdminUser();
        $firstArticle = self::createFirstArticle($adminUser);
    }

    private static function createAdminUser() {
        $user = new User();
        $user->setUid(uniqid());
        $user->setEmail('admin@scims.fr');
        $user->setFirstName('Admin');
        $user->setLastName('Admin');
        $user->setPassword(password_hash('admin', PASSWORD_DEFAULT));
        $user->save();

        return $user;
    }

    private static function createFirstArticle($user) {
        $article = new Article();
        $article->setUser($user);
        $article->setTitle('My First Article!');
        $article->setContent('Welcome to SciMS');
        $article->setPublicationDate(time());
        $article->save();

        return $article;
    }
}