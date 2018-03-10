<?php
    if(!isset($app)) die();
    $app->get("/setup", function($request, $response){
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'text/html')
            ->write(file_get_contents("./page/setup.html"));
    });
?>