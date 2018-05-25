<?php
    $app->get("/update", function($request, $response){
        global $db, $baseurl, $response_body, $response_url;
        
        if ($_SESSION["admin"] === false) {
            throw new Exception("You do not have access");
        }
        
        function execInBackground($cmd) {
            if (substr(php_uname(), 0, 7) == "Windows"){
                pclose(popen("start /B ". $cmd, "r")); 
            }
            else {
                exec($cmd . " > /dev/null &");  
            }
        } 
        
        $response_body = "Attempting git pull";
        $response_url = "/";
        
        execInBackground("git pull");
        
        
    })->setName('get.update');
?>