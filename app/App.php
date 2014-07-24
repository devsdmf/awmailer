<?php

require_once __DIR__ . '/AppKernel.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Zend\Config\Reader\Ini as ConfigReader;
use Iset\Api\Controller\MainController as ApiController;

class App
{
	
	public static function configure()
	{
		# Initializing Kernel
	    $kernel = new AppKernel();
	    
	    # Setting root path
	    $kernel['root_path'] = dirname(__FILE__) . '/../';
		
		# Loading application configuration
		$reader = new ConfigReader();
		$kernel['config'] = $reader->fromFile($kernel['root_path'] . 'app/config/application.ini');
		var_dump($kernel['config']);die;
		
		# Configuring application
		$kernel['base_url'] = $kernel['config']['general']['base_url'];
		$kernel['debug']    = ((int)$kernel['config']['general']['debug'] == 1) ? true : false;
		foreach ($kernel['config']['paths'] as $key => $value) {
		    $path = $kernel['root_path'] . $value;
		    $kernel['config']['paths'][$key] = $path;
		    $kernel[$key.'_path'] = $path;
		}
		
		# Register providers
		$kernel->register(new Silex\Provider\DoctrineServiceProvider(), array(
            'db.options'=>$kernel['config']['database']['mysql']
		));
		$kernel->register(new Silex\Provider\SessionServiceProvider());
		$kernel->register(new SilexExtension\MongoDbExtension(), array(
			'mongodb.connection'=>array(
			    'server'=>$kernel['config']['database']['mongo']['dsn'],
			    'options'=>array(),
			    'eventmanager'=>function ($eventmanager) {}
		    ),
		));
		
		# Register controllers
		$kernel->mount('/api', new ApiController())
		       ->before(function (Request $request) use ($kernel) {
		           # Validating request content-type
			       if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
			           $data = json_decode($request->getContent(), true);
			           $request->request->replace(is_array($data) ? $data : array());
			       } else {
			           return $kernel->abort(Response::HTTP_BAD_REQUEST);
			       }
			       
			       # Getting authentication headers
			       $auth_service_key = $request->headers->get($kernel['config']['api']['auth_header']['service_key']);
			       $auth_token       = $request->headers->get($kernel['config']['api']['auth_header']['token']);
			       $auth_ip_address  = $_SERVER['REMOTE_ADDR'];
			       
			       # Creating session with auth headers
			       $kernel['session']->set($kernel['config']['api']['auth_session']['service'],$auth_service_key);
			       $kernel['session']->set($kernel['config']['api']['auth_session']['token'],$auth_token);
			       $kernel['session']->set($kernel['config']['api']['auth_session']['ipaddress'],$auth_ip_address);
		       });
		
		# Cleaning api session
		$kernel->finish(function (Request $request, Response $response) use ($kernel) {
			$kernel['session']->remove($kernel['config']['api']['auth_session']['service']);
			$kernel['session']->remove($kernel['config']['api']['auth_session']['token']);
			$kernel['session']->remove($kernel['config']['api']['auth_session']['ipaddress']);
		});
		
		return $kernel;
	}
}