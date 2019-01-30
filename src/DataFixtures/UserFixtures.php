<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


//@remember : https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
class UserFixtures extends Fixture
{

    private $encoder;
    public const USER_REFERENCE = 'user';


    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $admin = new User();
        $admin->setEmail("admin@admin.com");
        $password= $this->encoder->encodePassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setFirstname("Alfred");
        $admin->setLastname("Admin");
        $manager->persist($admin);


        $user = new User();
        $user->setEmail("user@user.com");
        $password= $this->encoder->encodePassword($user, 'user');
        $user->setPassword($password);
        $user->setFirstname("Bruce");
        $user->setLastname("User");
        $user->setRoles(['ROLE_USER']);


        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::USER_REFERENCE, $user);

    }
}
