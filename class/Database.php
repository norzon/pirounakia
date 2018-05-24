<?php
    class Database extends DBWrapper {
        
        /**
         * Array to store the table names for easy reference
         * @access protected
         */
        protected $tablenames = array(
            "reservation" => "",
            "store_days" => "",
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
         * Prepare to get all store days
         * @access public
         */
        public function prepareGetStoreDays () {
            $this->prepare(
                "get.days",
                "SELECT *
                FROM `{$this->tablenames['store_days']}`;"
            );
        }

        /**
         * Get all store days
         * @access public
         */
        public function getStoreDays () {
            return $this->execute("get.days");
        }

        /**
         * Prepare to get a store day
         * @access public
         */
        public function prepareGetStoreDay () {
            $this->prepare(
                "get.day",
                "SELECT *
                FROM `{$this->tablenames['store_days']}`
                WHERE `day` = :day;"
            );
        }

        /**
         * Get a store day
         * @access public
         */
        public function getStoreDay ($day) {
            return $this->execute("get.day", array(":day" => $day));
        }

        /**
         * Prepare to get all users
         * @access public
         */
        public function prepareGetUsers () {
            $this->prepare(
                "get.users",
                "SELECT *
                FROM `{$this->tablenames['user']}`;"
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
                FROM `{$this->tablenames['user']}`
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
                FROM `{$this->tablenames['user']}`
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
         * Prepare to get reservations
         * @access public
         */
        public function prepareGetReservations () {
            $this->prepare(
                "get.reservation",
                "SELECT *
                FROM `{$this->tablenames['reservation']}`
                WHERE `res_date` > CURRENT_DATE;"
            );
        }

        /**
         * Get user by token
         * @access public
         * @param token The user's token
         */
        public function getReservations () {
            return $this->execute("get.reservation");
        }
        
        /**
         * Prepare to get reservations for a user
         * @access public
         */
        public function prepareGetUserReservations () {
            $this->prepare(
                "get.reservation",
                "SELECT *
                FROM `{$this->tablenames['reservation']}`
                WHERE `user_id` = :uid
                ORDER BY `date`, `time` DESC;"
            );
        }

        /**
         * Get user by id
         * @access public
         * @param uid The user's id
         */
        public function getUserReservations ($uid) {
            return $this->execute("get.reservation", array(":uid" => $uid));
        }
        
        
        /**
         * Prepare to get availability for a reservation
         * @access public
         */
        public function prepareGetAvailability () {
            $this->prepare(
                "get.availability",
                "SELECT *
                FROM `{$this->tablenames['reservation']}`
                WHERE `res_date` = :date
                AND `res_time` > :time_start
                AND `res_time` < :time_end;"
            );
        }

        /**
         * Get user by id
         * @access public
         * @param uid The user's id
         */
        public function getAvailability ($arr) {
            return $this->execute("get.availability", $this->transformKeys($arr));
        }



        /*
        |----------------------------------------------------------------
        |
        |   Database inserts below
        |
        |----------------------------------------------------------------
        */

        /**
         * Prepare to insert a new user
         */
        public function prepareInsertUser ($columns) {
            $col = join($columns, ", ");
            $val = join($this->transformKeys($columns), ", ");
            $this->prepare(
                "insert.user",
                "INSERT INTO `{$this->tablenames['user']}` ($col)
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
        
        
        /**
         * Prepare to insert a new reservation
         */
        public function prepareInsertReservation ($columns) {
            $col = join($columns, ", ");
            $val = join($this->transformKeys($columns), ", ");
            $this->prepare(
                "insert.reservation",
                "INSERT INTO `{$this->tablenames['reservation']}` ($col)
                VALUES ($val)"
            );
        }


        /**
         * Insert new reservation
         * @access public
         * @param data An array of key->value pairs
         */
        public function insertReservation ($data) {
            $data = $this->transformData($data);
            return $this->execute("insert.reservation", $data);
        }
        
        
        /*
        |----------------------------------------------------------------
        |
        |   Database updates below
        |
        |----------------------------------------------------------------
        */
        
        /**
         * Prepare to update a new user
         */
        public function prepareUpdateUser ($columns) {
            $values = $this->transformKeys($columns);
            $str = [];
            for ($i=0; $i < count($columns); $i++) { 
                $str[] = "`${columns[$i]}`={$values[$i]}";
            }
            $str = join($str, ", ");
            $this->prepare(
                "update.user",
                "UPDATE `{$this->tablenames['user']}` SET $str
                WHERE `id`=:uid"
            );
        }


        /**
         * Update new user
         * @access public
         * @param data An array of key->value pairs
         */
        public function updateUser ($uid, $data) {
            $data = $this->transformData($data);
            $data[":uid"] = $uid;
            return $this->execute("update.user", $data);
        }
    }
?>