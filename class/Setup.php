<?php
    require_once('./class/Database.php');
    
    class Setup {
        
        /**
         * Object to contain the Database class
         * @access private
         */
        private $db;
        
        
        /**
         * Options array, given at class initialization
         * @access private
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
            
            if ($options["db"]["create_datbase"]) {
                if (!$this->createDB($database))
                    throw new Exception($this->customError("Failed to create the Database"));
            }
            
            $this->createTables($options["db"]["table_name_prefix"]);
        }
        
        
        /**
         * A function to quickly get the last db error along
         * with a custom message
         * @param msg The message to be added to the returned string 
         */
        private function customError ($msg) {
            return "$msg. " . $this->db->getLastLog();
        }
        
        
        /**
         * The function which includes the SQL statement to create the database
         * @param dbname The database name
         */
        private function createDB ($dbname) {
            return $this->db->exec("CREATE DATABASE `$dbname` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;");
        }
        
        
        /**
         * Includes the flow of the table creation
         * @param prefix The table name prefix if any
         */
        private function createTables ($prefix = "") {
            if (!$this->createTableCompany($prefix))
                throw new Exception("Failed to create table 'company'. " . $this->db->getLastLog());
        }
        
        
        /**
         * Creates the table 'Company'
         * @param prefix
         */
        private function createTableCompany ($prefix) {
            return $this->db->exec(
                "CREATE TABLE `" . $prefix . "company` (
                    `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `name` mediumtext COLLATE utf8_bin NOT NULL
                );"
            );
        }
        
        
        /**
         * Creates the table 'Store'
         * @param prefix
         */
        private function createTableStore ($prefix) {
            return $this->db->exec(
                "CREATE TABLE `" . $prefix . "store` (
                    `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `cpid` int(11) NOT NULL,
                    `name` text COLLATE utf8_bin NOT NULL,
                    `location` mediumtext COLLATE utf8_bin NOT NULL,
                    `latitude` double NOT NULL,
                    `longitude` double NOT NULL,
                    `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                );
                ALTER TABLE `store`
                    ADD KEY `cpid` (`cpid`),
                    ADD CONSTRAINT `store_cpid` FOREIGN KEY (`cpid`) REFERENCES `company` (`id`);
                "
            );
        }
    }
?>