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
         * Transforms the array keys to include ':' character at the
         * beggining of the key
         */
        private function transformData ($data) {
            $new_data = [];
            foreach ($data as $key => $value) {
                if ($key[0] !== ':')
                $new_data[":$key"] = $value;
            }
            return $new_data;
        }
        
        
        private function transformKeys ($data) {
            $new_data = [];
            for ($i=0; $i < count($data); $i++) { 
                $new_data[] = ":{$data[$i]}";
            }
            return $new_data;
        }


        /*
        |----------------------------------------------------------------
        |
        |   Database getters below
        |
        |----------------------------------------------------------------
        */


        /**
         * Prepares to get all options
         * @access public
         */
        public function prepareGetOptions () {
            $this->prepare(
                "get.options",
                "SELECT *
                FROM `{$this->tablenames['options']}`;"
            );
        }

        /**
         * Gets the options from the DB
         * @access public
         */
        public function getOptions () {
            return $this->execute("get.options");
        }


        /**
         * Prepares to get option by some alias
         */
        public function prepareGetOption () {
            $this->prepare(
                "get.option",
                "SELECT *
                FROM `{$this->tablenames['options']}`
                WHERE `alias` LIKE :option;"
            );
        }

        /**
         * Get option by alias
         * @access public
         * @param str The string to search by
         */
        public function getOption ($str) {
            return $this->execute("get.option", array(":option" => "%$str%"));
        }


        /**
         * Prepare to get all users
         * @access public
         */
        public function prepareGetUsers () {
            $this->prepare(
                "get.users",
                "SELECT *
                FROM {$this->tablenames['user']};"
            );
        }

        /**
         * Get all users
         * @access public
         */
        public function getUsers () {
            return $this->execute("get.users");
        }


        /**
         * Prepare to get a user by id
         * @access public
         */
        public function prepareGetUserById () {
            $this->prepare(
                "get.user",
                "SELECT *
                FROM {$this->tablenames['user']}
                WHERE `id` = :id;"
            );
        }

        /**
         * Get user by id
         * @access public
         * @param id The user's id
         */
        public function getUserById ($id) {
            return $this->execute("get.user", array(":id" => $id));
        }


        /**
         * Prepare to get user by email
         */
        public function prepareGetUserByEmail () {
            $this->prepare(
                "get.user",
                "SELECT *
                FROM {$this->tablenames['user']}
                WHERE `email` = :email;"
            );
        }

        /**
         * Get user by email
         * @access public
         * @param email The user's email
         */
        public function getUserByEmail ($email) {
            return $this->execute("get.user", array(":email" => $email));
        }


        /**
         * Prepare to get user by token
         * @access public
         */
        public function prepareGetUserByToken () {
            $this->prepare(
                "get.user",
                "SELECT *
                FROM {$this->tablenames['user']}
                WHERE `token` = :token;"
            );
        }

        /**
         * Get user by token
         * @access public
         * @param token The user's token
         */
        public function getUserByToken ($token) {
            return $this->execute("get.user", array(":token" => $token));
        }



        /*
        |----------------------------------------------------------------
        |
        |   Database inserts below
        |
        |----------------------------------------------------------------
        */


        /**
         * Prepare to insert an option
         */
        public function prepareInsertOption () {
            $this->prepare(
                "insert.option",
                "INSERT INTO {$this->tablenames['options']} (`alias`, `value`)
                VALUES (:alias, :value)"
            );
        }


        /**
         * Insert the option by some alias and value
         * No check needed, since both 'alias' and 'value' must be given
         */
        public function insertOption ($data) {
            $data = $this->transformData($data);
            return $this->execute("insert.option", $data);
        }


        /**
         * Prepare to insert a new user
         */
        public function prepareInsertUser ($columns) {
            $col = join($columns, ", ");
            $val = join($this->transformKeys($columns), ", ");
            $this->prepare(
                "insert.user",
                "INSERT INTO {$this->tablenames['user']} ($col)
                VALUES ($val)"
            );
        }


        /**
         * Insert new user
         * @access public
         * @param data An array of key->value pairs
         */
        public function insertUser ($data) {
            $data = $this->transformData($data);
            return $this->execute("insert.user", $data);
        }
    }
?>