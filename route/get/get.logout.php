<?php
    $app->get('/logout', function($request, $response){
        try {
            
            $_SESSION = array();
            session_destroy();
            
            // Check if ajax request
            if (detectAjax($request)) {
                return $response
                ->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode(array(
                    "success" => true,
                    "description" => "Successfully logged out"
                )));
            } else {
                return $response
                ->withStatus(302)
                ->withHeader("Location", $baseurl . "/");
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