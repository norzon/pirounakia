<?php
    /* Session start, should be left here, above all else */
    session_start();
    
    /* Require basic files below */
    require_once('vendor/autoload.php');
    
    
    /* Creating slim app and setting global/session variables */
    $app = new \Slim\App;
    
    
    
    /* Setting up Middlewares */
    
    
    /* Main route */
    $app->get('/', function($request, $response){});
    
    
    /* Including route files */
    
    
    
    /* Running app */
    $app->run();
?>