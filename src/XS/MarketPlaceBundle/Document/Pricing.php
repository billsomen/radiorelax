<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/6/2015
 * Time: 7:23 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Pricing
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

class Pricing
{
    /** @MongoDB\EmbedMany(targetDocument="PriceList") */
    protected $prices_list; //Liste des differents prix proposes par le vendeur, pour prix de gros, etc...(qte_min et prix_unitaire)

    /** @MongoDB\EmbedOne(targetDocument="Auction") */
    protected $auction; //la mise en vente aux encheres

    //todo: Because of its embedness, nothing else to add

    /**
     * Pricing constructor.
     * @param $auction
     */

    /**
     * @return mixed
     */
    public function getAuction() {
        return $this->auction;
    }

    /**
     * @param mixed $auction
     */
    public function setAuction($auction) {
        $this->auction = $auction;
    }

    /**
     * @return mixed
     */
    public function getPricesList() {
        return $this->prices_list;
    }

    /**
     * @param mixed $prices_list
     */
    public function setPricesList($prices_list) {
        $this->prices_list = $prices_list;
    }

    //Methodes particulieres

    public function addPrice($data){
        $this->prices_list[] = $data;
    }

    public function __construct() {
        $this->auction = new Auction();
    }





    /**
     * Add pricesList
     *
     * @param XS\MarketPlaceBundle\Document\PriceList $pricesList
     */
    public function addPricesList(\XS\MarketPlaceBundle\Document\PriceList $pricesList)
    {
        $this->prices_list[] = $pricesList;
    }

    /**
     * Remove pricesList
     *
     * @param XS\MarketPlaceBundle\Document\PriceList $pricesList
     */
    public function removePricesList(\XS\MarketPlaceBundle\Document\PriceList $pricesList)
    {
        $this->prices_list->removeElement($pricesList);
    }
}
