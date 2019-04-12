<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 3/14/2019
 * Time: 12:10 PM
 */

namespace XS\AfrobankBundle\Interfaces;


interface CartInterface
{
//  The current amount of the cart
  public function getAmount();

//  The total quantity of items in the cart
  public function getQuantity();

//  Vide le panier
  public function drop();
}