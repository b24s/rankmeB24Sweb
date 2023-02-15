<?php 
  // Database
  // include('config/db.php');
  include_once("../config.php");
  
  // Set session
  session_start();
  if(isset($_POST['records-limit'])){
      $_SESSION['records-limit'] = $_POST['records-limit'];
  }
  
  $limit = isset($_SESSION['records-limit']) ? $_SESSION['records-limit'] : 10;
  $page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 1;
  $paginationStart = ($page - 1) * $limit;
  $authors = $connection->query("SELECT * FROM vip_users ORDER BY account_id DESC LIMIT $paginationStart, $limit")->fetchAll();
  // Get total records
  $sql = $connection->query("SELECT count(account_id) AS account_id FROM vip_users")->fetchAll();
  $allRecrods = $sql[0]['vip_users'];
  
  // Calculate total pages
  $totoalPages = ceil($allRecrods / $limit);
  // Prev + Next
  $prev = $page - 1;
  $next = $page + 1;
  
  
  include ('header.php');
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Vips</h1>
      </div>
	  
    <div class="container-fluid">
 <div class="row my-3">
  
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
                    <th scope="col">Rate</th>
                    <th scope="col">End date</th>					
                </tr>
            </thead>
            <tbody>
                <?php
				foreach($authors as $author):
				?>
                <tr class="align-bottom">				
                    <td><img src="img/vip/vip.png" width="40" height="38" alt="..."> <?php echo $author['name']; ?></td>
                    <td><?php echo $author['group']; ?></td>																		
					<td><?php if($author['expires'] == 0)
                            {
                             echo "Forever";
                            }
                             else {
                            echo date('d-m-Y H:i', $author['expires']);
                            }
	                ?></td>
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
        $(document).ready(function () {
            $('#records-limit').change(function () {
                $('form').submit();
            })
        });
    </script>
	
<?php	
	  include ('footer.php');
?>