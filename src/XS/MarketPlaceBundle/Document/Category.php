<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/6/2015
 * Time: 10:50 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\CoreBundle\Document\ManagerSystem;

/**
 * Class Category
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\Document
 */

class Category extends ManagerSystem
{
    /** @MongoDB\Field(type="string") */
    protected $slug; //Slug, genere par le name

    /** @MongoDB\Field(type="string") */
    protected $name; //Slug, genere par le name

    /** @MongoDB\ReferenceMany(targetDocument="Category") */
    protected $ancestors;

    /**
     * Category constructor.
     */
    public function __construct() {
        $this->setDateAdd(new \DateTime());
        $this->ancestors = new ArrayCollection();
    }


    //Methodes particulieres
    public function addAncestor($data){
        $this->ancestors[] = $data;
    }

    /**
     * @return mixed
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug) {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAncestors() {
        return $this->ancestors;
    }

    /**
     * @param mixed $ancestors
     */
    public function setAncestors($ancestors) {
        $this->ancestors = $ancestors;
    }


    /**
     * Remove ancestor
     *
     * @param XS\MarketPlaceBundle\Document\Category $ancestor
     */
    public function removeAncestor(\XS\MarketPlaceBundle\Document\Category $ancestor)
    {
        $this->ancestors->removeElement($ancestor);
    }
}
