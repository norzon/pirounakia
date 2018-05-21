<?php
    $app->get('/logout', function($request, $response){
        global $baseurl, $response_body, $response_url;

        $_SESSION = array();
        session_destroy();
        
        $response_body = array(
            "success" => true,
            "description" => "Successfully logged out"
        );
        $response_url = "/";
        
        return $response;
    })->setName('get.logout');
?>