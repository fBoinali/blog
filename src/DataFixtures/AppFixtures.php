<?php

namespace App\DataFixtures;

use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Articles;
use App\Entity\Categories;
use App\Entity\Commentaires;
use App\Entity\Team;
use App\Entity\User;


class AppFixtures extends Fixture
{
    private $faker;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr-FR');
    }

    public function load(ObjectManager $manager): void
    {
        $this->truncate($manager);
        $this->teamFixtures($manager);
        $this->userFixtures($manager);
        $this->categoriesFixtures($manager);
        $this->articlesFixtures($manager);
        $this->commentairesFixtures($manager);
    }

    protected function teamFixtures($manager)
    {
        {
            $team = new Team;
            $team->setEmail('thomas.thomas@thomas.fr');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $team,
                'thomas123'
            );
            $team->setPassword($hashedPassword);
            $team->setRoles(['ROLE_ADMIN']);
            $team->setFirstname('thomas');
            $team->setLastname('DUPONT');
            $manager->persist($team);
        }
        $manager->flush();
    }

    protected function userFixtures($manager)
    {

        for ($i = 1; $i <= 2; $i++) {
            $user[$i] = new User;
            $user[$i]->setEmail('thierry' . $i . '.chemin@thomas.fr');
            $hashedPassword = $this->passwordHasher->hashPassword(
                $user[$i], 'thierry123');
            $user[$i]->setPassword($hashedPassword);
            $user[$i]->setRoles(['ROLE_IDENTIFIED']);
            $user[$i]->setFirstname('thierry' . $i);
            $user[$i]->setLastname('CHEMIN');
            $manager->persist($user[$i]);
        }
        $manager->flush();
    }

    protected function categoriesFixtures($manager)
    {
        for ($i = 1; $i <= 3; $i++) {
            $categorie[$i] = new Categories;
            $categorie[$i]->setName('POSTE' . $i);
            $manager->persist($categorie[$i]);
        }
        $manager->flush();
    }

    protected function articlesFixtures($manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $artilcle[$i] = new Articles;
            $artilcle[$i]->setFkCategories($this->getRandonReference(CAtegories::class, $manager));
            $artilcle[$i]->setFkTeam($this->getReferencedObject(Team::class, 1, $manager));
            $artilcle[$i]->setTitre($this->faker->word());
            $artilcle[$i]->setArticle($this->faker->text(100));
            $artilcle[$i]->setDate($this->faker->dateTime());
            $artilcle[$i]->setLogo('https://loremflickr.com/640/480/pets?rand=' . '{' . rand(1, 999) . '}');
            $manager->persist($artilcle[$i]);
        }
        $manager->flush();
    }

    protected function commentairesFixtures($manager)
    {

        for ($i = 1; $i <= 5; $i++) {
            $commentaire[$i] = new Commentaires;
            $commentaire[$i]->setFkArticle($this->getRandonReference(Articles::class, $manager));
            $commentaire[$i]->setFkUser($this->getRandonReference(User::class, $manager));
            $commentaire[$i]->setCommentaire($this->faker->text(100));
            $commentaire[$i]->setDate($this->faker->dateTime());
            $manager->persist($commentaire[$i]);
        }
        $manager->flush();
    }

    protected function getReferencedObject(string $classname, int $id, object $manager)
    {
        return $manager->find($classname, $id);
    }

    protected function getRandonReference(string $classname, object $manager)
    {
        $list = $manager->getRepository($classname)->findAll();
        return $list[array_rand($list)];
    }

    protected function truncate($manager): void
    {
        $db = $manager->getConnection();

        $db->beginTransaction();

        $sql = '
            SET FOREIGN_KEY_CHECKS = 0;
            TRUNCATE team;
            TRUNCATE user;
            TRUNCATE categories;
            TRUNCATE articles;
            TRUNCATE commentaires;
            SET FOREIGN_KEY_CHECKS=1;
          ';
        $db->prepare($sql);
        $db->executeQuery($sql);

        $db->commit();
        $db->beginTransaction();
    }
}
