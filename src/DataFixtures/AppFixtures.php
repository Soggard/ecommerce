<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->encoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName($i);
            $product->setSku("sku" . $i);
            $product->setPrice(rand(10, 50));
            $product->setDescription("Description du produit ".$i);

            $manager->persist($product);
        }

        $user = new User();
        $user->setEmail("e.bernet@laposte.net");
        $user->setRoles([
            'ROLE_ADMIN'
        ]);
        $user->setPassword($this->encoder->encodePassword($user, 'password'));

        $manager->persist($user);

        $manager->flush();
    }
}
