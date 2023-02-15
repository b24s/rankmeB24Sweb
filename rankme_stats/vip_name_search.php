<?php

//Including Database configuration file.

include "../config.php";

 global $bd_table; 
  $db = new mysqli($host, $benutzer, $passwort, $datenbank);
   if($db->connect_errno > 0){
	 die('Fehler beim Verbinden: ' . $db->connect_error);
 }

//Getting value of "search" variable from "script.js".

if (isset($_POST['search'])) {

//Search box value assigning to $Name variable.

   $Name = $_POST['search'];

//Search query.

   // $Query = "SELECT Name FROM rankme WHERE name LIKE '%$Name%' LIMIT 5";
   
   $getuk = mysqli_query($db,"SELECT * FROM vip_users WHERE name LIKE '%$Name%' LIMIT 5");

//Query execution

   // $ExecQuery = mysqli_query($db, $Query);

//Creating unordered list to display result.

   echo '

<ul>

   ';

   //Fetching result from database.

   while ($Result = mysqli_fetch_array($getuk)) {

       ?>

   <!-- Creating unordered list items.

        Calling javascript function named as "fill" found in "script.js" file.

        By passing fetched result as parameter. -->

   <li onclick='fill("<?php echo $Result['playername']; ?>")'>

   <a>

   <!-- Assigning searched result in "Search box" in "points.php" file. -->

    
	<?php echo '<a href="showplayer.php?id='.$Result['id'].'">'.$Result['name'].'</a>'; ?></h5>

   </li></a>

   <!-- Below php code is just for closing parenthesis. Don't be confused. -->

   <?php

}}


?>

</ul>