<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 8/20/2015
 * Time: 6:05 PM
 */

namespace XS\UserBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use MainBundle\Document\Node;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use XS\MarketPlaceBundle\Document\Cart;

/**
 * Class User
 * @package XS\UserBundle\Document
 * @MongoDB\Document(repositoryClass="XS\UserBundle\Document\UserRepository")
 * @MongoDBUnique("username")
 */

class User implements AdvancedUserInterface
{
  /** @MongoDB\Id() */
  protected $id;

  /** @MongoDB\Field(type="date") */
  //Quand as-t'on ajoute cela ?
  protected $date_add;

    /** @MongoDB\EmbedOne(targetDocument="Profiles") */
//    Tous les profiles de l'artiste
  protected $profiles;

  /** @MongoDB\Field(type="string") */
  protected $gender; //m (pour homme) et f(pour femme).

  /** @MongoDB\Field(type="string") */
  protected $namespace;

  /** @MongoDB\Field(type="string") */
  protected $name; //Nom tel que sur la CNI
  
  /** @MongoDB\Field(type="string") */
//    Prenom tel que sur la CNI
  protected $surname;
  
  /** @MongoDB\Field(type="string") */
  protected $first_name;

  /** @MongoDB\Field(type="string") */
//  nom d'utilisateur du parain
  protected $godfather_namespace;

  /** @MongoDB\Field(type="date") */
  protected $date_connection; //Date de connexion...? Permet de definir le statut de l'utilisateur

  //Les deux sont utilises pour effectuer les transactions BledDownloader.
  /** @MongoDB\Field(type="string")
   * @Assert\Length(
   *      min = "4",
   *      max = "10",
   *      minMessage = "Votre nom d'utilisateur doit avoir au moins 4 caractères",
   *      maxMessage = "Votre nom d'utilisateur ne peut avoir plus de 20 caractères",
   * )
   */
  protected $nickname;
  //Pseudo, utilisé dans l'application... Ex: Somen Diego
  
  /** @MongoDB\Field(type="string")
   * @Assert\NotBlank()
   * @Assert\NotNull()
   */
  protected $username;
  
  /**
   * @MongoDB\Field(type="string")
   */
  protected $email; //Ceci est l'adresse E-mail Afrochat, elle sera plus tard utilisée comme unique moyen de connexion de l'utilisateur...
  
  /** @MongoDB\Field(type="date") */
  protected $birth_date;

  /** @MongoDB\EmbedMany(targetDocument="Telephone") */
  protected $telephones;

  /** @MongoDB\EmbedOne(targetDocument="Locale") */
//  langue, timezone, currency, gmaps
  protected $locale;

  /**
   * @MongoDB\Field(type="string")
   */
//    todo..     * @Assert\NotBlank()
  protected $password;
  
  protected $oldPassword;
  
  /** @MongoDB\Field(type="string") */
  protected $salt;

  /** @MongoDB\Field(type="boolean") */
  protected $terms = false;
  
  /** @MongoDB\Field(type="boolean") */
  protected $confirmed;
  
  /** @MongoDB\Field(type="date") */
  protected $date_confirmation;
  
  /** @MongoDB\Field(type="string") */
  protected $confirmation_code;
  
  /** @MongoDB\Field(type="string") */
//  Le code temporaire enoyé à l'utilisateur
  protected $tmp_pass;
  
  /** @MongoDB\Field(type="date") */
//  Le fin de *alidité du code temporaire (mot de passe)
  protected $tmp_deadline;

  /** @MongoDB\Field(type="collection") */
  protected $roles;
  
    /** @MongoDB\EmbedMany(targetDocument="AddressBook") */
  protected $address_book; //Le carnet d'adresse de l'utilisateur.
  
  /** @MongoDB\ReferenceOne(targetDocument="XS\AfrobankBundle\Document\Account") */
  protected $account; //Le compte bancaire de l'utilisateur...

  /** @MongoDB\EmbedOne(targetDocument="XS\MarketPlaceBundle\Document\Cart") */
//  Mon Panier
  protected $cart;

  /** @MongoDB\Field(type="boolean") */
//  Requete de profil artiste ?
  protected $artist_request;

  /** @MongoDB\Field(type="date") */
//  Date de demande d'accès au profil artiste
  protected $date_artist_request;

  /** @MongoDB\ReferenceOne(targetDocument="MainBundle\Document\Node") */
//  Mon noeud MLM
  protected $node;

  /**
   * User constructor.
   */
  public function __construct()
  {
//        On remplit les champs par defaut...
    $this->confirmed = true;
//    $this->node = new Node(0);
    $this->roles = array();
//        On ajoute le genre
    $this->gender = 'Male';
    //        On ajoute la localisation
    $this->addTelephone(new Telephone());
    $this->birth_date = new \DateTime('1990-01-01');
    $this->setDateAdd(new \DateTime());
    $this->addRole('ROLE_USER');
    $this->profiles = new Profiles();
    $this->cart = new Cart();

  }
  
  public function __toString() {
    $string = ''.$this->nickname;
    return $string;
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

  /**
   * @return mixed
   */
  public function getGender()
  {
    return $this->gender;
  }

  /**
   * @param mixed $gender
   */
  public function setGender($gender): void
  {
    $this->gender = $gender;
  }

  /**
   * @return mixed
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * @param mixed $name
   */
  public function setName($name): void
  {
    $this->name = $name;
  }

  /**
   * @return mixed
   */
  public function getSurname()
  {
    return $this->surname;
  }

  /**
   * @param mixed $surname
   */
  public function setSurname($surname): void
  {
    $this->surname = $surname;
  }

  /**
   * @return mixed
   */
  public function getDateConnection()
  {
    return $this->date_connection;
  }

  /**
   * @param mixed $date_connection
   */
  public function setDateConnection($date_connection): void
  {
    $this->date_connection = $date_connection;
  }

  /**
   * @return mixed
   */
  public function getNickname()
  {
    return $this->nickname;
  }

  /**
   * @param mixed $nickname
   */
  public function setNickname($nickname): void
  {
    $this->nickname = $nickname;
  }

  /**
   * @return mixed
   */
  public function getUsername()
  {
    return $this->username;
  }

  /**
   * @param mixed $username
   */
  public function setUsername($username): void
  {
    $this->username = $username;
  }

  /**
   * @return mixed
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * @param mixed $email
   */
  public function setEmail($email): void
  {
    $this->email = $email;
  }

  /**
   * @return mixed
   */
  public function getTelephones()
  {
    return $this->telephones;
  }

  /**
   * @param mixed $telephones
   */
  public function setTelephones($telephones): void
  {
    $this->telephones = $telephones;
  }

  /**
   * @return mixed
   */
  public function getLocale()
  {
    return $this->locale;
  }

  /**
   * @param mixed $locale
   */
  public function setLocale($locale): void
  {
    $this->locale = $locale;
  }

  /**
   * @return mixed
   */
  public function getPassword()
  {
    return $this->password;
  }

  /**
   * @param mixed $password
   */
  public function setPassword($password): void
  {
    $this->password = $password;
  }

  /**
   * @return mixed
   */
  public function getOldPassword()
  {
    return $this->oldPassword;
  }

  /**
   * @param mixed $oldPassword
   */
  public function setOldPassword($oldPassword): void
  {
    $this->oldPassword = $oldPassword;
  }

  /**
   * @return mixed
   */
  public function getSalt()
  {
    return $this->salt;
  }

  /**
   * @param mixed $salt
   */
  public function setSalt($salt): void
  {
    $this->salt = $salt;
  }

  /**
   * @return mixed
   */
  public function getTerms()
  {
    return $this->terms;
  }

  /**
   * @param mixed $terms
   */
  public function setTerms($terms): void
  {
    $this->terms = $terms;
  }

  /**
   * @return mixed
   */
  public function getConfirmed()
  {
    return $this->confirmed;
  }

  /**
   * @param mixed $confirmed
   */
  public function setConfirmed($confirmed): void
  {
    $this->confirmed = $confirmed;
  }

  /**
   * @return mixed
   */
  public function getDateConfirmation()
  {
    return $this->date_confirmation;
  }

  /**
   * @param mixed $date_confirmation
   */
  public function setDateConfirmation($date_confirmation): void
  {
    $this->date_confirmation = $date_confirmation;
  }

  /**
   * @return mixed
   */
  public function getConfirmationCode()
  {
    return $this->confirmation_code;
  }

  /**
   * @param mixed $confirmation_code
   */
  public function setConfirmationCode($confirmation_code): void
  {
    $this->confirmation_code = $confirmation_code;
  }

  /**
   * @return mixed
   */
  public function getTmpPass()
  {
    return $this->tmp_pass;
  }

  /**
   * @param mixed $tmp_pass
   */
  public function setTmpPass($tmp_pass): void
  {
    $this->tmp_pass = $tmp_pass;
  }

  /**
   * @return mixed
   */
  public function getTmpDeadline()
  {
    return $this->tmp_deadline;
  }

  /**
   * @param mixed $tmp_deadline
   */
  public function setTmpDeadline($tmp_deadline): void
  {
    $this->tmp_deadline = $tmp_deadline;
  }

  /**
   * @return mixed
   */
  public function getRoles()
  {
    return $this->roles;
  }

  /**
   * @param mixed $roles
   */
  public function setRoles($roles): void
  {
    $this->roles = $roles;
  }

  public function addRole($data){
    if(!in_array($data, $this->roles)){
      $this->roles[] = $data;
    }
  }

  public function makeAdmin(){
    $this->addRole('ROLE_ADMIN');
  }

  public function removeAdmin(){
    $this->roles = array();
    $this->addRole('ROLE_USER');
  }

  /**
   * @return mixed
   */
  public function getAddressBook()
  {
    return $this->address_book;
  }

  /**
   * @param mixed $address_book
   */
  public function setAddressBook($address_book): void
  {
    $this->address_book = $address_book;
  }

  /**
   * @return mixed
   */
  public function getAccount()
  {
    return $this->account;
  }

  /**
   * @param mixed $account
   */
  public function setAccount($account): void
  {
    $this->account = $account;
  }

  public function addTelephone($telephone){
    $this->telephones[] = $telephone;
  }

  public function isAccountNonExpired()
  {
    // TODO: Implement isAccountNonExpired() method.
    return true;
  }

  public function isAccountNonLocked()
  {
    // TODO: Implement isAccountNonLocked() method.
    return true;
  }

  public function isCredentialsNonExpired()
  {
    // TODO: Implement isCredentialsNonExpired() method.
    return true;
  }

  public function isEnabled()
  {
    // TODO: Implement isEnabled() method.
    return true;
  }

  public function eraseCredentials()
  {
    // TODO: Implement eraseCredentials() method.
  }

  /**
   * @return mixed
   */
  public function getFirstName()
  {
    return $this->first_name;
  }

  /**
   * @param mixed $first_name
   */
  public function setFirstName($first_name): void
  {
    $this->first_name = $first_name;
  }

  /**
   * @return mixed
   */
  public function getBirthDate()
  {
    return $this->birth_date;
  }

  /**
   * @param mixed $birth_date
   */
  public function setBirthDate($birth_date): void
  {
    $this->birth_date = $birth_date;
  }

  /**
   * @return mixed
   */
  public function getProfiles()
  {
    return $this->profiles;
  }

  /**
   * @param mixed $profiles
   */
  public function setProfiles($profiles): void
  {
    $this->profiles = $profiles;
  }

  /**
   * @return mixed
   */
  public function getNamespace()
  {
    return $this->namespace;
  }

  /**
   * @param mixed $namespace
   */
  public function setNamespace($namespace): void
  {
    $this->namespace = $namespace;
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
  public function setCart($cart): void
  {
    $this->cart = $cart;
  }

  public function generateNamespace($input, $uniqueness = false){
//    Set the efault input element...
//    uniqueness est la clé qui intègre des trucs au namespace :). Pour s'assurer de son unicité (à l'inscription par exemple)

//        Generates the final namespace from the namespace typed on the form.
    $string = \transliterator_transliterate('Any-Latin; Latin-ASCII; [\u0080-\u7fff] remove', $input);
    $tmp = preg_replace('/-{2,}/', '-',
      preg_replace('/\s+/i', '-',
        preg_replace('/[^0-9a-z-\s]/i', '-',
          strtolower(
            trim($string)
          )
        )
      )
    );

    $last_index = strlen($tmp)-1;
    if($last_index > 0){
      if(in_array($tmp[$last_index], ['.', '-']) ){
        $tmp = substr($tmp, 0, $last_index-1);
      }
    }

    if($uniqueness){
      $tmp = time().'-'.$tmp;
    }
    $this->setNamespace($tmp);
    return $tmp;
  }

  public function makeArtist(){
    foreach ($this->roles as $role){
      if("ROLE_ARTIST" == $role){
        return;
      }
    }
    $this->roles[] = "ROLE_ARTIST";
    $this->profiles->add("artist");
    return;
  }

  public function removeArtist(){
    print_r($this->roles);
    foreach ($this->roles as $role){
      if("ROLE_ARTIST" == $role){
        $this->roles = array_diff($this->roles, array($role));
        print_r($this->roles);
//        On ne supprime pas le compte artiste de l'utilisateur
        return;
      }
    }
    return;
  }

  public function addRequestArtistAccess(){
//    demande d'accès au profil artist
    $this->artist_request = true;
    $this->date_artist_request = new \DateTime();
  }

  public function removeRequestArtistAccess(){
//    demande d'accès au profil artist
    $this->artist_request = false;
  }

  /**
   * @return mixed
   */
  public function getArtistRequest()
  {
    return $this->artist_request;
  }

  /**
   * @param mixed $artist_request
   */
  public function setArtistRequest($artist_request): void
  {
    $this->artist_request = $artist_request;
  }

  /**
   * @return mixed
   */
  public function getDateArtistRequest()
  {
    return $this->date_artist_request;
  }

  /**
   * @param mixed $date_artist_request
   */
  public function setDateArtistRequest($date_artist_request): void
  {
    $this->date_artist_request = $date_artist_request;
  }

  /**
   * @return mixed
   */
  public function getNode()
  {
    return $this->node;
  }

  /**
   * @param Node $node
   */
  public function setNode(Node $node): void
  {
    $this->node = $node;
    $node->setUser($this);
  }

  /**
   * @return mixed
   */
  public function getGodfatherNamespace()
  {
    return $this->godfather_namespace;
  }

  /**
   * @param mixed $godfather_namespace
   */
  public function setGodfatherNamespace($godfather_namespace): void
  {
    $this->godfather_namespace = $godfather_namespace;
  }
}