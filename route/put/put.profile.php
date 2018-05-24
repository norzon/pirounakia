<?php
    $app->put("/profile", function($request, $response){
        global $db, $baseurl, $errors, $response_body, $response_url;
        
        if ($_SESSION['logged'] == false) {
            throw new Exception("Cannot update profile if not logged in");
        }
        
        // Load post data
        $data = $request->getParsedBody();

        // Get the email and the password
        $firstname = dataCheck($data["firstname"], "No firstname given", "empty");
        $lastname = dataCheck($data["lastname"], "No lastname given", "empty");
        
        // Get the user from the database
        $db->prepareGetUserById();
        $user = $db->getUserById($_SESSION["user"]->id);
        
        // Check that the user exists
        if (empty($user)) {
            throw new Exception("No user account found");
        } else {
            $user = $user[0];
        }
        
        $db->prepareUpdateUser(['firstname', 'lastname']);
        $user = $db->updateUser($_SESSION["user"]->id, [
            "firstname" => $firstname,
            "lastname" => $lastname
        ]);
        
        $user = $db->getUserById($_SESSION["user"]->id);
        $_SESSION["user"] = $user[0];
        
        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully updated your personal details"
        ));
        $response_url = "/my";
        
        return $response;
    })->setName('post.login');
?>