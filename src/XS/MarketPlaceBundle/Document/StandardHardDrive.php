<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/10/2015
 * Time: 6:51 AM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class StandardHardDrive
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */
//todo: La plupart des sources viennent de : http://www.computerhope.com/hdspecs.htm
//todo: sinon, se balader de manufacturer a manufacturer.
class StandardHardDrive extends StandardPhone
{
    /** @MongoDB\Field(type="string") */
    protected $type; //External, internal, etc... USB, Carte memoire

    //On redefinit juste les valeurs storages, brands, modeles...
    public function __construct() {
        //On met le nom de la categorie a Tablette...
        $category = new Category();
        $category->setName('hard_drive');

        $this
            ->setCategory($category)
        ;
        //todo: Le slug est omis pour l'instant...
        //On separe les espaces par des _.
        //todo: implementer les index...
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type) {
        $this->type = $type;
    }



}
