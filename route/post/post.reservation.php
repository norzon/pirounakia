<?php
    $app->post("/reservation", function($request, $response){
        global $db, $baseurl, $errors, $response_body, $response_url;
        
        if ($_SESSION['logged'] == false) {
            throw new Exception("Cannot update profile if not logged in");
        }
        
        // Load post data
        $data = $request->getParsedBody();

        // Get the email and the password
        $people = dataCheck($data["people"], "No number of people given", "empty");
        $date = dataCheck($data["date"], "No date given", "empty");
        
        // Get the user from the database
        $db->prepareGetUserByEmail();
        $user = $db->getUserByEmail($_SESSION["user"]->email);
        
        // Check that the user exists
        if (empty($user)) {
            throw new Exception("No user account found");
        } else {
            $user = $user[0];
        }
        
        $db->prepareInsertReservation(["uid", "date", "people"]);
        $db->insertReservation([
            "uid" => $user->id,
            "date" => $date,
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