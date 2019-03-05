<?php
/**
 * Created by PhpStorm.
 * User: Jeannette
 * Date: 1/3/2018
 * Time: 3:09 PM
 */

namespace XS\UserBundle\Document;


use Doctrine\ODM\MongoDB\DocumentRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserRepository extends DocumentRepository implements UserProviderInterface
{
  public function loadUserByUsername($username)
  {
    // TODO: Implement loadUserByUsername() method.
    $dm =  $this->getDocumentManager();
    $user = null;
//    Téléphone (username)
    $user = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
      'username' => $username
    ));
    if(isset($user)){
      return $user;
    }
    $base_query = $dm->createQueryBuilder('XSUserBundle:User')
      ->field('username')->equals($username);
    
//  Matricule
    $user = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
      'registration_number' => $username
    ));
    if(isset($user)){
      return $user;
    }
    
    $base_query->addOr($base_query->expr()
        ->field('registration_number')->equals(strtoupper($username))
      )
    ;
//    Email
    $base_query->addOr($base_query->expr()
        ->field('email')->equals($username)
      )
    ;
  
    $user = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
      'email' => $username
    ));
    if(isset($user)){
      return $user;
    }
    
//    namespace
    $base_query->addOr($base_query->expr()
      ->field('namespace')->equals($username)
    )
    ;
  
    $user = $dm->getRepository('XSUserBundle:User')->findOneBy(array(
      'namespace' => $username
    ));
    
    if(isset($user)){
      return $user;
    }
    else{
      throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
//      return null;
    }
    
    return $base_query->getQuery()->toArray()[0];
  
    $repository = $this->getRepository();
    if (null !== $this->property) {
      $user = $repository->findOneBy(array($this->property => $username));
    } else {
      if (!$repository instanceof UserLoaderInterface) {
        throw new \InvalidArgumentException(sprintf('You must either make the "%s" entity Doctrine Repository ("%s") implement "Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface" or set the "property" option in the corresponding entity provider configuration.', $this->classOrAlias, get_class($repository)));
      }
    
      $user = $repository->loadUserByUsername($username);
    }
  
    if (null === $user) {
    
    }
  
    return $user;
    
    
//    return $this->getDocumentManager()->createQueryBuilder();
  }
  
  public function refreshUser(UserInterface $user)
  {
    // TODO: Implement refreshUser() method.
    return $this->loadUserByUsername($user->getUsername());
  }
  
  public function supportsClass($class)
  {
    // TODO: Implement supportsClass() method.
    return $class === 'XS\UserBundle\DocumentUser';
  }
  
}