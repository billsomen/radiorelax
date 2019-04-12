<?php

namespace XS\AfrobankBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use MainBundle\Document\Album;
use MainBundle\Document\Listener;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use XS\AfrobankBundle\Document\Account;
use XS\AfrobankBundle\Document\Transaction;
use XS\UserBundle\Document\User;

ini_set('error_reporting', E_ALL); // or error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

class PayPalController extends Controller
{

  /**
   * Returns PayPal HTTP client instance with environment which has access
   * credentials context. This can be used invoke PayPal API's provided the
   * credentials have the access to do so.
   */
  public function client()
  {
    return new PayPalHttpClient(self::environment());
  }

  /**
   * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
   * For demo purpose, we are using SandboxEnvironment. In production this will be
   * LiveEnvironment.
   */
  public function environment()
  {
    $clientId = getenv("PAYPAL_CLIENT_ID") ?: $this->getParameter("paypal_client_id");
    $clientSecret = getenv("PAYPAL_CLIENT_SECRET") ?: $this->getParameter("paypal_secret");
    return new SandboxEnvironment($clientId, $clientSecret);
  }

  public function indexAction()
  {
//    To make sure we don't pay for and element that is not on the cart, we lock the albums to be paid
    $user = $this->getUser();
    $cart = $user->getCart();

    $cart->lockAlbums();
    $dm = $this->get("doctrine.odm.mongodb.document_manager");
    $user->setCart($cart);
    $dm->persist($user);
    $dm->flush();

//    print_r("<br><br><br><br><br><br><br><br><br><br>".count($cart->getAlbumsPending()));

    return $this->render('XSAfrobankBundle:PayPal:index.html.twig', array(
      'cart' => $cart
    ));
  }

  public function payAlbums(DocumentManager $dm, User $user){
//    Pay pending albums
    $cart = $user->getCart();

    $listener = new Listener();
    $user->getProfiles()->setListener($listener);
//    $listener = $user->getProfiles()->getListener();
//    return count($cart->getAlbumsPending());

    foreach ($cart->getAlbumsPending() as $album){
//    save order on local user store : on the profiles->listener
      $listener->getAlbums()->add($album);
      $artist = $album->getArtist();
//      create transaction
      $transaction = new Transaction();
      $transaction->setSender($user);
      $transaction->setReceiver($artist);
      $transaction->setAmount($album->getPrice());

      //    update album : add transaction
      $album->getAccount()->getTransactions()->add($transaction);
      if(empty($artist->getAccount())){
        $account = new Account($artist);
        $artist->setAccount($account);
        $dm->persist($account);
      }
      $artist->getAccount()->getTransactions()->add($transaction);
      $user->getAccount()->getTransactions()->add($transaction);

      $dm->persist($transaction);
      $dm->persist($album);
      $dm->persist($artist);
      $dm->persist($user);

      $dm->flush();
    }
//    update cart and remove albums from cart
    $cart->unLockAlbums();
    $user->setCart($cart);
//    persist all in database
    $dm->persist($user);
    $dm->flush();
  }

  public function addOrderAction($id){
    $client = self::client();
    $response = $client->execute(new OrdersGetRequest($id));
//   check the total amounts and check if matched by iterating from top to bottom
    $user = $this->getUser();
    $dm = $this->get("doctrine.odm.mongodb.document_manager");
    $this->payAlbums($dm, $user);

/*

    $cart = $user->getCart();

    $listener = $user->getProfiles()->getListener();
//    return count($cart->getAlbumsPending());

    foreach ($cart->getAlbumsPending() as $album){
//    save order on local user store : on the profiles->listener
      $listener->getAlbums()->add($album);
      $artist = $album->getArtist();
//      create transaction
      $transaction = new Transaction();
      $transaction->setSender($user);
      $transaction->setReceiver($artist);
      $transaction->setAmount($album->getPrice());

      //    update album : add transaction
      $album->getAccount()->getTransactions()->add($transaction);
      if(empty($artist->getAccount())){
        $artist->setAccount(new Account());
      }
      $artist->getAccount()->getTransactions()->add($transaction);
      $user->getAccount()->getTransactions()->add($transaction);

      $dm->persist($transaction);
      $dm->persist($album);
      $dm->persist($artist);
      $dm->persist($user);

      $dm->flush();
    }
//    update cart and remove albums from cart
    $cart->unLockAlbums();
    $user->setCart($cart);
//    persist all in database
    $dm->persist($user);
    $dm->flush();




*/



//    return new JsonResponse( $user->getCart()->getAlbumsPending());
//    TODO: Later manager with MLM network transactions

    // To print the whole response body, uncomment the following line
    return new JsonResponse( $response->result);
  }
}
