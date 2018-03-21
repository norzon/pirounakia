<?php
    /**
     * A collection of key->value pairs, where the key is a unique identifier
     * and the value is a PDO prepared statement
     */
    class QueryCollection {

        /**
         * An array to store the queries
         * @access private
         */
        private $collection = [];


        /**
         * Empty constructor as it is not used
         */
        public function __construct () {}


        /**
         * Adds a Query object to the collection
         * @param Query
         */
        public function add (Query $query) {
            $this->collection[$query->getId()] = $query;
        }


        /**
         * Returns a specific query by id
         * @param id If null, returns the collection
         */
        public function get ($id = null) {
            if ($id == null) {
                return $this->collection;
            }else if (isset($this->collection[$id])) {
                return $this->collection[$id];
            }
            return null;
        }


        /**
         * Removes a query by a specific id, or clears all if no id given
         * @param id
         */
        public function clear ($id) {
            if ($id == null) {
                $this->collection = [];
            } else if (isset($this->collection[$id])) {
                unset($this->collection[$id]);
            }
        }

    }
?>