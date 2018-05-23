<?php
    $app->get('/my', function($request, $response){
        global $db, $baseurl, $errors;
        
        $reservations = [];
        if ($_SESSION["logged"] === true) {
            if ($_SESSION["admin"] === true) {
                $db->prepareGetReservations();
                $reservations = $db->getReservations();
            } else {
                $db->prepareGetUserReservations();
                $reservations = $db->getUserReservations($_SESSION["token"]);
            }
        }
        
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
            "errors" => $errors,
            "reservations" => $reservations
        );

        return $twig->render($response, "profile.twig", $data);
    })->setName('get.profile');
?>