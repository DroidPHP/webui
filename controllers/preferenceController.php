<?php

/*
 * This file is part of the DroidPHP Web Interface.
 *
 * (c) Shushant Kumar <shushantkumar786@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app->get('/preference/update', function () use($app) {

            $request = $app['request'];
            $pref = new \DroidPHP\Preference($app['shared_prefs']);

            // string
            $pref->setValue('use_server_httpd', $request->get('daemon'));
            $pref->setValue('server_port', $request->get('port'));
            $pref->setValue('mysql_username', $request->get('mysql_username'));
            $pref->setValue('mysql_password', $request->get('mysql_password'));
            // boolean
            $pref->setValue('run_as_root', $request->get('run_as_root'), 0x01);
            $pref->setValue('enable_server_on_app_startup', $request->get('enable_app_startup'), 0x01);
            $pref->setValue('enable_server_on_boot', $request->get('enable_system_startup'), 0x01);
            $pref->setValue('enable_screen_on', $request->get('enable_wifiLock'), 0x01);
            $pref->setValue('enable_lock_wifi', $request->get('enable_screenLock'), 0x01);

            $rc = $pref->commit();
            return json_encode([
                'message' => $rc ? 'Setting successfully updated' : 'Something Went Wrong',
            ]);
        });