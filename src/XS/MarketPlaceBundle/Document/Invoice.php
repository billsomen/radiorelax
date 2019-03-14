<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 3/13/2018
 * Time: 9:01 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\AfrobankBundle\Document\Amount;
use XS\CoreBundle\Document\CalendarEntry;
use XS\EducationBundle\Document\Contact;

/**
 *
 * Class Invoice
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\Document()
 *
 */


class Invoice extends Cart
{
  const COMPANY_NAME = "Edutools Ltd";
  const COMPANY_ADDRESS_1 = "795 Park Ave, Suite 120";
  const COMPANY_ADDRESS_2 = "San Francisco, CA 94107";
  const COMPANY_PHONE = "(+237)695456185";
  const COMPANY_EMAIL = "service@edutools.me";
  
  
  /** @MongoDB\Id() */
  protected $id;
  
//  Modèle des différentes factures du système :) !
  /** @MongoDB\Field(type="integer") */
  protected $discount;
  
  /** @MongoDB\Field(type="integer") */
  protected $vat;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
  protected $sub_total;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
  protected $grand_total;
  
  /** @MongoDB\Field(type="integer") */
//  Durée totale des sessions en minutes
  protected $duration_total;
  
//  Modèle des différentes factures du système :) !
  /** @MongoDB\Field(type="string") */
  protected $client_name;
  
//  Modèle des différentes factures du système :) !
  /** @MongoDB\Field(type="string") */
  protected $client_full_name;
  
  /** @MongoDB\Field(type="string") */
  protected $client_id;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\EducationBundle\Document\Contact") */
  protected $client_contacts;
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
  protected $client;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\CoreBundle\Document\GMaps") */
//  Position exacte de li*raison du client
  protected $client_gmaps;
  
  /** @MongoDB\Field(type="string") */
//  Description générale :: A*OUT!
  protected $description;
  
//  PAYMENT_DETAILS
  /** @MongoDB\Field(type="string") */
//  Description générale :: A*OUT!
  protected $payment_vat_reg;
  
  /** @MongoDB\Field(type="string") */
//  Description générale :: A*OUT!
  protected $payment_account_name;
  
  /** @MongoDB\Field(type="string") */
//  Description générale :: A*OUT!
  protected $payment_swift_code;
  
  /** @MongoDB\Field(type="string") */
//  Description générale :: A*OUT!
  protected $payment_paypal_address;
  
  /** @MongoDB\Field(type="string") */
//  THe code of this in*oice
  protected $code;
  
//  Company details
  /** @MongoDB\Field(type="string") */
//  Entreprise
  protected $company;
  
  
  /** @MongoDB\Field(type="string") */
//  Entreprise
  protected $company_address_1;
  
  /** @MongoDB\Field(type="string") */
  protected $company_address_2;
  
  /** @MongoDB\Field(type="string") */
  protected $company_phone;
  
  /** @MongoDB\Field(type="string") */
  protected $company_email;
  
//  Company details
  /** @MongoDB\Field(type="string") */
//  Entreprise
  protected $company_id;
  
//  Company details
  /** @MongoDB\Field(type="string") */
//  Entreprise
  protected $company_owner_name;
  
//  Company details
  /** @MongoDB\Field(type="string") */
//  Entreprise
  protected $company_owner_id;
  
//  Company details
  /** @MongoDB\Field(type="string") */
//  Nom de l'entreprise
  protected $company_name;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\CoreBundle\Document\GMaps") */
//  Gmaps de l'entreprise
  protected $company_gmaps;
  
  /** @MongoDB\EmbedOne(targetDocument="XS\EducationBundle\Document\Contact") */
//  Gmaps de l'entreprise
  protected $company_contacts;
  
  /**
   * Invoice constructor.
   */
  public function __construct()
  {
    parent::__construct();
    $this->discount = 0;
    $this->vat = 19.25;
    $this->duration_total = 0;
    $this->grand_total = new Amount();
    $this->grand_total->setPaymentFrequency(Amount::PAY_ONCE);
    
    $this->sub_total = new Amount();
    $this->sub_total->setPaymentFrequency(Amount::PAY_ONCE);
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
  public function setId($id)
  {
    $this->id = $id;
  }
  
  /**
   * @return mixed
   */
  public function getClientName()
  {
    return $this->client_name;
  }
  
  /**
   * @param mixed $client_name
   */
  public function setClientName($client_name)
  {
    $this->client_name = $client_name;
  }
  
  /**
   * @return mixed
   */
  public function getClientId()
  {
    return $this->client_id;
  }
  
  /**
   * @param mixed $client_id
   */
  public function setClientId($client_id)
  {
    $this->client_id = $client_id;
  }
  
  /**
   * @return mixed
   */
  public function getClientContacts()
  {
    return $this->client_contacts;
  }
  
  /**
   * @param mixed $client_contacts
   */
  public function setClientContacts($client_contacts)
  {
    $this->client_contacts = $client_contacts;
  }
  
  /**
   * @return mixed
   */
  public function getClient()
  {
    return $this->client;
  }
  
  /**
   * @param mixed $client
   */
  public function setClient(\XS\UserBundle\Document\User $client)
  {
    $this->client = $client;
    $folder = $client->getEdu()->getStudent()->getFolder();
    if(isset($folder)){
      $this->setClientFullName($folder->getSurname().' '.$folder->getFirstName());
    }
    $this->setClientName($client->getNickname());
    $this->setClientId($client->getId());
    $this->setClientGmaps($client->getLocalisation()->getGmaps());
//    Les contacts de l'utilisateur
    $contacts = new Contact();
    $contacts->setEmail($client->getEmail());
    $this->setClientContacts($contacts);
    
    $this->setPaymentPaypalAddress($client->getEmail());
//    Compagnie
    $this->setCompanyName(self::COMPANY_NAME);
    $this->setCompanyAddress1(self::COMPANY_ADDRESS_1);
    $this->setCompanyAddress2(self::COMPANY_ADDRESS_2);
    $this->setCompanyEmail(self::COMPANY_EMAIL);
  }
  
  /**
   * @return mixed
   */
  public function getClientGmaps()
  {
    return $this->client_gmaps;
  }
  
  /**
   * @param mixed $client_gmaps
   */
  public function setClientGmaps($client_gmaps)
  {
    $this->client_gmaps = $client_gmaps;
  }
  
  /**
   * @return mixed
   */
  public function getDescription()
  {
    return $this->description;
  }
  
  /**
   * @param mixed $description
   */
  public function setDescription($description)
  {
    $this->description = $description;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyAddress1()
  {
    return $this->company_address_1;
  }
  
  /**
   * @param mixed $company_address_1
   */
  public function setCompanyAddress1($company_address_1)
  {
    $this->company_address_1 = $company_address_1;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyAddress2()
  {
    return $this->company_address_2;
  }
  
  /**
   * @param mixed $company_address_2
   */
  public function setCompanyAddress2($company_address_2)
  {
    $this->company_address_2 = $company_address_2;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyPhone()
  {
    return $this->company_phone;
  }
  
  /**
   * @param mixed $company_phone
   */
  public function setCompanyPhone($company_phone)
  {
    $this->company_phone = $company_phone;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyEmail()
  {
    return $this->company_email;
  }
  
  /**
   * @param mixed $company_email
   */
  public function setCompanyEmail($company_email)
  {
    $this->company_email = $company_email;
  }
  
  /**
   * @return mixed
   */
  public function getPaymentVatReg()
  {
    return $this->payment_vat_reg;
  }
  
  /**
   * @param mixed $payment_vat_reg
   */
  public function setPaymentVatReg($payment_vat_reg)
  {
    $this->payment_vat_reg = $payment_vat_reg;
  }
  
  /**
   * @return mixed
   */
  public function getPaymentAccountName()
  {
    return $this->payment_account_name;
  }
  
  /**
   * @param mixed $payment_account_name
   */
  public function setPaymentAccountName($payment_account_name)
  {
    $this->payment_account_name = $payment_account_name;
  }
  
  /**
   * @return mixed
   */
  public function getPaymentSwiftCode()
  {
    return $this->payment_swift_code;
  }
  
  /**
   * @param mixed $payment_swift_code
   */
  public function setPaymentSwiftCode($payment_swift_code)
  {
    $this->payment_swift_code = $payment_swift_code;
  }
  
  /**
   * @return mixed
   */
  public function getPaymentPaypalAddress()
  {
    return $this->payment_paypal_address;
  }
  
  /**
   * @param mixed $payment_paypal_address
   */
  public function setPaymentPaypalAddress($payment_paypal_address)
  {
    $this->payment_paypal_address = $payment_paypal_address;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyName()
  {
    return $this->company_name;
  }
  
  /**
   * @param mixed $company_name
   */
  public function setCompanyName($company_name)
  {
    $this->company_name = $company_name;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyGmaps()
  {
    return $this->company_gmaps;
  }
  
  /**
   * @param mixed $company_gmaps
   */
  public function setCompanyGmaps($company_gmaps)
  {
    $this->company_gmaps = $company_gmaps;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyContacts()
  {
    return $this->company_contacts;
  }
  
  /**
   * @param mixed $company_contacts
   */
  public function setCompanyContacts($company_contacts)
  {
    $this->company_contacts = $company_contacts;
  }
  
  /**
   * @return mixed
   */
  public function getClientFullName()
  {
    return $this->client_full_name;
  }
  
  /**
   * @param mixed $client_full_name
   */
  public function setClientFullName($client_full_name)
  {
    $this->client_full_name = $client_full_name;
  }
  
  /**
   * @return mixed
   */
  public function getCompany()
  {
    return $this->company;
  }
  
  /**
   * @param mixed $company
   */
  public function setCompany($company)
  {
    $this->company = $company;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyId()
  {
    return $this->company_id;
  }
  
  /**
   * @param mixed $company_id
   */
  public function setCompanyId($company_id)
  {
    $this->company_id = $company_id;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyOwnerName()
  {
    return $this->company_owner_name;
  }
  
  /**
   * @param mixed $company_owner_name
   */
  public function setCompanyOwnerName($company_owner_name)
  {
    $this->company_owner_name = $company_owner_name;
  }
  
  /**
   * @return mixed
   */
  public function getCompanyOwnerId()
  {
    return $this->company_owner_id;
  }
  
  /**
   * @param mixed $company_owner_id
   */
  public function setCompanyOwnerId($company_owner_id)
  {
    $this->company_owner_id = $company_owner_id;
  }
  
  public function generate(\XS\UserBundle\Document\User $user){
//    Generate the in*oice from the pending items in the cart and return the in*oice
//    This is specialized to the sessions for now
    $this->setDescription("Sessions de cours de soutiens ou de perfectionnement");
    $this->setDateCreate(new \DateTime());
    
//    Client and Company infos
    $this->setClient($user);
    $cart = $user->getMarket()->getCart();
    
    $sessions = $cart->getSessionsCarts("pending");
    $this->setSessionsCarts($sessions);
//    Duration in minutes
    $this->setDurationTotal($cart->getSessionsDuration($sessions));
    $this->setAmount($cart->getSessionsPrice($sessions));
    
    $this->setSubTotal($this->amount);
    $this->setGrandTotal($this->amount->multNumberAmount(1+$this->getVat()/100));
    $this->sub_total->setPaymentFrequency(Amount::PAY_ONCE);
    $this->grand_total->setPaymentFrequency(Amount::PAY_ONCE);
  }
  
  /**
   * @return mixed
   */
  public function getDiscount()
  {
    return $this->discount;
  }
  
  /**
   * @param mixed $discount
   */
  public function setDiscount($discount)
  {
    $this->discount = $discount;
  }
  
  /**
   * @return mixed
   */
  public function getVat()
  {
    return $this->vat;
  }
  
  /**
   * @param mixed $vat
   */
  public function setVat($vat)
  {
    $this->vat = $vat;
  }
  
  /**
   * @return mixed
   */
  public function getSubTotal()
  {
    return $this->sub_total;
  }
  
  /**
   * @param mixed $sub_total
   */
  public function setSubTotal($sub_total)
  {
    $this->sub_total = $sub_total;
  }
  
  /**
   * @return mixed
   */
  public function getGrandTotal()
  {
    return $this->grand_total;
  }
  
  /**
   * @param mixed $grand_total
   */
  public function setGrandTotal($grand_total)
  {
    $this->grand_total = $grand_total;
  }
  
  /**
   * @return mixed
   */
  public function getDurationTotal()
  {
    return $this->duration_total;
  }
  
  /**
   * @param mixed $duration_total
   */
  public function setDurationTotal($duration_total)
  {
    $this->duration_total = $duration_total;
  }
  
  /**
   * @return mixed
   */
  public function getCode()
  {
    return $this->code;
  }
  
  /**
   * @param mixed $code
   */
  public function setCode($code)
  {
    $this->code = $code;
  }
}