<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.104.2">
    <title><?php echo ''.$title2.''; ?></title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/dashboard/">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	

      <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
	  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

 <!-- Including jQuery is required. -->
<!-- jQuery + Bootstrap JS -->	

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
  </head>
  <body>

  <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
<div class="col-md-auto">
<select class="form-select form-select-sm" onchange="location = this.options[this.selectedIndex].value;">
  <option selected>Choose a server</option>
  <option value="index.php"><?php echo ''.$servername.''; ?></option>
  <option value="index2.php"><?php echo ''.$servername2.''; ?></option>
</select>
</div>

<div class="col">
<a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-6" href="steam://connect/<?php echo ''.$serverip2.''; ?>/">
  <img src="<?php echo ''.$servericon2.''; ?>"  width=34 height=29 align=absmiddle border=0> <?php echo ''.$servername2.''; ?></a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button> 
  </div>
</header>


    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse shadow">
      <div class="position-sticky pt-3 sidebar-sticky" style="background-color: #e3f2fd;">
        <ul class="nav flex-column">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="index2.php">
              <span data-feather="home" class="align-text-bottom"></span>
              Dashboard
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="points2.php">
            <span data-feather="file-text" class="align-text-bottom"></span>
              Players
            </a>
          </li>
		   <li class="nav-item">
            <a class="nav-link" href="bestusers2.php">
			  <span data-feather="users" class="align-text-bottom"></span>
              Best Players
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="vipslist2.php">
            <span data-feather="file-text" class="align-text-bottom"></span>
              Vips
            </a>
          </li>
		   <li class="nav-item">
            <a class="nav-link" href="overviewranks2.php">
              <span data-feather="bar-chart-2" class="align-text-bottom"></span>
              Ranks
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="playeronline2.php">
              <span data-feather="bar-chart-2" class="align-text-bottom"></span>
              Players Online
            </a>
          </li>
        </ul>
      </div>
    </nav>