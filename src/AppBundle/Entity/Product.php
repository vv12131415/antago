<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Product
{
    protected $id;

    /**
     * @var string
     */
    protected $name;

    protected $categories;

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

    /**
     * @return ArrayCollection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param \AppBundle\Entity\Category $categories
     */
    public function addCategories(\AppBundle\Entity\Category $categories)
    {
        $this->categories[] = $categories;
    }
}
