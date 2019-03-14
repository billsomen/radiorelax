<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 10/15/2015
 * Time: 7:06 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\CoreBundle\Document\ManagerSystem;

/**
 * Class StandardOther
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\Document
 */

class StandardOther extends ManagerSystem
{
    /** @MongoDB\Field(type="string") */
    protected $category; //todo: Categorie du produit...

    /** @MongoDB\Field(type="string") */
    protected $sub_category; //todo: Sous-Categorie du produit...

    /**
     * StandardOther constructor.
     */
    public function __construct() {
        $this->setDateAdd(new \DateTime());
    }

    /**
     * @return mixed
     */
    public function getCategory() {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getSubCategory() {
        return $this->sub_category;
    }

    /**
     * @param mixed $sub_category
     */
    public function setSubCategory($sub_category) {
        $this->sub_category = $sub_category;
    }



}