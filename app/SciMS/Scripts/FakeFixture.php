<?php
/**
 * @author antoine
 */

namespace SciMS\Scripts;


use Composer\Script\Event;
use Faker\Factory;
use SciMS\Models\Article;
use SciMS\Models\Category;
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
      $article->setTitle($faker->sentence(10));
      $article->setContent($faker->paragraphs(5, true));
      $article->setPublicationDate($faker->dateTimeThisYear->getTimestamp());
      $article->setcategory($faker->randomElement($categories));

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