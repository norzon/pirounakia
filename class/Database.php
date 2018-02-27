<?php
    class Database extends DBWrapper {
        
        /**
         * Array to store the table names for easy reference
         * @access private
         */
        private $tablenames = array(
            "company" => "",
            "store" => "",
            "table" => "",
            "dish" => "",
            "store_dish" => "",
            "reservation" => "",
            "order" => "",
            "reservation_order" => "",
            "customer" => "",
        );
        
        
        /**
         * Stores the prefix of a table name
         * Eg. 'company' can become 'My_custom_Prefix_company'
         * @access private
         */
        private $prefix;
        
        
        public function __construct($options) {
            $dbconfig = array();
            $prefix = isset($options["prefix"]) ? $options["prefix"] : "";
            
            if (isset($options["domain"]))
                $dbconfig["domain"] = $options["domain"];
                
            if (isset($options["port"]))
                $dbconfig["port"] = $options["port"];
                
            if (isset($options["username"]))
                $dbconfig["username"] = $options["username"];
                
            if (isset($options["password"]))
                $dbconfig["password"] = $options["password"];
                
            if (isset($options["database"]))
                $dbconfig["database"] = $options["database"];
                
            parent::__construct($dbconfig);
            
            foreach ($this->tablenames as $name) {
                $this->tablenames[$name] = $prefix . $name;
            }
        }
        
        
        /**
         * Getter for the tablenames
         * @access public
         */
        public function getTableNames () {
            return $this->tablenames;
        }
    }
?>