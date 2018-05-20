<?php
    $app->post("/register", function($request, $response){
        global $db, $baseurl, $errors;
        
        try {
            if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true) {
                throw new Exception("Cannot register when logged in");
            }
            
            // Load post data
            $data = $request->getParsedBody();
            
            // Get the email and the password
            $email = dataCheck($data["email"], "No email given", "empty");
            $password = dataCheck($data["password"], "No password given", "empty");
            $repeat_password = dataCheck($data["repeat-password"], "No repeat password given", "empty");
            
            if ($password !== $repeat_password) {
                throw new Exception("Passwords do not match");
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
            
            // Check if ajax request
            if (detectAjax($request)) {
                return $response
                ->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode(array(
                    "success" => true,
                    "description" => "Successfully registered"
                )));
            } else {
                return $response
                ->withStatus(302)
                ->withHeader("Location", $request->getReferrer());
            }
        } catch (Exception $e) {
            if (detectAjax($request)) {
                return $response
                ->withStatus(400) // Check error code
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode(array(
                    "success" => false,
                    "description" => $e->getMessage()
                )));
            } else {
                $_SESSION["errors"][] = $e->getMessage();
                
                return $response
                ->withStatus(302)
                ->withHeader("Location", $request->getHeader("Referer"));
            }
        }
    });
?>