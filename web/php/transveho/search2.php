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

  /*Business Insight 2: Most Popular Attractions Among Gender Groups*/
  $sql = "SELECT A1.aname, COUNT(*) as NumberOfVisits, 
          COUNT(CASE WHEN P.gender='M' then 1 end) as Male_Count, 
          COUNT(CASE WHEN P.gender='F' then 1 end) as Female_Count 
          FROM attr_travelid A , Attractions A1, person_travelid P2, Person P 
          WHERE A1.city = ? AND A.Aid = A1.Aid AND A.travelid = P2.travelid AND P.pid = P2.pid GROUP BY A1.Aid HAVING COUNT(*) = (
              SELECT MAX(c) 
              FROM (
                  SELECT COUNT(*) as C 
                  FROM attr_travelid A2 , Attractions A3 
                  WHERE A3.city = ? AND A2.Aid = A3.Aid GROUP BY A2.Aid) AS TEMP)";

  $stmt = $db->prepare($sql);
  $stmt->bind_param("ss", $city, $city);
  $stmt->execute();

  $name; $m; $f; $vistis;
  $stmt->bind_result($name, $visits, $m, $f);

  while ($stmt->fetch()) {
    $results .= "<div class='div-table-row'>";
    $results .= "<div class='div-table-col'>".$name."</div>";
    $results .= "<div class='div-table-col'>".$visits."</div>";
    $results .= "<div class='div-table-col'>".$m."</div>";
    $results .= "<div class='div-table-col'>".$f."</div>";
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
  <link rel="stylesheet" type="text/css" href="search2.css">
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
        <h3><strong>Business Insight 2: Most Popular Attractions Among Gender Groups</strong></h3>
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
  </section>
  
   <section>
    <div class="div-table">
      <div class="div-table-row">
        <div class="div-table-col"><strong>ATTRACTION</strong></div>
        <div class="div-table-col"><strong>NUMBER OF VISITS</strong></div>
        <div  class="div-table-col"><strong>MALE</strong></div>
        <div  class="div-table-col"><strong>FEMALE</strong></div>
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