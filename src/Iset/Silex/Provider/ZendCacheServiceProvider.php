<?php

namespace Iset\Silex\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Zend\Cache\StorageFactory;

/**
 * Zend Cache Provider
 *
 * This is a extension of ControllerProviderInterface provided by Silex framework
 * customized for this application
 *
 * @package Iset\Silex
 * @subpackage Provider
 * @namespace Iset\Silex\Provider
 * @author Lucas Mendes de Freitas <devsdmf>
 * @copyright M4A1 (c) iSET - Internet, Soluções e Tecnologia LTDA.
 *
 */
class ZendCacheServiceProvider implements ServiceProviderInterface
{
    /**
     * Register Cache service in application dependency injection container
     * 
     * @param Application $app
     * @see \Silex\ServiceProviderInterface::register()
     */
    public function register(Application $app)
    {           
        # Defining default cache options
        $app['cache.default_options'] = array(
            'zendcache'=> array(
                'adapter'=>'filesystem',
                'plugins'=>array(
                    'exception_handler'=>array(
                        'throw_exceptions'=>false
                    ),
                ),
            ),
            'cache_dir'=>'/tmp/',
        );
        
        # Initializing Service
        $app['cache'] = $app->share(function ($app) {
            # Verifying if user options is defined or use default options
            $app['cache.options'] = (isset($app['cache.options'])) ? $app['cache.options'] : $app['cache.default_options'];
            
            # Initializing Cache Provider
            $cache = StorageFactory::factory($app['cache.options']['zendcache']);
            
            # Set cache directory and permissions
            $cache->setOptions(array(
                'cache_dir'=>$app['cache.options']['cache_dir'],
                'dir_permission'=>0777,
                'file_permission'=>0666,             
            ));
            
            return $cache;
        });
    }
    
    /**
     * Bootstrap the application
     * 
     * @param Application $app
     * @see \Silex\ServiceProviderInterface::boot()
     */
    public function boot(Application $app){}
}