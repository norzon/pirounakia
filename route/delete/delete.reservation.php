<?php
    $app->delete("/reservation/{rid}", function($request, $response, $args){
        global $db, $baseurl, $errors, $response_body, $response_url;
        
        if ($_SESSION['logged'] == false) {
            throw new Exception("Cannot delete a reservation if not logged in");
        }
        $db->prepareGetReservation();
        $reservation = $db->getReservation($args["rid"]);
        if (empty($reservation)) {
            throw new Exception("Reservation not found");
        } else {
            $reservation = $reservation[0];
        }
        if ($_SESSION["admin"] == false && $reservation->user_id !== $_SESSION["user"]->id) {
            throw new Exception("You cannot delete a reservation that does not belong to you");
        }
        
        $db->prepareUpdateReservation(['status']);
        $db->updateReservation($args["rid"], [
            "status" => "Cancelled"
        ]);
        
        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully cancelled your reservation"
        ));
        $response_url = "/my";
        
        return $response;
    })->setName('delete.reservation');
?>