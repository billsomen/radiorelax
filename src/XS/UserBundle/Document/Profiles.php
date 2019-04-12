<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 3/5/2019
 * Time: 3:32 PM
 */

namespace XS\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use MainBundle\Document\Artist;
use MainBundle\Document\Listener;

/**
 * Class Profiles
 * @MongoDB\EmbeddedDocument()
 * @package XS\UserBundle\Document
 */


class Profiles
{
//  All the profiles a user can get
// This also refers to the possible user's behaviors

  /** @MongoDB\Field(type="boolean") */
//  Ai-je accès au profil Artiste
  protected $accessArtist;

  /** @MongoDB\EmbedOne(targetDocument="MainBundle\Document\Artist") */
//    Champ contenant la variale Artiste
  protected $artist;

  /** @MongoDB\Field(type="date") */
//    Champ contenant la date à laquelle on a été ajouté comme artiste
  protected $date_artist;

  /** @MongoDB\EmbedOne(targetDocument="MainBundle\Document\Listener") */
//    Champ contenant la variale Listener : pour tous les utilisateurs por d&faut
  protected $listener;

  /**
   * Profiles constructor.
   */
  public function __construct()
  {
//    Le profil Listener est créé par défaut, donc, on l'instancie au constructeur de cette classe
    $this->add("listener");
  }

  /**
   * @return mixed
   */
  public function getAccessArtist()
  {
    return $this->accessArtist;
  }

  /**
   * @param mixed $accessArtist
   */
  public function setAccessArtist($accessArtist): void
  {
    $this->accessArtist = $accessArtist;
  }

  /**
   * @return mixed
   */
  public function getListener()
  {
    return $this->listener;
  }

  /**
   * @param mixed $listener
   */
  public function setListener($listener): void
  {
    $this->listener = $listener;
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

  public function add($profile): void
  {
    switch ($profile){
      case "artist":
        $this->setArtist(new Artist());
        $this->setAccessArtist(true);
        $this->setDateArtist(new \DateTime());
        break;
      case "listener":
        $this->setListener(new Listener());
        break;
    }
  }

  public function remove($profile): void
  {
    switch ($profile){
      case "artist":
        $this->setArtist(null);
        $this->setAccessArtist(false);
        $this->setDateArtist(new \DateTime());
        break;
      case "listener":
        $this->setListener(null);
        break;
    }
  }

  /**
   * @return mixed
   */
  public function getDateArtist()
  {
    return $this->date_artist;
  }

  /**
   * @param mixed $date_artist
   */
  public function setDateArtist($date_artist): void
  {
    $this->date_artist = $date_artist;
  }
}