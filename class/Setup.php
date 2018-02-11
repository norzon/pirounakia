<?php
    require_once('./Database.php')
    class Setup {
        
        /**
         * Object to contain the Database class
         */
        private $db;
        
        
        /**
         * Options array, given at class initialization
         */
        private $options;
        
        
        /**
         * Constructor
         * @param options Array key->value
         */
        public function __contruct ($options = []) {
            if (empty($options))
                throw new Exception("Empty paramater 'options'");
            
            if (!is_array($options))
                throw new Exception("Parameter 'options' must be array of key value pairs");
                
            if (!is_array($options["db"]))
                throw new Exception("Parameter 'options' must contain 'db' array");
                
            if (
                !isset($options["db"]["domain"]) ||
                !isset($options["db"]["username"]) ||
                !isset($options["db"]["password"])
            )
                throw new Exception("Database parameters not in array 'options'");
                
            
            // After all checks, save options array
            $this->options = options;
            
            // If input is ok, initialize database class
            $domain = $options["db"]["domain"];
            $username = $options["db"]["username"];
            $password = $options["db"]["password"];
            $database = $options["db"]["database"];
            $this->db = new Database($domain, $username, $password, $database);
        }
        
        
        private function createDB ($dbname = $this->options["db"]["database"]) {
            $this-db->exec("CREATE DATABASE `test` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;");
        }
    }
?>