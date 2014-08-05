<?php

namespace Iset\Silex\Auth;

use Silex\Application;

/**
 * Authentication Interface
 * 
 * This is a interface that provide a list of methods that must be
 * implemented in authentication services in API calls
 * 
 * @package Iset\Silex
 * @subpackage Auth
 * @namespace Iset\Silex\Auth
 * @author Lucas Mendes de Freitas <devsdmf>
 * @copyright M4A1 (c) iSET - Internet, Soluções e Tecnologia LTDA.
 *
 */
interface AuthInterface
{
    /**
     * Authenticate
     * 
     * Perform authentication process by a static method without 
     * create a concrete instance of authentication provider.
     * 
     * @param Application $app
     */
    public static function authenticate(Application &$app);
}