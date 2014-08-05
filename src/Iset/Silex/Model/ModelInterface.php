<?php

namespace Iset\Silex\Model;

use Iset\Silex\Db\TableGatewayAbstract;

/**
 * Model Interface
 * 
 * This is a interface that provides a list of methods that must be
 * implemented by model objects.
 * 
 * @package Iset\Silex
 * @subpackage Model
 * @namespace Iset\Silex\Model
 * @author Lucas Mendes de Freitas <devsdmf>
 * @copyright M4A1 (c) iSET - Internet, Soluções e Tecnologia LTDA.
 *
 */
interface ModelInterface
{
    /**
     * The Constructor
     * 
     * @param TableGatewayAbstract $gateway
     */
    public function __construct(TableGatewayAbstract $gateway = null);
    
    /**
     * Fill object properties with an configured associative array
     * 
     * @param array $data
     */
    public function exchangeArray(array $data);
    
    /**
     * Get object in a array representation
     * 
     * @return array
     */
    public function asArray();
    
    /**
     * Validate object
     * 
     * @return mixed
     */
    public function validate();
    
    /**
     * Save object in database
     * 
     * @return mixed
     */
    public function save();
    
    /**
     * Remove object
     * 
     * @return mixed
     */
    public function delete();
}