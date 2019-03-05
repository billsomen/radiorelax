<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 3/5/2019
 * Time: 3:32 PM
 */

namespace XS\UserBundle\Document;


use CoreBundle\Document\Artist;

class Profiles
{
//  All the profiles a user can get
// This also refers to the possible user's behaviors

  /** @MongoDB\Field(type="boolean") */
//  Ai-je accès au profil Artiste
  protected $accessArtist;

  /** @MongoDB\EmbedOne(targetDocument="RadioRelax\CoreBundle\Document\Artist") */
//    Champ contenant la variale Artiste
  protected $artist;

  /** @MongoDB\Field(type="date") */
//    Champ contenant la variale Artiste
  protected $date_artist;

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