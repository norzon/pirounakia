<?php
    $app->get("/setup", function($request, $response){
        global $baseurl;
        require_once("$baseurl/page/setup.php");
    });
?>