<?php
    class Setup extends Database {
        
        /**
         * Options array, given at class initialization
         * @access private
         */
        private $options;
        
        
        /**
         * Temporary array to store the tablenames
         * @access private
         */
        private $tablenames;
        
        
        /**
         * Constructor
         * @param options Array key->value
         */
        
        // interface SetupOptions {
        //     db: {
        //         domain: string;
        //         port: number;
        //         username: string;
        //         password: string;
        //         database: string;
        //         prefix: string;
        //     }
        //     root: {
        //         username: string;
        //         password: string;
        //     }
        //     createDBUser: boolean;
        // }
         
        public function __contruct ($options = []) {
            if (empty($options))
                throw new Exception("Empty paramater 'options'");
            
            if (!is_array($options))
                throw new Exception("Parameter 'options' must be array of key value pairs");
                
            if (!is_array($options["db"]))
                throw new Exception("Parameter 'options' must contain 'db' array");
            
                
            // Set default values if none given. This is wrong for production,
            // but it works for a demo project.
            if (!isset($options["db"]["domain"]))
                $options["db"]["domain"] = "localhost";
                
            if (!isset($options["db"]["port"]))
                $options["db"]["port"] = "";
                
            if (!isset($options["db"]["username"]))
                $options["db"]["username"] = "root";
                
            if (!isset($options["db"]["password"]))
                $options["db"]["password"] = "";
                
            if (!isset($options["db"]["database"]))
                $options["db"]["database"] = "";
            
            // After all checks, save options array
            $this->options = options;
            
            // If db options is ok, initialize database class
            parent::__construct($options["db"]);
            
            
            if ($options["create_datbase"]) {
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
            $tablename = $prefix . "company";
            return $this->db->exec(
                "CREATE TABLE `$tablename` (
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
            $tablename = $prefix . "store";
            $constraint = $tablename . "_cpid";
            return $this->db->exec(
                "CREATE TABLE `$tablename` (
                    `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `cpid` int(11) NOT NULL,
                    `name` text COLLATE utf8_bin NOT NULL,
                    `location` mediumtext COLLATE utf8_bin NOT NULL,
                    `latitude` double NOT NULL,
                    `longitude` double NOT NULL,
                    `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                );
                ALTER TABLE `$tablename`
                    ADD KEY `cpid` (`cpid`),
                    ADD CONSTRAINT `$constraint` FOREIGN KEY (`cpid`) REFERENCES `company` (`id`);
                "
            );
        }
        
        
        /**
         * Creates the table 'Table'
         * @param prefix
         */
        private function createTableTable($prefix) {
            $tablename = $prefix . "store";
            $constraint = $tablename . "_stid";
            return $this->db->exec(
                "CREATE TABLE `$tablename` (
                    `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `stid` int(11) NOT NULL,
                    `number` varchar(255) COLLATE utf8_bin NOT NULL,
                    `seats` tinyint(3) UNSIGNED NOT NULL,
                    `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                  );
                  ALTER TABLE `$tablename`
                    ADD KEY `stid` (`stid`),
                    ADD CONSTRAINT `$constraint` FOREIGN KEY (`stid`) REFERENCES `store` (`id`) ON UPDATE NO ACTION;
                "
            );
        }
        
        
        /**
         * Create the table 'Dish'
         * @param prefix
         */
    }
?>