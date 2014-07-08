<?php

/**
 * DroidPHP Web Interface
 * http://github.com/droidphp/droidphp
 */

$config = parse_ini_file('config.ini', true);
require 'vendor/autoload.php';

$app = new Silex\Application();

$app['baseurl'] = rtrim($config['app']['baseurl'], '/');
$app['shared_prefs'] = rtrim($config['config']['android_shared_pref']);

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
        //'twig.options'    => array('cache' => __DIR__.'/cache'),
));

// Add the md5() function to Twig scope
$app['twig']->addFilter('md5', new Twig_Filter_Function('md5'));

// Load controllers
include 'controllers/indexController.php';
include 'controllers/preferenceController.php';
include 'controllers/confController.php';

// Error handling
$app->error(function (\Exception $e, $code) use ($app) {
            return $app['twig']->render('error.twig', array(
                        'baseurl' => $app['baseurl'],
                        'message' => $e->getMessage(),
            ));
        });

$app->run();
