<?php
    class Query {

        /**
         * The unique id of the query
         * @access private
         */
        private $id;


        /**
         * The prepared statement
         * @access private
         */
        private $stmt;
        
        
        /**
         * The operation type (select, update ...)
         * @access private
         */
        private $operation;


        /**
         * Sets the id and the prepared statement
         */
        public function __construct ($id, $stmt, $operation) {
            $this->id = $id;
            $this->stmt = $stmt;
            $this->operation = $operation;
        }


        /**
         * Getter for the id
         * @return id
         */
        public function getId () {
            return $this->id;
        }


        /**
         * Returns the prepared statement
         * @return stmt
         */
        public function getStmt () {
            return $this->stmt;
        }


        /**
         * Returns the operation type
         * @return operation
         */
        public function getOperation () {
            return $this->opertaion;
        }
    }
?>