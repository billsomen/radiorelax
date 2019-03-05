<?php

namespace XS\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use XS\MboadjossBundle\Document\Message;
use XS\MboadjossBundle\Form\MessageType;

class AddressBookController extends Controller
{
    public function showAction($namespace)
    {
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $data = $dm->getRepository('XSUserBundle:User')->findOneByNamespace($namespace);
        if($data != null){
            $user = $this->getUser();
            //On va direct consulter le carnet d'addresses...
//            On ajoute le formulaire d'envoi de message :)
            $form_message = $this->createForm(new MessageType(), new Message());
            return $this->render('@XSUser/AddressBook/show.html.twig', array(
                'user' => $data,
                'form_message' => $form_message->createView()
            ));
//            Le premier message ne peut etre avec une image... =P
        }
        return $this->redirectToRoute('main_home');
    }

/*    public function searchAction(Request $request, $query, $town, $country, $sort_tag, $sort_by, $page){
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        //nombre de produit par page = nb_produits
        $nb_produits = 12;
        $users = null;
//            todo......Coooooooooool
//            On applique les filtres de recherche

        $base_query = $dm->createQueryBuilder('XSUserBundle:User');

        if($town != (null OR 0) ){
            $base_query->addAnd($base_query->expr()->field('localisation.town')->equals($town));
        }

        if($country != (null OR 0)){
            $base_query->addAnd($base_query->expr()->field('localisation.country')->equals($country));
        }

//            MOTEUR DE RECHERCHE
//            Ajout du formulaire
        $form = $this->createFormBuilder()
            ->add('search', 'text')
            ->getForm();

        $form->handleRequest($request);
//            Gestion de la soumission et recherche :)
        if($form->isValid()){
            $search_form = $form->getData()['search'];
            $keywords = explode(' ', $search_form);
//            On effectue la recherche dans le nom de la user :)
            $base_query->addAnd($base_query->expr()->field('nickname')->in($keywords));
        }
//            FIN MOTEUR DE RECHERCHE

        if($page != (null OR 0)){
            //Gestion de la page :)
            $page = (int) $page;
            $page = ceil($page);
            if($page > 0){
                $base_query->skip(($page-1)*$nb_produits);
            }
        }

        if($sort_tag != (null OR 0)){
            if($sort_by != (null OR 0)){
                //On fait le classement :)
                $base_query->sort($sort_tag, $sort_by);
            }
        }

        //PAGINNATION
        $base_query->limit($nb_produits);

        // Les produits a envoyer...
        $user = $base_query->getQuery()->execute();
        //Nombre actualise de pages...
        $users['pages'] = ceil(count($user)/$nb_produits);

        //On envois nos parametres a la vue... :)
//            todo : On fait plonger les parametres
        $params['town'] = $town;
        $params['country'] = $country;
        $params['query'] = $query;
        $params['sort_tag'] = $sort_tag;
        $params['sort_by'] = $sort_by;
        $params['page'] = $page;
//            Fin params...
//            Tout est ok, on peut appliquer les groupages :)


        $qb = $base_query
            ->group(
                array(
                    'localisation.town' => null
                ),
                array(
                    'total' => 0,
                )
            )
            ->reduce('function(k, vals){
                    vals.total++;
            }')
        ;
        $query = $qb->getQuery();
        $users['town'] = $query->execute()->toArray();

        $qb = $base_query
            ->group(
                array(
                    'localisation.country' => null
                ),
                array(
                    'total' => 0,
                )
            )
            ->reduce('function(k, vals){
                    vals.total++;
            }')
        ;
        $query = $qb->getQuery();
        $users['country']  = $query->execute()->toArray();

        $groups['users'] = $users;
        //-----------SERVICES :)
//            todo: A venir... :)
        //------------------
        return $this->render('XSUserBundle:AddressBook:edit.html.twig', array(
            'users' => $user,
            'groups' => $groups,
            'form' => $form->createView(),
            'params' => $params
        ));
    }*/


    public function addAction($namespace, $user_namespace)
    {
        //On recupere l'utilisateur et on l'injecte dans la liste des contacts...
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $data = $dm->getRepository('XSUserBundle:User')->findOneByNamespace($user_namespace);
        if($data != null){
            $user = $this->getUser();
            $user->addUserToAddressBook($data);
//            On cree la notification pour l'utilisateur ajoute...
            $listener = $data;
            $message = $user->getNickname()." vous a ajouté à son carnet d'adresses";
            $link = $this->generateUrl('xs_user_show', array('namespace' => $user->getNamespace()));
            $notification = $this->get('app.notification_controller')->create($message, $link, $dm);
            $this->get('app.notification_controller')->associate($notification, $listener, $dm);
            //On enregistre le tout.
            $dm->persist($user);
            $dm->flush();
        }
        //On va direct consulter le carnet d'addresses...
        return $this->redirectToRoute('xs_address_book_show', array(
            'namespace' => $namespace
        ));
    }
}
