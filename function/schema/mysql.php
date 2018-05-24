<?php
    function schemaMysql ($prefix) {
        return <<<EOF

CREATE TABLE {$prefix}user (
	id INT(11) AUTO_INCREMENT,
	email VARCHAR(255) NOT NULL,
	password VARCHAR(1000) NOT NULL,
	firstname VARCHAR(100) DEFAULT NULL,
	lastname VARCHAR(100) DEFAULT NULL,
	is_admin TINYINT(1) DEFAULT 0 NOT NULL,
	updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (id),
	UNIQUE (email)
);

CREATE TABLE {$prefix}store_days (
    id INT(11) UNSIGNED AUTO_INCREMENT,
	day ENUM ('MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN') NOT NULL,
    open_time TIME NOT NULL,
    close_time TIME NOT NULL,
    tables SMALLINT UNSIGNED NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE {$prefix}reservation (
    id INT(11) UNSIGNED AUTO_INCREMENT,
    user_id INT(11) UNSIGNED REFERENCES {$prefix}user (id),
    people TINYINT UNSIGNED NOT NULL,
    res_date DATE NOT NULL,
    res_time TIME NOT NULL,
    status VARCHAR(50),
    comments VARCHAR(1000),
    PRIMARY KEY (id)
);
        
EOF;
    }
?>