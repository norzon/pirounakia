<?php
    function dataDefault (&$input, $default) {
        return isset($input) ? $input : $default;
    }
?>