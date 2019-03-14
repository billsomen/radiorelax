<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/9/2015
 * Time: 9:34 PM
 */

namespace XS\MarketPlaceBundle\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Class StandardComputer
 * @package XS\MarketPlaceBundle\Document
 * @MongoDB\EmbeddedDocument()
 */

//todo: Ici le modele des standards pour les tablettes On fait juste un heritage du StandardPhone...
//todo: Ne pas oublier de mettre des contraintes
//Source: pcmag.com

class StandardTablet extends StandardPhone
{
    //Zuut, rien a ajouter hein... :(
    public function __construct() {
        //On met le nom de la categorie a Tablette...
        $this->setCategory( new Category());
        $this->getCategory()->setName('tablet');
        //todo: Le slug est omis pour l'instant...
    }

}
