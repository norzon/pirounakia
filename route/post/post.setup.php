<?php
    if(!isset($app)) die();
    $app->post("/", function($request, $response){
        try {
            $data = $request->getParsedBody();
            
            $domain = dataCheck($data["domain"], "Invalid domain", "empty");
            $port = dataDefault($data["port"], "");
            $username = dataCheck($data["username"], "Invalid username", "empty");
            $password = dataDefault($data["password"], "");
            $database = dataCheck($data["database"], "Invalid database", "empty");
            $prefix = dataDefault($data["prefix"], "");
            
            $setup = new Setup(array(
                "domain" => $domain,
                "port" => $port,
                "username" => $username,
                "password" => $password,
                "database" => $database,
                "prefix" => $prefix
            ));

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

            $config = fopen("config.php", "w");
            if ($config) {
                fwrite($config, $str);
                fclose($config);
                return $response
                ->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode(array(
                    "success" => true,
                    "description" => "Tables and config file created successfully"
                )));
            } else {
                return $response
                ->withStatus(200)
                ->withHeader("Content-Type", "application/json")
                ->write(json_encode(array(
                    "success" => false,
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
                "description" => $e->getMessage()
            )));
        }
    })->setName('post.setup');
?>