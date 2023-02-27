<h3>Logs data <small><a href="<?php echo base_url("admin/logs/export/$application_id/$user_id/$date1/$date2"); ?>" class="btn btn-primary btn-sm" target="_blank">Click to Export</a></small></h3>
<?php 
echo form_open('admin/logs/process_filter', 'id="log_process_filter"');


?>
  <div class="row">
  <div class="col">
    <label for="application_id" class="form-label">Select Application</label>
   <select class="form-select" name="application_id" id="application_id" aria-label="application_id">
  <option value="0"
<?php
if($application_id == 0)
    {
      echo " selected";
    }

 ?>
  >All Applications</option>
  <?php
  foreach ($list_directory as $key => $single_application) {
    echo '<option value="';
    echo $single_application['id'];
    echo '"';
    if($application_id == $single_application['id'])
    {
      echo " selected";
    }
    echo '>';
    echo $single_application['solution_name'];
    echo '</option>';
  }


   ?>
</select>
  </div>
  <div class="col">
    <label for="user_id" class="form-label">Select User</label>
   <select class="form-select" name="user_id" id="user_id" aria-label="user_id">
  <option value="0"
<?php
if($user_id == 0)
    {
      echo " selected";
    }

 ?>
  >All Users</option>

   <option value="system"
<?php
if($user_id == "system")
    {
      echo " selected";
    }

 ?>
  >Only System Logs</option>
  <?php
  foreach ($list_users as $key => $single_user) {
    echo '<option value="';
    echo $single_user['id'];
    echo '"';
    if($user_id == $single_user['id'])
    {
      echo " selected";
    }
    echo '>';
    echo $single_user['fname'];
    echo " (";
    echo $single_user['role_name'];
    echo ")";
    echo '</option>';
  }


   ?>
</select>
  </div>
  <div class="col">
    <label for="date1" class="form-label">Date 1</label>
    <input type="date" class="form-control" id="date1" name="date1" 
    <?php 
    if($date1 != 0)
    {
      echo 'value = "';
      echo $date1;
      echo '"';
    }
    ?>
     placeholder="select date 1">
  </div>

  <div class="col">
    <label for="date2" class="form-label">Date 2</label>
    <input type="date" class="form-control" id="date2" name="date2"
    <?php 
    if($date2 != 0)
    {
      echo 'value = "';
      echo $date2;
      echo '"';
    }
    ?>
     placeholder="select date 2">
  </div>

  <div class="col mt-4 pt-2">
    <button type="submit" class="btn btn-primary btn-sm mt-1">Filter</button>
    <a class="btn btn-warning btn-sm mt-1" href="<?php echo base_url("admin/logs/0/0/0/0/0"); ?>">Remove Filters</a>
  </div>
<?php
echo form_close();

 ?>

</div>

<div class="table-responsive">
<table class="table table-sm">
  <thead>
    <tr>
      <th>Sn</th>
      <th>Log details</th>
      <th>Perform on</th>
      
      <th>Application name</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $sn=$table_row_count_start_value;
foreach ($logs_list as $key => $single_log_details) {
  echo "<tr>";
  echo "<td>";
  echo $sn;
  echo "</td>";
  echo "<td>";
  echo $single_log_details['comment'];
  echo "</td>";

  echo "<td>";
 
  echo date("d-m-Y h:i A", strtotime($single_log_details['perform_on']));
   echo " UTC";
  echo "</td>";

  //echo "<td>";
  //echo $single_log_details['fname'];
  //echo "</td>";

    echo "<td>";
   
    $application_ids_array = array_column($list_directory, 'id');
        $application_id_key = array_search($single_log_details['application_id'], $application_ids_array);
        if (is_numeric($application_id_key)) {
          echo $list_directory[$application_id_key]['solution_name'];
        }

  echo "</td>";



  echo "</tr>";
  $sn = $sn+1;
}


    ?>
  </tbody>
  
</table>






    <style type="text/css">
          /* Pattern styles */
.left-half {
 
  float: left;
  width: 50%;
  font-size: 15px;
  padding-top: 15px;
}
.right-half {
 
  float: left;
  text-align: right;
  width: 50%;
}
        </style>
  <div style='margin-top: 10px; '>
    <div class="left-half">
      <?php
      $tr_limit = $sn-1;
      echo "Showing $table_row_count_start_value to $tr_limit of $allcount entries"; ?>
    </div>
    
  </div>


<div class="d-flex flex-row-reverse bd-highlight ">
  <div class='p-1 bd-highlight'>
          <?= $pagination; ?>
  </div>

</div>





  </div>      

	</div>
</div><!-- col-10 end -->
</div><!-- row end -->










</div><!-- container-fluid end -->



<script type="text/javascript">
 
  $( '#user_id' ).select2( {theme: "bootstrap-5",width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,} );
  $( '#application_id' ).select2( {theme: "bootstrap-5",width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,} );
</script>

