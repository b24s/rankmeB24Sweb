<?php 
  // Database
  include_once("../config.php");
  // include('config/db.php');
  
  // Set session
  session_start();
  if(isset($_POST['records-limit'])){
      $_SESSION['records-limit'] = $_POST['records-limit'];
  }
  
  $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 10;
  $page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
  $paginationStart = ($page - 1) * $limit;
  $authors = $connection->query("SELECT * FROM rankme ORDER BY score DESC LIMIT $paginationStart, $limit")->fetchAll();
  // Get total records
  $sql = $connection->query("SELECT count(id) AS score FROM rankme")->fetchAll();
  $allRecrods = $sql[0]['score'];
  
  // Calculate total pages
  $totoalPages = ceil($allRecrods / $limit);
  // Prev + Next
  $prev = $page - 1;
  $next = $page + 1;
  
  
  include ('header.php');
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Players</h1>
      </div>
	  
    <div class="container-fluid">
 <div class="row my-3">
  <div class="col-sm-9 mb-3 mb-sm-0">	
<!-- Search box. -->
   <input type="text" class="form-control" id="search" placeholder="Search" />
   <br>
   <b>Ex: </b><i>David, Ricky, Ronaldo, Messi, Watson, Robot</i>
   <br />
   <!-- Suggestions will be displayed in below div. -->
   <div id="display"></div>	
   <!-- Search box End. -->
   </div>
   
        <!-- Select dropdown -->
         <div class="col-sm-3 mb-3 mb-sm-0">
			<form action="points.php" method="post">
                <select name="records-limit" id="records-limit" class="custom-select">
                    <option disabled selected>Records Limit</option>
                    <?php
					foreach([10,20,30,50] as $limit) : 
					?>
                    <option
                        <?php if(isset($_SESSION['records-limit']) && $_SESSION['records-limit'] == $limit) echo 'selected'; ?>
                        value="<?= $limit; ?>">
                        <?= $limit; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
		</div>
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
				foreach($authors as $author):
				?>
                <tr class="align-bottom">
                    <td><h5><?php echo '<a href="showplayer.php?id='.$author['id'].'">'.$author['name'].'</a>'; ?></h5></td>
                    <td><?php echo $author['score']; ?></td>
                    <td><?php echo $author['kills']; ?></td>
                    <td><?php echo $author['deaths']; ?></td>
					<td><?php echo $author['headshots']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <!-- Pagination -->
        <nav aria-label="Page navigation example mt-5">
            <ul class="pagination pagination-sm justify-content-center">
                <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?page=" . $prev; } ?>" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
                   <span class="sr-only">Previous</span>
					</a>
                </li>
                <?php for($i = 1; $i <= $totoalPages; $i++ ): ?>
                <li class="page-item <?php if($page == $i) {echo 'active'; } ?>">
                    <a class="page-link" href="points.php?page=<?= $i; ?>"> <?= $i; ?> </a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php if($page >= $totoalPages) { echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($page >= $totoalPages){ echo '#'; } else {echo "?page=". $next; } ?>" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
                     <span class="sr-only">Next</span>
					</a>
                </li>
            </ul>
        </nav>
    </div>
	</main>
	 <script>	
	//Getting value from "name_search.php".

function fill(Value) {

   //Assigning value to "search" div in "points.php" file.

   $('#search').val(Value);

   //Hiding "display" div in "points.php" file.

   $('#display').hide();

}

$(document).ready(function() {

   //On pressing a key on "Search box" in "points.php" file. This function will be called.

   $("#search").keyup(function() {

       //Assigning search box value to javascript variable named as "name".

       var playername = $('#search').val();

       //Validating, if "playername" is empty.

       if (playername == "") {

           //Assigning empty value to "display" div in "points.php" file.

           $("#display").html("");

       }

       //If playername is not empty.

       else {

           //AJAX is called.

           $.ajax({

               //AJAX type is "Post".

               type: "POST",

               //Data will be sent to "name_search.php".

               url: "name_search.php",

               //Data, that will be sent to "ajax.php".

               data: {

                   //Assigning value of "name" into "search" variable.

                   search: playername

               },

               //If result found, this funtion will be called.

               success: function(html) {

                   //Assigning result to "display" div in "search.php" file.

                   $("#display").html(html).show();

               }

           });

       }

   });

});
</script>

    <script>
        $(document).ready(function () {
            $('#records-limit').change(function () {
                $('form').submit();
            })
        });
    </script>
	
<?php	
	  include ('footer.php');
?>