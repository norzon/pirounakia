<?php
    function detectAjax ($request) {
        if (
            $request->hasHeader('X-Requested-With') &&
            strtolower($request->getHeader('X-Requested-With')) === "xmlhttprequest"
        ) {
            return true;
        }

        if (
            $request->hasHeader('x-requested-with') &&
            strtolower($request->getHeader('x-requested-with')) === "xmlhttprequest"
        ) {
            return true;
        }
        
        return false;
    }
?>