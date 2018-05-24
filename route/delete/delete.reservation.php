<?php
    $app->delete("/reservation/{rid}", function($request, $response, $args){
        global $db, $baseurl, $errors, $response_body, $response_url;
        
        if ($_SESSION['logged'] == false) {
            throw new Exception("Cannot update profile if not logged in");
        }
        
        $db->prepareUpdateReservation(['status']);
        $db->updateReservation($args["rid"], [
            "status" => "Cancelled"
        ]);
        
        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully updated your personal details"
        ));
        $response_url = "/my";
        
        return $response;
    })->setName('delete.reservation');
?>