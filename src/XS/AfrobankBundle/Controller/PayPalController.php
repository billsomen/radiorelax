<?php

namespace XS\AfrobankBundle\Controller;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

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
    $cart = $this->getUser()->getCart();
    return $this->render('XSAfrobankBundle:PayPal:index.html.twig', array(
      'cart' => $cart
    ));
  }

  public function addOrderAction($id){
    $client = self::client();
    $response = $client->execute(new OrdersGetRequest($id));

    //print json_encode($response->result);
    print "Status Code: {$response->statusCode}\n";
    print "Status: {$response->result->status}\n";
    print "Order ID: {$response->result->id}\n";
    print "Intent: {$response->result->intent}\n";
    print "Links:\n";
    foreach($response->result->links as $link)
    {
      print "\t{$link->rel}: {$link->href}\tCall Type: {$link->method}\n";
    }
    // 4. Save the transaction in your database. Implement logic to save transaction to your database for future reference.
    print "Gross Amount: {$response->result->purchase_units[0]->amount->currency_code} {$response->result->purchase_units[0]->amount->value}\n";

    // To print the whole response body, uncomment the following line
    return new JsonResponse( $response->result);
  }


}
