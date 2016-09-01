<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Category
{
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param \AppBundle\Entity\Product $products
     */
    public function addProducts(\AppBundle\Entity\Product $products)
    {
        $this->products[] = $products;
    }
}
