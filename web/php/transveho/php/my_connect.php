<?php
function get_mysqli_conn() {
    $dbhost = 'localhost';
    $dbuser = 'ampathan';
    $dbpassword = 'Ap2509<3';
    $dbname = 'ampathan';
    $mysqli = new mysqli($dbhost, $dbuser, $dbpassword, $dbname);
    if ($mysqli->connect_errno) {
        echo 'Failed to connect to MySQL: (' . $mysqli->connect_errno . ')';
    }
    return $mysqli;
}

?>