<?php

namespace AppBundle\DataFixtures\ORM;

use AdminBundle\Entity\Product;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProduct implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $products = [
            'MacBook',
            'MacBook Air',
            'MacBook Pro',
            'iMac',
            'Mac Pro',
            'Mac mini',
            'iPad Pro',
            'Galaxy Note7',
            'Galaxy S7',
            'Galaxy View 18.4',
            'Galaxy Tab S2',
            'TV KS-series',
            'TV JS-series',
            'TV JU-series',
        ];

        for ($i = 0; $i < count($products); ++$i) {
            $product = new Product();
            $product->setName($products[$i]);
            $manager->persist($product);
        }

        $manager->flush();
    }

    public function setContainer(ContainerInterface $container = null)
    {
        // TODO: Implement setContainer() method.
        $this->container = $container;
    }

    public function getOrder()
    {
        return 2;
    }
}
