<?php
	require_once("./function/schema/mysql.php");
	function schema ($prefix, $dbtype = "mysql") {
		if ($dbtype == "mysql") {
			return schemaMysql($prefix);
		} else {
			die("Database Type not supported");
		}
    }
?>