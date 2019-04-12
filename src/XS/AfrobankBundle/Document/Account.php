<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/19/2015
 * Time: 7:22 AM
 */

namespace XS\AfrobankBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use XS\CoreBundle\Document\ManagerSystem;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\UserBundle\Document\User;

/**
 * Class Account
 * @package XS\AfrobankBundle\Document
 * @MongoDB\Document
 */
class Account
{
  /** @MongoDB\Id() */
  protected $id;

  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $user; //Proprietaire du compte

  /** @MongoDB\Field(type="date") */
  //Quand as-t'on ajoute cela ?
  protected $date_add;

  /** @MongoDB\Field(type="string") */
  protected $account_name;
  
  /** @MongoDB\ReferenceMany(targetDocument="Transaction") */
  // Proprietaire du compte
  protected $transactions;
  
  /** @MongoDB\EmbedOne(targetDocument="Amount") */
  protected $amount;
  
  /** @MongoDB\Field(type="string") */
  protected $state; //Etat : locked ->(1ere transaction entrante) unlocked -> frozen (plus tard, si loyer non paye :) )
  
  /** @MongoDB\Field(type="date") */
  protected $date_update; //Etat : locked ->(1ere transaction entrante) unlocked -> frozen (plus tard, si loyer non paye :) )
  
  
  public function __construct(User $user = null){
    $this->setDateAdd(new \DateTime());
    $this->state = 'locked';
    if($user){
      $this->user = $user;
      $this->account_name = $user->getNickname();
    }
    $this->amount = new Amount();
    $this->transactions = new ArrayCollection();
  }
  
  /**
   * @return mixed
   */
  public function getUser() {
    return $this->user;
  }
  
  /**
   * @param mixed $user
   */
  public function setUser($user) {
    $this->user = $user;
  }
  
  /**
   * @return mixed
   */
  public function getTransactions() {
    return $this->transactions;
  }
  
  /**
   * @param mixed $transactions
   */
  public function setTransactions($transactions) {
    $this->transactions = $transactions;
  }
  
  /**
   * @return mixed
   */
  public function getAmount() {
    return $this->amount;
  }
  
  /**
   * @param mixed $amount
   */
  public function setAmount($amount) {
    $this->amount = $amount;
    $this->date_update = new \DateTime();
  }
  
  /**
   * @return mixed
   */
  public function getState() {
    return $this->state;
  }
  
  /**
   * @param mixed $state
   */
  public function setState($state) {
    $this->state = $state;
    $this->date_update = new \DateTime();
  }
  
  public function addTransaction($transaction){
    $this->transactions->add($transaction);
  }
  
  /**
   * @return mixed
   */
  public function getDateUpdate() {
    return $this->date_update;
  }
  
  /**
   * @param mixed $date_update
   */
  public function setDateUpdate($date_update) {
    $this->date_update = $date_update;
  }
  
  public function debit($amount){
    $ok = true;
    //todo>> On suppose ici que les deux comptes ont la meme devise
    if($this->amount->getValue() > (1.01)*$amount->getValue()){
      //C ok, on peut debiter.
//            On debite directement la somme transferee plus nos 1% :)
      $tmp = $this->amount->getValue();
      $tmp -= (1.01)*$amount->getValue();
      $this->amount->setValue($tmp);
      $this->setDateUpdate(new \DateTime());
      //Et c'est bon...
    }
    else{
      $ok = false;
    }
    return $ok;
  }
  
  public function debitSoft($amount){
    $ok = true;
    //todo>> On suppose ici que les deux comptes ont la meme devise
    if($this->amount->getValue() >= $amount->getValue()){
      //C ok, on peut debiter.
//            On debite directement la somme transferee sans les 1%
      $tmp = $this->amount->getValue();
      $tmp -= $amount->getValue();
      $this->amount->setValue($tmp);
      $this->setDateUpdate(new \DateTime());
      //Et c'est bon...
    }
    else{
      $ok = false;
    }
    
    return $ok;
  }
  
  
  public function credit(Amount $amount, $fee=0){
    $tmpAmount = $this->getAmount();
    $tmp = $tmpAmount->getValue();
    $tmp += $amount->getValue();
    $tmp -= $fee;
    $tmpAmount->setValue($tmp);
    
    $this->activate();
    $this->setAmount($tmpAmount);
    $this->setDateUpdate(new \DateTime());
    //Et c'est bon...
    return ($tmp >= 0);
  }
  
  public function deposit($amount){
    //todo>> On suppose ici que les deux comptes ont la meme devise

//        On ne fait pas de depot, de moins de 500 F :) =P...
//        On ne coupe rien, pour l'instant... =P
    $ok = false;
    if($amount->getValue() >= 500) {
      $tmpAmount = $this->getAmount();
      $tmp = $tmpAmount->getValue();
      $tmp += $amount->getValue();
      $tmpAmount->setValue($tmp);

//            On active le compte :)
      $this->activate();
      $this->setAmount($tmpAmount);
      $this->setDateUpdate(new \DateTime());
      //Et c'est bon...
      $ok = true;
    }
    
    return $ok;
  }
  
  
  /**
   * @param $amount The amount to transfer
   * @param $bank The way used to deposit the money (Orange Money, MTN Mobile Money, Store Deposit. According to the way, we will cut an amount as fees to get the physical money
   * @return bool
   */
  public function depositFly($amount, $bank){
//
    //todo>> On suppose ici que les deux comptes ont la meme devise

//        On ne fait pas de depot, de moins de 500 F :) =P...
    $result = null;
    $value = $amount->getValue();
    $fee = 0;
    if(($value >= 500) AND ($value <= 500000)) {
      switch ($bank){
        case 'deposit_store' :
          $fee = 0;
          break;
        case 'orange_money' :
          if($value < 5000){
            $fee = 100;
          }
          else if($value < 10000){
            $fee = 150;
          }
          else if($value < 25000){
            $fee = 400;
          }
          else if($value < 30000){
            $fee = 650;
          }
          else if($value < 50000){
            $fee = 750;
          }
          else if($value < 75000){
            $fee = 1200;
          }
          else if($value < 100000){
            $fee = 1700;
          }
          else if($value < 200000){
            $fee = 2200;
          }
          else if($value < 300000){
            $fee = 2300;
          }
          else if($value < 400000){
            $fee = 2400;
          }
          else if($value < 500000){
            $fee = 2500;
          }
          break;
        case 'mtn_mobile_money' :
          if($value < 2500){
            $fee = 100;
          }
          else if($value < 10000){
            $fee = 150;
          }
          else if($value < 25000){
            $fee = 350;
          }
          else if($value < 50000){
            $fee = 700;
          }
          else if($value < 75000){
            $fee = 1200;
          }
          else if($value < 100000){
            $fee = 1800;
          }
          else if($value < 200000){
            $fee = 2100;
          }
          else if($value < 300000){
            $fee = 2600;
          }
          else if($value < 500000){
            $fee = 3000;
          }
          break;
      }

//            On a les frais. On peut donc effectuer les modifications sur les comptes :)
      $value -= $fee;
//            1. On credite le compte actuel
      //            On active le compte :)
      $tmpAmount = $this->getAmount();
      $tmp = $tmpAmount->getValue();
      $tmp += $value;
      $tmpAmount->setValue($tmp);
      
      $this->activate();
      $this->setAmount($tmpAmount);
      $this->setDateUpdate(new \DateTime());

//            2. On credite le compte de l'admin des frais comme compensation
      //Et c'est bon...
      $result['closed'] = true;
      $result['fee'] = $fee;
    }
    
    return $result;
  }
  
  public function creditAfrobanking($amount){
    //todo>> On suppose ici que les deux comptes ont la meme devise
//        Ici, on va definir les frais de transferts et les envoyer a Afrobanking, et aussi, on a deja coupe xa de chez l'expediteur... :)
    
    $tmpAmount = $this->getAmount();
    $tmp = $tmpAmount->getValue();
    $tmp += (0.01)*($amount->getValue());
    $tmpAmount->setValue($tmp);
    
    $this->setAmount($tmpAmount);
    $this->setDateUpdate(new \DateTime());
    //Et c'est bon...
  }
  
  public function activate(){
    //Activation du compte
    $this->setState('unlocked');
  }
  
  public function lock(){
    //Bloquage du compte : On ne sait pas encore pourquoi...
    $this->setState('locked');
  }
  
  /**
   * @return mixed
   */
  public function getAccountName()
  {
    return $this->account_name;
  }
  
  /**
   * @param mixed $account_name
   */
  public function setAccountName($account_name)
  {
    $this->account_name = $account_name;
  }

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
  public function setId($id): void
  {
    $this->id = $id;
  }

  /**
   * @return mixed
   */
  public function getDateAdd()
  {
    return $this->date_add;
  }

  /**
   * @param mixed $date_add
   */
  public function setDateAdd($date_add): void
  {
    $this->date_add = $date_add;
  }
}