<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/6/2015
 * Time: 7:36 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Auction
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

class Auction
{
    /** @MongoDB\EmbedMany(targetDocument="Proposal") */
    protected $proposals; //Propositions de ventes aux encheres...

    /** @MongoDB\Field(type="float") */
    protected $reserve_price;

    /** @MongoDB\Field(type="boolean") */
    protected $available;  //todo: C'est par ici qu'on met fin a une vente aux encheres. Ceci peut aussi se produire lorsque la deadline est atteinte.

    /** @MongoDB\Field(type="date") */
    protected $deadline;

    /**
     * @return mixed
     */
    public function getProposals() {
        return $this->proposals;
    }

    /**
     * @param mixed $proposals
     */
    public function setProposals($proposals) {
        $this->proposals = $proposals;
    }

    /**
     * @return mixed
     */
    public function getReservePrice() {
        return $this->reserve_price;
    }

    /**
     * @param mixed $reserve_price
     */
    public function setReservePrice($reserve_price) {
        $this->reserve_price = $reserve_price;
    }

    /**
     * @return mixed
     */
    public function getDeadline() {
        return $this->deadline;
    }

    /**
     * @param mixed $deadline
     */
    public function setDeadline($deadline) {
        $this->deadline = $deadline;
    }

    //methodes particulieres

    public function addProposal($data){
        $this->proposals[] = $data;
    }

    /**
     * @return mixed
     */
    public function isAvailable() {
        return $this->available;
    }

    public function end() {
        //Mettre fin a la vente aux encheres
        $this->available = false;
    }

    public function __construct()
    {
        $this->proposals = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Remove proposal
     *
     * @param XS\MarketPlaceBundle\Document\Proposal $proposal
     */
    public function removeProposal(\XS\MarketPlaceBundle\Document\Proposal $proposal)
    {
        $this->proposals->removeElement($proposal);
    }

    /**
     * Set available
     *
     * @param boolean $available
     * @return self
     */
    public function setAvailable($available)
    {
        $this->available = $available;
        return $this;
    }

    /**
     * Get available
     *
     * @return boolean $available
     */
    public function getAvailable()
    {
        return $this->available;
    }
}
