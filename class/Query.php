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
         * Sets the id and the prepared statement
         */
        public function __construct ($id, $stmt) {
            $this->id = $id;
            $this->stmt = $stmt;
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
    }
?>