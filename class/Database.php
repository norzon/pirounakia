<?php
    class Database {
        
        /**
         * DB Domain name
         * @access private 
         */
        private $domain;
        
        
        /**
         * DB user name
         * @access private
         */
        private $username;
        
        
        /**
         * DB user password
         * @access private
         */
        private $password;
        
        
        /**
         * DB database name
         * @access private
         */
        private $database;
        
        
        /**
         * The PDO object connector
         * @access private
         */
        private $conn;
        
        
        /**
         * Valid db connection
         * @access private
         */
        private $valid;
        
        
        /**
         * Last connection status
         * @access public
         */
        public $conn_status;
        
        
        /**
         * Array to store action logs
         * @access public
         */
        public $log = array();
        
        
        /**
         * Constructor
         * @access public
         * @param domain The domain/url of the database
         * @param name The DB user name
         * @param pass The DB user password
         * @param database The DB database name
         */
        public function __construct ($domain, $username, $password, $database = '') {
            $this->domain = $domain;
            $this->username = $username;
            $this->password = $password;
            $this->database = $database;
            
            $str = "mysql:host=$domain";
            if (!empty($database)) {
                $str .= ";dbname=$database";
            }
            
            // Try to connect
            try {
                $db = new PDO($str, $username, $password);
                if (!$this->hasPDOError($db)) {
                    $this->conn = $db;
                    $this->setConnStatus(true, "Connected successfully");
                    $this->log[] = "Connected to the database with user '$username'@'$domain'";
                } else {
                    $error = $this->parsePDOError();
                    $this->setConnStatus(false, $error);
                    $this->log[] = "Error connecting to $domain with $username. $error";
                }
            } catch (PDOException $e) {
                $msg = $e->getMessage();
                $this->setConnStatus(false, $msg);
                $this->log[] = "Error connecting to $domain with $username. $msg";
            } finally {
                $db = null;
            }
        }
        
        
        /**
         * Sets the connection status of the db
         * @access private
         * @param valid If the status is ok or not
         * @param status The explanation to the validity
         */
        private function setConnStatus ($valid, $status) {
            $this->valid = $valid;
            $this->conn_status = $status;
        }
        
        
        /**
         * Return the connection status as an array
         * @access public
         * @return Array key->value
         *  @property valid Type boolean
         *  @property status Type string
         */
        public function getConnStatus () {
            return array(
                "valid" => $this->valid,
                "status" => $this->conn_status
            );
        }
        
        
        /**
         * Gets the last error
         * @access public
         */
        public function getLastLog () {
            return end($this->log);
        }
        
        
        /**
         * Gets the PDO error and resturns it as string
         * @param conn The PDO connector object
         */
        private function parsePDOError($conn = null) {
            $db = (isset($conn) && !empty($conn) && is_object($conn)) ? $conn : $this->conn;
            $error = $db->errorInfo();
            return "$error[0][$error[1]]: $error[2]";
        }
        
        
        /**
         * Tries to find if there is a PDO error
         * @return boolean
         */
        private function hasPDOError ($conn = null) {
            $db = (isset($conn) && !empty($conn) && is_object($conn)) ? $conn : $this->conn;
            $error = $db->errorInfo();
            return (empty($error[1]) && empty($error[2]) && (empty($error[0]) || $error[0] == 0)) ? false : true;
        }
        
        
        /**
         * Generic wrapper for the PDO "exec" function
         * @param str The string to execute
         */
        public function exec($str) {
            $result;
            try {
                $db = $this->conn;
                $db->exec($str);
                if (!$this->hasPDOError()) {
                    $result = true;
                    $this->log[] = "Succesful: '$str'";
                } else {
                    $error = $this->parsePDOError();
                    $result = false;
                    $this->log[] = "Failed: '$str'";
                }
            } catch (PDOException $e) {
                $result = false;
                $this->log[] = "Exception: '" . $e->getMessage() . "'";
            } finally {
                return $result;
            }
        }
    }
?>