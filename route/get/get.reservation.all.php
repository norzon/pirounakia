<?php
    $app->get("/reservation", function($request, $response){
        global $db, $baseurl, $errors, $response_body, $response_url;

        $reservations = [];
        if ($_SESSION["logged"] === true) {
            if ($_SESSION["admin"] === true) {
                $db->prepareGetReservations();
                $reservations = $db->getReservations();
            } else {
                $db->prepareGetUserReservations();
                $reservations = $db->getUserReservations($_SESSION["user"]->id);
            }
        } else {
            throw new Exception("You are not logged in");
        }
        
        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully loaded all reservations",
            "results" => $reservations
        ));
        $response_url = "/";
        
        return $response;
    })->setName('get.reservation.all');
?>