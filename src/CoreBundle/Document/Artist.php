<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 1/4/2019
 * Time: 10:04 AM
 */

namespace CoreBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use phpDocumentor\Reflection\Types\This;
use RadioRelax\ComingSoonBundle\Document\TempUser;
use XS\CoreBundle\Document\GMaps;
use XS\CoreBundle\Document\Localisation;

/**
 * Class Artist
 * @MongoDB\EmbeddedDocument()
 * @package CoreBundle\Document
 */

//Propriétés et fonçtionnalité d'un artiste, injeçtées dans la grande çlasse User :)
class Artist
{
  /** @MongoDB\Field(type="string") */
  protected $name;

  /** @MongoDB\Field(type="string") */
  protected $link;

  /** @MongoDB\EmbedOne("XS\CoreBundle\Document\Localisation") */
  protected $localisation;

  /** @MongoDB\EmbedOne("XS\CoreBundle\Document\GMaps") */
  protected $gmaps;

  /** @MongoDB\Field(type="string") */
  protected $profile_pic;

  /** @MongoDB\Field(type="string") */
//  Lien permanent :)
  protected $namespace;

  /** @MongoDB\ReferenceMany(targetDocument="Album") */
//  Les albums de l'artist
  protected $albums;

  /** @MongoDB\Field(type="string") */
//  Identifiant de la requête de l'artiste
  protected $request_id;

  /** @MongoDB\Field(type="string")*/
  protected $email;

  /** @MongoDB\Field(type="string")*/
//  Informations sur l'artiste :)
  protected $description;

  /**
   * Artist constructor.
   */
  public function __construct()
  {
//    On çrée l'album par défaut qui çontient les musiques hors album : __relax
    $solo_album = new Album("__radio__");
    $this->albums = new ArrayCollection();
    $this->albums->add($solo_album);
    $this->setGmaps(new GMaps());
    $this->setLocalisation(new Localisation());
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
  public function getLink()
  {
    return $this->link;
  }

  /**
   * @param mixed $link
   */
  public function setLink($link): void
  {
    $this->link = $link;
  }


  /**
   * @return mixed
   */
  public function getLocalisation()
  {
    return $this->localisation;
  }

  /**
   * @param mixed $localisation
   */
  public function setLocalisation($localisation): void
  {
    $this->localisation = $localisation;
  }

  /**
   * @return mixed
   */
  public function getProfilePic()
  {
    return $this->profile_pic;
  }

  /**
   * @param mixed $profile_pic
   */
  public function setProfilePic($profile_pic): void
  {
    $this->profile_pic = $profile_pic;
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
  public function getAlbums()
  {
    return $this->albums;
  }

  /**
   * @param mixed $albums
   */
  public function setAlbums($albums): void
  {
    $this->albums = $albums;
  }

  /**
   * @return mixed
   */
  public function getRequestId()
  {
    return $this->request_id;
  }

  /**
   * @param mixed $request_id
   */
  public function setRequestId($request_id): void
  {
    $this->request_id = $request_id;
  }

  public function fillFromRequest(TempUser $tmp_user) : string
  {
    $this->setName($tmp_user->getName());
    $this->setLink($tmp_user->getLinkPortfolio());
    $this->setRequestId($tmp_user->getId());
    $this->setEmail($tmp_user->getEmail());
    return $this->generateNamespace($this->getName(), true);
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

  public function generateNamespace($input=null, $uniqueness = false){
//    Set the efault input element...
//    uniqueness est la clé qui intègre des trucs au namespace :). Pour s'assurer de son unicité (à l'inscription par exemple)
    if(empty($input)){
      $input = $this->getName();
    }
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
  public function setDescription($description): void
  {
    $this->description = $description;
  }

  /**
   * @return mixed
   */
  public function getGmaps()
  {
    return $this->gmaps;
  }

  /**
   * @param mixed $gmaps
   */
  public function setGmaps(GMaps $gmaps): void
  {
    $gmaps->setFormattedAddress($gmaps->getLnLocality().", ".$gmaps->getLnCountry());
    $this->gmaps = $gmaps;
  }
}