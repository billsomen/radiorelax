<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 3/14/2019
 * Time: 11:38 AM
 */

namespace XS\AfrobankBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class Transaction
 * @package XS\AfrobankBundle\Document
 * @MongoDB\Document
 */


class EntityAccount
{
//  Details on an item : buyers, dates,
  /** @MongoDB\ReferenceMany(targetDocument="Transaction") */
  // Transactions sur l'entité
  protected $transactions;

  /**
   * EntityAccount constructor.
   */
  public function __construct()
  {
    $this->transactions = new ArrayCollection();
  }

  /**
   * @return mixed
   */
  public function getTransactions()
  {
    return $this->transactions;
  }

  /**
   * @param mixed $transactions
   */
  public function setTransactions($transactions): void
  {
    $this->transactions = $transactions;
  }

  /**
   * @return mixed
   */
  public function getAmount()
  {
// Get the value generated
//    Tout est conservé en CAD
//  Le solde actuel du compte : total des transactions : il est calculé à l'affichage
    $amount = new Amount();
    foreach ($this->transactions as $transaction){
      $amount->addAmount($transaction->getAmount(), true);
    }

    return $amount;
  }

}