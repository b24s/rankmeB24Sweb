<center>
<?php
    $copyYear = 2023; // Set your website start date
    $curYear = date('Y'); // Keeps the second year updated
      echo $copyYear . (($copyYear != $curYear) ? '-' . $curYear : '');
  ?> Copyright <a href="https://b24s.eu/forum/" target="_blank">B24S.EU</a>  - 
  <a href="https://forums.alliedmods.net/showthread.php?p=1456869" target="_blank">RankMe</a> - 
    <a href="https://forums.alliedmods.net/showthread.php?p=1571556" target="_blank">RankMe Connect Announcer</a> - 
   <a href="https://forums.alliedmods.net/showthread.php?t=290063" target="_blank">Rankme GO Plugin</a>
  </center><br>
    
    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
	  <script src="dashboard.js"></script>	  
	  
	  <script>
	$('.selectpicker').change(function () {
        var selectedItem = $('.selectpicker').val();
        alert(selectedItem);
    });
	</script>
  </body>
</html>