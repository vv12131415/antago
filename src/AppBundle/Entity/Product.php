<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 8/15/16
 * Time: 6:42 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * @ORM\Entity
 * @ORM\Table(name="product")
 */
class Product
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="name", type="text", length=255, nullable=false)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Company", mappedBy="products")
     * @ORM\JoinTable(name="product_company",
     * joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="company_id", referencedColumnName="id")}
     * )
     */
    protected $companies;

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
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param \AppBundle\Entity\Company $companies
     */
    public function addCompanies(\AppBundle\Entity\Company $companies)
    {
        $this->companies[] = $companies;
    }

}