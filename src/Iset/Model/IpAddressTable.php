<?php

/**
 * AwMailer - The Awesome Mailer Service
 *
 * The AwMailer is a software developed for provide a mail service
 * which can be used by all services of iSET.
 *
 * The proposal of AwMailer is provide a mail tool that runs a daemon
 * as a observer for new services to be triggered, this services
 * runs natively on Linux servers independent of each others.
 *
 * This is a source code file, part of AwMailer product and this
 * source code is privately and only iSET and your developers
 * can use or distribute it.
 *
 * @copyright AwMailer (c) iSET - Internet, Soluções e Tecnologia LTDA.
 * @version $Id$
 *
 */

namespace Iset\Model;

use Iset\Db\TableGatewayAbstract;
use Iset\Api\Resource\IpAddress;

/**
 * IpAddress Table Gateway
 *
 * This is a table gateway provider for IpAddress objects
 *
 * @package Iset
 * @subpackage Model
 * @namespace Iset\Model
 * @author Lucas Mendes de Freitas <devsdmf>
 * @copyright AwMailer (c) iSET - Internet, Soluções e Tecnologia LTDA.
 *
 */
class IpAddressTable extends TableGatewayAbstract
{
    /**
     * The table name
     * @var string
     */
    const TABLE_NAME = 'ipaddress';

    /**
     * Fetch all ip addresses from database
     *
     * @return array
     */
    public function fetchAll()
    {
        # Retrieving data from database
        $query = "SELECT * FROM `" . self::TABLE_NAME . "`";
        $this->assertGatewayConnection();
        $result = $this->tableGateway->fetchAll($query);

        # Stack for store result
        $stack = array();

        foreach ($result as $row) {
            $ipaddress = new IpAddress();
            $stack[] = $ipaddress->exchangeArray($row);
        }

        return $stack;
    }

    /**
     * Fetch an IpAddress from database
     *
     * @param  string                       $ipaddress
     * @return \Iset\Api\Resource\IpAddress
     */
    public function getIpAddress($ipaddress)
    {
        # Retrieving data from database
        $query = "SELECT * FROM `" . self::TABLE_NAME . "` WHERE `ipaddress`=?";
        $this->assertGatewayConnection();
        $result = $this->tableGateway->fetchAssoc($query,array($ipaddress));

        # Verifying result
        if ($result) {
            $ipaddress = new IpAddress($this);

            return $ipaddress->exchangeArray($result);
        } else {
            return false;
        }
    }

    /**
     * Save an IpAddress
     *
     * @param  IpAddress $ipaddress
     * @return mixed
     */
    public function saveIpAddress(IpAddress &$ipaddress)
    {
        # Validating Ip Address
        $result = $ipaddress->validate();

        # Verifying result
        if ($result === true) {
            # Verifying if IpAddress exists in database
            $result = $this->getIpAddress($ipaddress->ipaddress);

            # Verifying result
            if (!$result) {
                # Inserting
                $query = "INSERT INTO `" . self::TABLE_NAME . "` (`ipaddress`) VALUES (?)";
                $this->assertGatewayConnection();
                $result = $this->tableGateway->executeUpdate($query,array($ipaddress->ipaddress));

                # Verifying result
                if ($result == 1) {
                    return $ipaddress;
                } else {
                    return array('error'=>'An error ocurred at try to insert data in database');
                }
            } else {
                return array('error'=>'IP Address already allowed');
            }
        } else {
            return array('error'=>'Invalid IP address, see details for more information','details'=>$result['error']);
        }
    }

    /**
     * Delete an IpAddress
     *
     * @param  IpAddress $ipaddress
     * @return boolean
     */
    public function deleteIpAddress(IpAddress &$ipaddress)
    {
        # Mounting and executing query
        $query = "DELETE FROM `" . self::TABLE_NAME . "` WHERE `ipaddress`=?";
        $this->assertGatewayConnection();
        $result = $this->tableGateway->executeUpdate($query,array($ipaddress->ipaddress));

        # Verifying result
        if ($result == 1) {
            return true;
        } else {
            return false;
        }
    }
}
