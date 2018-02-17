<?php
    /* Session start, should be left here, above all else */
    session_start();
    
    /* Require basic files below */
    require_once('vendor/autoload.php');
    require_once('class/Database.php');
    require_once('class/Setup.php');
    
    
    /* Slim app and global/session variables */
    $app = new \Slim\App;
    
    
    
    /* Middlewares */
    $app->add(function ($request, $response, $next) {
        // If app not initialized
        $path = '/'. $request->getUri()->getPath();
        if (!file_exists('./config.php') && $path !== '/setup') {
            $response
                ->withStatus(503)
                ->withHeader('Content-Type', 'text.html')
                ->write(file_get_contents('./page/503.html'));
        } else {
            $response = $next($request, $response);
        }
        return $response;
    });
    
    
    /* Main route */
    $app->get('/', function($request, $response){});
    
    
    /* Routes */
    
    
    
    /* Running app */
    $app->run();
?>