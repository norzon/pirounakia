<?php
    $app->get('/', function($request, $response){
        global $db, $baseurl;
    
        $response = $response->withStatus(200)->withHeader("Content-Type", "text/html");
        $options_raw = $db->getOptions();
        $options = array();
        foreach ($options_raw as $row) {
            $options[$row->alias] = $row->value;
        }
        $twig = new \Slim\Views\Twig('page/twig');
        $data = array(
            "baseurl" => $baseurl,
            "session" => $_SESSION,
            "options" => $options
        );

        return $twig->render($response, "customer.twig", $data);
    });
?>