<?php
/**
 * Created by PhpStorm.
 * User: SOMEN
 * Date: 1/4/2019
 * Time: 10:05 AM
 */

namespace MainBundle\Document;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use XS\AfrobankBundle\Document\Amount;
use XS\AfrobankBundle\Document\EntityAccount;

/**
 * Class Album
 * @MongoDB\Document()
 * @package RadioRelax\CoreBundle\Document
 */

class Album extends FilesTemplate
{
  /** @MongoDB\Field(type="boolean") */
//  L'album est çelui de sons sans albums :)
  protected $is_solo;

  /** @MongoDB\ReferenceMany(targetDocument="Music") */
//  ALl the musics of the album
  protected $musics;

//  Montant de l'album
  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\Amount") */
  protected $price;

  /** @MongoDB\EmbedOne(targetDocument="XS\AfrobankBundle\Document\EntityAccount") */
//  The Local entinty's of the Album
  protected $account;

  /**
   * Album constructor.
   * @param $name string
   */
  public function __construct($name = null)
  {
    @parent::__construct();
    $this->name = $name;
    $this->musics = new ArrayCollection();
    $this->account = new EntityAccount();
    $this->price = new Amount();
  }

  /**
   * @return mixed
   */
  public function getMusics()
  {
    return $this->musics;
  }

  /**
   * @param mixed $musics
   */
  public function setMusics($musics): void
  {
    $this->musics = $musics;
  }

  /**
   * @return mixed
   */
  public function isSolo()
  {
    return $this->is_solo;
  }

  /**
   * @param mixed $is_solo
   */
  public function setIsSolo($is_solo): void
  {
    $this->is_solo = $is_solo;
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

  /**
   * @return mixed
   */
  public function getPrice()
  {
    return $this->price;
  }

  /**
   * @param mixed $price
   */
  public function setPrice($price): void
  {
    $this->price = $price;
  }
}
