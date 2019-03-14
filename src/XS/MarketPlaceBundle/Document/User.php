<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 1/12/2018
 * Time: 4:18 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class User
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument
 */

class User
{
  /** @MongoDB\ReferenceMany(targetDocument="Store") */
//    Stores
  protected $stores;

  /** @MongoDB\ReferenceOne(targetDocument="Store") */
//    Stores
  protected $store;
  
  /** @MongoDB\EmbedOne(targetDocument="Cart") */
//    My Cart
  protected $cart;
  
  /** @MongoDB\ReferenceMany(targetDocument="Invoice") */
//    All my in*oices :) !
  protected $invoices;
  
  /** @MongoDB\EmbedOne(targetDocument="Cart") */
  protected $wish_list;
  
  /** @MongoDB\EmbedOne(targetDocument="Cart") */
  protected $compare_list;
  
  /** @MongoDB\EmbedOne(targetDocument="Cart") */
  protected $orders;
  
  /**
   * User constructor.
   */
  public function __construct()
  {
    $this->stores = new ArrayCollection();
    $this->invoices = new ArrayCollection();
    $this->cart = new Cart();
    $this->wish_list = new Cart();
    $this->compare_list = new Cart();
    $this->orders = new Cart();
  }
  
  /**
   * @return mixed
   */
  public function getStores()
  {
    return $this->stores;
  }
  
  /**
   * @param mixed $stores
   */
  public function setStores($stores)
  {
    $this->stores = $stores;
  }
  
  /**
   * @return mixed
   */
  public function getCart()
  {
    return $this->cart;
  }
  
  /**
   * @param mixed $cart
   */
  public function setCart($cart)
  {
    $this->cart = $cart;
  }
  
  /**
   * @return mixed
   */
  public function getWishList()
  {
    return $this->wish_list;
  }
  
  /**
   * @param mixed $wish_list
   */
  public function setWishList($wish_list)
  {
    $this->wish_list = $wish_list;
  }
  
  /**
   * @return mixed
   */
  public function getOrders()
  {
    return $this->orders;
  }
  
  /**
   * @param mixed $orders
   */
  public function setOrders($orders)
  {
    $this->orders = $orders;
  }
  
  /**
   * @return mixed
   */
  public function getCompareList()
  {
    return $this->compare_list;
  }
  
  /**
   * @param mixed $compare_list
   */
  public function setCompareList($compare_list)
  {
    $this->compare_list = $compare_list;
  }
  
  /**
   * @return mixed
   */
  public function getInvoices()
  {
    return $this->invoices;
  }
  
  /**
   * @param mixed $invoices
   */
  public function setInvoices($invoices)
  {
    $this->invoices = $invoices;
  }

  /**
   * @return mixed
   */
  public function getStore()
  {
    return $this->store;
  }

  /**
   * @param mixed $store
   */
  public function setStore($store): void
  {
    $this->store = $store;
  }
}