<?php
    function dataCheck ($input, $error_message, $extra = "") {
        $throw_error = !isset($input);

        if ($extra == "array")
            $throw_error |= !is_array($input);
        else if ($extra == "empty")
            $throw_error |= empty($input);

        if ($throw_error)
            throw new Exception($error_message);
        else
            return $input;
    }
?>