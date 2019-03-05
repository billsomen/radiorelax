<?php

namespace XS\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use XS\CoreBundle\Document\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ImageController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    public function gridImage($image){
//        Permet de transformer une image fraichement uploadee et de la mettre dans notre Grid...
        $doc = new Image();

        $doc->setFile($image->getPathname());
        $doc->setFilename($image->getClientOriginalName());
        $doc->setMimeType($image->getClientMimeType());
// De cette façon, on est bon... On a centralisé notre upload
        return $doc;
    }

    public function profiles(){
//        Renvoit un tableau d'id d'images de profils utilisateurs par genre...
        $profiles['Male'] = '5648c418fa51f3ac1b000071';
        $profiles['Female'] = '5648d38efa51f3ac1b000093';
        $profiles['stores'] = '5649fe3dfa51f33c2700003c';

        return $profiles;
    }


    public function showHrefAction($id){
        //todo finalement, ceci ne sert plus trop a rien...
        /*
         *  Permet d'afficher une image clicquable qui va s'ouvrir dans un modal en plein ecran
         * Par exemple :
         * - Photo de profil
         * - Differentes photos d'un produit
         * - Photos de profil de chaque produit d'une Store (Boutique)
         */
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $data = $dm->getRepository('XSCoreBundle:Image')->findOneById($id);
        if($data != null){
            $response = new Response();
            $response->headers->set('Content-Type', $data->getMimeType());
            $response->setContent($data->getFile()->getBytes());


            $img = "<img style='height: 60%' src='data:".$data->getMimeType().";base64,".base64_encode($data->getFile()->getBytes())."' />";
            $src = "data:".$data->getMimeType().";base64,".base64_encode($data->getFile()->getBytes());

            return $this->render('@XSCore/Image/index.html.twig', array('src' => $src));
        }
        return new Response('#Aucune image.');

    }

    public function cropAction(){
        return $this->render('@XSCore/Image/crop.html.twig');
    }

    public function removeProfile(Image $image){
//        Permet d'effacer l'image de profil, en s'assurant de ne pas effacer les images par defaut :)

//        TODO : Attention, cette methode est appelee apres que l'on ait defini une nouvelle relation.
//        Donc, il ne faut pas redetacher l'utilisateur de cette image...
            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            if(isset($image)){
                $id = $image->getId();
                if(!in_array($id, $this->profiles())) {
                    $dm->remove($image);
                    $dm->flush();
                    return true;
                }
            }

        return false;

    }

    public function persistBase64($uri){
//        Permet de persister une image dont on vient de fournir l'uri...
//        On retourne l'id de l'objet nouvellement cree... et 0 sinon...

        $result = false;

        if(isset($uri)) {
            $dm = $this->get('doctrine.odm.mongodb.document_manager');
            $user = $this->getUser();
            $name = '[www.afrobusiness.biz]'.$user->getNickname().'.'.time().'.png';

            $data = str_replace(' ', '+', $uri);
            list($type, $data) = explode(';', $data);
            list(, $data) = explode(',', $data);

            $data = base64_decode($data);

//                On enregistre le fichier dans le repertoire temporaire
            file_put_contents('tmp/'.$name, $data);
            $path = 'tmp/'.$name;
            $type = explode(':', $type);
            $image = new UploadedFile($path, $name, $type[1], filesize($path));

            $doc = $this->get('app.image_controller')->gridImage($image);

//                todo: On peut persister...

            $dm->persist($doc);
            $dm->flush();
//                On efface le fichier tempo... vu kil est deja en base de donnnees :)
            unlink($path);
            $result = $doc->getId();
        }

        return $result;
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */

    public function adminAddAction(Request $request){
        $dm = $this->get('doctrine.odm.mongodb.document_manager');
        $form = $this->createFormBuilder()
            ->add('uri', 'hidden')
            ->getForm();
        $uri = 0;
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            if($form->isValid()){
                $uri = $form->getData()['uri'];
                $id = $this->persistBase64($uri);
                $image = $dm->getRepository('XSCoreBundle:Image')->findOneById($id);
                return $this->render('@XSCore/Image/adminAdd.html.twig', array(
                        'form' => $form->createView(),
                        'src' => $uri,
                        'image' => $image
                    )
                );

            }
        }
        return $this->render('@XSCore/Image/adminAdd.html.twig', array(
                'form' => $form->createView(),
                'src' => $uri
            )
        );

    }
}


