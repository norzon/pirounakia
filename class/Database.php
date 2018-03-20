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
         * Gets the options from the DB
         * @access public
         */
        public function getOptions () {
            $this->prepare(
                "get.options",
                "SELECT *
                FROM {$this->tablenames['options']};"
            );
            return $this->execute("options");
        }


        /**
         * Get option by alias
         * @access public
         */
        public function getOption ($str) {
            $this->prepare(
                "get.option",
                "SELECT *
                FROM {$this->tablenames['options']}
                WHERE `alias` LIKE '%{$str}%';"
            );
            return $this->execute("options");
        }


        /**
         * Get all users
         * @access public
         */
        public function getUsers () {
            $this->prepare(
                "get.users",
                "SELECT *
                FROM {$this->tablenames['user']};"
            );
            return $this->execute("get.users");
        }


        /**
         * Get user by id
         * @access public
         * @param id The user's id
         */
        public function getUserById ($id) {
            $this->prepare(
                "get.user",
                "SELECT *
                FROM {$this->tablenames['user']}
                WHERE `id` = '$id';"
            );
            return $this->execute("get.user");
        }


        /**
         * Get user by email
         * @access public
         * @param email The user's email
         */
        public function getUserByEmail ($email) {
            $this->prepare(
                "get.user",
                "SELECT *
                FROM {$this->tablenames['user']}
                WHERE `email` = '$email';"
            );
            return $this->execute("get.user");
        }


        /**
         * Get user by token
         * @access public
         * @param token The user's token
         */
        public function getUserByToken ($token) {
            $this->prepare(
                "get.user",
                "SELECT *
                FROM {$this->tablenames['user']}
                WHERE `token` = '$token';"
            );
            return $this->execute("get.user");
        }
    }
?>