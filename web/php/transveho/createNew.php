<?php 
include("php/my_connect.php");

$lodgingType;
$lodgingName;
$lodgingTypeMenu = "";

$db = get_mysqli_conn();

/*lodging type dropdown options*/
$sql = "SELECT DISTINCT ltype FROM `Lodging`";
$stmt = $db->prepare($sql);
$stmt->execute();

$stmt->bind_result($lodgingType);

while ($stmt->fetch()) {
  $lodgingTypeMenu .= "<option value='".$lodgingType."'>".$lodgingType."</option>";
}

$results = "";
if (isset($_POST['city'])) {
  $country = $_POST['country'];
  $city = $_POST['city'];
  $fromDate = $_POST['fromDate'];
  $toDate = $_POST['toDate'];
  $lodgingType = $_POST['lodgingType'];
  $lodgingName = $_POST['lodgingName'];
  $lodgingAddress = $_POST['lodgingAddress'];
  $lodgingCost = $_POST['lodgingCost'];
  $lodgingAmmenities = $_POST['lodgingAmmenities'];
  $lodgeRating = $_POST['lodgeRating'];
  $foodName = $_POST['foodName'];
  $foodType = $_POST['foodType'];
  $foodAddress = $_POST['foodAddress'];
  $foodCost = $_POST['foodCost'];
  $foodComment = $_POST['foodComment'];
  $attractionName = $_POST['attractionName'];
  $attractionAddress = $_POST['attractionAddress'];
  $attractionType = $_POST['attractionType'];
  $attractionCost = $_POST['attractionCost'];
  $duration = $_POST['duration'];
  $attractionRating = $_POST['attractionRating'];
  $attractionComment = $_POST['attractionComment'];
  $tripRating = $_POST['tripRating'];
  $tripComment = $_POST['tripComment'];

  /*Insert values about location*/
  $sql = "INSERT INTO Location (city, country) 
          VALUES (?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ss", $city, $country);
  $stmt->execute();

  $sql = "INSERT INTO `travels`(`city`, `country`, `from_date`, `to_date`) 
          VALUES (?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ssss", $city, $country, $fromDate, $toDate);
  $stmt->execute();

  $travelID = $stmt->insert_id;

  /*Insert values about lodging*/
  $sql = "INSERT INTO Lodging (lname, ltype)  
          VALUES (?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ss", $lodgingName, $lodgingType);
  $stmt->execute();

  $nofamenities = explode(",", $lodgingAmmenities); /*to count the number of ammeneties*/
  $sql = "INSERT INTO log_travelid (travelid, lname, lcost, laddress, lamenity, lstars, nofamenities) 
          VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("isdssii", $travelID, $lodgingName, $lodgingCost, $lodgingAddress, $lodgingAmmenities, $lodgeRating, count($nofamenities));
  $stmt->execute();

  /*Insert values about food*/
  $sql = "INSERT INTO food (fname, ftype)  
          VALUES (?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ss", $foodName, $foodType);
  $stmt->execute();

  $sql = "INSERT INTO food_travelid (travelid, fname, faddress, fcost, fcomment)  
          VALUES (?, ?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("issds", $travelID, $foodName, $foodAddress, $foodCost, $foodComment);
  $stmt->execute();

  /*Insert values about attractions*/
  $sql = "INSERT INTO Attractions (Aname, Aaddress, city, country)  
          VALUES (?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("ssss", $attractionName, $attractionAddress, $city, $country);
  $stmt->execute();

  $Aid = $stmt->insert_id;

  $sql = "INSERT INTO attr_travelid (travelid, Aid, acomment, atype, arating, atime, acost)  
          VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $db->prepare($sql);
  $stmt->bind_param("iissiid", $travelID, $Aid, $attractionComment, $attractionType, $attractionRating, $duration, $attractionCost);
  $stmt->execute();
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
  <link rel="stylesheet" type="text/css" href="createNew.css">
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
    <h3><strong>Create New Travel Log</strong></h3>
     <div class="row">
      <div class="form">
        <form method="POST" action="">
             
             <!--LOCATION-->
    <p><strong>* LOCATION INFORMATION</strong></p>
    
    <label class="desc" id="title106" for="Field106">Country</label>
      <div>
        <input id="Field1" name="country" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    
    <label class="desc" id="title106" for="Field106">City</label>
      <div>
       <input id="Field1" name="city" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>

    <div>
    <label class="desc" id="title1" for="Field1">From Date (yyyy-mm-dd)</label>
      <div>
        <input id="Field1" name="fromDate" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>

    <div>
    <label class="desc" id="title1" for="Field1">To Date (yyyy-mm-dd)</label>
      <div>
        <input id="Field1" name="toDate" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>
    
              <!--LODGING-->
    <p><strong>* LODGING INFORMATION </strong></p>

      <label class="desc" id="title106" for="Field106">Type</label>
      <div>
        <select id="Field106" name="lodgingType" class="field select medium" tabindex="11"> 
          <?php echo $lodgingTypeMenu; ?>
        </select>
      </div>

      <label class="desc" id="title106" for="Field106">Lodge Name</label>
      <div>
        <input id="Field1" name="lodgingName" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    
    <div>
    <label class="desc" id="title1" for="Field1">Address</label>
      <div>
        <input id="Field1" name="lodgingAddress" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>

    <div>
    <label class="desc" id="title1" for="Field1">Cost/ Night ($)</label>
      <div>
          <input id="Field1" name="lodgingCost" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>  
    
    <div>
    <label class="desc" id="title1" for="Field1">Ammenities (seperate each by a comma)</label>
      <div>
        <input id="Field1" name="lodgingAmmenities" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>    
    
    <label class="desc" id="title106" for="Field106">Rating</label>
    <div>
      <select id="Field106" name="lodgeRating" class="field select medium" tabindex="11"> 
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
      </select>
    </div>  
    
              <!--RESTAURANT-->
    <p><strong>* RESTAURANT INFORMATION </strong></p>

    <div>
    <label class="desc" id="title1" for="Field1">Name of Restaurant</label>
      <div>
        <input id="Field1" name="foodName" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>   
    
    <label class="desc" id="title106" for="Field106">Food Type</label>
      <div>
        <input id="Field1" name="foodType" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>  

    <div>
    <label class="desc" id="title1" for="Field1">Address</label>
        <div>
          <input id="Field1" name="foodAddress" type="text" class="field text fn" value="" size="8" tabindex="1">
        </div>
    </div>    
    
    <div>
    <label class="desc" id="title1" for="Field1">Cost ($)</label>
      <div>
          <input id="Field1" name="foodCost" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>  
    
    <div>
    <label class="desc" id="title1" for="Field1">Comment</label>
      <div>
        <input id="Field1" name="foodComment" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div> 
    
              <!--ATTRACTION-->
    <p><strong>ATTRACTION INFORMATION</strong></p>    

    <div>
    <label class="desc" id="title1" for="Field1">Name of Attraction</label>
      <div>
        <input id="Field1" name="attractionName" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>   
    
    <div>
    <label class="desc" id="title1" for="Field1">Address</label>
      <div>
        <input id="Field1" name="attractionAddress" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>    
   
    <label class="desc" id="title106" for="Field106">Type of Attraction</label>
      <div>
        <select id="Field106" name="attractionType" class="field select medium" tabindex="11"> 
          <option value="Nature">Nature</option>
          <option value="Urban">Urban</option>
        </select>
      </div>  
      
    <div>
    <label class="desc" id="title1" for="Field1">Cost ($)</label>
      <div>
        <input id="Field1" name="attractionCost" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div> 
    
    <div>
    <label class="desc" id="title1" for="Field1">Duration of Time Spent (hours)</label>
      <div>
        <input id="Field1" name="duration" type="text" class="field text fn" value="" size="8" tabindex="1">
      </div>
    </div>

    <label class="desc" id="title106" for="Field106">Rating</label>
      <div>
        <select id="Field106" name="attractionRating" class="field select medium" tabindex="11"> 
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
        </select>
      </div> 

    <div>
    <label class="desc" id="title1" for="Field1">Comment</label>
        <div>
          <input id="Field1" name="attractionComment" type="text" class="field text fn" value="" size="8" tabindex="1">
        </div>
    </div>  
    

              <!--REVIEW-->
    <p><strong>TRIP REVIEW</strong></p>

    <label class="desc" id="title106" for="Field106">Rating</label>
    <div>
      <select id="Field106" name="tripRating" class="field select medium" tabindex="11"> 
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
      </select>
    </div>
    
    <div>
      <label class="desc" id="title1" for="Field1">Comment</label>
        <div>
          <input id="Field1" name="tripComment" type="text" class="field text fn" value="" size="8" tabindex="1">
        </div>
    </div>             
             
    <div>
      <div>
  	     <input id="saveForm" name="saveForm" type="submit" value="Submit">
      </div>
    </div>
     </form>
     </div>   
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