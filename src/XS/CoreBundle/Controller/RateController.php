<?php

namespace XS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RateController extends Controller
{
    public function addAction($obj_id, $obj_name, $value, Request $request){
        //Si on trouve l'objet, on persiste les valeurs et on rentre d'ou on vient.
        //todo: S'assurer que la personne n'a pas encore agit la dessus
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $object = null;
//        L'utilisateur qui recevra la notification suit...
        $listener = null;
        $link = null;
        $message = 'Nouvelle note sur votre ';
        switch ($obj_name) {
            case 'Store':
                $object = $dm->getRepository('MainBundle:Store')->findOneById($obj_id);
                $listener = $object->getAuthor();
                $message .= 'boutique';
                $link = $this->generateUrl('main_stores_show', array('namespace' => $object->getNamespace()));
                break;

            case 'BledDownloader':
                $object = $dm->getRepository('XSBledDownloaderBundle:BledDownloader')->findOneById($obj_id);
                $listener = $object->getAuthor();
                $message .= 'banque';
                $link = $this->generateUrl('xs_afrobank_home');
                break;

            case 'Afrobanking':
                $object = $dm->getRepository('XSAfrobankBundle:Afrobank')->findOneById($obj_id);
                $listener = $object->getAuthor();
                $message .= 'banque';
                $link = $this->generateUrl('xs_afrobank_home');
                break;

            case 'Product':
                $object = $dm->getRepository('MainBundle:Product')->findOneById($obj_id);
                $link = $this->generateUrl('main_products_show', array('id' => $object->getId()));
                $message .= 'produit';
                $listener = $object->getStore()->getAuthor();
                break;

            case 'Service':
                $object = $dm->getRepository('MainBundle:Service')->findOneById($obj_id);
                $link = $this->generateUrl('main_services_show', array('id' => $object->getId()));
                $message .= 'service';
                $listener = $object->getStore()->getAuthor();
                break;

            default:
                # code...
                break;
        }
        if($object != null){
            //On peut persister...
            //On fait la relation.
            $user = $this->getUser();
            $object->addRate($user, $value);
            $dm->persist($object);
            $dm->flush();
//            On rentre d'ou l'on vient
            $url = $request->getSession()->get('url');


            if($listener != null) {
                //        On peut generer la notif...
                $notification = $this->get('app.notification_controller')->create($message, $link, $dm);
                $this->get('app.notification_controller')->associate($notification, $listener, $dm);
                $dm->flush();
            }
            return $this->redirect($url);
        }
        //Si aucun objet n'est trouve, on rentre a la page d'accueil
        return $this->redirect($this->generateUrl('main_home'));

    }
}
