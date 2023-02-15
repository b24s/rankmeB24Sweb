<?php 
  // Database
  include_once("../config.php");
  include ('header2.php');
  
  global $bd_table; 
  $db = new mysqli($host2, $benutzer2, $passwort2, $datenbank2);
 if($db->connect_errno > 0){
	 die('Fehler beim Verbinden: ' . $db->connect_error);
 }
 // LIMIT 0,7
 $getuk = mysqli_query($db,"SELECT * FROM rankme ORDER BY headshots DESC LIMIT 0,10");
 $getuk2 = mysqli_query($db,"SELECT * FROM rankme");
 $ukcounter=mysqli_num_rows($getuk2);

 // Vips Total
$result = mysqli_query($db, "SELECT count(*) FROM vip_users");
$roww = mysqli_fetch_row($result);
$total = $roww[0];
 
 $getuk3 = mysqli_query($db,"SELECT lastconnect FROM rankme");
 $lastcounter=mysqli_num_rows($getuk3);
 
 $time = time()-60;
 $querytime = mysqli_query($db,"SELECT lastconnect FROM rankme where lastconnect > '".$time."'");
 $lastcounter2=mysqli_num_rows($querytime);
 
 $query = "SELECT sum(headshots), sum(score), sum(kills) FROM rankme";
 $result = mysqli_query($db, $query);
 
 $row = mysqli_fetch_array($result);
 $total_headshots = $row[0];
 $total_scores = $row[1];
 $total_kills = $row[2];

$getuk_score = mysqli_query($db,"SELECT * FROM rankme ORDER BY hits DESC LIMIT 0,10 ");
$score_ukcounter=mysqli_num_rows($getuk_score);


$getuk_kills = mysqli_query($db,"SELECT * FROM rankme ORDER BY kills DESC LIMIT 0,10 ");
$kills_ukcounter=mysqli_num_rows($getuk_kills);


$query2 = "SELECT sum(rounds_ct) FROM rankme";
$result2 = mysqli_query($db, $query2);

$row2 = mysqli_fetch_array($result2);
$total_ct_wins = $row2[0];

$query3 = "SELECT sum(rounds_tr) FROM rankme";
$result3 = mysqli_query($db, $query3);

$row3 = mysqli_fetch_array($result3);
$total_tr_wins = $row3[0];

// Hitbox 
$hitbox = "SELECT sum(head),sum(chest),sum(stomach),sum(left_arm),sum(right_arm),sum(left_leg),sum(right_leg) FROM rankme";
$res = mysqli_query($db, $hitbox);
$row_hitbox = mysqli_fetch_array($res);
$total_head = $row_hitbox[0];
$total_chest = $row_hitbox[1];
$total_stomach = $row_hitbox[2];
$total_leftarm = $row_hitbox[3];
$total_rightarm = $row_hitbox[4];
$total_leftleg = $row_hitbox[5];
$total_rightleg = $row_hitbox[6];


$query4 = "SELECT sum(c4_planted) FROM rankme";
$result4 = mysqli_query($db, $query4);

$row4 = mysqli_fetch_array($result4);
$total_bombs_planted = $row4[0];

$query5 = "SELECT sum(c4_exploded) FROM rankme";
$result5 = mysqli_query($db, $query5);

$row5 = mysqli_fetch_array($result5);
$total_bombs_exploded = $row5[0];

$query6 = "SELECT sum(c4_defused) FROM rankme";
$result6 = mysqli_query($db, $query6);

$row6 = mysqli_fetch_array($result6);
$total_bombs_defused = $row6[0];
 
  ?>
   <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard Real time Statistics</h1>
      </div>
<div class="container-fluid">
    <div class="row my-3">
  <div class="col-sm-6 mb-3 mb-sm-0">
    <div class="card text-bg-secondary shadow border-left-primary py-2 mb-3 rounded">
      <div class="card-body">
	  <div class="row row-cols-auto">
	  <div class="col">
	  <span class="material-symbols-rounded md-48">group</span>
	  </div>
    <div class="col-sm-6">
      <h5 class="card-title"> Total Players</h5>
    </div>
    <div class="col">
      <h5 class="card-text"><a class="nav-link" href="points2.php"><?php echo $ukcounter; ?></a></h5>
    </div>
  </div>
  <div class="row row-cols-auto">
	  <div class="col">
	  <span class="material-symbols-rounded md-48">rocket_launch</span>
	  </div>
    <div class="col-sm-6">
      <h5 class="card-title"> Total Vips</h5>
    </div>
    <div class="col">
      <h5 class="card-text"><a class="nav-link" href="vipslist2.php"><?php echo $total; ?></a></h5>
    </div>
  </div>
  <div class="row row-cols-auto">
	  <div class="col">
	  <span class="material-symbols-rounded md-48">online_prediction</span>
	  </div>
    <div class="col-sm-6">
      <h5 class="card-title"> Total Players Online</h5>
    </div>
    <div class="col">
      <h5 class="card-text"><a class="nav-link" href="playeronline2.php"><?php echo ''.$lastcounter2.''; ?></a></h5>
    </div>
  </div>
  
      </div>
    </div>
  </div>
  <div class="col-sm-6 mb-3 mb-sm-0">
    <div class="card text-bg-info shadow border-left-primary py-2 mb-3 rounded">
      <div class="card-body">
         <div class="row row-cols-auto">
		 <div class="col">
	 <span class="material-symbols-rounded md-48">skull</span>
	  </div>
    <div class="col-sm-6">
      <h5 class="card-title">Total Headshots</h5>
    </div>
    <div class="col">
      <h5 class="card-text"><?php echo $total_headshots; ?>
    </div>
  </div>
  <div class="row row-cols-auto">
		 <div class="col">
	 <span class="material-symbols-rounded md-48">trending_up</span>
	  </div>
    <div class="col-sm-6">
      <h5 class="card-title">Total Scores</h5>
    </div>
    <div class="col">
      <h5 class="card-text"><?php echo $total_scores; ?>
    </div>
  </div>
  <div class="row row-cols-auto">
		 <div class="col">
	 <span class="material-symbols-rounded md-48">whatshot</span>
	  </div>
    <div class="col-sm-6">
      <h5 class="card-title">Total Kills</h5>
    </div>
    <div class="col">
      <h5 class="card-text"><?php echo $total_kills; ?>
    </div>
  </div>
      </div>
    </div>
  </div>
</div>
</div>

<div class="container-fluid">
    <div class="row my-2">
        <div class="col-md-6 py-1">
            <div class="card">
                <div class="card-body">
				<div class="chartcontainer">
                    <canvas id="chLine"></canvas>
					</div>
                </div>
            </div>
        </div>
        <div class="col-md-6 py-1">
            <div class="card">
                <div class="card-body">
				<div class="chartcontainer">
                    <canvas id="chBar"></canvas>
					</div>
                </div>
            </div>
        </div>
    </div>
    <div class="row py-2">
        <div class="col-md-4 py-1">
            <div class="card">
                <div class="card-body">
				<div class="chartcontainer2">
                    <canvas id="chDonut1"></canvas>
					</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 py-1">
            <div class="card">
                <div class="card-body">
				<div class="chartcontainer2">
                    <canvas id="chDonut2"></canvas>
					</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 py-1">
            <div class="card">
                <div class="card-body">
				<div class="chartcontainer2">
                    <canvas id="chDonut3"></canvas>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

	  </main>
	  
	  <?php

        // date('Y-m-d',$data['lastconnect']);
		// $data['$lastcounter'];
        foreach($getuk as $data)
        {
          $day[] = $data['name'];
          $con[] = $data['headshots'];
        }		
		?>
		
		 <?php
        foreach($getuk_score as $data)
        {
          $spieler[] = $data['name'];
          $punkte[] = $data['score'];
		  $hits[] = $data['hits'];
        }		
		?>
		
		<?php
        foreach($getuk_kills as $data)
        {
          $spieler_kills[] = $data['name'];
		  $kills[] = $data['kills'];
          $deaths[] = $data['deaths'];
        }		
		?>
		
  
 <!--
    <div class="chartCard">
      <div class="chartBox">
        <canvas id="myChart"></canvas>
      </div>
    </div>
	-->
		
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* chart.js chart examples */

// chart colors
var colors = ['#007bff','#28a745','#333333','#c3e6cb','#dc3545','#6c757d'];

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

/* large line chart */
var chLine = document.getElementById("chLine");
var chartData = {
  labels: <?php echo json_encode($spieler) ?>,
  datasets: [{
	label: ' Top 10 Scores',
    data: <?php echo json_encode($punkte) ?>,
    fill: true,
    backgroundColor: 'rgba(60,179,113,0.4)',
    borderColor: 'rgba(60,179,113,1)',
    borderWidth: 1,
	tension: 0.1
  },
  {
    label: " Top 10 Hits",	
	fill: true,
    backgroundColor: 'rgba(89, 171, 227,0.4)',
    borderColor: 'rgba(9, 171, 227,1)',
    data: <?php echo json_encode($hits) ?>,
    borderWidth: 1,
	tension: 0.1
    }]
};
if (chLine) {
  new Chart(chLine, {
  type: 'line',
  data: chartData,
  options: {
	  maintainAspectRatio: false,
	  plugins: {
		customCanvasBackgroundColor: {
        color: '#F9f9f9',
      },
      title: {
        display: true,
        text: 'Top 10 Scores and Hits',
      }
    },
    scales: {
      xAxes: [{
        ticks: {
          beginAtZero: false
        }
      }]
    },
    legend: {
      display: false
    },
    responsive: true
  },
   plugins: [plugin],
  });
}

/* large pie/donut chart 
var chPie = document.getElementById("chPie");
if (chPie) {
  new Chart(chPie, {
    type: 'pie',
    data: {
      labels: ['Desktop', 'Phone', 'Tablet', 'Unknown'],
      datasets: [
        {
          backgroundColor: [colors[1],colors[0],colors[2],colors[5]],
          borderWidth: 0,
          data: [50, 40, 15, 5]
        }
      ]
    },
    plugins: [{
      beforeDraw: function(chart) {
        var width = chart.chart.width,
            height = chart.chart.height,
            ctx = chart.chart.ctx;
        ctx.restore();
        var fontSize = (height / 70).toFixed(2);
        ctx.font = fontSize + "em sans-serif";
        ctx.textBaseline = "middle";
        var text = chart.config.data.datasets[0].data[0] + "%",
            textX = Math.round((width - ctx.measureText(text).width) / 2),
            textY = height / 2;
        ctx.fillText(text, textX, textY);
        ctx.save();
      }
    }],
    options: {layout:{padding:0}, legend:{display:false}, cutoutPercentage: 80}
  });
}
*/
/* bar chart */
var chBar = document.getElementById("chBar");
if (chBar) {
  new Chart(chBar, {
  type: 'bar',
  data: {
    labels: <?php echo json_encode($spieler_kills) ?>,
    datasets: [{
      label: ' Top 10 Kills',
      data: <?php echo json_encode($kills) ?>,
      backgroundColor: ['rgba(54, 162, 235, 0.2)'],
	  borderColor: ['rgba(54, 162, 235, 1)'],
	  borderWidth: 1
    },
    {
	  label: ' Top 10 Deaths',
      data: <?php echo json_encode($deaths) ?>,
      backgroundColor: ['rgba(255, 206, 86, 0.2)'],
	  borderColor: ['rgba(255, 206, 86, 1)'],
	  borderWidth: 1
    }]
  },
  options: {
	  maintainAspectRatio: false,
	   plugins: {
		customCanvasBackgroundColor: {
        color: '#F9f9f9',
      },
      title: {
        display: true,
        text: 'Top 10 Kills and Deaths',
      }
    },
    legend: {
      display: false
    },
    scales: {
      y: {
        beginAtZero: true
      }
    }
  },
  plugins: [plugin],
  });
}

/* 3 donut charts */
var donutOptions = {
  cutoutPercentage: 100.0, 
  legend: {position:'bottom', padding:5, labels: {pointStyle:'circle', usePointStyle:true}}
};

// donut 1
var chDonutData1 = {
    labels: [' Head ', ' Chest ' , ' Stomach ' , ' Left Arm ' , ' Right Arm ' , ' Left Leg ' , ' Right Leg '],
    datasets: [
      {
		data: [<?php echo json_encode($total_head) ?> , 
		<?php echo json_encode($total_chest) ?>,
		<?php echo json_encode($total_stomach) ?>,
		<?php echo json_encode($total_leftarm) ?>,
		<?php echo json_encode($total_rightarm) ?>,
		<?php echo json_encode($total_leftleg) ?>,
		<?php echo json_encode($total_rightleg) ?>],
        backgroundColor: ['rgba(255, 0, 0, 0.52)',
		'rgba(230, 14, 242, 0.4)',
		'rgba(255, 141, 0, 0.52)',
		'rgba(255, 0, 135, 0.52)',
		'rgba(89, 171, 227,0.4)',
		'rgba(255, 159, 64, 0.2)',
		'rgba(60,179,113,0.4)'],
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
        text: 'Hitbox',
      },
      customCanvasBackgroundColor: {
        color: '#F9f9f9',
      }
    }
  },
  plugins: [plugin],
  });
}

// donut 2
var chDonutData2 = {
    labels: [' CT WIN ', ' TR WIN '],
    datasets: [
      {
		data: [ <?php echo json_encode($total_ct_wins) ?> , <?php echo json_encode($total_tr_wins) ?>],
        backgroundColor: ['rgba(89, 171, 227,0.4)','rgba(255, 159, 64, 0.2)'],
      }
    ],
	hoverOffset: 4
};
var chDonut2 = document.getElementById("chDonut2");
if (chDonut2) {
  new Chart(chDonut2, {
      type: 'pie',
      data: chDonutData2,
      options: {
		  maintainAspectRatio: false,
      plugins: {
		title: {
        display: true,
        text: 'Rounds Win',
      },
      customCanvasBackgroundColor: {
        color: '#F9f9f9',
      }
    }
  },
  plugins: [plugin],
  });
}

// donut 3
var chDonutData3 = {
    labels: ['Planted Bombs', 'Exploded Bombs', 'Defused Bombs'],
    datasets: [
      {
		backgroundColor: ['rgba(60,179,113,0.4)','rgba(255, 159, 64, 0.2)','rgba(89, 171, 227,0.4)'],
        data: [<?php echo json_encode($total_bombs_planted) ?> , 
		<?php echo json_encode($total_bombs_exploded) ?> , 
		<?php echo json_encode($total_bombs_defused) ?>]
      }
    ],
	hoverOffset: 4
};
var chDonut3 = document.getElementById("chDonut3");
if (chDonut3) {
  new Chart(chDonut3, {
      type: 'pie',
      data: chDonutData3,
      options: {
		  maintainAspectRatio: false,
      plugins: {
		 title: {
        display: true,
        text: 'Bombs Statistics',
      },
      customCanvasBackgroundColor: {
        color: '#F9f9f9',
      }
    }
  },
  plugins: [plugin],
  });
}

/* 3 line charts 
var lineOptions = {
    legend:{display:false},
    tooltips:{interest:false,bodyFontSize:11,titleFontSize:11},
    scales:{
        xAxes:[
            {
                ticks:{
                    display:false
                },
                gridLines: {
                    display:false,
                    drawBorder:false
                }
            }
        ],
        yAxes:[{display:false}]
    },
    layout: {
        padding: {
            left: 6,
            right: 6,
            top: 4,
            bottom: 6
        }
    }
};

var chLine1 = document.getElementById("chLine1");
if (chLine1) {
  new Chart(chLine1, {
      type: 'line',
      data: {
          labels: ['Jan','Feb','Mar','Apr','May'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [10, 11, 4, 11, 4],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}
var chLine2 = document.getElementById("chLine2");
if (chLine2) {
  new Chart(chLine2, {
      type: 'line',
      data: {
          labels: ['A','B','C','D','E'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [4, 5, 7, 13, 12],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}

var chLine3 = document.getElementById("chLine3");
if (chLine3) {
  new Chart(chLine3, {
      type: 'line',
      data: {
          labels: ['Pos','Neg','Nue','Other','Unknown'],
          datasets: [
            {
              backgroundColor:'#ffffff',
              borderColor:'#ffffff',
              data: [13, 15, 10, 9, 14],
              fill: false
            }
          ]
      },
      options: lineOptions
  });
}
*/
</script>


<?php	
	  include ('footer.php');
?>