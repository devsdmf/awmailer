<?php

namespace Iset\Model;

use Iset\Silex\Db\TableGatewayAbstract;
use Iset\Model\Service;

/**
 * Service Table Gateway
 *
 * This is a table gateway provider for Service objects
 *
 * @package Iset
 * @subpackage Model
 * @namespace Iset\Model
 * @author Lucas Mendes de Freitas <devsdmf>
 * @copyright M4A1 (c) iSET - Internet, Soluções e Tecnologia LTDA.
 *
 */
class ServiceTable extends TableGatewayAbstract
{
    /**
     * The table name
     * @var string 
     */
    const TABLE_NAME = 'service';
    
    /**
     * Fetch all services from database
     * 
     * @return array
     */
    public function fetchAll()
    {
        # Retrieving data from database
        $query = "SELECT * FROM `" . self::TABLE_NAME . "`";
    	$result = $this->tableGateway->fetchAll($query);
    	
    	# Stack for store result
    	$stack = array();
    	
    	foreach ($result as $row) {
    	    $service = new Service();
    	    $stack[] = $service->exchangeArray($row);
    	}
    	
    	return $stack;
    }
    
    /**
     * Fetch an service from database
     * 
     * @param string $key
     * @return \Iset\Model\Service
     */
    public function getService($key)
    {
        # Retrieving data from database
    	$query = "SELECT * FROM `" . self::TABLE_NAME . "` WHERE `key`=?";
    	$result = $this->tableGateway->fetchAssoc($query,array($key));
    	
    	# Verifying result
    	if ($result) {
    	    $service = new Service($this);
    	    return $service->exchangeArray($result);
    	} else {
    	    return false;
    	}
    }
    
    /**
     * Save an Service
     * 
     * @param Service $service
     * @return mixed
     */
    public function saveService(Service &$service)
    {
        # Validating service
    	$result = $service->validate();
    	
    	if ($result === true) {
    	    if (is_null($service->id)) {
    	        # INSERT
    	        $query = "SELECT * FROM `" . self::TABLE_NAME . "` WHERE `key`=? OR `token`=?";
    	        $result = $this->tableGateway->fetchAll($query,array($service->key,$service->getToken()));
    	        
    	        # Verifying result
    	        if (count($result) == 0) {
    	            # Mounting query
    	            $query = "INSERT INTO `" . self::TABLE_NAME . "` (`name`,`key`,`token`) VALUES (?,?,?)";
    	            $data = array($service->name,$service->key,$service->getToken());
    	            
    	            # Inserting
    	            $result = $this->tableGateway->executeUpdate($query,$data);
    	            
    	            # Verifying result
    	            if ($result == 1) {
    	                $service->id = $this->tableGateway->lastInsertId();
    	                return $service;
    	            } else {
    	                return array('error'=>'An error ocourred at try to insert data in database');
    	            }
    	        } else {
    	            return array('error'=>'The service key or token is already in use');
    	        }
    	    } else {
    	        # UPDATE
    	        # Verifying if key was available
    	        $query = "SELECT * FROM `" . self::TABLE_NAME . "` WHERE `key`=? AND `idservice`!=?";
    	        $result = $this->tableGateway->fetchAll($query,array($service->key,$service->id));
    	        
    	        # Verifying result
    	        if (count($result) == 0) {
    	            # Mouting query
    	            $query = "UPDATE `" . self::TABLE_NAME . "` SET `name`=?,`key`=? WHERE `idservice`=?";
    	            $data = array($service->name,$service->key,$service->id);
    	            
    	            # Updating 
    	            $result = $this->tableGateway->executeUpdate($query,$data);
    	            
    	            # Verifying result
    	            if ($result == 1) {
    	                return $service;
    	            } elseif($result == 0) {
    	            	return array('error'=>'No changes');
    	            } else {
    	                return array('error'=>'An error ocourred at try to update data in database');
    	            }
    	        } else {
    	            return array('error'=>'The service key is already in use, please try another');
    	        }
    	    }
    	} else {
    	    return array('error'=>'Invalid service, see details for more information','details'=>$result['error']);
    	}
    }
    
    /**
     * Delete an Service
     * 
     * @param Service $service
     * @return boolean
     */
    public function deleteService(Service &$service)
    {
    	# Mounting and executing query
    	$query = "DELETE FROM `" . self::TABLE_NAME . "` WHERE `idservice`=?";
    	$result = $this->tableGateway->executeUpdate($query,array($service->id));
    	
    	# Verifying result
    	if ($result == 1) {
    	    return true;
    	} else {
    	    return false;
    	}
    }
}