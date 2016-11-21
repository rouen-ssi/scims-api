<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1479723073.
 * Generated on 2016-11-21 10:11:13 by antoine
 */
class PropelMigration_1479723073
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        $pdo = $manager->getAdapterConnection('scims');
        $faker = \Faker\Factory::create();

        /** @var \SciMS\Models\User[] $users */
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new \SciMS\Models\User();
            $user->setUid(uniqid());
            $user->setEmail($faker->email);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setBiography($faker->text(140));
            $user->setPassword(password_hash('testtest', PASSWORD_DEFAULT));

            $user->save($pdo);
            $users[] = $user;
        }

        /** @var \SciMS\Models\Category[] $categories */
        $categories = [];
        $categoryNames = ['Mathematics', 'Physics', 'Chemistry'];
        foreach ($categoryNames as $categoryName) {
            $category = new \SciMS\Models\Category();
            $category->setName($categoryName);

            $category->save($pdo);
            $categories[] = $category;
        }

        /** @var \SciMS\Models\Article[] $articles */
        $articles = [];
        for ($i = 0; $i < count($users) * 10; $i++) {
            $article = new \SciMS\Models\Article();
            $article->setuser($faker->randomElement($users));
            $article->setTitle($faker->sentence(10));
            $article->setContent($faker->paragraphs(5, true));
            $article->setPublicationDate($faker->dateTimeThisYear->getTimestamp());
            $article->setcategory($faker->randomElement($categories));

            $article->save($pdo);
            $articles[] = $article;
        }

        foreach ($users as $user) {
            $userArticles = $user->getArticles(null, $pdo);
            $highlighted = $faker->randomElements($userArticles);
            foreach ($highlighted as $a) {
                $highlightedArticle = new \SciMS\Models\HighlightedArticle();
                $highlightedArticle->setuser($user);
                $highlightedArticle->setarticle($a);

                $highlightedArticle->save($pdo);
            }
        }
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        $pdo = $manager->getAdapterConnection('scims');

        \SciMS\Models\HighlightedArticleQuery::create()->deleteAll($pdo);
        \SciMS\Models\ArticleQuery::create()->deleteAll($pdo);
        \SciMS\Models\CategoryQuery::create()->deleteAll($pdo);
        \SciMS\Models\UserQuery::create()->deleteAll($pdo);
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return [];
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return [];
    }

}