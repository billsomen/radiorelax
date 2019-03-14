<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/19/2015
 * Time: 7:22 AM
 */

namespace XS\AfrobankBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\CoreBundle\Document\ManagerSystem;
use XS\EducationBundle\Document\Course;

/**
 * Class Transaction
 * @package XS\AfrobankBundle\Document
 * @MongoDB\Document
 */

class Transaction extends ManagerSystem
{
//  Type de transactions
  const TYPE_WITHDRAW = "withdraw";
  const TYPE_PAYMENT = "payment";
  const TYPE_TRANSFER = "transfer";
  const TYPE_DEPOSIT = "deposit";

//  Les banques (mode de transfert ou de stocjkage de fonds)
  const BANK_PAYPAL = "PayPal";
  const BANK_AFROBANK = "Afrobank";

//  Etats de transaction
  
  const STATE_PENDING = "pending";
  const STATE_FINISHED = "finished";
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $sender; //Qui envoit ?
  //
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $sender_name;
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $receiver; //On envoit a qui ?
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $receiver_name; //On envoit a qui ?
  //
  /** @MongoDB\Field(type="boolean") */
//  This transaction is for an in*oice ?
  protected $has_invoice;
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\MarketPlaceBundle\Document\Invoice") */
//  The in*oice attached to this transaction :) !
  protected $invoice;

//    todo: dans le cas du paiement d'une application, envoit a Afrobanking (ici afrobanking)
  
  /** @MongoDB\ReferenceOne(targetDocument="BledDownloader") */
  protected $afrobanking; //Paiment d'application ou de loyer...
  
  /** @MongoDB\EmbedOne(targetDocument="Amount") */
  protected $amount; //Argent Echangé

//    /** @MongoDB\ReferenceOne(targetDocument="BledDownloader") */
  /** @MongoDB\Field(type="string") */
  protected $bank; //Canal d'envoit : Afrobanking (par defaut) Express Union, Paypal, etc...
  
  /** @MongoDB\EmbedOne(targetDocument="XS\CoreBundle\Document\Localisation") */
  protected $location_from; //De quelle localite
  
  /** @MongoDB\EmbedOne(targetDocument="XS\CoreBundle\Document\Localisation") */
  protected $location_to; //VERS quelle localite
  
  /** @MongoDB\Field(type="string")*/
  protected $reason; //Raison de cet envoi...
  
  /** @MongoDB\Field(type="string")*/
  protected $fee;
  
  /** @MongoDB\Field(type="string")*/
//    C'est un depot, un retrait, un transfert, etc...?
  protected $type;
  
  /** @MongoDB\Field(type="string")*/
  protected $state; //Etat de la transaction : finished(termine), error(erreur), pending(en cours)
  
  /** @MongoDB\Field(type="string")*/
//    todo: A gerer : AutoIncrement Hexa a plusieurs chiffres..
  protected $code; //Code de transaction...
  
  /** @MongoDB\Field(type="string")*/
//    todo: Inséré par le client et généré par le moyen 2 tpaiement
  protected $local_code; //Code de transaction...
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\EducationBundle\Document\Course") */
//    Cours payé/à payer par cette transaction :)
  protected $course;
  
  public function __construct($count){
    $this->setDateAdd(new \DateTime());
    $this->state = 'pending';
    $this->fee = 0;
    $amount = new Amount();
    $amount->setValue(0);
    $this->amount = $amount;
    $this->generateCode($count);
    $this->bank = 'Afrobank';
    $this->has_invoice = false;
  }
  
  /**
   * @return mixed
   */
  public function getSender() {
    return $this->sender;
  }
  
  /**
   * @param mixed $sender
   */
  public function setSender($sender) {
    $this->sender = $sender;
  }
  
  /**
   * @return mixed
   */
  public function getReceiver() {
    return $this->receiver;
  }
  
  /**
   * @param mixed $receiver
   */
  public function setReceiver($receiver) {
    $this->receiver = $receiver;
  }
  
  /**
   * @return mixed
   */
  public function getAfrobanking() {
    return $this->afrobanking;
  }
  
  /**
   * @param mixed $afrobanking
   */
  public function setAfrobanking($afrobanking) {
    $this->afrobanking = $afrobanking;
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
  }
  
  /**
   * @return mixed
   */
  public function getBank() {
    return $this->bank;
  }
  
  /**
   * @param mixed $bank
   */
  public function setBank($bank) {
    $this->bank = $bank;
  }
  
  /**
   * @return mixed
   */
  public function getLocationFrom() {
    return $this->location_from;
  }
  
  /**
   * @param mixed $location_from
   */
  public function setLocationFrom($location_from) {
    $this->location_from = $location_from;
  }
  
  /**
   * @return mixed
   */
  public function getLocationTo() {
    return $this->location_to;
  }
  
  /**
   * @param mixed $location_to
   */
  public function setLocationTo($location_to) {
    $this->location_to = $location_to;
  }
  
  /**
   * @return mixed
   */
  public function getReason() {
    return $this->reason;
  }
  
  /**
   * @param mixed $reason
   */
  public function setReason($reason) {
    $this->reason = $reason;
  }
  
  /**
   * @return mixed
   */
  public function getCode() {
    return $this->code;
  }
  
  /**
   * @param mixed $code
   */
  public function setCode($code) {
    $this->code = $code;
  }
  
  protected function generateCode($count){
    //todo: count est le nombre actuel de transactions...
    $count++;
    $transaction = dechex($count);
    $transaction = 'T'.str_pad($transaction, 8, "0", STR_PAD_LEFT);
    $this->setCode($transaction);
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
    $this->setDateUpdate(new \DateTime());
  }
  
  public function finish(){
    //Mettre fin a la transaction
    $this->setState(self::STATE_FINISHED);
    $this->setDateUpdate(new \DateTime());
  }
  
  public function reject(){
    //Mettre fin a la transaction
    $this->setState('rejected');
    $this->setDateUpdate(new \DateTime());
  }
  
  /**
   * @return mixed
   */
  public function getType()
  {
    return $this->type;
  }
  
  /**
   * @param mixed $type
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  
  /**
   * @return mixed
   */
  public function getFee()
  {
    return $this->fee;
  }
  
  /**
   * @param mixed $fee
   */
  public function setFee($fee)
  {
    $this->fee = $fee;
  }
  
  /**
   * @return mixed
   */
  public function getLocalCode()
  {
    return $this->local_code;
  }
  
  /**
   * @param mixed $local_code
   */
  public function setLocalCode($local_code)
  {
    $this->local_code = $local_code;
  }
  
  /**
   * @return mixed
   */
  public function getCourse()
  {
    return $this->course;
  }
  
  /**
   * @param mixed $course
   */
  public function setCourse($course)
  {
    $this->course = $course;
  }
  
  /**
   * @return mixed
   */
  public function getSenderName()
  {
    return $this->sender_name;
  }
  
  /**
   * @param mixed $sender_name
   */
  public function setSenderName($sender_name)
  {
    $this->sender_name = $sender_name;
  }
  
  /**
   * @return mixed
   */
  public function getReceiverName()
  {
    return $this->receiver_name;
  }
  
  /**
   * @param mixed $receiver_name
   */
  public function setReceiverName($receiver_name)
  {
    $this->receiver_name = $receiver_name;
  }
  
  /**
   * @return mixed
   */
  public function getHasInvoice()
  {
    return $this->has_invoice;
  }
  
  /**
   * @return mixed
   */
  public function hasInvoice()
  {
    return $this->has_invoice;
  }
  
  /**
   * @param mixed $has_invoice
   */
  public function setHasInvoice($has_invoice)
  {
    $this->has_invoice = $has_invoice;
  }
  
  /**
   * @return mixed
   */
  public function getInvoice()
  {
    return $this->invoice;
  }
  
  /**
   * @param mixed $invoice
   */
  public function setInvoice($invoice)
  {
    $this->invoice = $invoice;
  }
  
}