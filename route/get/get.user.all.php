<?php
    $app->get("/user", function($request, $response){
        global $db, $baseurl, $errors, $response_body, $response_url;
        
        $users = [];
        if ($_SESSION["logged"] === true) {
            if ($_SESSION["admin"] === true) {
                $db->prepareGetUsersSafe();
                $users = $db->getUsersSafe();
            } else {
                throw new Exception("You do not have access");
            }
        } else {
            throw new Exception("You are not logged in");
        }
        
        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully loaded all users",
            "results" => $users
        ));
        $response_url = "/";
        
        return $response;
    })->setName('get.user.all');
?>