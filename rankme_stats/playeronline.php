<?php 
  // Database
  include_once("../config.php");
  include ('header.php');
  
  global $bd_table; 
  $db = new mysqli($host, $benutzer, $passwort, $datenbank);
 if($db->connect_errno > 0){
	 die('Fehler beim Verbinden: ' . $db->connect_error);
 }

$time = time()-60;
$querytime = mysqli_query($db,"SELECT * FROM rankme where lastconnect > '".$time."' ORDER BY score DESC ");
$lastcounter2=mysqli_num_rows($querytime);

$getuk = mysqli_query($db,"SELECT * FROM rankme ORDER BY score DESC");

 ?>
 <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Players Online</h1>
      </div>
      <div class="col-sm-8 mb-3 mb-sm-auto">
<!-- Datatable -->
<table class="table align-middle table-light table-hover table-bordered shadow p-3 mb-5 bg-white rounded overflow-hidden">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Points</th>
                    <th scope="col">Kills</th>
                    <th scope="col">Deaths</th>
					<th scope="col">Headshots</th>
                </tr>
            </thead>
            <tbody>

    <?php
    while($author=mysqli_fetch_array($querytime)) {
    if($author['id'])
	    {
        ?>
        <tr class="align-bottom">
                    <td><h5><?php echo '<a href="showplayer.php?id='.$author['id'].'">'.$author['name'].'</a>'; ?></h5></td>
                    <td><?php echo $author['score']; ?></td>
                    <td><?php echo $author['kills']; ?></td>
                    <td><?php echo $author['deaths']; ?></td>
					          <td><?php echo $author['headshots']; ?></td>
                </tr>
       <?php
      }
    }
    ?>
          </tbody>
        </table>
</div>
</main>
<?php	
	  include ('footer.php');
?>