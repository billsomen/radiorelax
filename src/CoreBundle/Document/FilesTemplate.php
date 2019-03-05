<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 1/20/2019
 * Time: 3:04 PM
 */

namespace RadioRelax\CoreBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class FilesTemplate
 * @MongoDB\EmbeddedDocument()
 * @package RadioRelax\CoreBundle\Document
 */


class FilesTemplate
{
  /** @MongoDB\Id() */
  protected $id;

  /** @MongoDB\Field(type="string") */
  protected $name;

  /** @MongoDB\Field(type="date") */
  protected $date_add;

  /** @MongoDB\Field(type="date") */
  protected $date_release;

  /** @MongoDB\Field(type="string") */
  protected $desc;

  /** @MongoDB\Field(type="integer") */
//  duration in seçonds
  protected $duration;

  /** @MongoDB\ReferenceOne(targetDocument="XS\UserBundle\Document\User") */
//  Artiste principal
  protected $artist;

  /** @MongoDB\ReferenceMany(targetDocument="XS\UserBundle\Document\User") */
//  Co Artistes
  protected $co_artists;

  /** @MongoDB\Field("string") */
//  Lien vers la photo de l'élément
  protected $profile;

  /**
   * Album constructor.
   */
  public function __construct()
  {
    $this->date_add = new \DateTime();
    $this->co_artists = new ArrayCollection();
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
  public function getDateRelease()
  {
    return $this->date_release;
  }

  /**
   * @param mixed $date_release
   */
  public function setDateRelease($date_release): void
  {
    $this->date_release = $date_release;
  }

  /**
   * @return mixed
   */
  public function getDesc()
  {
    return $this->desc;
  }

  /**
   * @param mixed $desc
   */
  public function setDesc($desc): void
  {
    $this->desc = $desc;
  }

  /**
   * @return mixed
   */
  public function getArtist()
  {
    return $this->artist;
  }

  /**
   * @param mixed $artist
   */
  public function setArtist($artist): void
  {
    $this->artist = $artist;
  }

  /**
   * @return mixed
   */
  public function getProfile()
  {
    return $this->profile;
  }

  /**
   * @param mixed $profile
   */
  public function setProfile($profile): void
  {
    $this->profile = $profile;
  }

  /**
   * @return mixed
   */
  public function getDuration()
  {
    return $this->duration;
  }

  /**
   * @param mixed $duration
   */
  public function setDuration($duration): void
  {
    $this->duration = $duration;
  }

  /**
   * @return mixed
   */
  public function getCoArtists()
  {
    return $this->co_artists;
  }

  /**
   * @param mixed $co_artists
   */
  public function setCoArtists($co_artists): void
  {
    $this->co_artists = $co_artists;
  }
}