<?php

namespace XS\AfrobankBundle\Controller;

use MainBundle\Form\StoreType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\AfrobankBundle\Document\Account;
use XS\AfrobankBundle\Document\Afrobank;
use XS\AfrobankBundle\Document\Transaction;
use XS\AfrobankBundle\Form\AmountType;
use XS\CoreBundle\Document\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AfrobankController extends Controller
{
  public function indexAction(Request $request)
  {
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
//    $data = $dm->getRepository('XSAfrobankBundle:Afrobank')->findAll();
   /* if($data == null){
      return $this->redirectToRoute('main_homepage');
    }*/
    $user = $this->getUser();
    
    $store = $user->getStore();

//        TODO: On avait un petit test sur le paiement des boutiques... NOw, tout est OK ... :)
    /*$date = new \DateTime();
    $store->setDeadline($date->modify('-1 month'));
    $dm->persist($store);
    $dm->flush();*/
//    $data = $data[0];
//        Je me definit comme admin... :)
//        $user->addRole('ROLE_ADMIN');
//        $dm->persist($user);
//        $dm->flush();
    
    $system = new \AppBundle\Service\Transaction();
    $data = $system->_getSystemReceiver($dm);
    
    //todo: Important
    $id = $data->getId();
    $session = $request->getSession();
    $session->set('url', $this->generateUrl('xs_afrobank_home'));
    $session = $request->getSession();
    $session->set('object_doc', 'Afrobank');
    $session->set('object_id', $id);
    
    return $this->render('XSAfrobankBundle:Afrobank:index.html.twig', array(
      'store' => $data
    ));
  }
  
  
  
  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function adminAction(Request $request)
  {
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $data = $dm->getRepository('XSAfrobankBundle:Afrobank')->findAll();
    if($data == null){
      return $this->redirectToRoute('main_homepage');
    }
    $user = $this->getUser();
//        $this->denyAccessUnlessGranted('ROLE_ADMIN', $user, 'Unable to access this page!');
    $data = $data[0];
    //On ajoute la vue, s'il le faut..
    $ctrl_envy = $this->get('envy_controller');
    $ctrl_envy->addView($data, $dm, $user);

//        On recupere tous les utilisateurs de Afrobusiness...
    $users = $dm->getRepository('XSUserBundle:User')->findAll();
//        On retrieve aussi toutes lestransactions...
    $transactions = $dm->getRepository('XSAfrobankBundle:Transaction')->findAll();
    
    
    //todo: Important
    $id = $data->getId();
    $session = $request->getSession();
    $session->set('url', $this->generateUrl('xs_afrobank_home'));
    $session = $request->getSession();
    $session->set('object_doc', 'Afrobank');
    $session->set('object_id', $id);
    
    return $this->render('XSAfrobankBundle:Afrobank:admin.html.twig', array(
      'store' => $data,
      'transactions' => $transactions,
      'users' => $users
    ));
  }
  
  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function addAction(Request $request){
    //Ajout d'une boutique a l'utilisateur courant...
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    //Si l'on a deja active BledDownloader, on l'affiche...
    
    $data = $dm->getRepository('XSAfrobankBundle:Afrobank')->findAll();
    if($data != null){
      return $this->redirectToRoute('xs_afrobank_home');
    }
    
    $store = new Afrobank();
    $user = $this->getUser();
    
    $store->setLocalisation($user->getLocalisation());
//        $store->getLocalisation()->setTown($user->getTown());
    
    $form = $this->createForm(new StoreType(), $store);
    
    if($request->isMethod('POST')){
      $form->handleRequest($request);
      if($form->isValid()){
        //On definit maintenant l'image de profil de l'utilisateur avant de le persister...
        //On associe la boutique a l'utilisateur.
//                todo: On retire la relation setStore de cet utilisateur, il ne s'agit pas de sa Store.
        //ceci est une store independante...
//                $user->setStore($store);
        $store->setAuthor($user);
        //On genere l'Email...
        //todo: Avant de terminer, on cree le compte Afrobanking...
        $account = new Account($user);
        $store ->setAccount($account);
        
        //Parametrages...
        $dm->persist($account);
        $dm->persist($store);
        $dm->persist($user);
        $dm->flush();
        
        return $this->redirect($this->generateUrl('xs_afrobank_home'));
        
      }
    }
    
    return $this->render('XSAfrobankBundle:Afrobank:add.html.twig', array('form' => $form->createView()));
    
  }
  
  /**
   * @Security("has_role('ROLE_ADMIN')")
   */
  public function creditAccountAction(Request $request, $namespace){
    //Permet de crediter le compte de l'utilisateur de namespace '$' dont il est Emetteur et recepteur, avec passerelle Express Union... :)
    
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    //Si l'on a deja active BledDownloader, on l'affiche...
    
    $data = $dm->getRepository('XSAfrobankBundle:Afrobank')->findAll();
    if($data == null){
      return $this->redirectToRoute('xs_afrobank_home');
    }
    
    $user = $dm->getRepository('XSUserBundle:User')->findOneByNamespace($namespace);
    $form = $this->createFormBuilder()
      ->add('amount', new AmountType())
      ->getForm();
    
    if(isset($user)) {
      if($request->isMethod('POST')){
        $form->handleRequest($request);
        if($form->isValid()){
          $amount = $form->getData()['amount'];
          if($user->getAccount()->deposit($amount)){
            $transactions = $dm->getRepository('XSAfrobankBundle:Transaction')->findAll();
            $transaction =  new Transaction(count($transactions));
            $transaction->setSender($user);
            $transaction->setReceiver($user);
            $transaction->setAmount($amount);
//                    On definit la banque :: Express Union :)
            $transaction->setBank('Express Union');
//                    Raison :: DEPOT, ce n'est pas un approvisionnement ici... L'appro c'est lorsqu'il envoit... :)
            $transaction->setReason('Deposit');
            $user->getAccount()->addTransaction($transaction);
            $transaction->finish();
            //La transaction est parfaite, now, on effectue les MAJ des comptes...
//                        On persiste tout... :)
            $dm->persist($transaction);
            $dm->persist($user);
            $dm->flush();
//        On recupere tous les utilisateurs de Afrobusiness...
            $users = $dm->getRepository('XSUserBundle:User')->findAll();
            return $this->redirectToRoute('xs_afrobank_admin');
          }
        }
      }
    }
    
    return $this->render('XSAfrobankBundle:Afrobank:creditAccount.html.twig', array(
      'form' => $form->createView(),
      'namespace' => $namespace
    ));
    
  }
}
