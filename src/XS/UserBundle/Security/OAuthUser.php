<?php
/**
 * Created by PhpStorm.
 * User: SOMEN Diego
 * Date: 9/11/2015
 * Time: 12:33 PM
 */

namespace XS\UserBundle\Security;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthUser as BaseOAuthUser;

class OAuthUser extends BaseOAuthUser
{
    protected $data;

    public function __construct(UserResponseInterface $response) {
        parent::__construct($response->getUsername());
        $this->data = array(
            'provider'=>$response->getResourceOwner()->getName(),
            'providerId'=>$response->getUsername()
        );
        $vars = array(
            'nickname',
            'realname', //todo: On omet ce point pour l'instant...
            'email',
            'profilePicture',
            'accessToken',
            'refreshToken',
            'tokenSecret',
            'expiresIn',
        );
        foreach($vars as $v) {
            $fct = 'get'.ucfirst($v);
            $this->data[$v] = $response->$fct();
        }
    }

    public function getData() {
        return $this->data;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles() {
        return array('ROLE_OAUTH_USER');
    }


}