<?php
    if(!isset($app)) die();
    $app->post("/setup", function($request, $response){
        try {
            $data = $request->getParsedBody();
            
            dataCheck($data["db"], "Database options not given", "array");
            $domain = dataDefault($data["db"]["domain"], "localhost");
            $port = dataDefault($data["db"]["port"], "");
            $username = dataDefault($data["db"]["username"], "root");
            $password = dataDefault($data["db"]["password"], "");
            $database = dataDefault($data["db"]["database"], "pirounakia");
            $prefix = dataDefault($data["db"]["prefix"], "");

            $setup = new Setup(array(
                "domain" => $domain,
                "port" => $port,
                "username" => $username,
                "password" => $password,
                "database" => $database,
                "prefix" => $prefix
            ));

            $config = fopen("config.php", "w") or die("Unable to open file!");
            $str = <<<EOF
<?php
    return array(
        "db" => array(
            "domain" => "$domain",
            "port" => "$port",
            "username" => "$username",
            "password" => "$password",
            "database" => "$database",
            "prefix" => "$prefix"
        )
    );
?>
EOF;
            fwrite($config, $str);
            fclose($config);

            if (is_file("config.php")) {

            } else {
                return $response
                ->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode(array(
                    "success" => "false",
                    "description" => "Could not find file, so please create it",
                    "file" => array(
                        "name" => "config.php",
                        "data" => "$str"
                    )
                )));
            }
        } catch (Exception $e) {
            return $response
            ->withStatus(400) // Check error code
            ->withHeader("Content-Type", "application/json")
            ->write(json_encode(array(
                "success" => false,
                "description" => $e->error()
            )));
        }
    });
?>