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


        public function __construct ($id, $stmt) {
            $this->id = $id;
            $this->stmt = $stmt;
        }


        public function getId () {
            return $this->id;
        }

        public function getStmt () {
            return $this->stmt;
        }
    }
?>