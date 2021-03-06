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

namespace Iset\Db;

use Silex\Application;

/**
 * Abstract Table Gateway
 *
 * This is a abstract class that provides a basic structure for models
 * that performs updates in MySQL databases.
 *
 * @package Iset
 * @subpackage Db
 * @namespace Iset\Db
 * @author Lucas Mendes de Freitas <devsdmf>
 * @copyright AwMailer (c) iSET - Internet, Soluções e Tecnologia LTDA.
 *
 */
abstract class TableGatewayAbstract
{
    /**
     * An instance of Doctrine DBAL
     * @var \Doctrine\DBAL\Connection
     */
    protected $tableGateway = null;

    /**
     * The Constructor
     *
     * @param Application $app
     */
    public function __construct(Application &$app)
    {
        $this->tableGateway = &$app['db'];
    }

    /**
     * Assert if table gateway is connected to the server
     */
    public function assertGatewayConnection()
    {
        try {
            $this->tableGateway->query("SELECT 1");
        } catch (\Exception $e) {
            $this->tableGateway->close();
            $this->tableGateway->connect();
        }
    }
}
