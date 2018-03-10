<?php
    class Setup extends Database {
        
        /**
         * Options array, given at class initialization
         * @access private
         */
        private $options;
   

        /**
         * Constructor
         * @param options Array key->value. Look at config-template
         */
        public function __contruct ($options = []) {
            if (empty($options))
                throw new Exception("Empty paramater 'options'");
            
            if (!is_array($options))
                throw new Exception("Parameter 'options' must be array of key value pairs");
                
            if (!is_array($options))
                throw new Exception("Parameter 'options' must contain 'db' array");
            
                
            // Set default values if none given. This is wrong for production,
            // but it works for a demo project.
            if (!isset($options["domain"]))
                $options["domain"] = "localhost";
                
            if (!isset($options["port"]))
                $options["port"] = "";
                
            if (!isset($options["username"]))
                $options["username"] = "root";
                
            if (!isset($options["password"]))
                $options["password"] = "";
                
            if (!isset($options["database"]))
                $options["database"] = "";
                
            if (!isset($options["prefix"]))
                $options["prefix"] = "";
            
            // After all checks, save options array
            $this->options = options;
            
            // If db options is ok, initialize database class
            parent::__construct($options);
            
            $this->createTables($options["table_name_prefix"]);
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
         * Includes the flow of the table creation
         * @param prefix The table name prefix if any
         */
        private function createTables ($prefix = "") {
            if (!$this->createTableCompany($prefix))
                throw new Exception("Failed to create table 'company'. " . $this->db->getLastLog());
            if (!$this->createTableStore($prefix))
                throw new Exception("Failed to create table 'store'. " . $this->db->getLastLog());
            if (!$this->createTableTable($prefix))
                throw new Exception("Failed to create table 'table'. " . $this->db->getLastLog());
        }
        
        
        /**
         * Creates the table 'Company'
         * @param prefix
         */
        private function createTableCompany () {
            return $this->db->exec(
                "CREATE TABLE `$this->table_company` (
                    `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `name` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL
                );"
            );
        }
        
        
        /**
         * Creates the table 'Store'
         * @param prefix
         */
        private function createTableStore () {
            $constraint = $this->table_store . "_cpid";
            return $this->db->exec(
                "CREATE TABLE `$this->table_store` (
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
                    ADD CONSTRAINT `$constraint` FOREIGN KEY (`cpid`) REFERENCES `$this->table_company` (`id`);
                "
            );
        }
        
        
        /**
         * Creates the table 'Table'
         * @param prefix
         */
        private function createTableTable() {
            $constraint = $this->table_table . "_stid";
            return $this->db->exec(
                "CREATE TABLE `$this->table_table` (
                    `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `stid` int(11) NOT NULL,
                    `number` varchar(255) COLLATE utf8_bin NOT NULL,
                    `seats` tinyint(3) UNSIGNED NOT NULL,
                    `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
                  );
                  ALTER TABLE `$tablename`
                    ADD KEY `stid` (`stid`),
                    ADD CONSTRAINT `$constraint` FOREIGN KEY (`stid`) REFERENCES `$this->table_store` (`id`) ON UPDATE NO ACTION;
                "
            );
        }
        
        
        /**
         * Create the table 'Dish'
         * @param prefix
         */
        private function createTableDish() {
            $constraint = $this->table_dish . "";
            return $this->db->exec();
        }

        
        /**
         * Creates the table 'Customer'
         * @param prefix
         */
        private function createTableCustomer() {
            $constraint = $this->table_customer . "";
            return $this->db->exec();
        }
    }
?>