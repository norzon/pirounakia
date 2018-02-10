<?php
    /* Session start, should be left here, above all else */
    session_start();
    
    /* Require basic files below */
    require_once('vendor/autoload.php');
    
    
    /* Slim app and global/session variables */
    $app = new \Slim\App;
    
    
    
    /* Middlewares */
    
    
    /* Main route */
    $app->get('/', function($request, $response){});
    
    
    /* Routes */
    
    
    
    /* Running app */
    $app->run();
?>