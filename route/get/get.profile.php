<?php
    $app->get('/my', function($request, $response){
        global $db, $baseurl, $errors;
        
        $reservations = [];
        $page = "";
        if ($_SESSION["logged"] === true) {
            if ($_SESSION["admin"] === true) {
                $db->prepareGetReservations();
                $reservations = $db->getReservations();
                $page = "admin.profile.twig";
            } else {
                $db->prepareGetUserReservations();
                $reservations = $db->getUserReservations($_SESSION["user"]->id);
                $page = "customer.profile.twig";
            }
        }
        
        $today = date("Y-m-d H:i:s");
        for ($i=0; $i < count($reservations); $i++) { 
            $reservations[$i]->cancellable = (($reservations[$i]->res_date . " " . $reservations[$i]->res_time) > $today) ? true : false;
        }
        
        $db->prepareGetStoreDays();
        $store_days = $db->getStoreDays();
        
        $response = $response->withStatus(200)->withHeader("Content-Type", "text/html");
        $twig = new \Slim\Views\Twig('page/twig');
        $data = array(
            "baseurl" => $baseurl,
            "session" => $_SESSION,
            "errors" => $errors,
            "reservations" => $reservations,
            "store_days" => $store_days
        );

        return $twig->render($response, $page, $data);
    })->setName('get.profile');
?>