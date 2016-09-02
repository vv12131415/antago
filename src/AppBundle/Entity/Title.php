<?php
/**
 * Created by PhpStorm.
 * User: ubuntu
 * Date: 4/28/16
 * Time: 1:33 PM
 */
namespace ApiBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity
 * @ORM\Table(name="title")
 *
 */
class Title
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     *
     * @ORM\Column(name="string", type="string", length=255)
     */
    protected $string;

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
     * @return mixed
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param mixed $string
     */
    public function setString($string)
    {
        $this->string = $string;
    }

}