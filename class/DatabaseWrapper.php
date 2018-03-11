<?php
    /**
     * Base class to manage basic prepared and executed SQL statements
     */
    class DBWrapper {
        
        /**
         * DB Domain name
         * @access private 
         */
        private $domain;
        
        
        /**
         * DB Port number
         * @access private
         */
        private $port;
        
        
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
        public function __construct ($options) {
            $this->domain = $options["domain"];
            $this->username = $options["username"];
            $this->password = $options["password"];
            $this->database = $options["database"];
            $this->port = $options["port"];
            
            $str = "mysql:host=$this->domain";

            if (!empty($this->port))
                $str .= ";port=$this->port";

            if (!empty($this->database))
                $str .= ";dbname=$this->database";
            
            // Try to connect
            try {
                $db = new PDO($str, $this->username, $this->password, array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ));
                if (!$this->hasPDOError($db)) {
                    $this->conn = $db;
                    $this->setConnStatus(true, "Connected successfully");
                    $this->log[] = "Connected to the database with user '$this->username'@'$this->domain'";
                } else {
                    $error = $this->parsePDOError();
                    $this->setConnStatus(false, $error);
                    $this->log[] = "Error connecting to $this->domain with $this->username. $error";
                }
            } catch (PDOException $e) {
                $msg = $e->getMessage();
                $this->setConnStatus(false, $msg);
                $this->log[] = "Error connecting to $this->domain with $this->username. $msg";
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
         * @access private
         * @param conn The PDO connector object
         */
        private function parsePDOError($conn = null) {
            $db = (isset($conn) && !empty($conn) && is_object($conn)) ? $conn : $this->conn;
            $error = $db->errorInfo();
            return "$error[0][$error[1]]: $error[2]";
        }
        
        
        /**
         * Tries to find if there is a PDO error
         * @access private
         * @return boolean
         */
        private function hasPDOError ($conn = null) {
            $db = (isset($conn) && !empty($conn) && is_object($conn)) ? $conn : $this->conn;
            $error = $db->errorInfo();
            return (empty($error[1]) && empty($error[2]) && (empty($error[0]) || $error[0] == 0)) ? false : true;
        }
        
        
        /**
         * Generic wrapper for the PDO "exec" function
         * @access protected
         * @param str The string to execute
         */
        protected function exec($str, $msg = null) {
            $result = false;
            $msg = dataDefault($msg, $str);
            try {
                $db = $this->conn;
                $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                $db->exec($str);
                var_dump($db->errorInfo());
                if (!$this->hasPDOError()) {
                    $result = true;
                    $this->log[] = "Successful: '$msg'";
                } else {
                    $error = $this->parsePDOError();
                    $result = false;
                    $this->log[] = "Failed: '$msg'";
                }
            } catch (PDOException $e) {
                $result = false;
                $this->log[] = "Exception: '" . $e->getMessage() . "'";
            } catch (Exception $e) {
                $result = false;
                $this->log[] = "Exception: '" . $e->getMessage() . "'";
            } finally {
                return $result;
            }
        }
    }
?>