<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadProductCompany implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $apple = [
            'iPhone 6',
            'Samsung Galaxy S6',
            'Xiaomi RedMi 3',
            'iPhone 6s',
            'Nokia Lumia',
            'HTC One M8',
            'LG Optimus G',
        ];

        $samsung = [
            'Samsung 4K 32 inch',
            'Sony 4K 32 inch',
            'LG 4K 32 inch',
            'Panasonic 4K 32 inch',
            'Xiaomi 4K 32 inch',
            'Sony 4K 40 inch',
            'Samsung 4K 4- inch',
        ];

        $companies = [
            'Apple',
            'Samsung',
        ];

        for ($i = 0; $i < count($apple); $i++) {
            $company = $manager->getRepository('AdminBundle:Company')->findOneByName($companies[0]);
            $product = $manager->getRepository('AdminBundle:Product')->findOneByName($apple[$i]);

            $company->addProducts($product);
        }

        for ($i = 0; $i < count($samsung); $i++) {
            $company = $manager->getRepository('AdminBundle:Company')->findOneByName($companies[1]);
            $product = $manager->getRepository('AdminBundle:Product')->findOneByName($samsung[$i]);

            $company->addProducts($product);
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
