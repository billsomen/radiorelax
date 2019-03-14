<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/21/2017
 * Time: 7:28 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class StoreProSuCat
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

//TODO : Implemente* automatically
class StoreProSuCat
{
    /** @MongoDB\Field(type="string") */
    protected $cat;

    /** @MongoDB\Field(type="collection")*/
    protected $su_cats = array();

    /**
     * StoreProSuCat constructor.
     */
    public function __construct() {
        $this->su_cats = array();
    }

    /**
     * @return mixed
     */
    public function getCat()
    {
        return $this->cat;
    }

    /**
     * @param mixed $cat
     */
    public function setCat($cat)
    {
        $this->cat = $cat;
    }

    /**
     * @return mixed
     */
    public function getSuCats()
    {
        return $this->su_cats;
    }

    /**
     * @param mixed $su_cats
     */
    public function setSuCats($su_cats)
    {
        $this->su_cats = $su_cats;
    }



}
