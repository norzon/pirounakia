<?php
    $app->get("/store", function($request, $response){
        global $db, $baseurl, $errors, $response_body, $response_url;
        
        $days = [];
        if ($_SESSION["logged"] === true) {
            if ($_SESSION["admin"] === true) {
                $db->prepareGetStoreDays();
                $days = $db->getStoreDays();
            } else {
                throw new Exception("You do not have access");
            }
        } else {
            throw new Exception("You are not logged in");
        }
        
        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully loaded all store days",
            "results" => $days
        ));
        $response_url = "/";
        
        return $response;
    })->setName('get.store');
?>