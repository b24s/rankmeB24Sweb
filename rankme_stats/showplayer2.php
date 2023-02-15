<?php 
  // Database
 include_once("../config.php");
  
  global $bd_table; 
  // include "../check_restore2.php";
  include ('header2.php');
  
  $db = new mysqli($host2, $benutzer2, $passwort2, $datenbank2);
 if($db->connect_errno > 0){
	 die('Fehler beim Verbinden: ' . $db->connect_error);
 }

 
 // Add and Mul functions are made by Nitrogen. (http://www.php.net/manual/pt_BR/function.bcmul.php#92103)
function Add($Num1,$Num2,$Scale=null) { 
   // check if they're valid positive numbers, extract the whole numbers and decimals 
   if(!preg_match("/^\+?(\d+)(\.\d+)?$/",$Num1,$Tmp1)|| 
      !preg_match("/^\+?(\d+)(\.\d+)?$/",$Num2,$Tmp2)) return('0'); 

   // this is where the result is stored 
   $Output=array(); 

   // remove ending zeroes from decimals and remove point 
   $Dec1=isset($Tmp1[2])?rtrim(substr($Tmp1[2],1),'0'):''; 
   $Dec2=isset($Tmp2[2])?rtrim(substr($Tmp2[2],1),'0'):''; 

   // calculate the longest length of decimals 
   $DLen=max(strlen($Dec1),strlen($Dec2)); 

   // if $Scale is null, automatically set it to the amount of decimal places for accuracy 
   if($Scale==null) $Scale=$DLen; 

   // remove leading zeroes and reverse the whole numbers, then append padded decimals on the end 
   $Num1=strrev(ltrim($Tmp1[1],'0').str_pad($Dec1,$DLen,'0')); 
   $Num2=strrev(ltrim($Tmp2[1],'0').str_pad($Dec2,$DLen,'0')); 

   // calculate the longest length we need to process 
   $MLen=max(strlen($Num1),strlen($Num2)); 

   // pad the two numbers so they are of equal length (both equal to $MLen) 
   $Num1=str_pad($Num1,$MLen,'0'); 
   $Num2=str_pad($Num2,$MLen,'0'); 

   // process each digit, keep the ones, carry the tens (remainders) 
   for($i=0;$i<$MLen;$i++) { 
     $Sum=((int)$Num1{$i}+(int)$Num2{$i}); 
     if(isset($Output[$i])) $Sum+=$Output[$i]; 
     $Output[$i]=$Sum%10; 
     if($Sum>9) $Output[$i+1]=1; 
   } 

   // convert the array to string and reverse it 
   $Output=strrev(implode($Output)); 
   return($Output); 
	/*//echo  ";:;" . substr($Output,0,strlen($Output));
   // substring the decimal digits from the result, pad if necessary (if $Scale > amount of actual decimals) 
   // next, since actual zero values can cause a problem with the substring values, if so, just simply give '0' 
   // next, append the decimal value, if $Scale is defined, and return result 
   $Decimal=str_pad(substr($Output,-$DLen,$Scale),$Scale,'0'); 
   $Output=(($MLen-$DLen<1)?'0':substr($Output,0,-$DLen)); 
   $Output.=(($Scale>0)?".{$Decimal}":''); 
   */
   
 }
 
 function Mul($Num1='0',$Num2='0') {
   // check if they're both plain numbers
   if(!preg_match("/^\d+$/",$Num1)||!preg_match("/^\d+$/",$Num2)) return(0);

   // remove zeroes from beginning of numbers
   for($i=0;$i<strlen($Num1);$i++) if(@$Num1{$i}!='0') {$Num1=substr($Num1,$i);break;}
   for($i=0;$i<strlen($Num2);$i++) if(@$Num2{$i}!='0') {$Num2=substr($Num2,$i);break;}

   // get both number lengths
   $Len1=strlen($Num1);
   $Len2=strlen($Num2);

   // $Rema is for storing the calculated numbers and $Rema2 is for carrying the remainders
   $Rema=$Rema2=array();

   // we start by making a $Len1 by $Len2 table (array)
   for($y=$i=0;$y<$Len1;$y++)
     for($x=0;$x<$Len2;$x++)
       // we use the classic lattice method for calculating the multiplication..
       // this will multiply each number in $Num1 with each number in $Num2 and store it accordingly
       @$Rema[$i++%$Len2].=sprintf('%02d',(int)$Num1{$y}*(int)$Num2{$x});

   // cycle through each stored number
   for($y=0;$y<$Len2;$y++)
     for($x=0;$x<$Len1*2;$x++)
       // add up the numbers in the diagonal fashion the lattice method uses
       @$Rema2[Floor(($x-1)/2)+1+$y]+=(int)$Rema[$y]{$x};

   // reverse the results around
   $Rema2=array_reverse($Rema2);

   // cycle through all the results again
   for($i=0;$i<count($Rema2);$i++) {
     // reverse this item, split, keep the first digit, spread the other digits down the array
     $Rema3=str_split(strrev($Rema2[$i]));
     for($o=0;$o<count($Rema3);$o++)
       if($o==0) @$Rema2[$i+$o]=$Rema3[$o];
       else @$Rema2[$i+$o]+=$Rema3[$o];
   }
   // implode $Rema2 so it's a string and reverse it, this is the result!
   $Rema2=strrev(implode($Rema2));

   // just to make sure, we delete the zeros from the beginning of the result and return
   while(strlen($Rema2)>1&&$Rema2{0}=='0') $Rema2=substr($Rema2,1);

   return($Rema2);
 }
date_default_timezone_set('America/Sao_Paulo');
	
/*include_once "geoip.inc";
$gi = geoip_open("GeoIP.dat",GEOIP_STANDARD);
function secondsToTime($seconds)
{
    // extract hours
    $hours = floor($seconds / (60 * 60));
 
    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);
 
    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);
 
    // return the final array
    $obj = array(
        "h" => (int) $hours,
        "m" => (int) $minutes,
        "s" => (int) $seconds,
    );
	$time = $obj['h'] . " hours, " . $obj['m'] . " minutes, " . $obj['s'] . " seconds";
    return $time;
}
*/
 
 $getuk = mysqli_query($db,"SELECT * FROM rankme ORDER BY score DESC");
 $getuk2 = mysqli_query($db,"SELECT * FROM rankme where id = '".$_GET['id']."'"); 
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Player <?php
			$rank = 0;
			  while($row=mysqli_fetch_array($getuk)) {
				  $rank++;
				  if($_GET['id'] == $row['id'])
	               {
					 echo $row['name'];  
			   ?>
			  </h1>
      </div>
	  	  
<div class="container-fluid mt-5">
  <!-- Content starts here-->
  <div class="row mt-5 mb-3">
  
    <div class="col-md-2 mb-3">
      <div class="card shadow h-100 mb-3 rounded">
      <div class="card-header text-bg-secondary">
      <h6 class="m-0 text-wrap">
  <div class="p-2 flex-fill"><h6 class="my-0"><span class="material-symbols-rounded md-48 align-text-bottom">person</span> Player</h6></div>
	  </h6>
      </div>
        <div class="card-body bg-light text-left">
           <span class="text-success">
		  <?php 
		 function getFriendId($steamId)
	{
		
		//Example SteamID: "STEAM_X:Y:ZZZZZZZZ"
		$gameType = 0; //This is X.  It's either 0 or 1 depending on which game you are playing (CSS, L4D, TF2, etc)
		$authServer = 0; //This is Y.  Some people have a 0, some people have a 1
		$clientId = ''; //This is ZZZZZZZZ.

		//Remove the "STEAM_"
		$steamId = str_replace('STEAM_', '' ,$steamId);

		//Split steamId into parts
		$parts = explode(':', $steamId);
		$gameType = $parts[0];
		$authServer = $parts[1];
		$clientId = $parts[2];

		//Calculate friendId
		$t = Add("76561197960265728", $authServer,0);
		$s = Mul($clientId, '2',0);
		$result = Add($t,$s,0);
		//$result = bcadd((bcadd('76561197960265728', $authServer)), (bcmul($clientId, '2')));
		return($result);
	}

	$authid = $row['steam'];
		  		  
		  $json = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=741466C2C1DB9732369BAEEACA0251A8&steamids='. getFriendId($authid) .'');
                $parsed = json_decode($json);

                foreach($parsed->response->players as $player){
                // echo $player->personaname . '<br>';
                echo '<center><img src=' . $player->avatarfull . ' class=rounded float-start></center>';
					  
					  
				$state = 0;
				if($player->personastate == 0){
				$state = "Offline";	
				}else if($player->personastate == 1){
				$state = "Online";		
				}else if($player->personastate == 2){
				$state = "Busy";		
				}else if($player->personastate == 3){
				$state = "Away";		
				}else if($player->personastate == 4){
				$state = "Snooze";		
				}else if($player->personastate == 5){
				$state = "looking to trade";		
				}else if($player->personastate == 6){
				$state = "looking to play";		
				}
				
				echo '<br><br>Status: ' . $state . '';
				// date('Y-m-d ',$player->lastlogoff )
				//echo '<br>Steam-Login as: ' . date('Y-m-d H:i:s' ,$player->lastlogoff ) . '';
				// echo '<br>'.$player->gameid.''; // If the user is currently in-game, this value will be returned and set to the gameid of that game.
				
				if($player->loccountrycode == ""){
					$img_country = 0;					
				}else{
					$img_country = $player->loccountrycode;
				}
				
				echo '<br>Country: <img src=locations/'.$img_country.'.png>'; // If set on the user's Steam Community profile, The user's country of residence, 2-character ISO country code		        
				echo '<br>'.$player->gameextrainfo.''; // Gameinfo
		   }
           ?>
		   <br>
		   <div class="mb-1 lh-sm">
              Connection:
		   <?php echo date('Y.m.d H:i',$row['lastconnect']);?>
		   </div>
            <div class="mb-1 lh-sm">
		   Total Time
		   <?php echo gmdate('H:i:s',$row['connected']);?>
		   </div>
           <div class="mb-1 lh-sm">
         Steam Profile:
          <?php echo "<a href=\"http://steamcommunity.com/profiles/" . getFriendId($authid) . "\" target=_blank>Profile</a>"; ?>
           </div>
        </div>		
      </div>
    </div>
<div class="col-md-3 mb-3">
      <div class="card shadow h-100 mb-3 ">
        <div class="card-header text-bg-secondary">
		  <div class="p-2 flex-fill"><h6 class="my-0"><span class="material-symbols-rounded md-48 align-text-bottom">trending_up</span> Rank Statistic</h6></div>
        </div>
        <div class="card-body bg-light">
         <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Rank:</h6>
            </div>
            <span class="text-muted"><?php echo $rank;?></span>
          </li>
		  <li class="list-group-item  d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Score:</h6>
            </div>
            <span class="text-muted"><?php echo $row['score'];?></span>
          </li>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Hits:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['hits'];?>
			</span>
          </li>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0"></h6>
			  </div>
            <span class="text-muted">
			
			</span>
			
          </li>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Kills:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['kills'];?>
			</span>
          </li>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Deaths:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['deaths'];?>
			</span>
          </li>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Shots:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['shots'];?>
			</span>
          </li>
		  <br>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Rounds Won as TR:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['tr_win'];?>/<?php echo $row['rounds_tr'];?>
			</span>
          </li>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Rounds Won as CT:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['ct_win'];?>/<?php echo $row['rounds_ct'];?>
			</span>
          </li>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Rounds Total:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['rounds_ct']+$row['rounds_tr'];?>
			</span>
          </li>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Most played teams as </h6>
			  </div>
            <span class="text-muted">
			<?php
			   $team_game;
			   if($row['rounds_tr'] > $row['rounds_ct']){
				   $team_game =  '<p style=color:red;><b>TR</b></p>';
			   }else if($row['rounds_tr'] < $row['rounds_ct']){
				   $team_game = '<p style=color:blue;><b>CT</b></p>';
			   }
			?>
			<?php echo ''.$team_game.'';?>
			</span>
          </li>
		  <br>
		  <div>
            <div>
              <h6><p class="text-center"><b>Current rank</b></p></h6>
			   <p class="text-center">
			<?php
            if($row['kills'] > 0 and  $row['kills'] < 50){
				$me_rank = "Private";
				$me_img = "<img src=img/rank/private.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 50 - $row['kills'];
				$me_prozent_view = ($row['kills']-1)/(49/100);
			}else if($row['kills'] > 49 and  $row['kills'] < 150 ){
				$me_rank= "Private First Class";
				$me_img = "<img src=img/rank/private_first_class.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 150 - $row['kills'];
				$me_prozent_view = ($row['kills']-49)/(99/100);
			}else if($row['kills'] > 149 and  $row['kills'] < 200 ){
				$me_rank= "Specialist";
				$me_img = "<img src=img/rank/specialist.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 200 - $row['kills'];
				$me_prozent_view = ($row['kills']-149)/(99/100);
			}else if($row['kills'] > 199 and  $row['kills'] < 250 ){
				$me_rank= "Specialist 5";
				$me_img = "<img src=img/rank/specialist5.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 250 - $row['kills'];
				$me_prozent_view = ($row['kills']-199)/(49/100);
			}else if($row['kills'] > 249 and  $row['kills'] < 300 ){
				$me_rank= "Specialist 6";
				$me_img = "<img src=img/rank/specialist6.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 300 - $row['kills'];
				$me_prozent_view = ($row['kills']-249)/(49/100);
			}else if($row['kills'] > 299 and  $row['kills'] < 400 ){
				$me_rank= "Specialist 7";
				$me_img = "<img src=img/rank/specialist7.png  width=67 height=67 align=absmiddle border=0>";
				$me_rank_view = 400 - $row['kills'];
				$me_prozent_view = ($row['kills']-299)/(99/100);
			}else if($row['kills'] > 399 and  $row['kills'] < 500 ){
				$me_rank= "Specialist 8";
				$me_img = "<img src=img/rank/specialist8.png  width=67 height=67 align=absmiddle border=0>";
				$me_rank_view = 500 - $row['kills'];
				$me_prozent_view = ($row['kills']-399)/(99/100);
			}else if($row['kills'] > 499 and  $row['kills'] < 600 ){
				$me_rank= "Specialist 9";
				$me_img = "<img src=img/rank/specialist9.png  width=67 height=67 align=absmiddle border=0>";
				$me_rank_view = 600 - $row['kills'];
				$me_prozent_view = ($row['kills']-499)/(99/100);
			}else if($row['kills'] > 599 and  $row['kills'] < 700 ){
				$me_rank= "Corporal";
				$me_img = "<img src=img/rank/corporal.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 700 - $row['kills'];
				$me_prozent_view = ($row['kills']-599)/(99/100);
			}else if($row['kills'] > 699 and  $row['kills'] < 800 ){
				$me_rank= "Sergeant";
				$me_img = "<img src=img/rank/sergeant.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 800 - $row['kills'];
				$me_prozent_view = ($row['kills']-699)/(99/100);
			}else if($row['kills'] > 799 and  $row['kills'] < 1000 ){
				$me_rank= "Staff Sergeant";
				$me_img = "<img src=img/rank/staff_sergeant.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 1000 - $row['kills'];
				$me_prozent_view = ($row['kills']-799)/(199/100);
			}else if($row['kills'] > 999 and  $row['kills'] < 1200 ){
				$me_rank= "Sergeant First Class";
				$me_img = "<img src=img/rank/sergeant_first_class.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 1200 - $row['kills'];
				$me_prozent_view = ($row['kills']-999)/(199/100);
			}else if($row['kills'] > 1199 and  $row['kills'] < 1400 ){
				$me_rank= "Master Sergeant";
				$me_img = "<img src=img/rank/master_sergeant.png  width=100 height=67 align=absmiddle border=0>";
				$me_prozent_view = ($row['kills']-1199)/(199/100);
			}else if($row['kills'] > 1399 and  $row['kills'] < 1600 ){
				$me_rank= "First Sergeant";
				$me_img = "<img src=img/rank/first_sergeant.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 1600 - $row['kills'];
				$me_prozent_view = ($row['kills']-1399)/(199/100);
			}else if($row['kills'] > 1599 and  $row['kills'] < 1800 ){
				$me_rank= "Sergeant Major";
				$me_img = "<img src=img/rank/sergeant_major.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 1800 - $row['kills'];
				$me_prozent_view = ($row['kills']-1599)/(199/100);
			}else if($row['kills'] > 1799 and  $row['kills'] < 2200 ){
				$me_rank= "Command Sergeant Major";
				$me_img = "<img src=img/rank/command_sergeant_major.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 2200 - $row['kills'];
				$me_prozent_view = ($row['kills']-1799)/(399/100);
			}else if($row['kills'] > 2199 and  $row['kills'] < 2600 ){
				$me_rank= "Sergeant Major of the Army";
				$me_img = "<img src=img/rank/sergeant_major_of_the_army.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 2600 - $row['kills'];
				$me_prozent_view = ($row['kills']-2199)/(399/100);
			}else if($row['kills'] > 2599 and  $row['kills'] < 3000 ){
				$me_rank= "Warrant Officer 1";
				$me_img = "<img src=img/rank/cw1.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 3000 - $row['kills'];
				$me_prozent_view = ($row['kills']-2599)/(399/100);
			}else if($row['kills'] > 2999 and  $row['kills'] < 3400 ){
				$me_rank= "Chief Warrant Officer 2";
				$me_img = "<img src=img/rank/cw2.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 3400 - $row['kills'];
				$me_prozent_view = ($row['kills']-2999)/(399/100);
			}else if($row['kills'] > 3399 and  $row['kills'] < 3800 ){
				$me_rank= "Chief Warrant Officer 3";
				$me_img = "<img src=img/rank/cw3.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 3800 - $row['kills'];
				$me_prozent_view = ($row['kills']-3399)/(399/100);
			}else if($row['kills'] > 3799 and  $row['kills'] < 4600 ){
				$me_rank= "Chief Warrant Officer 4";
				$me_img = "<img src=img/rank/cw4.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 4600 - $row['kills'];
				$me_prozent_view = ($row['kills']-3799)/(799/100);
			}else if($row['kills'] > 4599 and  $row['kills'] < 5400 ){
				$me_rank= "Chief Warrant Officer 5";
				$me_img = "<img src=img/rank/cw5.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 5400 - $row['kills'];
				$me_prozent_view = ($row['kills']-4599)/(799/100);
			}else if($row['kills'] > 5399 and  $row['kills'] < 6200 ){
				$me_rank= "Second Lieutenant";
				$me_img = "<img src=img/rank/officers_ranks01.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 6200 - $row['kills'];
				$me_prozent_view = ($row['kills']-5399)/(799/100);
			}else if($row['kills'] > 6199 and  $row['kills'] < 7000 ){
				$me_rank= "First Lieutenant";
				$me_img = "<img src=img/rank/officers_ranks02.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 7000 - $row['kills'];
				$me_prozent_view = ($row['kills']-6199)/(799/100);
			}else if($row['kills'] > 6999 and  $row['kills'] < 7800 ){
				$me_rank= "Captain";
				$me_img = "<img src=img/rank/officers_ranks03.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 7800 - $row['kills'];
				$me_prozent_view = ($row['kills']-6999)/(799/100);
			}else if($row['kills'] > 7799 and  $row['kills'] < 9400 ){
				$me_rank= "Major";
				$me_img = "<img src=img/rank/officers_ranks04.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 9400 - $row['kills'];
				$me_prozent_view = ($row['kills']-7799)/(1599/100);
			}else if($row['kills'] > 9399 and  $row['kills'] < 11000 ){
				$me_rank= "Lieutenant Colonel";
				$me_img = "<img src=img/rank/officers_ranks05.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 11000 - $row['kills'];
				$me_prozent_view = ($row['kills']-9399)/(1599/100);
			}else if($row['kills'] > 10999 and  $row['kills'] < 12600 ){
				$me_rank= "Colonel";
				$me_img = "<img src=img/rank/officers_ranks06.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 12600 - $row['kills'];
				$me_prozent_view = ($row['kills']-10999)/(1599/100);
			}else if($row['kills'] > 12599 and  $row['kills'] < 14200 ){
				$me_rank= "Brigadier General";
				$me_img = "<img src=img/rank/officers_ranks07.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 14200 - $row['kills'];
				$me_prozent_view = ($row['kills']-12599)/(1599/100);
			}else if($row['kills'] > 14199 and  $row['kills'] < 15800 ){
				$me_rank= "Major General";
				$me_img = "<img src=img/rank/officers_ranks08.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 15800 - $row['kills'];
				$me_prozent_view = ($row['kills']-14199)/(1599/100);
			}else if($row['kills'] > 15799 and  $row['kills'] < 20000 ){
				$me_rank= "Lieutenant General";
				$me_img = "<img src=img/rank/officers_ranks09.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 20000 - $row['kills'];
				$me_prozent_view = ($row['kills']-15799)/(4199/100);
			}else if($row['kills'] > 19999 and  $row['kills'] < 30000 ){
				$me_rank= "General";
				$me_img = "<img src=img/rank/officers_ranks10.png  width=100 height=67 align=absmiddle border=0>";
				$me_rank_view = 30000 - $row['kills'];
				$me_prozent_view = ($row['kills']-19999)/(9999/100);
			}else if($row['kills'] > 29999){
				$me_rank= "General of the Army";
				$me_img = "<img src=img/rank/officers_ranks_special.png  width=100 height=67 align=absmiddle border=0>";
			}else{
				$me_rank= "No Rank!";
				$me_rank_view = 50 - $row['kills'];
				$me_img = "<img src=img/rank/norank.png  width=100 height=67 align=absmiddle border=0>";
				$me_prozent_view = 0;
			}
			
			
			echo ''.$me_img.'<br>'.$me_rank.'<br>';
			
			
			
			?>
			</p>
			  </div>
          </div>
		  
		  <li class="list-group-item d-flex justify-content-between">
            <div class="col-sm-6">
			  	<!-- Example Code -->   
    <div class="progress">
      <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" 
	  role="progressbar" 
	  aria-label="Animated striped example" 
	  style="width: <?php echo round($me_prozent_view,0); ?>%;" 
	  aria-valuenow="<?php echo round($me_prozent_view,0); ?>" 
	  aria-valuemin="0" 
	  aria-valuemax="<?php echo round($me_prozent_view,0); ?>"><?php echo round($me_prozent_view,0); ?>%
	  </div>
    </div>   
    <!-- End Example Code -->
			  </div>
            <span class="text-muted">
			Kills needed: <?php echo ''.$me_rank_view.' ('.round($me_prozent_view,0).'%)'; ?>
			</span>
          </li>
		   <a href="overviewranks2.php">Overview ranks</a>
        </div>
      </div>
</div>
<div class="col-md-5 mb-3">
      <div class="card shadow h-100 mb-3">
        <div class="card-header text-bg-secondary">
           <div class="p-2 flex-fill"><h6 class="my-0"><span class="material-symbols-rounded md-48 align-text-bottom">pie_chart</span> Chart</h6></div>
        </div>
        <div class="card-body bg-light">
			<div class="chartcontainer3">
			<div class="text-success">
              <canvas id="chDonut1"></canvas>
			  </div>
			  <div class="text-success">
			  Example text
            </div>
			</div>
            
        </div>
      </div>
</div>
 <!-- Content starts here end-->

<div class="col-md-10 mb-3">
<div class="row">
<div class="col-md-6">		
		<ul class="list-group shadow mb-3">
          <li class="list-group-item list-group-item-warning d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0"><span class="material-symbols-rounded md-48 align-text-bottom">filter_list</span> Weapons Kills</h6>
            </div>
          </li>		  
		  <?php $weapon_string="knife,glock,usp,p228,deagle,elite,fiveseven,m3,xm1014,mac10,tmp,mp5navy,ump45,p90,galil,ak47,sg550,famas,m4a1,aug,scout,sg552,awp,g3sg1,m249,hegrenade,flashbang,smokegrenade";
						$weapon_array = explode(",",$weapon_string);
						if($row['kills'] == 0){$kills = 1;} else {$kills=$row['kills'];}
						$i =0;
						foreach($weapon_array as $weapon){
							if(is_int($i)){
								?>
								<li class="list-group-item list-group-item-danger d-flex justify-content-between lh-sm">
								<?php
							}
							$temp = strval($row[$weapon]/$kills*100);
							
							$weapon_names=array('Knife','Glock','USP','P228 (Compact)','Deagle','Elite', 'FiveSeven', 'M3', 'XM1014', 'Mac10', 'TMP', 'MP5', 'UMP45', 'P90', 'Galil', 'AK-47', 'SG550', 'Famas (Clarion)', 'M4A1 (Colt)', 'Aug', 'Scout', 'SG552', 'AWP', 'G3SG1', 'M249 (Rambo)', 'HE', 'Flashbang', 'Smoke');
							$weapon_images=array('knife','Glock','USP','P228','Deagle','Elite', 'FiveSeven', 'M3', 'XM1014', 'Mac10', 'TMP', 'MP5', 'UMP45', 'P90', 'Galil', 'ak47', 'SG550', 'Famas', 'M4A1', 'Aug', 'Scout', 'SG552', 'AWP', 'G3SG1', 'M249', 'HE', 'Flashbang', 'Smoke');
							echo "<div class=col-sm-3><img src=img/{$weapon_images[$i]}.png  width=50 height=38 align=absmiddle border=0></div> 
							<div class=col-sm-3><h6 class=my-0>{$weapon_names[$i]}</h6></div>"; 
							echo "<div class=col-sm-3><span class=text-muted>{$row[$weapon]} ("; 
							$ts = 1;
							$temp = strval($row[$weapon]/$kills*100);
							if(strpos($temp,".") !== false || $ts == 0){
								for($i1 = 0; $i1<=strpos($temp,".")+2;$i1++){
									if( strlen($temp)-1 <$i1 ){
										$ts=0;
									}else{
										echo $temp[$i1];
										}
									
								}
							} else { echo $temp . ".00";} echo	"%)</span></div>";
							$i++;
							if(is_int($i/2)){
								?>
								</li>
								<?php
							}
						}
					?>		  
        </ul>
		
		
		</div>
		<div class="col-md-6">
		<ul class="list-group shadow mb-3">
          <li class="list-group-item list-group-item-info d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0"><span class="material-symbols-rounded md-48 align-text-bottom">monitoring</span> Statistics</h6>
            </div>
          </li>
          <li class="list-group-item list-group-item-light d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Score:</h6>
            </div>
            <span class="text-muted"><?php echo $row['score'];?></span>
          </li>
          <li class="list-group-item list-group-item-dark d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Rank:</h6>
            </div>
            <span class="text-muted"><?php echo $rank;?></span>
          </li>
           <li class="list-group-item list-group-item-primary d-flex justify-content-between lh-sm">
            <div class="text-success">
              <h6 class="my-0">Kills per minute:</h6>
            </div>
            <span class="text-success">
			<?php $temp = strval($row['kills']/($row['connected']/60));
						if(strpos($temp,".") !== false){
							for($i = 0; $i<=strpos($temp,".")+2;$i++){
								if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}
																				
									}
								} else { echo $temp . ".00";}
			?>
			</span>
          </li>
          <li class="list-group-item list-group-item-secondary d-flex justify-content-between">
            <div>
              <h6 class="my-0">Kills per death:</h6>
			  </div>
            <span class="text-muted">
			<?php $deaths = 1; if($row['deaths'] != 0 ) { $deaths=$row['deaths'];}$temp = strval($row['kills']/$deaths);
					if(strpos($temp,".") !== false){
					    for($i = 0; $i<=strpos($temp,".")+2;$i++){
							if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}																				
									}
								} else { echo $temp . ".00";}
			?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-success d-flex justify-content-between">
            <div>
              <h6 class="my-0">Shots per kill:</h6>
			  </div>
            <span class="text-muted">
			<?php $kills = 1; if($row['kills'] != 0 ) { $kills=$row['kills'];} $temp= strval($row['shots']/$kills); 
						if(strpos($temp,".") !== false){
							for($i = 0; $i<=strpos($temp,".")+2;$i++){
								if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}
																				
									}
								} else { echo $temp . ".00";}
			?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-danger d-flex justify-content-between">
            <div>
              <h6 class="my-0">Headshots per kill:</h6>
			  </div>
            <span class="text-muted">
			<?php $temp = strval($row['headshots']/$kills);if(strpos($temp,".") !== false){
						for($i = 0; $i<=strpos($temp,".")+2;$i++){
							if( strlen($temp)-1 <$i ){
								break;
									}else{
										echo $temp[$i];
									}
																				
								}
						   } else { echo $temp . ".00";}
			?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-warning d-flex justify-content-between">
            <div>
              <h6 class="my-0">Accuracy:</h6>
			  </div>
            <span class="text-muted">
			<?php if($row['shots'] == 0){$shots = 1;} else {$shots = $row['shots'];}$temp = strval($row['hits']/$shots);if(strpos($temp,".") !== false){
					for($i = 0; $i<=strpos($temp,".")+2;$i++){
						if( strlen($temp)-1 <$i ){
							break;
								}else{
									echo $temp[$i];
								}
																				
							}
						} else { echo $temp . ".00";}
			?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-info d-flex justify-content-between">
            <div>
              <h6 class="my-0">Kills:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['kills'];?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-light d-flex justify-content-between">
            <div>
              <h6 class="my-0">Deaths:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['deaths'];?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-dark d-flex justify-content-between">
            <div>
              <h6 class="my-0">Suicides:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['suicides'];?>
			</span>
          </li>
		  <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Teammate Kills:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['tk'];?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-primary d-flex justify-content-between">
            <div>
              <h6 class="my-0">Shots:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['shots'];?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-secondary d-flex justify-content-between">
            <div>
              <h6 class="my-0">Hits:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['hits'];?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-success d-flex justify-content-between">
            <div>
              <h6 class="my-0">Rounds Won as TR:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['tr_win'];?>/<?php echo $row['rounds_tr'];?>
			</span>
          </li>
		  <li class="list-group-item list-group-item-danger d-flex justify-content-between">
            <div>
              <h6 class="my-0">Rounds Won as CT:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['ct_win'];?>/<?php echo $row['rounds_ct'];?>
			</span>
          </li>
        </ul>
		
		
		<ul class="list-group shadow mb-3">
          <li class="list-group-item list-group-item-warning d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0"><span class="material-symbols-rounded md-48 align-text-bottom">analytics</span> Hitbox</h6>
            </div>
          </li>
          <li class="list-group-item list-group-item-info d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Head</h6>
            </div>
            <span class="text-muted">
			<?php echo $row['head'];?> (<?php if($row['hits'] == 0){$hits = 1;} else { $hits=$row['hits'];} $temp = strval($row['head']/$hits*100);
						if(strpos($temp,".") !== false){
							for($i = 0; $i<=strpos($temp,".")+2;$i++){
								if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}
																				
									}
								} else { echo $temp . ".00";}?>%)
			</span>
          </li>
          <li class="list-group-item list-group-item-light d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Chest</h6>
            </div>
            <span class="text-muted">
			<?php echo $row['chest'];?> (<?php if($row['hits'] == 0){$hits = 1;} else { $hits=$row['hits'];} $temp = strval($row['chest']/$hits*100);
						if(strpos($temp,".") !== false){
							for($i = 0; $i<=strpos($temp,".")+2;$i++){
								if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}
																				
									}
								} else { echo $temp . ".00";}?>%)
			</span>
          </li>
           <li class="list-group-item list-group-item-dark d-flex justify-content-between lh-sm">
            <div class="text-success">
              <h6 class="my-0">Stomach</h6>
            </div>
            <span class="text-success">
			<?php echo $row['stomach'];?> (<?php if($row['hits'] == 0){$hits = 1;} else { $hits=$row['hits'];} $temp = strval($row['stomach']/$hits*100);
						if(strpos($temp,".") !== false){
							for($i = 0; $i<=strpos($temp,".")+2;$i++){
								if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}
																				
									}
								} else { echo $temp . ".00";}?>%)
			</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <div>
              <h6 class="my-0">Left Arm</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['left_arm'];?> (<?php if($row['hits'] == 0){$hits = 1;} else { $hits=$row['hits'];} $temp = strval($row['left_arm']/$hits*100);
						if(strpos($temp,".") !== false){
							for($i = 0; $i<=strpos($temp,".")+2;$i++){
								if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}
																				
									}
								} else { echo $temp . ".00";}?>%)
			</span>
          </li>
		  <li class="list-group-item list-group-item-primary d-flex justify-content-between">
            <div>
              <h6 class="my-0">Right Arm</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['right_arm'];?> (<?php if($row['hits'] == 0){$hits = 1;} else { $hits=$row['hits'];} $temp = strval($row['right_arm']/$hits*100);
						if(strpos($temp,".") !== false){
							for($i = 0; $i<=strpos($temp,".")+2;$i++){
								if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}
																				
									}
								} else { echo $temp . ".00";}?>%)
			</span>
          </li>
		  <li class="list-group-item list-group-item-secondary d-flex justify-content-between">
            <div>
              <h6 class="my-0">Left Leg</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['left_leg'];?> (<?php if($row['hits'] == 0){$hits = 1;} else { $hits=$row['hits'];} $temp = strval($row['left_leg']/$hits*100);
						if(strpos($temp,".") !== false){
							for($i = 0; $i<=strpos($temp,".")+2;$i++){
								if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}
																				
									}
								} else { echo $temp . ".00";}?>%)
			</span>
          </li>
		  <li class="list-group-item list-group-item-success d-flex justify-content-between">
            <div>
              <h6 class="my-0">Right Leg</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['right_leg'];?> (<?php if($row['hits'] == 0){$hits = 1;} else { $hits=$row['hits'];} $temp = strval($row['right_leg']/$hits*100);
						if(strpos($temp,".") !== false){
							for($i = 0; $i<=strpos($temp,".")+2;$i++){
								if( strlen($temp)-1 <$i ){
									break;
										}else{
											echo $temp[$i];
										}
																				
									}
								} else { echo $temp . ".00";}?>%)
			</span>
          </li>
        </ul>
				
		<ul class="list-group shadow mb-3">
          <li class="list-group-item list-group-item-danger d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0"><span class="material-symbols-rounded md-48 align-text-bottom">timelapse</span> Bomb/Hostage Statistics</h6>
            </div>
          </li>
          <li class="list-group-item list-group-item-warning d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Planted Bombs:</h6>
            </div>
            <span class="text-muted">
			<?php echo $row['c4_planted'];?>
			</span>
          </li>
          <li class="list-group-item list-group-item-info d-flex justify-content-between lh-sm">
            <div>
              <h6 class="my-0">Exploded Bombs:</h6>
            </div>
            <span class="text-muted">
			<?php echo $row['c4_exploded'];?>
			</span>
          </li>
           <li class="list-group-item list-group-item-light d-flex justify-content-between lh-sm">
            <div class="text-success">
              <h6 class="my-0">Defused Bombs:</h6>
            </div>
            <span class="text-success">
			<?php echo $row['c4_defused'];?>
			</span>
          </li>
          <li class="list-group-item list-group-item-dark d-flex justify-content-between">
            <div>
              <h6 class="my-0">Rescued Hostages:</h6>
			  </div>
            <span class="text-muted">
			<?php echo $row['hostages_rescued'];?>
			</span>
          </li>
        </ul>		
		</div>
		</div>
		</div>
		
		 <?php
				   }
			  }
			  
        foreach($getuk2 as $data)
        {
        $trwin = $data['tr_win'];
		$roundstr = $data['rounds_tr'];
        $ctwin = $data['ct_win'];
		$roundsct = $data['rounds_ct'];
		  
		$gesrounds = $roundstr + $roundsct;
		$geswon = $trwin + $ctwin;
		
        if($gesrounds > 0){		
	    $won = $geswon / ($gesrounds / 100);
		$lost = 100 - $won;
		}
        }

	    $wonrounds = round($won, 0);
		$lostrounds = round($lost, 0);
		
		?>
		
		</main>
		
<script>
function show(id) {
    if(document.getElementById) {
        var mydiv = document.getElementById(id);
        mydiv.style.display = (mydiv.style.display=='block'?'none':'block');
    }
}
</script>
		
			  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>
			  <script>
			  // Note: changes to the plugin code is not reflected to the chart, because the plugin is loaded at chart construction time and editor changes only trigger an chart.update().
const plugin = {
  id: 'customCanvasBackgroundColor',
  beforeDraw: (chart, args, options) => {
    const {ctx} = chart;
    ctx.save();
    ctx.globalCompositeOperation = 'destination-over';
    ctx.fillStyle = options.color || '#99ffff';
    ctx.fillRect(0, 0, chart.width, chart.height);
    ctx.restore();
  }
};

/* 3 donut charts */
var donutOptions = {
  cutoutPercentage: 100.0, 
  legend: {position:'bottom', padding:5, labels: {pointStyle:'circle', usePointStyle:true}}
};

// donut 1
var chDonutData1 = {
    labels: [' Won % ', ' Lost % '],
    datasets: [
      {
		data: [<?php echo json_encode($wonrounds) ?> , <?php echo json_encode($lostrounds) ?>],
        backgroundColor: ['rgba(60,179,113,0.4)','rgba(255, 0, 0, 0.52)'],
      }
    ],
	hoverOffset: 4
};
var chDonut1 = document.getElementById("chDonut1");
if (chDonut1) {
  new Chart(chDonut1, {
      type: 'pie',
      data: chDonutData1,
     options: {
		  maintainAspectRatio: false,
      plugins: {
		 title: {
        display: true,
        text: 'Rounds won end loos (%)',
      },
      customCanvasBackgroundColor: {
        color: '#F9f9f9',
      }
    }
  },
  plugins: [plugin],
  });
}
</script>		
<?php	
	  include ('footer.php');
?>