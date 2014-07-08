<?php

/*
 * This file is part of the DroidPHP Web Interface.
 *
 * (c) Shushant Kumar <shushantkumar786@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app->get('/', function() use($app) {

            $extensions = get_loaded_extensions();

            return $app['twig']->render('index.twig', array(
                        'baseurl' => $app['baseurl'],
                        'extensions' => $extensions
            ));
        });

$app->get('/settings', function () use($app) {

            $pref = new \DroidPHP\Preference($app['shared_prefs']);

            $values = [
                // string
                'use_server_httpd' => $pref->getValue('use_server_httpd'),
                'server_port' => $pref->getValue('server_port'),
                'mysql_username' => $pref->getValue('mysql_username'),
                'mysql_password' => $pref->getValue('mysql_password'),
                // boolean
                'run_as_root' => $pref->getValue('run_as_root', 0x01),
                'enable_server_on_app_startup' => $pref->getValue('enable_server_on_app_startup', 0x01) == 'true' ? 'checked="true"' : '',
                'enable_server_on_boot' => $pref->getValue('enable_server_on_boot', 0x01) == 'true' ? 'checked="true"' : '',
                'enable_screen_on' => $pref->getValue('enable_screen_on', 0x01) == 'true' ? 'checked="true"' : '',
                'enable_lock_wifi' => $pref->getValue('enable_lock_wifi', 0x01) == 'true' ? 'checked="true"' : '',
            ];

            return $app['twig']->render('settings.twig', array(
                        'baseurl' => $app['baseurl'],
                        'pref' => $values
            ));
        });

$app->get('/php_info', function() use($app) {

            phpinfo();
            return '';
        });