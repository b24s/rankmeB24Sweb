<html>
<head>
<title>
CT Bans
</title>
<style type="text/css">
body {background-color: #3b5998; color: #ffffff;}
body, td, th, h1, h2 {font-family: sans-serif;}
pre {margin: 0px; font-family: monospace;}
a:link {color: #ffffff; text-decoration: none;}
a:visited {color: #ffffff; text-decoration: none;}
a:hover {text-decoration: underline;}
table {border-collapse: collapse;}
.center {text-align: center;}
.center table { margin-left: auto; margin-right: auto; text-align: center;}
.center th { text-align: center !important; }
td, th { border: 1px solid #000000; font-size: 75%; vertical-align: baseline;}
h1 {font-size: 150%;}
h2 {font-size: 125%;}
.p {text-align: left;}
.e {background-color: #3399ff; font-weight: bold; color: #000000; }
.h {background-color: #85cdc1; font-weight: bold; color: #000000; }
.v {background-color: #cccccc; color: #000000; } 
.vr {background-color: #cccccc; text-align: right; color: #000000;}
img {float: right; border: 0px;}
hr {width: 600px; background-color: #cccccc; border: 0px; height: 1px; color:
#000000;}
</style>
</head>
<body>
<div class="center">

<h2>CT Bans</h2>

<?php

$server="45.89.140.168";
$username="u982_t2ao39kMl0";
$password="oRtCZ52X8Q.vaTL+LG=E2hk1";
$database="s982_b24s_datebank";

$table_prefix="";
$results_per_page="15";

function show_ctban_table($dbh, $query, $results_per_page, $ctbanpage)
{
	$results = mysql_query($query) or die("Unable to execute query");
	
	if (!empty($results))
	{	
		$countquery = mysql_fetch_array($results);
		$rows = $countquery[0];
		$lastpage = ceil($rows/$results_per_page);
		
		if ($ctbanpage < 1)
		{
			$ctbanpage = 1;
		}
		elseif ($ctbanpage > $lastpage)
		{
			$ctbanpage = $lastpage;
		}
		
		echo "<p>";
		echo "Page " . strval($ctbanpage) . " of " . $lastpage;
		echo "<br>";
		if ($ctbanpage != 1)
		{
			echo " <a href='{$_SERVER['PHP_SELF']}?ctbanpage=1'> <<-First</a> ";
			echo " ";
			$previouspage = $ctbanpage - 1;
			echo " <a href='{$_SERVER['PHP_SELF']}?ctbanpage=$previouspage'> <-Previous</a> ";
		}
		
		
		
		if ($ctbanpage != $lastpage)
		{
			if ($ctbanpage != 1)
			{
				echo " | ";
			}
			$nextpage = $ctbanpage + 1;
			echo " <a href='{$_SERVER['PHP_SELF']}?ctbanpage=$nextpage'>Next -></a> ";
			echo " ";
			echo " <a href='{$_SERVER['PHP_SELF']}?ctbanpage=$lastpage'>Last ->></a> ";
		}
		
		echo "</p>";
		
		$paginated_query = "SELECT * FROM " . $table_prefix . "CTBan_Log ORDER BY TIMESTAMP DESC LIMIT " .($ctbanpage - 1) * $results_per_page . "," . $results_per_page;
		$results = mysql_query($paginated_query) or die ("Unable to execute paginated query");
		
		print '<table border="0" cellpadding="3" width=90%>' . "\n";
		print "<tr class='h'><th>Perp SteamID</th><th>Perp Name</th><th>Time Banned</th><th>Duration</th>"
			. "<th>Admin Name</th><th>Reason</th><th>Time Remaining</th></tr>\n";
			
		$counter = 0;
		
		while ($row = mysql_fetch_array($results))
		{
			$counter = $counter + 1;
			if ($counter % 2)
				$classtext = "e";
			else
				$classtext = "v";
			$unbanme = "";
			if ($row["bantime"] > 0)
			{
				$bantime = $row["bantime"] . "m";
				if ($row["timeleft"] >= 0)
				{
					$timeleft = $row["timeleft"] . "m";
				}
				else
					$timeleft = "Expired";
			}
			else
			{
				$bantime = "Permanent";
				if ($row["timeleft"] >= 0)
				{
					$timeleft = "Indefinite";
				}
				else
					$timeleft = "Unbanned";
			}
			print "<tr><td class='" . $classtext ."'>" . $row["perp_steamid"]
				. "</td><td class='" . $classtext ."'>" . $row["perp_name"] 
				. "</td><td class='" . $classtext . "'>" . date('r', $row["timestamp"]) . "</td>" 
				. "<td class='" . $classtext ."'>" . $bantime
				. "</td><td class='" . $classtext ."'>" . $row["admin_name"]
				. "</td><td class='" . $classtext ."'>" . $row["reason"]
				. "</td><td class='" . $classtext . "'>" . $timeleft . "</td></tr>\n";
		}
		print "</table>\n";
		
		echo "Total CT Ban Records: " . $rows;
	} else {
		print "No results\n";
	}
}
try
{
$dbh = mysql_connect($server,$username,$password);
if (!$dbh) {
	print "Unable to connect to database server with specified parameters\n";
} else {
	@mysql_select_db($database, $dbh) or die("Unable to select specified database");
	
	$query = "SELECT COUNT(*) FROM " . $table_prefix . "CTBan_Log";
	
	if (empty($_GET['ctbanpage']))
	{
		$ctbanpage = 1;
	}
	else
	{
		$ctbanpage = $_GET['ctbanpage'];
	}
	
	show_ctban_table($dbh, $query, $results_per_page, $ctbanpage);
}
}
catch (Exception $e)
{
	die($e);
}
mysql_close($dbh);
?>

</div>
</body>
</html>

