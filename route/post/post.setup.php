<?php
    if(!isset($app)) die();
    $app->post("/setup", function($request, $response){
        try {
            $data = $request->getParsedBody();
            
            dataCheck($data["db"], "Database options not given", "array");
            $domain = dataCheck($data["db"]["domain"], "Database domain is not set");
            $port = dataCheck($data["db"]["port"], "Database port is not set");
            $username = dataCheck($data["db"]["username"], "Database username is not set");
            $password = dataCheck($data["db"]["password"], "Database password is not set");
            $database = dataCheck($data["db"]["database"], "Database name is not set");
            $prefix = dataCheck($data["db"]["prefix"], "Table prefix is not set");

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