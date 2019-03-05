<?php

namespace MainBundle\Controller;

use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use Sample\PayPalClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
  public function businessCardAction($id)
  {
//    create the content of the partial to preview the business card to book :)
//    id is the id of the card on the local folder
    return $this->render('@Main/Api/business_card.html.twig', array('id' => $id));
  }

  public function getOrderAction($id)
  {

    // 3. Call PayPal to get the transaction details
    $client = PayPalClient::client();
    $response = $client->execute(new OrdersGetRequest($id));
    /**
     *Enable the following line to print complete response as JSON.
     */
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

     return new Response("Paypal");
  }
}
