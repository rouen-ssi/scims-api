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
use SciMS\Models\User;

class FakeFixture
{
  public static function onMigrate(Event $event)
  {
    if (!$event->isDevMode()) {
      $event->getIO()->write('ignoring fake data...');
      return;
    }

    require_once "config/config.php";

    $faker = Factory::create();

    /** @var User[] $users */
    $users = [];
    for ($i = 0; $i < 10; $i++) {
      $user = new User();
      $user->setUid(uniqid());
      $user->setEmail($faker->email);
      $user->setFirstName($faker->firstName);
      $user->setLastName($faker->lastName);
      $user->setBiography($faker->text(140));
      $user->setPassword(password_hash('testtest', PASSWORD_DEFAULT));

      $user->save();
      $users[] = $user;
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
    for ($i = 0; $i < count($users) * 10; $i++) {
      $article = new Article();
      $article->setuser($faker->randomElement($users));
      $article->setTitle($faker->sentence($faker->numberBetween(5, 15), false));
      $article->setContent($faker->realText($faker->numberBetween(3000, 10000)));
      $articlePublicationDate = $faker->dateTimeThisYear;
      $article->setPublicationDate($articlePublicationDate->getTimestamp());
      $article->setcategory($faker->randomElement($categories));

      /** @var Comment[] $comments */
      $comments = [];
      for ($j = 0; $j < $faker->numberBetween(0, 30); $j++) {
          $comment = new Comment();
          $comment->setParentComment($faker->boolean(30) ? $faker->randomElement($comments) : null);
          $comment->setAuthor($faker->randomElement($users));
          $comment->setArticle($article);
          $comment->setPublicationDate($faker->dateTimeBetween($articlePublicationDate)->getTimestamp());
          $comment->setContent($faker->paragraph(15));

          $comments[] = $comment;
      }

      $article->save();
      $articles[] = $article;
    }

    foreach ($users as $user) {
      $userArticles = $user->getArticles();
      $highlighted = $faker->randomElements($userArticles->getData());
      foreach ($highlighted as $a) {
        $highlightedArticle = new HighlightedArticle();
        $highlightedArticle->setuser($user);
        $highlightedArticle->setarticle($a);

        $highlightedArticle->save();
      }
    }
  }
}
