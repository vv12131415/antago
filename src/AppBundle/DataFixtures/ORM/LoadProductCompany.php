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
            'MacBook',
            'MacBook Air',
            'MacBook Pro',
            'iMac',
            'Mac Pro',
            'Mac mini',
            'iPad Pro',
        ];

        $samsung = [
            'Galaxy Note7',
            'Galaxy S7',
            'Galaxy View 18.4',
            'Galaxy Tab S2',
            'TV KS-series',
            'TV JS-series',
            'TV JU-series',
        ];

        $companies = [
            'Apple',
            'Samsung',
        ];

        for ($i = 0; $i < count($apple); ++$i) {
            $company = $manager->getRepository('AdminBundle:Company')->findOneByName($companies[0]);
            $product = $manager->getRepository('AdminBundle:Product')->findOneByName($apple[$i]);

            $company->addProducts($product);
        }

        for ($i = 0; $i < count($samsung); ++$i) {
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
