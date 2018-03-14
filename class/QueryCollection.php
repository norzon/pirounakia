<?php
    class QueryCollection {

        /**
         * 
         */
        private $collection = [];


        /**
         * 
         */
        public function __construct () {}


        /**
         * 
         */
        public function add (Query $query) {
            $this->collection[$query->getId()] = $query->getStmt();
        }

        /**
         * Returns a specific query by id
         * 
         */
        public function get ($id = null) {
            if ($id == null) {
                return $this->collection;
            }else if (isset($this->collection[$id])) {
                return $this->collection[$id];
            }
            return null;
        }


        public function remove ($id) {
            if ($id == null) {
                $this->collection = [];
            } else if (isset($this->collection[$id])) {
                unset($this->collection[$id]);
            }
        }

    }
?>