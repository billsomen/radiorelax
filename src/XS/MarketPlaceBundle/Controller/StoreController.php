<?php

namespace XS\MarketPlaceBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use XS\MarketPlaceBundle\Document\Store;
use XS\MarketPlaceBundle\Document\StoreContact;
use XS\MarketPlaceBundle\Document\User;
use XS\MarketPlaceBundle\Form\StoreType;

class StoreController extends Controller
{
  public function indexAction($name)
  {
    return $this->render('', array('name' => $name));
  }
  
  public function _newAction(Request $request){
    //Ajout d'une boutique a l'utilisateur courant...
    
    $dm = $this->get('doctrine_mongodb')->getManager();
 
    $store = new Store();
    $contacts = new StoreContact();
    $user = $this->getUser();
    //On s'assure qu'on n'a pas deja de store...
    $contacts->setTelephones($user->getTelephones());
    $store->setContacts($contacts);
    $store->setLocalisation($user->getLocalisation());
    
    $form = $this->createForm(StoreType::class, $store);
    
    if($request->isMethod('POST')){
      $form->handleRequest($request);
      if($form->isValid()){
//                On defini la photo de profil de la boutique...
        //On associe la boutique a l'utilisateur.
        if(empty($user->getMarket())){
          $user->setMarket(new User());
        }
        $user->getMarket()->getStores()->add($store);
        $store->setAuthor($user);
        $store->setOwner($user);
        
//        $store->setNamespace($namespace);
//        $store->setName($name);
        
        //On genere l'Email...
        $contacts = $store->getContacts();
//        $contacts->generateEmail($store->getNamespace());
        $store->setContacts($contacts);
        //Parametrages...
        $dm->persist($store);
        $dm->persist($user);
        $dm->flush();
        
        $this->addFlash('notice', "Boutique créée avec succès!");
        
        return $this->redirectToRoute('main_welcome');
      }
    }
    
    return $this->render('@XSMarketPlace/Store/_new.html.twig', array(
      'form' => $form->createView(),
      'user' => $user
    ));
  }
  
}
