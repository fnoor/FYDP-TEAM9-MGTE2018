<?php
session_start();
?>

<html>
    <head>
        <title>Team Maker</title>
        <h2>Instructor Login</h2>
    </head>
    <body>

		<form action="instructor-login-validation.php" method="post">

        <!--Instructor ID-->
        <p>
          <label>Instructor ID: </label>
            <p>
              <input type="text" name="instructorID">
            </p>
        </p>
        <!--Term_ID-->
        <p>
            <label>Term ID: </label>
            <p>
                <select name="termID">
                  <option value="0117">Fall 2017</option>
                  <option value="0217">Winter 2017</option>
                  <option value="0317">Spring 2017</option>
                </select>
            </p>
        </p>
        <!--Login Button-->
        <input type="submit" value="Login" />
<?php
     if(isset($_SESSION["error"])){
         $error = $_SESSION["error"];
               echo "<span>$error</span>";
                   }
               ?>

</body>
</html>
<?php
unset($_SESSION["error"]);
?>
<a href="index.php">Back</a>
