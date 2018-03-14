<?php
    /* Session start, should be left here, above all else */
    session_start();
    
    /* Require basic files below */
    require_once('vendor/autoload.php');
    require_once('class/Query.php');
    require_once('class/QueryCollection.php');
    require_once('class/DatabaseWrapper.php');
    require_once('class/Database.php');
    require_once('function/dataCheck.php');
    require_once('function/dataDefault.php');
    
    if (!file_exists('config.php')) {
        require_once('class/Setup.php');
    } else {
        $config = require_once("config.php");
        $db = new Database($config["db"]);
    }
    
    
    /* Slim app and global/session variables */
    $app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);


    
    /* 
    --- Middlewares ---
    !Important! The last declared runs first
    */
    // Setup middleware
    // $app->add(function ($request, $response, $next) {
    //     global $path;
    //     If app not initialized
    //     if (!file_exists("config.php") && $path !== "/setup") {
    //         $response
    //             ->withStatus(503)
    //             ->withHeader("Content-Type", "text/html")
    //             ->write(file_get_contents("./page/503.html"));
    //     } else {
    //         $response = $next($request, $response);
    //     }
    //     return $response;
    // });
    
    
    // Set global variables
    $app->add(function ($request, $response, $next) {
        $uri = $request->getUri();
        $GLOBALS["baseurl"] = $uri->getBasePath();
        $GLOBALS["path"] = ($uri->getPath()[0] !== "/") ? "/" . $uri->getPath() : $uri->getPath();
        $response = $next($request, $response);
        return $response;
    });
    
    
    
    
    /* Main route */
    $app->get('/', function($request, $response){
        global $db, $baseurl;
        if (!file_exists("config.php")) {
            return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'text/html')
            ->write(file_get_contents("./page/setup.html"));
        } else {
            $response = $response->withStatus(200)->withHeader("Content-Type", "text/html");
            $options_raw = $db->getOptions();
            $options = array();
            foreach ($options_raw as $row) {
                $options[$row->alias] = $row->value;
            }
            $twig = new \Slim\Views\Twig('page');
            $data = array(
                "baseurl" => $baseurl,
                "session" => $_SESSION,
                "options" => $options
            );
            echo "<pre>";
            var_dump($options_raw);
            echo "</pre>";
            die();
            return $twig->render($response, "twig/base.twig", $data);
        }
    });
    
    
    /* Routes */
    // Include setup route only if config does not exist
    if (!file_exists('config.php')) {
        require_once('route/post/post.setup.php');
    } else {
        
    }
    
    
    /* Running app */
    $app->run();
?>