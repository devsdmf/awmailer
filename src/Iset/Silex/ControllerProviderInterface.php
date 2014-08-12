<?php

namespace Iset\Silex;

use Silex\Application;
use Silex\ControllerProviderInterface as SilexControllerProviderInterface;

/**
 * Controller Provider Interface
 * 
 * This is a extension of ControllerProviderInterface provided by Silex framework
 * customized for this application 
 * 
 * @package Iset
 * @subpackage Silex
 * @namespace Iset\Silex
 * @author Lucas Mendes de Freitas <devsdmf>
 * @copyright M4A1 (c) iSET - Internet, Soluções e Tecnologia LTDA.
 *
 */
interface ControllerProviderInterface extends SilexControllerProviderInterface
{
    /**
     * Get the Request service
     * 
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest();
    
    /**
     * Get the instance of TableGateway
     * 
     * @return \Iset\Silex\Db\TableGatewayAbstract
     */
    public function getTableGateway();
    
    /**
     * Register all routes with the controller methods
     * 
     * @return \Silex\ControllerCollection
     */
    public function register();
    
    /**
     * Static Factory Method
     * 
     * This is a static method that provides a configured instance of Controller
     * 
     * @param Application $app
     */
    public static function factory(Application &$app);
}