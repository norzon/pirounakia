<?php
    $app->post("/register", function($request, $response){
        global $db, $baseurl, $errors, $response_body, $response_url;

        if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true) {
            throw new Exception("Cannot register when logged in");
        }
        
        // Load post data
        $data = $request->getParsedBody();
        
        // Get the email and the password
        $email = dataCheck($data["email"], "No email given", "empty");
        $password = dataCheck($data["password"], "No password given", "empty");
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("$email is not a valid email address");
        }
        
        // Get the user from the database
        $db->prepareGetUserByEmail();
        $user = $db->getUserByEmail($email);
        
        // Check that the user exists
        if (!empty($user)) {
            throw new Exception("User account already exists");
        }
        
        // Hash the password
        $password = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert user to the database
        $db->prepareInsertUser(["email", "password"]);
        $result = $db->insertUser([
            "email" => $email,
            "password" => $password
        ]);
        
        if (!$result) {
            throw new Exception("Something went wrong");
        }
        
        $response_body = json_encode(array(
            "success" => true,
            "description" => "Successfully registered"
        ));
        
        $response_url = "/";
        
        return $response;
    })->setName('post.register');
?>