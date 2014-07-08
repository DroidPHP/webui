<?php

/*
 * This file is part of the DroidPHP Web Interface.
 *
 * (c) Shushant Kumar <shushantkumar786@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

$app->get('/conf/list', function () use($app) {

            $finder = new \Symfony\Component\Finder\Finder;

            $iterator = $finder
                    ->files()
                    ->name('/\.(?:ini|conf)$/')
                    ->depth('< 3')
                    ->in('/mnt/sdcard/droidphp');

            $conf = [];
            foreach ($iterator as $file) {
                $conf[]['location'] = $file->getRealpath();
            }

            return $app['twig']->render('/conf/view.twig', [
                        'conf' => $conf
            ]);
        });

$app->get('/conf/edit', function() use($app) {

            $filename = $app['request']->get('file');
            $content = file_get_contents($filename);

            return $app['twig']->render('configure.twig', array(
                        'content' => $content,
                        'name' => $filename
            ));
        });

$app->post('/conf/edit', function () use($app) {

            $content = $app['request']->get('content');
            $filename = $app['request']->get('file');
            file_put_contents($filename, $content);
            return $app->redirect('/conf/edit?file=' . $filename . '&_updated');
        });

$app->get('/conf/delete', function () use($app) {

            $filename = $app['request']->get('file');
            $rc = @unlink($filename);
            return json_encode(['status' => $rc]);
        });

$app->get('/conf/download', function () use($app) {

            $file = $app['request']->get('file');
            
            if (!preg_match('/\.(?:ini|conf)$/', $file)) {
                return $app->abort(404, 'This is not a valid configuration file');
            }

            if (!file_exists($file)) {
                return $app->abort(404, 'The file was not found.');
            }            

            $stream = function () use ($file) {
                        readfile($file);
                    };

            return $app->stream($stream, 200, array('Content-Type' => 'plain/text'));
        });
