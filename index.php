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
        $_SESSION["errors"] = [];
    } else {
        $errors = [];
    }
    
    $response_body = [];
    $response_url = "/";
    $response_code = 200;
    $response_content = "application/json";
    
    /* Slim app and global/session variables */
    $app = new \Slim\App([
        'settings' => [
            'displayErrorDetails' => true,
            'determineRouteBeforeAppMiddleware' => true
        ],
        'debug' => true
    ]);
    
    
    $_SESSION["logged"] = dataDefault($_SESSION["logged"], false);
    $_SESSION["admin"] = dataDefault($_SESSION["admin"], false);
    $_SESSION["token"] = dataDefault($_SESSION["token"], null);


    
    /* 
    --- Middlewares ---
    !Important! The last declared runs first
    */
    $app->add(function ($request, $response, $next) {
        global $baseurl, $response_body, $response_code, $response_content, $response_url;
        $route = $request->getAttribute('route');
        if (!empty($route)) {
            $name = $route->getName();
            $passthrough = ['get.index', 'get.setup', 'post.setup', 'get.profile'];
        }
        if (isset($name, $passthrough) && !in_array($name, $passthrough)) {
            try {
                $response = $next($request, $response);
                // Check if ajax request
                if (detectAjax($request)) {
                    $response = $response
                    ->withStatus($response_code)
                    ->withHeader("Content-Type", $response_content)
                    ->write($response_body);
                } else {
                    $response = $response
                    ->withStatus(302)
                    ->withHeader("Location", $baseurl . $response_url);
                }
            } catch (Exception $e) {
                if (detectAjax($request)) {
                    $response = $response
                    ->withStatus(400)
                    ->withHeader("Content-Type", "application/json")
                    ->write(json_encode(array(
                        "success" => false,
                        "description" => $e->getMessage()
                    )));
                } else {
                    $_SESSION["errors"][] = $e->getMessage();
                    
                    $referer = $request->getHeader("Referer");
                    if (is_array($referer) && !empty($referer)) {
                        $referer = $referer[0];
                    } else {
                        $referer = '/';
                    }
                    $response = $response
                    ->withStatus(302)
                    ->withHeader("Location", $referer);
                }
            } finally {
                return $response;
            }
        } else {
            $response = $next($request, $response);
            return $response;
        }
    });
    
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
                $_SESSION["user"] = $user;
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
        // GET
        require_once('route/get/get.index.php');
        require_once('route/get/get.logout.php');
        require_once('route/get/get.reservation.all.php');
        require_once('route/get/get.user.all.php');
        require_once('route/get/get.store.php');
        require_once('route/get/get.update.php');
        
        if ($_SESSION["logged"] === true) {
            require_once('route/get/get.profile.php');
        }
        
        // POST
        require_once('route/post/post.login.php');
        require_once('route/post/post.register.php');
        require_once('route/post/post.reservation.php');
        require_once('route/post/post.store.php');
        
        // PUT
        require_once('route/put/put.profile.php');
        
        // DELETE
        require_once('route/delete/delete.reservation.php');
    }
    
    
    /* Running app */
    $app->run();
?>