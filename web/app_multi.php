<?php

use Symfony\Component\ClassLoader\ApcClassLoader;
use Symfony\Component\HttpFoundation\Request;

$loader = require_once __DIR__.'/../app/bootstrap.php.cache';

// Use APC for autoloading to improve performance
// Change 'sf2' by the prefix you want in order to prevent key conflict with another application

$loader = new ApcClassLoader('sf2', $loader);
$loader->register(true);


require_once __DIR__.'/../app/AppKernel.php';
require_once __DIR__.'/../app/AppCache.php';

$kernel = new AppKernel('multi', false);
$kernel->loadClassCache();
$kernel = new AppCache($kernel);

// Find the website
$container = $kernel->getContainer();
$container->enterScope('request');
$container->set('request', $request);
$siteManager = $this->container->get('site_manager');
$user = $siteManager->getCurrentSite();

// Load the database for this website
$dbname = $user;
// FIXME: get $dbuser and $dbpass from config
$dbuser = 'www-data';
$dbpass = null;
$container->get('doctrine.dbal.dynamic_connection')->forceSwitch($dbname, $dbuser, $dbpass);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
// only trust proxy headers coming from this IP addresses
Request::setTrustedProxies(array('10.0.0.0/8'));