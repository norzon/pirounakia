<?php
    $app->post("/login", function($request, $response){
        global $db, $baseurl, $errors, $response_body, $response_url;

        // Load post data
        $data = $request->getParsedBody();

        // Get the email and the password
        $email = dataCheck($data["email"], "No email given", "empty");
        $password = dataCheck($data["password"], "No password given", "empty");
        
        // Get the user from the database
        $db->prepareGetUserByEmail();
        $user = $db->getUserByEmail($email);
        
        // Check that the user exists
        if (empty($user)) {
            throw new Exception("No user account found");
        } else {
            $user = $user[0];
        }
        
        // Check that the passwords match
        if (!password_verify($password, $user->password)) {
            throw new Exception("Invalid password detected");
        }
        
        // Save user and login status on the session
        $_SESSION["logged"] = true;
        $_SESSION["admin"] = $user->is_admin ? true : false;
        $_SESSION["user"] = $user;
        
        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully logged in",
            "results" => [
                "id" => $user->id,
                "email" => $user->email,
                "firstname" => $user->firstname,
                "lastname" => $user->lastname
            ]
        ));
        $response_url = "/";
        
        return $response;
    })->setName('post.login');
?>