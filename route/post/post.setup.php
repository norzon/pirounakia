<?php
    $app->post("/setup", function($request, $response){
        var_dump($request->getParsedBody());
    });
?>