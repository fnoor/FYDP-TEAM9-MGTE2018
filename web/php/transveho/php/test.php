<?php

error_reporting(E_ALL ^ E_NOTICE);

include("my_connect.php");

$mysqli = get_mysqli_conn();

/*$sql1 = "INSERT INTO Person (pname, paddress, email, age, gender) VALUES('Aniqa Pathan', '315 King St N', 'ampathan@uwaterloo.ca', ?, 'F');";

$sql2 = "INSERT INTO Person (pname, paddress, email, age, gender) VALUES('John Cena', '333 King St N', 'youcantseeme@uwaterloo.ca', ?, 'M');";

$sql3 = "INSERT INTO Person (pname, paddress, email, age, gender) VALUES('Prakhar Adhikary', '333 King St N', 'padhikar@uwaterloo.ca', ?, 'M');";*/

$age = 20;
$age2 = 39;
/*
$stmt = $mysqli->prepare($sql1);
$stmt->bind_param('i', $age);
$stmt->execute();

$stmt = $mysqli->prepare($sql2);
$stmt->bind_param('i', $age2);
$stmt->execute();

$stmt = $mysqli->prepare($sql3);
$stmt->bind_param('i', $age);
$stmt->execute();*/

$sql = "SELECT p.pname, p.paddress FROM Person p WHERE p.age <= ?;";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $age);
$stmt->execute();

$pname;
$padd;
$stmt->bind_result($pname, $padd);

echo "<ul>";
while ($stmt->fetch()) {
    echo '<li>'. $pname .', '. $padd.'</li>';
}
echo "</ul>";

$stmt->close();
$mysqli->close();


?>