<?php

/**
 * M4A1 - The Awesome Mailer Service
 *
 * The M4A1 is a software developed for provide a mail service
 * which can be used by all services of iSET.
 *
 * The proposal of M4A1 is provide a mail tool that runs a daemon
 * as a observer for new services to be triggered, this services
 * runs natively on Linux servers independent of each others.
 *
 * This is a source code file, part of M4A1 product and this
 * source code is privately and only iSET and your developers
 * can use or distribute it.
 *
 * @copyright M4A1 (c) iSET - Internet, Soluções e Tecnologia LTDA.
 * @version $Id$
 *
 */

namespace Iset\Silex\Db;

use Silex\Application;

/**
 * Abstract Table Gateway
 * 
 * This is a abstract class that provides a basic structure for models
 * that performs updates in MySQL databases.
 * 
 * @package Iset\Silex
 * @subpackage Db
 * @namespace Iset\Silex\Db
 * @author Lucas Mendes de Freitas <devsdmf>
 * @copyright M4A1 (c) iSET - Internet, Soluções e Tecnologia LTDA.
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
}