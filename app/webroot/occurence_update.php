<?php
error_reporting(E_ALL);

   $production = array(
		'driver' => 'mysql',
		'persistent' => false,
		'host' => 'internal-db.s78390.gridserver.com',
		'login' => 'db78390',
		'password' => 'kmSAI3iso',
		'database' => 'db78390_lucca',
		'prefix' => '',
	);

// Create connection
$conn = mysqli_connect($production['host'], $production['login'], $production['password'], $production['database']);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM occurrences WHERE subcategory = 4";
$results = mysqli_query($conn,$query);
while($row = mysqli_fetch_assoc($results)){
	$o[$row['category']][$row['location']]	= $row['id'];
}


$query = "SELECT * FROM items WHERE item_category_id = 4";
$results = mysqli_query($conn,$query);

while($row = mysqli_fetch_assoc($results)){
	
	$qquery = 'SELECT * FROM inventory_quantity WHERE item = '.$row['id'];
	$qresults = mysqli_query($conn,$qquery);
	$loc = mysqli_fetch_assoc($qresults);
	$location = $loc['location'];
	
	$check = array();
	$check[] = $o[0][0];
	$check[] = $o[0][$location];
	$check[] = $o[$row['item_type_id']][0];
	$check[] = $o[$row['item_type_id']][$location];
	
	foreach($check as $c){
		$cquery = "SELECT * FROM item_occurences WHERE occurrence_id = ".$c;
		$cresults = mysqli_query($conn,$cquery);
		if(mysqli_num_rows($cresults) < 1){
			$query2 = "INSERT INTO item_occurences VALUES ('".$row['id']."','".$c."',1,2)";
			echo $query2;
			//$results2 = mysql_query($query);
		}else{
			$record = mysqli_fetch_assoc($cresults);
		    echo "Record exists: ".json_encode($record);	
		}
	}
	
}