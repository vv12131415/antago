<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProductCategory implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $phones = [
            'iPhone 6',
            'Samsung Galaxy S6',
            'Xiaomi RedMi 3',
            'iPhone 6s',
            'Nokia Lumia',
            'HTC One M8',
            'LG Optimus G',
        ];

        $tvs = [
            'Samsung 4K 32 inch',
            'Sony 4K 32 inch',
            'LG 4K 32 inch',
            'Panasonic 4K 32 inch',
            'Xiaomi 4K 32 inch',
            'Sony 4K 40 inch',
            'Samsung 4K 4- inch',
        ];

        $categories = [
            'Phones',
            'TVs',
        ];

        for ($i = 0; $i < count($phones); $i++) {
            $category = $manager->getRepository('AppBundle:Category')->findOneByName($categories[0]);
            $product = $manager->getRepository('AppBundle:Product')->findOneByName($phones[$i]);

            $category->addProducts($product);
        }

        for ($i = 0; $i < count($tvs); $i++) {
            $category = $manager->getRepository('AppBundle:Category')->findOneByName($categories[1]);
            $product = $manager->getRepository('AppBundle:Product')->findOneByName($tvs[$i]);

            $category->addProducts($product);
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
        return 10;
    }
}
