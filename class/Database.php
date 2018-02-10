<?php
    class Database {
        
        /**
         * Domain name
         * @access private 
         */
        private $domain;
        
        
        /**
         * DB usern ame
         * @access private
         */
        private $username;
        
        
        /**
         * DB user password
         * @access private
         */
        private $password;
        
        
        /**
         * DB table name
         * @access private
         */
        private $table;
        
        
        /**
         * Constructor
         * @param domain The domain/url of the database
         * @param name The DB user name
         * @param pass The DB user password
         * @param table The DB table name
         */
        function __construct ($domain, $name, $pass, $table) {
            $this->domain = $domain;
            $this->username = $name;
            $this->password = $pass;
            $this->table = $table;
        }
        
    }
?>