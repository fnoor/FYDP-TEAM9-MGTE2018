<?php 
include("php/my_connect.php");

$db = get_mysqli_conn();

$cityName;
$cityMenu = "";

/*city dropdown*/
$sql = "SELECT city FROM `Location`";
$stmt = $db->prepare($sql);
$stmt->execute();

$stmt->bind_result($cityName);

while ($stmt->fetch()) {
  $cityMenu .= "<option value='".$cityName."'>".$cityName."</option>";
}

$results = "";
if (isset($_POST['citySearch'])) {
  $city = $_POST['citySearch'];

  /*Bussiness Insight 1: Best Seasons to Lodge by City*/
  $sql = "SELECT month(T.from_date) AS MonthNumber, GROUP_CONCAT(L.lname), GROUP_CONCAT(L.lcost) 
          FROM log_travelid L JOIN travels T ON L.travelid = T.travelid 
          WHERE T.city = ? GROUP BY month(T.from_date) HAVING SUM(L.lcost) = (
               SELECT MIN(c) 
               FROM (
                     SELECT SUM(L1.lcost) AS c 
                     FROM log_travelid L1 JOIN travels T1 ON L1.travelid = T1.travelid                       
                     WHERE T1.city = ? GROUP BY month(T1.from_date)) as TEMP) ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ss", $city, $city);
  $stmt->execute();

  $month; $lodge; $cost;
  $stmt->bind_result($month, $lodge, $cost);

  while ($stmt->fetch()) {
    $results .= "<div class='div-table-row'>";
    $results .= "<div class='div-table-col'>".$month."</div>";
    $results .= "<div class='div-table-col'>".$lodge."</div>";
    $results .= "<div class='div-table-col'>".$cost."</div>";
    $results .= "</div>";
  }
}

$stmt->close();
$db->close();

?>
<!DOCTYPE html>
<html>
    
<head>
  <title>Transveho</title>
  <meta charset="utf-8"/>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" type="text/css" href="search1.css">
</head>
    
<body>
    
<header class="container">
    <div class="row">
      <h1 class="col-sm-10"><a href="home.html" ><strong>Transveho</strong> | Christopher Columbus</a></h1>
  
      <nav class="col-sm-8 text-right">
          <p><a href="createNew.php" ><strong>Create</strong></a></p>
          <p><a href="search1.php" ><strong> 1</strong></a></p>
          <p><a href="search2.php" ><strong> 2</strong></a></p>
          <p><a href="search3.php" ><strong> 3</strong></a></p>
          <p><a href="search4.php" ><strong> 4</strong></a></p>
          <p><a href="search5.php" ><strong> 5</strong></a></p>
   	 </nav>
 	 </div>
 </header>
    
	
    
	<section class="container"> 
        <h3><strong>Bussiness Insight 1: Best Seasons to Lodge by City</strong></h3>
         <div class="row">
            <div class="form">
              <form method="POST" action="">
                
        <label class="desc" id="title106" for="Field106">City</label>
            <div>
              <select id="Field106" name="citySearch" class="field select medium" tabindex="11"> 
                  <?php echo $cityMenu; ?>
              </select>
            </div>


            <div>
              <div>
                <input id="saveForm" name="saveForm" type="submit" value="Search">
              </div>
            </div>
          </form>
        </div>   
    </div>
	</section>
    
    
  <section>
      <div class="div-table">
        <div class="div-table-row">
          <div class="div-table-col"><strong>MONTH</strong></div>
          <div  class="div-table-col"><strong>LODGING</strong></div>
          <div  class="div-table-col"><strong>COST</strong></div>
        </div>
        <?php echo $results; ?>
      </div>
    </section>  
    
    
  <footer class="container">
  	<div class="row">
    	<p class="col-sm-4">&copy; 2016 AFS</p>
   	  <ul class="col-sm-8">
    		<li class="col-sm-1"><img src="https://s3.amazonaws.com/codecademy-content/projects/make-a-website/lesson-4/twitter.svg"></li>
 			  <li class="col-sm-1"><img src="https://s3.amazonaws.com/codecademy-content/projects/make-a-website/lesson-4/facebook.svg"></li>
  			<li class="col-sm-1"><img src="https://s3.amazonaws.com/codecademy-content/projects/make-a-website/lesson-4/instagram.svg"></li>
    	</ul>
  	</div>
 </footer>
</body>
</html>