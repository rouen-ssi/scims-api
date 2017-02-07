<?php

namespace SciMS\Scripts;

require_once "generated-conf/prod/config.php";

use SciMS\Models\Account;
use SciMS\Models\AccountQuery;
use SciMS\Models\Article;

class DataSeed {
    /**
     * Creates initial data like a welcome article and the admin account.
     */
    public static function create() {
        if (!DataSeed::createAdminAccount()) {
            error_log("Unable to create the admin account.");
            return -1;
        } else if (!DataSeed::createWelcomeArticle()) {
            error_log("Unable to create the welcome article.");
            return -1;
        }
    }

    /**
     * Creates the admin account.
     */
    public static function createAdminAccount() {
        $email = "admin@example.com";
        $firstName = "admin";
        $lastName = "admin";
        $password = password_hash("admin", PASSWORD_DEFAULT);

        $account = new Account();
        $account->setUid(uniqid());
        $account->setRole("admin");
        $account->setEmail($email);
        $account->setFirstName($firstName);
        $account->setLastName($lastName);
        $account->setPassword($password);
        
        return $account->save();
    }

    /**
     * Creates a welcome article.
     */ 
    public static function createWelcomeArticle() {
        $title = "Welcome to SciMS";
        $content = "<p>You have successfully installed SciMS.<br />";
        $content .= "You can sign-in to the admin account with these credentials:";
        $content .= "<ul>";
        $content .= "<li><b>Email:</b> admin@example.com";
        $content .= "<li><b>Password: </b> admin";
        $content .= "</ul></p>";
        $content .= "<p><b>ENjoy!</b></p>";
        
        $article = new Article();
        $article->setTitle($title);
        $article->setAccountId(AccountQuery::create()->findOneByEmail("admin@example.com")->getId());
        $article->setContent($content);
        $article->setPublicationDate(time());
        $article->setLastModificationDate(time());
        $article->setIsDraft(false);

        return $article->save();
    }
}