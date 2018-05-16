<?php
    function dataCheck (&$input, $error_message, $extra = "") {
        $throw_error = !isset($input);

        if (!is_array($extra))
            $extra = array($extra);
        
        foreach ($extra as $value) {
            if ($value == "array")
                $throw_error |= !is_array($input);
            else if ($value == "empty")
                $throw_error |= empty($input);
        }

        if ($throw_error)
            throw new Exception($error_message);
        else
            return $input;
    }
?>