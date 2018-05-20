<?php
    $app->get('/', function($request, $response){
        global $db, $baseurl, $errors;
    
        $response = $response->withStatus(200)->withHeader("Content-Type", "text/html");
        $db->prepareGetOptions();
        $options_raw = $db->getOptions();
        $options = array();
        foreach ($options_raw as $row) {
            $options[$row->alias] = $row->value;
        }
        $twig = new \Slim\Views\Twig('page/twig');
        $data = array(
            "baseurl" => $baseurl,
            "session" => $_SESSION,
            "options" => $options,
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
    });
?>