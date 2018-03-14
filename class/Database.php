<?php
    class Database extends DBWrapper {
        
        /**
         * Array to store the table names for easy reference
         * @access protected
         */
        protected $tablenames = array(
            "category" => "",
            "company" => "",
            "dish" => "",
            "dish_ingredient" => "",
            "employee" => "",
            "ingredient" => "",
            "options" => "",
            "orders" => "",
            "reservation" => "",
            "store" => "",
            "store_dish" => "",
            "tables" => "",
            "user" => ""
        );
        
        
        /**
         * Constructor
         * @param options Array of key->value
         */
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
            
            foreach ($this->tablenames as $name => $value) {
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


        /**
         * 
         */
        public function getOptions () {
            $this->prepare(
                "options",
                "SELECT *
                FROM {$this->tablenames['options']};"
            );
            return $this->execute("options");
        }
    }
?>