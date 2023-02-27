

 		<div class="col-md-10">
 			<div class="custom_column_reviewer">
 				<div class="row">
 					<div class="col-md-12">
 						<div class="btn-group" role="group" aria-label="data1">
 	<a class="btn 
 	<?php
 	if($filter_mode == "yesterday")
 	{
 		echo "btn-primary";
 	}else{
 		echo "btn-outline-primary";
 		}

 	 ?>" href="<?php echo base_url("admin/dashboard/yesterday"); ?>">Yesterday</a>


<a class="btn 
 	<?php
 	if($filter_mode == "week")
 	{
 		echo "btn-primary";
 	}else{
 		echo "btn-outline-primary";
 		}

 	 ?>" href="<?php echo base_url("admin/dashboard/week"); ?>">Last 7 days</a>

<a class="btn 
 	<?php
 	if($filter_mode == "month")
 	{
 		echo "btn-primary";
 	}else{
 		echo "btn-outline-primary";
 		}

 	 ?>" href="<?php echo base_url("admin/dashboard/month"); ?>">Last 30 days</a>

   <a class="btn 
  <?php
  if($filter_mode == "all")
  {
    echo "btn-primary";
  }else{
    echo "btn-outline-primary";
    }

   ?>" href="<?php echo base_url("admin/dashboard/all"); ?>">All days</a>

  
</div>
 					</div>
 				</div>
<br>

<!-- row 1 start -->
 	<div class="row">
 		<div class="col text-center">
 			<h2><?php echo $not_complete; ?></h2>
 			<div>Applications not completed</div>
 		</div>
 		<div class="col text-center">
 			<h2><?php echo $applications_received; ?></h2>
 			<div>Applications received</div>
 		</div>
 		<div class="col text-center">
 			<h2><?php echo $review_completed; ?></h2>
 			<div>Review completed</div>
 		</div>
 	</div><!-- row 1 end -->
<br>
<div class="row">
	<div>
  <canvas id="graph1"></canvas>
</div>
</div>




 		</div>	<!-- custom_column_reviewer end -->


 		<div class="custom_column_reviewer mt-2">
 				
<div class="row">
 					<div class="col-md-12">
 						<div class="btn-group" role="group" aria-label="data1">
 	<button class="btn btn-primary">Overall</button>
  
</div>
 					</div>
 				</div>
 				<br>

<!-- row 1 start -->
 	<div class="row">
 		<div class="col text-center">
 			<h2><?php echo $dpgs_count; ?></h2>
 			<div>DPGs</div>
 		</div>
 		<div class="col text-center">
 			<h2><?php echo $nominees_count; ?></h2>
 			<div>Nominees</div>
 		</div>
 		<div class="col text-center">
 			<h2><?php echo $ineligible_count; ?></h2>
 			<div>Ineligible</div>
 		</div>
 	</div><!-- row 1 end -->






 		</div>	<!-- custom_column_reviewer end -->








<div class="custom_column_reviewer mt-2">
 				
<div class="row">
 					<div class="col-md-12">
 						<div class="btn-group" role="group" aria-label="data1">
 	<button class="btn btn-primary">Status Wise Report</button>
  
</div>
 					</div>
 				</div>
 				<br>

<!-- row 1 start -->
 	<div class="row">
 		<div class="col-md-12">
 			<table class="table table-sm">
 				<?php 
 				foreach ($status_wise_count as $key => $single_status_details) {
 					echo "<tr>";
 					echo "<td>";
$admin_status_array = array_column($all_app_status_for_admin, 'reviewer');
            $admin_status_key_array = array_column($all_app_status_for_admin, 'id');
            $key_id = array_search($single_status_details['status_id'],$admin_status_key_array,true);
           
            echo $status_name = $admin_status_array[$key_id];




 					echo "</td>";
					echo "<td>";
					echo "<a href='";
					echo base_url("admin/applications/?search=$status_name");
					echo "'>";
 					echo $single_status_details['total'];
 					echo "</a>";
 					echo "</td>";

 					echo "</tr>";
 				}


 				?>
 				
 			</table>
 		</div>
 	</div><!-- row 1 end -->






 		</div>	<!-- custom_column_reviewer end -->







<div class="custom_column_reviewer mt-2">



<!-- row 1 start -->
 	<div class="row">
 		<div class="col">
<div>
 <canvas id="graph3"></canvas>
</div>
 		</div>
 	</div><!-- row 1 end -->

 		</div>	<!-- custom_column_reviewer end -->






<div class="custom_column_reviewer mt-2">
 
<div class="row">
 					<div class="col-md-12">
 						<div class="btn-group" role="group" aria-label="data1">
 	<button class="btn btn-primary">Probability Based Report</button>
  
</div>
 					</div>
 				</div>
 				<br>


<!-- row 1 start -->
 	<div class="row">
 		<div class="col text-center">
 			<h2><?php echo $count_pending_applications; ?></h2>
 			<div>Applications Pending</div>
 		</div>
 		<div class="col text-center">
 			<h2><?php echo $per_day_app_process; ?></h2>
 			<div>Completed per day (based on last 7 days)</div>
 		</div>
 		<div class="col text-center">
 			<h2><?php echo round($count_pending_applications/$per_day_app_process); ?></h2>
 			<div>Days to clear all backlogs</div>
 		</div>
 


 	</div><!-- row 1 end -->

 		</div>	<!-- custom_column_reviewer end -->










<div class="custom_column_reviewer mt-2">
 
<div class="row">
 					<div class="col-md-12">
 						<div class="btn-group" role="group" aria-label="data1">
 	<button class="btn btn-primary">Late Application Details</button>
  
</div>
 					</div>
 				</div>
 				<br>


<!-- row 1 start -->
 	<div class="row">
 		<div class="col-md-12">
 			<table class="table table-sm">
 				<?php 
 				foreach ($status_wise_count_with_late as $key => $single_status_details) {
 					echo "<tr>";
 					echo "<td>";
$admin_status_array = array_column($all_app_status_for_admin, 'reviewer');
            $admin_status_key_array = array_column($all_app_status_for_admin, 'id');
            $key_id = array_search($single_status_details['status_id'],$admin_status_key_array,true);
           
            echo $status_name = $admin_status_array[$key_id];




 					echo "</td>";
					echo "<td>";
					echo "<a href='";
					echo base_url("admin/applications/?search=$status_name Late");
					echo "'>";
 					echo $single_status_details['total'];
 					echo "</a>";
 					echo "</td>";

 					echo "</tr>";
 				}


 				?>
 				
 			</table>
 		</div>
 


 	</div><!-- row 1 end -->

 		</div>	<!-- custom_column_reviewer end -->

<br>








































 		</div>
 	</div>
 	
 </div>










 <script>
  const labels = [
  <?php 
foreach ($graph1_data as $key => $single_graph_data) {
	echo "'";
	echo date("d M", strtotime($single_graph_data['date']));
	echo "',";
}
  ?>
  ];

  const data = {
    labels: labels,
    datasets: [{
      label: 'Application Received',
      backgroundColor: 'blue',
      borderColor: 'blue',
      data: [
<?php 
foreach ($graph1_data as $key => $single_graph_data) {
	
	echo $single_graph_data['application_received'];
	echo ",";
}
  ?>
      ],
    },
    {
      label: 'Decision Complete',
      backgroundColor: 'green',
      borderColor: 'green',
      data: [
<?php 
foreach ($graph1_data as $key => $single_graph_data) {
	
	echo $single_graph_data['decision_completed'];
	echo ",";
}
  ?>
      ],
    }
    ]
  };

  const config_graph1 = {
    type: 'line',
    data: data,
    options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Application Received vs Decision Completed Chart Old'
      }
    }
  },
  };






  const data3 = {
    labels: [
<?php 
foreach ($graph3_data_array as $key => $graph3_data) {
	echo "'";
	echo $key;
	echo "',";
}
?>
 ],
    datasets: [{
      label: 'Total Decision Completed',
      backgroundColor: 'blue',
      borderColor: 'blue',
      data: [
<?php 
foreach ($graph3_data_array as $key => $graph3_data) {
	echo "'";
	echo $graph3_data;
	echo "',";
}
?>
      ],
    }
    ]
  };


  const config_graph3 = {
    type: 'bar',
    data: data3,
    options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'top',
      },
      title: {
        display: true,
        text: 'Approx chart graph'
      }
    }
  },
  };
</script>
<script>
  const graph1 = new Chart(
    document.getElementById('graph1'),
    config_graph1
  );


  const graph3 = new Chart(
    document.getElementById('graph3'),
    config_graph3
  );
</script>
