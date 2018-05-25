<?php
    $app->post("/reservation", function($request, $response){
        global $db, $baseurl, $errors, $response_body, $response_url;
        
        if ($_SESSION['logged'] == false) {
            throw new Exception("Cannot update profile if not logged in");
        }

        // Get the user from the database
        $db->prepareGetUserByEmail();
        $user = $db->getUserByEmail($_SESSION["user"]->email);
        
        // Check that the user exists
        if (empty($user)) {
            throw new Exception("No user account found");
        } else {
            $user = $user[0];
        }
        
        if (empty($user->firstname) || empty($user->lastname)) {
            throw new Exception("You need to fill out your details to make a reservation");
        }

        // Load post data
        $data = $request->getParsedBody();

        // Get the email and the password
        $people = dataCheck($data["people"], "No number of people given", "empty");
        $date = dataCheck($data["date"], "No date given", "empty");
        $date = new DateTime($date);
        
        if ($_SESSION["admin"] === true) {
            $uid = dataCheck($data["uid"], "No user id given", "empty");
        }
        
        $week_day = strtoupper($date->format("D"));
        $db->prepareGetStoreDay();
        $days = $db->getStoreDay($week_day);
        
        if (empty($days)) {
            throw new Exception("The restaurant is not open on this day");
        } else if (count($days) > 1) {
            throw new Exception("This date is not valid");
        } else {
            $days = $days[0];
        }
        
        $time_start = $date->format("H:i:s");
        $time_end = new DateTime($date->format("Y-m-d H:i:s"));
        $time_end->add(new DateInterval('PT3H'));
        $time_end = $time_end->format("H:i:s");
        
        if ($time_start < $days->open_time || $time_end > $days->close_time) {
            throw new Exception("The restaurant is not open at these times");
        }
        
        // Get the user from the database
        $db->prepareGetAvailability();
        $reservations = $db->getAvailability([
            "date" => $date->format("Y-m-d"),
            "time_start" => $time_start,
            "time_end" => $time_end
        ]);
        
        $required_tables = ceil(($people - 2) / 2);
        if ($required_tables < 1) {
            $required_tables = 1;
        }
        if (count($reservations) + $required_tables > $days->tables) {
            throw new Exception("We are full on the requested date and time");
        }
        
        $db->prepareInsertReservation(["user_id", "res_date", "res_time", "people"]);
        $db->insertReservation([
            "user_id" => dataDefault($uid, $user->id),
            "res_date" => $date->format("Y-m-d"),
            "res_time" => $time_start,
            "people" => $people
        ]);
        
        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully created your reservation"
        ));
        
        $response_url = "/my";
        
        return $response;
    })->setName('post.reservation');
?>