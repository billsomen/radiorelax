<?php

namespace XS\AfrobankBundle\Controller;

use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Sample\PayPalClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PayPalController extends Controller
{
  public function indexAction()
  {
    $cart = $this->getUser()->getCart();
    return $this->render('XSAfrobankBundle:PayPal:index.html.twig', array(
      'cart' => $cart
    ));
  }

  public static function addOrderAction($id){
    $client = PayPalClient::client();
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
     echo json_encode($response->result, JSON_PRETTY_PRINT);

  }


}
