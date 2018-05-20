<?php
    function detectAjax ($request) {
        if (
            $request->hasHeader('X-Requested-With') &&
            strtolower($request->getHeader('X-Requested-With')[0]) === "xmlhttprequest"
        ) {
            return true;
        }

        if (
            $request->hasHeader('x-requested-with') &&
            strtolower($request->getHeader('x-requested-with')[0]) === "xmlhttprequest"
        ) {
            return true;
        }
        
        return false;
    }
?>