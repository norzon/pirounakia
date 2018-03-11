<?php
    require_once("./function/schema.php");
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
        public function __construct ($options = []) {
            if (empty($options))
            throw new Exception("Empty paramater 'options'");
            
            if (!is_array($options))
            throw new Exception("Parameter 'options' must be array of key value pairs");
            
            // Set default values if none given. This is wrong for production,
            // but it works for a demo project.
            dataCheck($options["domain"], "Database domain is not set");
            dataCheck($options["port"], "Database port is not set");
            dataCheck($options["username"], "Database username is not set");
            dataCheck($options["password"], "Database password is not set");
            dataCheck($options["database"], "Database name is not set");
            dataCheck($options["prefix"], "Database table prefix is not set");
            
            
            // After all checks, save options array
            $this->options = $options;
            
            // If db options is ok, initialize database class
            parent::__construct($options);
            
            $this->createSchema();
        }


        /**
         * A function to quickly get the last db error along
         * with a custom message
         * @param msg The message to be added to the returned string 
         */
        private function customError ($bool, $msg) {
            if ($bool)
                throw new Exception("$msg. " . $this->db->getLastLog());
        }
        
        
        /**
         * Includes the flow of the table creation
         * @param prefix The table name prefix if any
         */
        public function createSchema () {
            $this->exec(schema($this->options["prefix"]), "Create Database Tables and Data");
        }
    }
?>