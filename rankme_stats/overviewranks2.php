<?php 
  // Database
  include_once("../config.php");
  // include('config/db.php');
  
  // Set session
  session_start();
  include ('header2.php');
  
 
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
 
$authors = $connection2->query("SELECT * FROM rankme ORDER BY kills DESC LIMIT 10")->fetchAll();
?>
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
 <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Ranks</h1>
      </div>
<div class="container-fluid mt-5">

 <div class="row my-4">
 <div class="col-sm-4 mb-3 mb-sm-auto">
<div class="card shadow bg-light h-100 mb-3">
  <div class="card-header text-bg-secondary">
    Most awarded players
  </div>
  <div class="card-body">
  <ul class="list-group list-group-flush">
  <div class="row">
        <div class="col text-left">Rank</div>
        <div class="col text-left">Name</div>
        <div class="col text-right">Kills</div>
	  </div>
  <?php
  
    // ------------------------ Avatar Function
            
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
		
		
		//----------------------------
  
    $rank = 0;
	foreach($authors as $author):
	$rank++;  		
														
	?>
    <li class="list-group-item bg-light">
	<div class="row">
	<div class="col-sm-2 text-left"><?php echo $rank;?></div>
        <div class="col-sm-2 text-left">
		<?php
		
      if($author['kills'] > 0 and  $author['kills'] < 50){
				echo '<img src=img/rank/private.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 49 and  $author['kills'] < 150 ){
				echo '<img src=img/rank/private_first_class.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 149 and  $author['kills'] < 200 ){
				echo '<img src=img/rank/specialist.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 199 and  $author['kills'] < 250 ){
				echo '<img src=img/rank/specialist5.png  width=33 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 249 and  $author['kills'] < 300 ){
				echo '<img src=img/rank/specialist6.png  width=33 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 299 and  $author['kills'] < 400 ){
				echo '<img src=img/rank/specialist7.png  width=33 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 399 and  $author['kills'] < 500 ){
				echo '<img src=img/rank/specialist8.png  width=33 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 499 and  $author['kills'] < 600 ){
				echo '<img src=img/rank/specialist9.png  width=33 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 599 and  $author['kills'] < 700 ){
				echo '<img src=img/rank/corporal.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 699 and  $author['kills'] < 800 ){
				echo '<img src=img/rank/sergeant.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 799 and  $author['kills'] < 1000 ){
				echo '<img src=img/rank/staff_sergeant.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 999 and  $author['kills'] < 1200 ){
				echo '<img src=img/rank/sergeant_first_class.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 1199 and  $author['kills'] < 1400 ){
				echo '<img src=img/rank/master_sergeant.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 1399 and  $author['kills'] < 1600 ){
				echo '<img src=img/rank/first_sergeant.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 1599 and  $author['kills'] < 1800 ){
				echo '<img src=img/rank/sergeant_major.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 1799 and  $author['kills'] < 2200 ){
				echo '<img src=img/rank/command_sergeant_major.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 2199 and  $author['kills'] < 2600 ){
				echo '<img src=img/rank/sergeant_major_of_the_army.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 2599 and  $author['kills'] < 3000 ){
				echo '<img src=img/rank/cw1.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 2999 and  $author['kills'] < 3400 ){
				echo '<img src=img/rank/cw2.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 3399 and  $author['kills'] < 3800 ){
				echo '<img src=img/rank/cw3.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 3799 and  $author['kills'] < 4600 ){
				echo '<img src=img/rank/cw4.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 4599 and  $author['kills'] < 5400 ){
				echo '<img src=img/rank/cw5.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 5399 and  $author['kills'] < 6200 ){
				echo '<img src=img/rank/officers_ranks01.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 6199 and  $author['kills'] < 7000 ){
				echo '<img src=img/rank/officers_ranks02.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 6999 and  $author['kills'] < 7800 ){
				echo '<img src=img/rank/officers_ranks03.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 7799 and  $author['kills'] < 9400 ){
				echo '<img src=img/rank/officers_ranks04.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 9399 and  $author['kills'] < 11000 ){
				echo '<img src=img/rank/officers_ranks05.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 10999 and  $author['kills'] < 12600 ){
				echo '<img src=img/rank/officers_ranks06.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 12599 and  $author['kills'] < 14200 ){
				echo '<img src=img/rank/officers_ranks07.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 14199 and  $author['kills'] < 15800 ){
				echo '<img src=img/rank/officers_ranks08.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 15799 and  $author['kills'] < 20000 ){
				echo ' <img src=img/rank/officers_ranks09.png  width=50 height=33 align=absmiddle border=0>';
			}else if($author['kills'] > 19999 and  $author['kills'] < 30000 ){
				echo '<img src=img/rank/officers_ranks10.png  width=50 height=33 align=absmiddle border=0>';				
			}else if($author['kills'] > 29999){
				echo '<img src=img/rank/officers_ranks_special.png  width=50 height=33 align=absmiddle border=0>';
			}else{
				echo '<img src=img/rank/norank.png  width=50height=33 align=absmiddle border=0>';
			}
			echo '</div>';
			echo '<div class=col-sm-5 text-left>';
	// ------------------------ Avatar Function
        $authid = $author['steam'];
		  		  
		$json = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=741466C2C1DB9732369BAEEACA0251A8&steamids='. getFriendId($authid) .'');
        $parsed = json_decode($json);
		
		        $parseddata = $parsed->response->players;
		        
				foreach($parseddata as $player):
                // echo $player->personaname . '<br>';
                echo '<img src=' . $player->avatar . ' class=rounded float-start> ';
				endforeach;	  
			
//-------------------------		
		 echo ' <a href="showplayer2.php?id='.$author['id'].'">'.$author['name'].'</a>'; ?>
		</div>
		<div class="col-sm-3 text-right"><?php echo $author['kills']; ?></div>
	  </div>
	  </li>
	  <?php endforeach; ?>
  </ul>

</div>
</div>
</div>

 <div class="col-sm-8 mb-3 mb-sm-auto">
<div class="card shadow bg-light h-100 mb-3">
  <div class="card-header text-bg-secondary">
    Overview ranks
  </div>
  <div class="card-body">
  <ul class="list-group list-group-flush">
    <li class="list-group-item bg-light">
	<div class="row">
        <div class="col text-center">Private<br>0 - 49 Kills<br><img src=img/rank/private.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Private First Class<br>50 - 149 Kills<br><img src=img/rank/private_first_class.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Specialist<br>150 - 199 Kills<br><img src=img/rank/specialist.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Specialist 5<br>200 - 249 Kills<br><img src=img/rank/specialist5.png  width=70 height=67 align=absmiddle border=0></div>
	  </div>
	  </li>
    <li class="list-group-item bg-light">
	<div class="row">
        <div class="col text-center">Specialist 6<br>250 - 299 Kills<br><img src=img/rank/specialist6.png  width=70 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Specialist 7<br>300 - 399 Kills<br><img src=img/rank/specialist7.png  width=67 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Specialist 8<br>400 - 499 Kills<br><img src=img/rank/specialist8.png  width=67 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Specialist 9<br>500 - 599 Kills<br><img src=img/rank/specialist9.png  width=67 height=67 align=absmiddle border=0></div>
      </div>
	  </li>
    <li class="list-group-item bg-light">
	<div class="row">
        <div class="col text-center">Corporal<br>600 - 699 Kills<br><img src=img/rank/corporal.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Sergeant<br>700 - 799 Kills<br><img src=img/rank/sergeant.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Staff Sergeant<br>800 - 999 Kills<br><img src=img/rank/staff_sergeant.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Sergeant First Class<br>1000 - 1199 Kills<br><img src=img/rank/sergeant_first_class.png  width=100 height=67 align=absmiddle border=0></div>
      </div>
	  </li>
	<li class="list-group-item bg-light">
	<div class="row">
        <div class="col text-center">Master Sergeant<br>1200 - 1399 Kills<br><img src=img/rank/master_sergeant.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">First Sergeant<br>1400 - 1599 Kills<br><img src=img/rank/first_sergeant.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Sergeant Major<br>1600 - 1799 Kills<br><img src=img/rank/sergeant_major.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Command Sergeant Major<br>1800 - 2199 Kills<br><img src=img/rank/command_sergeant_major.png  width=100 height=67 align=absmiddle border=0></div>
      </div>
	  </li>
    <li class="list-group-item bg-light">
	<div class="row">
        <div class="col text-center">Sergeant Major of the Army<br>2200 - 2599 Kills<br><img src=img/rank/sergeant_major_of_the_army.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Warrant Officer 1<br>2600 - 2999 Kills<br><img src=img/rank/cw1.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Chief Warrant Officer 2<br>3000 - 3399 Kills<br><img src=img/rank/cw2.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Chief Warrant Officer 3<br>3400 - 3799 Kills<br><img src=img/rank/cw3.png  width=100 height=67 align=absmiddle border=0></div>
      </div>
	  </li>
    <li class="list-group-item bg-light">
	<div class="row">
        <div class="col text-center">Chief Warrant Officer 4<br>3800 - 4599 Kills<br><img src=img/rank/cw4.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Chief Warrant Officer 5<br>4600 - 5399 Kills<br><img src=img/rank/cw5.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Second Lieutenant<br>5400 - 6199 Kills<br><img src=img/rank/officers_ranks01.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">First Lieutenant<br>6200 - 6999 Kills<br><img src=img/rank/officers_ranks02.png  width=100 height=67 align=absmiddle border=0></div>
      </div>
	  </li>
	<li class="list-group-item bg-light">
	<div class="row">
        <div class="col text-center">Captain<br>7000 - 7799 Kills<br><img src=img/rank/officers_ranks03.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Major<br>7800 - 9399 Kills<br><img src=img/rank/officers_ranks04.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Lieutenant Colonel<br>9400 - 10999 Kills<br><img src=img/rank/officers_ranks05.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Colonel<br>11000 - 12599 Kills<br><img src=img/rank/officers_ranks06.png  width=100 height=67 align=absmiddle border=0></div>
      </div>
	  </li>
    <li class="list-group-item bg-light">
	<div class="row">
        <div class="col text-center">Brigadier General<br>12600 - 14199 Kills<br><img src=img/rank/officers_ranks07.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Major General<br>14200 - 15799 Kills<br><img src=img/rank/officers_ranks08.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">Lieutenant General<br>15800 - 19999 Kills<br><img src=img/rank/officers_ranks09.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center">General<br>20000 - 29999 Kills<br><img src=img/rank/officers_ranks10.png  width=100 height=67 align=absmiddle border=0></div>
      </div>
	  </li>
    <li class="list-group-item bg-light">
	<div class="row">
        <div class="col text-center">General of the Army<br>Over > 30000 Kills<br><img src=img/rank/officers_ranks_special.png  width=100 height=67 align=absmiddle border=0></div>
        <div class="col text-center"></div>
        <div class="col text-center"></div>
        <div class="col text-center"></div>
      </div>
	  </li>
  </ul>

</div>
</div>
</div>
</div>


  </div>
</main>
<?php	
	  include ('footer.php');
?>