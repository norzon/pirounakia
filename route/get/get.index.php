<?php
    $app->get('/', function($request, $response){
        global $db, $baseurl, $errors;
    
        $response = $response->withStatus(200)->withHeader("Content-Type", "text/html");
        $twig = new \Slim\Views\Twig('page/twig');
        $data = array(
            "baseurl" => $baseurl,
            "session" => $_SESSION,
            "errors" => $errors
        );
        
        $page = "guest.twig";
        if (isset($_SESSION["logged"]) && $_SESSION["logged"] == true) {
            if (isset($_SESSION["admin"]) && $_SESSION["admin"] == true) {
                $page = "admin.twig";
            } else {
                $page = "customer.twig";
            }
        }

        return $twig->render($response, $page, $data);
    })->setName('get.index');
?>