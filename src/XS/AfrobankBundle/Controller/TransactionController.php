<?php

namespace XS\AfrobankBundle\Controller;

use Osms\Osms;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use XS\AfrobankBundle\Document\Amount;
use XS\AfrobankBundle\Document\Transaction;
use XS\AfrobankBundle\Form\TransactionAdminType;
use XS\AfrobankBundle\Form\TransactionPaymentType;
use XS\AfrobankBundle\Form\TransactionPaymentUpdateType;
use XS\AfrobankBundle\Form\TransactionSendType;

//Le controleur qui gere et affiche le controleur.
class TransactionController extends Controller
{
  public function indexAction($name)
  {
    return $this->render('', array('name' => $name));
  }
  
  public function updateAction(Request $request, $id){
    //Le petit bloc d'ajouts...
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    
    $transaction = $dm->getRepository('XSAfrobankBundle:Transaction')->findOneBy(array(
      'id'=> $id
    ));
    $transaction_tmp = $transaction;
    $form = $this->createForm(TransactionPaymentUpdateType::class, $transaction);
    if($request->getMethod() == 'POST'){
      $form->handleRequest($request);
      if($form->isValid()){
        try{
          $approved = $_POST['approved'];
          $transaction->setBank($transaction_tmp->getBank());
          $transaction->setLocalCode($transaction_tmp->getLocalCode());
          if($approved){
            $transaction_actions = new \AppBundle\Service\Transaction();
            $res = $transaction_actions->_execute($dm, $transaction);
            if($res){
              $this->addFlash('notice', 'Transaction exécutée correctement : Penser à notifier le client');
            }
            else{
              $this->addFlash('error', 'Erreur, la transaction est incorrecte!');
            }
          }
          else{
            $transaction->reject();
            $this->addFlash('notice', 'Transaction rejetée!');
          }
          $dm->persist($transaction);
          $dm->flush();
        }catch(\Exception $exception){
          $this->addFlash('error', 'Merci de sélectionner un Statut pour la transaction!');
        }
      }
      else{
        $this->addFlash('error', 'Erreur formulaire incorrect!');
      }
      
      return $this->redirectToRoute('xs_education_admin_transactions');
    }
    
    return $this->render('@XSAfrobank/Afrobank/Transaction/update.html.twig', array(
      'form' => $form->createView(),
      'transaction' => $transaction
    ));
  }
  
  public function modalAddAction(Request $request){
    //Le petit bloc d'ajouts...
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    
    $data = $dm->getRepository('XSAfrobankBundle:Afrobank')->findAll();
    if($data == null){
      return $this->redirectToRoute('main_home');
    }
    $afrobanking = $data[0]; // La mangeoire :)
    
    $transactions = $dm->getRepository('XSAfrobankBundle:Transaction')->findAll();
    $user = $this->getUser();
    $transactionSend = new Transaction(count($transactions));
    
    $formSend = $this->createForm(new TransactionSendType(), $transactionSend);
    /*$formSend->handleRequest($request);
    if($formSend->isValid()){
//            var_dump($formSend->get('receiver'));
    }*/
    if($request->getMethod() == 'POST'){
      if(isset($_POST['xsafrobank_bundle_transaction_send'])){
        $tmp = $_POST['xsafrobank_bundle_transaction_send'];
        if(isset($tmp['amount']['value']) AND isset($tmp['receiver'])){
          $transactions = $dm->getRepository('XSAfrobankBundle:Transaction')->findAll();
          $transaction = new Transaction(count($transactions));
          //Simulation de transaction
          $receiver = $dm->getRepository('XSUserBundle:User')->findOneById($tmp['receiver']);
          if(isset($receiver)){
            $sender = $this->getUser();
            $amount = new Amount();
            $amount->setValue($tmp['amount']['value']);
            $transaction->setAmount($amount);
            $transaction->setSender($sender);
            $transaction->setReceiver($receiver);
            $transaction->setReason('Personnelle');
//                    $transaction->setLocationFrom($sender->getLocalisation());
//                    $transaction->setLocationTo($receiver->getLocalisation());
            //La transaction est parfaite, now, on effectue les MAJ des comptes...
            if($sender->getAccount()->debit($amount)){
//                    todo: On active le compte du recepteur
              $receiver->getAccount()->activate();
              $receiver->getAccount()->credit($amount);
              $receiver->getAccount()->addTransaction($transaction);
              $sender->getAccount()->addTransaction($transaction);
//                        TODO: Avant de terminer, on credite aussi Afrobanking des 1% de la transaction :)
              $afrobanking->getAccount()->activate();
              $afrobanking->getAccount()->creditAfrobanking($amount);
              $afrobanking->getAccount()->addTransaction($transaction);
//                        La mangeoire est OK :) ///
              
              //La transaction est terminee
              $transaction->finish();
              $dm->persist($transaction);
              $dm->persist($afrobanking);
              $dm->persist($receiver);
              $dm->persist($sender);
              $dm->flush();
            }
          }
        }
      }
//            Redirection
      return $this->redirectToRoute('xs_afrobank_home');
    }
    
    return $this->render('@XSAfrobank/Afrobank/Transaction/modalAdd.html.twig', array(
      'form_send' => $formSend->createView()
    ));
  }
  
  public function sendMoneyAction(Request $request, $namespace){
    //Le modal d'envoi de l'argent a un autre utilisateur...
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $transactions = $dm->getRepository('XSAfrobankBundle:Transaction')->findAll();
    $receiver = $dm->getRepository('XSUserBundle:User')->findOneByNamespace($namespace);
    $user = $this->getUser();
    $transaction = new Transaction(count($transactions));
    
    $data = $dm->getRepository('XSAfrobankBundle:Afrobank')->findAll();
    if($data == null){
      return $this->redirectToRoute('main_home');
    }
    $user = $this->getUser();
    $afrobanking = $data[0]; // La mangeoire :)
    
    $formSend = $this->createForm(new TransactionSendType(), $transaction);
    $formSend->handleRequest($request);
    if($request->getMethod() == 'POST'){
      if($formSend->isValid()){
        //Simulation de transaction
        if(isset($receiver)){
          $sender = $user;
          $transaction->setSender($sender);
          $transaction->setReceiver($receiver);
          $transaction->setReason('Personal');
//                    $transaction->setLocationFrom($sender->getLocalisation());
//                    $transaction->setLocationTo($receiver->getLocalisation());
          //La transaction est parfaite, now, on effectue les MAJ des comptes...
          $amount = $transaction->getAmount();
          if($sender->getAccount()->debit($amount)){
//                    todo: On active le compte du recepteur
            $receiver->getAccount()->activate();
            $receiver->getAccount()->credit($amount);
            $receiver->getAccount()->addTransaction($transaction);
            $sender->getAccount()->addTransaction($transaction);
//                        TODO: Avant de terminer, on credite aussi Afrobanking des 1% de la transaction :)
            $afrobanking->getAccount()->activate();
            $afrobanking->getAccount()->creditAfrobanking($amount);
            $afrobanking->getAccount()->addTransaction($transaction);
//                        La mangeoire est OK :) ///
            //La transaction est terminee
            $transaction->finish();
            $dm->persist($transaction);
            $dm->persist($receiver);
            $dm->persist($afrobanking);
            $dm->persist($sender);
            $dm->flush();
          }
        }
      }
      //            Redirection si bon ou mauvais....
      return $this->redirectToRoute('xs_afrobank_home');
    }
    
    return $this->render('@XSAfrobank/Afrobank/Transaction/sendMoney.html.twig', array(
      'form_send' => $formSend->createView(),
      'receiver' => $receiver
    ));
  }
  
  public function modalAdminAction(Request $request, $to){
    //Le modal d'envoi de l'argent a un autre utilisateur...
    $dm = $this->get('doctrine.odm.mongodb.document_manager');
    $transactions = $dm->getRepository('XSAfrobankBundle:Transaction')->findAll();
    $receiver = $dm->getRepository('XSUserBundle:User')->findOneById($to);

//        L.admin est le gerant du module. C'est dans son compte que sera arrose l'argent qu'il a travaille...
    $admin = $this->getUser();
    if(!in_array('admin', $admin->getOffice()->getOfficeTypes())){
      return $this->redirectToRoute('homepage');
    }
    if(!isset($receiver)){
      return $this->redirectToRoute('homepage');
    }
    $transaction = new Transaction(count($transactions));
    
    $form = $this->createForm(new TransactionAdminType(), $transaction);
    $form->handleRequest($request);
    if($request->getMethod() == 'POST'){
      if($form->isValid()){
        //todo : Si c'est un depot
        if($transaction->getType() == 'deposit'){
//                    L'emetteur est null
          $transaction->setSender(null);
          $transaction->setReceiver($receiver);
          $transaction->setReason('Dépôt dans le compte bancaire du Client.');
          
          $amount = $transaction->getAmount();
//                    todo: En fonction du canal d'envoit on update la raison et les frais de retrait.
          $result = $receiver->getAccount()->depositFly($amount, $transaction->getBank());
          if($result){
//                    todo: On active le compte du recepteur
            $receiver->getAccount()->activate();
            $receiver->getAccount()->addTransaction($transaction);
            
            $admin->getAccount()->activate();
            $tmpAmount = new Amount();
            $tmpAmount->setValue($result['fee']);
            $transaction->setFee($result['fee']);
            $admin->getAccount()->credit($tmpAmount);
            $admin->getAccount()->addTransaction($transaction);
            
            //La transaction est terminee
            $transaction->finish();
            $dm->persist($transaction);
            $dm->persist($receiver);
            $dm->persist($admin);

//                        Avant de finir, on verifie si le receiver n'a pas d'impression en attente...
//                        todo: Enfin...
            if($receiver->getOffice()) {
              $printings = $receiver->getOffice()->getClient()->getPrintings();
//            On effectue le paiement des impressions en attente si on a de l'argent
              if ($receiver->getAccount()->getAmount()->getValue() > 0) {
                $count = count($dm->getRepository('XSAfrobankBundle:Transaction')->findAll());
                foreach ($printings as $printing) {
                  if ($printing->getPending()) {
                    $amount = new Amount();
                    $amount->setValue($printing->getPrice());
                    $manager = $printing->getManager();
                    if (isset($manager)) {
                      if ($receiver->getAccount()->debitSoft($amount)) {
//                            C'est bon pour le paiement...
//                            On credite le compte du manager
                        $manager->getAccount()->credit($amount);
//                                On actualise l'etat de l'impression.
                        $printing->setPending(false);
                        $printing->setLoading(true);
                        $printing->setDateLoading(new \DateTime());
                        $transaction = new Transaction($count);
                        $transaction->setBank('afrobank');
                        $transaction->setFee(0);
                        $transaction->setReason("Paiement des frais d'impression");
                        $transaction->setAmount($amount);
                        $transaction->setSender($receiver);
                        $transaction->setReceiver($manager);
                        $transaction->finish();

//                                On associe les transactions aux receivers
                        $receiver->getAccount()->addTransaction($transaction);
                        $manager->getAccount()->addTransaction($transaction);
//                            On incremente le nombre de transactions
                        $count++;
//                                On persiste tout...
                        $dm->persist($transaction);
                        $dm->persist($receiver);
                        $dm->persist($manager);
                      }
                    }
                  }
                }
              }
            }
            
            $dm->flush();
          }
        }
        return $this->redirectToRoute('xs_cloud_office_users_all');
      }
      //            Redirection si bon ou mauvais....
      return $this->redirectToRoute('homepage');
    }
    
    return $this->render('@XSAfrobank/Afrobank/Transaction/modalAdmin.html.twig', array(
      'form' => $form->createView(),
      'receiver' => $receiver
    ));
  }
}
