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
  $month = $_POST['monthSearch'];

  /*Business Insight 5: Top 2 Cheapest Lodgings This Month By City*/
  $sql = "SELECT L.lname, L.lcost, L.laddress 
          FROM log_travelid L JOIN travels T ON L.travelid = T.travelid 
          WHERE T.city = ? AND month(T.from_date) = ? 
          ORDER BY L.lcost ASC LIMIT 2 ";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("si", $city, $month);
  $stmt->execute();

  $name; $address; $costs;
  $stmt->bind_result($name, $cost, $address);

  while ($stmt->fetch()) {
    $results .= "<div class='div-table-row'>";
    $results .= "<div class='div-table-col'>".$name."</div>";
    $results .= "<div class='div-table-col'>".$address."</div>";
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
  <link rel="stylesheet" type="text/css" href="search5.css">
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
        <h3><strong>Search 5: Top 2 Cheapest Lodgings This Month By City</strong></h3>
    <form method="POST" action="search5.php">

      <label class="desc" id="title106" for="Field106">City</label>
      <div>
        <select id="Field106" name="citySearch" class="field select medium" tabindex="11"> 
          <?php echo $cityMenu; ?>
        </select>
      </div>
      
      <label class="desc" id="title106" for="Field106">Month</label>
      <div>
        <select id="Field106" name="monthSearch" class="field select medium" tabindex="11"> 
            <option value="1">January</option>
            <option value="2">February</option>
            <option value="3">March</option>
            <option value="4">April</option>
            <option value="5">May</option>
            <option value="6">June</option>
            <option value="7">July</option>
            <option value="8">August</option>
            <option value="9">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
        </select>
      </div>    
     
      <div>
          <div>
    		    <input id="saveForm" name="saveForm" type="submit" value="Search">
          </div>
      </div>
    </form>
	</section>

  <section>
    <div class="div-table">
      <div class="div-table-row">
        <div class="div-table-col"><strong>NAME</strong></div>
        <div  class="div-table-col"><strong>ADDRESS</strong></div>
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