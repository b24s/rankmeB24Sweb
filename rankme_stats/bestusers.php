<?php
  // Database
 include_once("../config.php");
 // include('config/db.php');
 
  // Set session
  session_start();
  
  global $bd_table; 
  // include "../check_restore.php";
  include ('header.php');
  
$db = new mysqli($host, $benutzer, $passwort, $datenbank);
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

?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Best Players</h1>
      </div>
	<div class="container-fluid">
    <div class="row my-3 justify-content-center">
    <div class="col-sm-9 mb-3 mb-sm-0">	
	
<?php
$authors = $connection->query("SELECT * FROM rankme ORDER BY score DESC LIMIT 3")->fetchAll();

 $getuk = mysqli_query($db,"SELECT * FROM rankme ORDER BY score DESC LIMIT 0,04");
 $ukcounter=mysqli_num_rows($getuk);
 
 if($ukcounter==0){ 
	 echo '<div class="alert alert-danger"><b>Oh!</b> Currently no player found!  '.$ukcounter.' </div>';
 }else {

 ?>
     <!-- Podium Code -->
    <div class="card-group">
      <div class="card cards2 mb-3 text-center" style="max-width: 18rem;">
      <img src="img/podium/place2.png" class="card-img-top"  alt="...">
    <br>
    <?php 

       $rank = 0;
       foreach($authors as $author):
       $rank++;  
       
       // ------------------------ Avatar Function
       $authid = $author['steam'];
		  		  
       $json = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=741466C2C1DB9732369BAEEACA0251A8&steamids='. getFriendId($authid) .'');
           $parsed = json_decode($json);
       
               $parseddata = $parsed->response->players;
           
           // ------------------------ Avatar Function End
	
	     if($rank < 3 and  $rank > 1){

        if($author['steam'] == "BOT"){
          echo '<center><img src=img/podium/noavatar.png class=rounded float-start width=140 height=90 title="NO AVATAR"> </center>';
        }else{

          foreach($parseddata as $player):
            echo '<center><img src=' . $player->avatarfull . ' class=rounded float-start width=50 height=50> </center>';
          endforeach;	
        }


		    echo '<p><a href="showplayer.php?id='.$author['id'].'">'.$author['name'].'</a><br>';
		    echo ''.$author['score'].' Points';
	    }

    endforeach;	

      ?>	</p>
        <div class="card-body text-bg-info">
          <h1 class="card-title text-center" style="font-size: 70px;"><b>2</b></h1>
        </div>
      </div>
      <div class="card cards1 mb-3  text-center" style="max-width: 18rem; max-hight: 540px;">
      <img src="img/podium/place1.png" class="card-img-top"  alt="...">
    <br><p>
       <?php
       $rank = 0;
       foreach($authors as $author):
       $rank++; 
       
        
       // ------------------------ Avatar Function
       $authid = $author['steam'];
		  		  
       $json = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=741466C2C1DB9732369BAEEACA0251A8&steamids='. getFriendId($authid) .'');
           $parsed = json_decode($json);
       
               $parseddata = $parsed->response->players;
           
           // ------------------------ Avatar Function End
	
	     if($rank < 2 and  $rank > 0){

        if($author['steam'] == "BOT"){
          echo '<center><img src=img/podium/noavatar.png class=rounded float-start width=120 height=70 title="NO AVATAR"> </center>';
        }else{

          foreach($parseddata as $player):
            echo '<center><img src=' . $player->avatarfull . ' class=rounded float-start width=50 height=50> </center>';
          endforeach;	
        }

		    echo '<a href="showplayer.php?id='.$author['id'].'">'.$author['name'].'</a><br>';
		    echo ''.$author['score'].' Points';
	    }
    endforeach;	 
      ?>
      </p>
        <div class="card-body text-bg-warning">
        <H1 class="card-title text-center" style="font-size: 100px;"><b>1</b></H1>
        </div>
      </div>
      <div class="card cards3 mb-3 text-center" style="max-width: 18rem;">
      <img src="img/podium/place3.png" class="card-img-top" alt="...">
      <br><p>
    <?php 
        $rank = 0;
        foreach($authors as $author):
        $rank++;  
        
         // ------------------------ Avatar Function
       $authid = $author['steam'];
		  		  
       $json = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=741466C2C1DB9732369BAEEACA0251A8&steamids='. getFriendId($authid) .'');
           $parsed = json_decode($json);
       
               $parseddata = $parsed->response->players;
           
           // ------------------------ Avatar Function End
	
	     if($rank < 4 and  $rank > 2){
		    
        if($author['steam'] == "BOT"){
          echo '<center><img src=img/podium/noavatar.png class=rounded float-start width=140 height=90 title="NO AVATAR"> </center>';
        }else{

          foreach($parseddata as $player):
            echo '<center><img src=' . $player->avatarfull . ' class=rounded float-start width=50 height=50> </center>';
          endforeach;	
        }

		    echo '<a href="showplayer.php?id='.$author['id'].'">'.$author['name'].'</a><br>';
		    echo ''.$author['score'].' Points';
	    }
    endforeach;	 
      ?>	</p>
        <div class="card-body text-bg-success">
        <h1 class="card-title text-center"><b>3</b></h1>
        </div>
      </div>
    </div>
    
    <!-- End Podium Code -->
<?php
}
?>
 </div>
 </div>
 </div>
 </main>
 <?php	
	  include ('footer.php');
?>