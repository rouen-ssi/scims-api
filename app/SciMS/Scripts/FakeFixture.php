<?php
/**
 * @author antoine
 */

namespace SciMS\Scripts;


use Composer\Script\Event;
use Faker\Factory;
use SciMS\Models\Article;
use SciMS\Models\Category;
use SciMS\Models\Comment;
use SciMS\Models\HighlightedArticle;
use SciMS\Models\Account;

class FakeFixture
{
  public static function onMigrate(Event $event)
  {
    if (!$event->isDevMode()) {
      $event->getIO()->write('ignoring fake data...');
      return;
    }

    require_once "generated-conf/prod/config.php";

    $faker = Factory::create();

    /** @var Account[] $accounts */
    $accounts = [];
    for ($i = 0; $i < 10; $i++) {
      $account = new Account();
      $account->setUid(uniqid());
      $account->setEmail($faker->email);
      $account->setFirstName($faker->firstName);
      $account->setLastName($faker->lastName);
      $account->setBiography($faker->text(140));
      $account->setPassword(password_hash('testtest', PASSWORD_DEFAULT));

      $account->save();
      $accounts[] = $account;
    }

    /** @var Category[] $categories */
    $categories = [];
    $categoryNames = ['Mathematics', 'Physics', 'Chemistry'];
    foreach ($categoryNames as $categoryName) {
      $category = new Category();
      $category->setName($categoryName);

      $category->save();
      $categories[] = $category;
    }

    /** @var Article[] $articles */
    $articles = [];

    for ($i = 0; $i < count($accounts) * 10; $i++) {
      $article = new Article();
      $article->setAccount($faker->randomElement($accounts));
      $article->setIsDraft($faker->boolean(50));
      $article->setTitle($faker->sentence($faker->numberBetween(5, 15), false));
      $article->setContent($faker->realText($faker->numberBetween(3000, 10000)));
      $articlePublicationDate = $faker->dateTimeThisYear;
      $article->setPublicationDate($articlePublicationDate->getTimestamp());
      $article->setLastModificationDate($articlePublicationDate->getTimestamp());
      $article->setcategory($faker->randomElement($categories));
      $article->save();
      $articles[] = $article;

      /** @var Comment[] $comments */
      $comments = [];
      for ($j = 0; $j < $faker->numberBetween(0, 30); $j++) {
          $comment = new Comment();
          $comment->setParentComment($faker->boolean(30) ? $faker->randomElement($comments) : null);
          $comment->setAuthor($faker->randomElement($accounts));
          $comment->setArticle($article);
          $comment->setPublicationDate($faker->dateTimeBetween($articlePublicationDate)->getTimestamp());
          $comment->setContent($faker->paragraph(15));
          $comment->save();

          $comments[] = $comment;
      }
    }

    foreach ($accounts as $account) {
      $accountArticles = $account->getArticles();
      $highlighted = $faker->randomElements($accountArticles->getData());
      foreach ($highlighted as $a) {
        $highlightedArticle = new HighlightedArticle();
        $highlightedArticle->setaccount($account);
        $highlightedArticle->setarticle($a);

        $highlightedArticle->save();
      }
    }
  }
}
