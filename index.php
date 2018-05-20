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
    require_once('function/detectAjax.php');
    
    if (!file_exists('config.php')) {
        require_once('class/Setup.php');
    } else {
        $config = require_once("config.php");
        $db = new Database($config["db"]);
    }
    
    if (isset($_SESSION["errors"])) {
        $errors = $_SESSION["errors"];
        unset($_SESSION["errors"]);
    } else {
        $errors = [];
    }
    
    /* Slim app and global/session variables */
    $app = new \Slim\App(['settings' => ['displayErrorDetails' => true]]);


    
    /* 
    --- Middlewares ---
    !Important! The last declared runs first
    */
    // Middleware for getting token in header
    // Only a user can use this, not an employee
    $app->add(function ($request, $response, $next) {
        global $db;        
        $headers = $request->getHeaders();
        $params = $request->getQueryParams();
        
        if (isset($headers["Authorization"]) && !empty($headers["Authorization"])) {
            $token = $headers["Authorization"];
        }

        if (isset($params["token"]) && !empty($params["token"])) {
            $token = $params["token"];
        }

        if (isset($token) && !empty($token)) {
            $db->prepareGetUserByToken();
            $user = $db->getUserByToken($token);
            if (isset($user)) {
                $_SESSION["logged"] = true;
                $_SESSION["admin"] = false;
                $_SESSION["token"] = $token;
            }
        }
        
        $response = $next($request, $response);
        return $response;
    });
    
    
    // Set global variables
    $app->add(function ($request, $response, $next) {
        $uri = $request->getUri();
        $GLOBALS["baseurl"] = $uri->getBasePath();
        $GLOBALS["path"] = ($uri->getPath()[0] !== "/") ? "/" . $uri->getPath() : $uri->getPath();
        $response = $next($request, $response);
        return $response;
    });
    
    
    
    
    
    /* Routes */
    // Include setup route only if config does not exist
    if (!file_exists('config.php')) {
        require_once('route/get/get.setup.php');
        require_once('route/post/post.setup.php');
    } else {
        require_once('route/get/get.index.php');
        require_once('route/post/post.login.php');
    }
    
    
    /* Running app */
    $app->run();
?>