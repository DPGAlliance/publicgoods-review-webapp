    <div class="row">
      <div class="col-md-12">
        <?php 
        echo $this->session->flashdata('message');  
        ?>
          <h5>Under Review</h5>
      </div>
    </div>
    <div class="row">
      <div class="table-responsive">
        <table class="table table-bordered border-secondary solution_table">
          <thead>
            <tr>
              <th scope="col">Application ID</th>
              <th scope="col">Solution Name</th>
              <th scope="col">Tags</th>
              <th scope="col">Assigned On / Time Remaining</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php

            if(count($under_review_application)> 0)
            {
              foreach ($under_review_application as $key => $application) {
                echo "<tr>";
                echo "<td> " .$application['id']. "</td>";
                echo "<td> " .$application['answer']. "</td>";
                echo "<td> ";
                 // .$application['tags'].'
                $tags_string = $application['tags'];
                if($tags_string)
                {
                  $tags_array = explode(',', $tags_string);
                  foreach ($tags_array as $key => $tag_id) {
                    echo '<span class="custom_badge badge rounded-pill ';
                    echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['mode'];
                     echo '">';
                     echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['name'];
                     echo '</span>';
                  }
                }
                 echo "</td>";
                 echo "<td> ";
                 if($user_role == 2)
                 {
                  echo date("d-m-Y h:i A", strtotime($application['l1_assign_on']));
                  $limit_in_hours = $dpga_limits[0]['l1review'];
                  echo " UTC<br>";
                  $l1_assign_on_str = strtotime($application['l1_assign_on']);
                  $allow_till = strtotime("+" .$limit_in_hours. " hours", $l1_assign_on_str);

                  $time_remaining = $allow_till - strtotime("now");

                  $allow_till_datetime = new DateTime(date("Y-m-d h:i A", $allow_till));
                  $diff = $allow_till_datetime->diff(new DateTime());   
                  echo "<font color='";
                  if($time_remaining > 0)
                  {
                    echo "blue'>";
                  }else{
                    echo "red'>- ";
                  }
                  // echo $diff->d.' Days<br>'; 
                  echo $diff->h.' Hours '; 
                  echo $diff->i.' Minutes Left'; 
                  echo "</font>";
                 
                  // echo date("d-m-Y h:i A", strtotime($allow_till));
                 } else if($user_role == 3)
                 {

                  echo date("d-m-Y h:i A", strtotime($application['l2_assign_on']));
                  $limit_in_hours = $dpga_limits[0]['l2review'];
                  echo " UTC<br>";

                  
                  $l2_assign_on_str = strtotime($application['l2_assign_on']);
                  $allow_till = strtotime("+" .$limit_in_hours. " hours", $l2_assign_on_str);
                  
                  $consultation_duration = $application['consultation_duration'];
                  $allow_till = $allow_till + $consultation_duration;

                  $time_remaining = $allow_till - strtotime("now");

                  $allow_till_datetime = new DateTime(date("Y-m-d h:i A", $allow_till));
                  $diff = $allow_till_datetime->diff(new DateTime());   
                  echo "<font color='";
                  if($time_remaining > 0)
                  {
                    echo "blue'>";
                  }else{
                    echo "red'>- ";
                  }
                  // echo $diff->d.' Days<br>'; 
                  echo $diff->h.' Hours '; 
                  echo $diff->i.' Minutes Left'; 
                  echo "</font>";

                 }
                 echo "</td>";
                 echo "<td>";
                 echo anchor("start/review/" .$application['id']. "", 'Review', array('class' => 'btn btn-info btn-sm')); 

                echo "</td>";
                echo "</tr>";
              }
            }else{
              echo '<tr>
              <td colspan="5" style="color: red;">No Application found in under review. Please pull from below table</td>
            </tr>';
            }

             ?>
            
          </tbody>

        </table>
      </div>
    </div> <!-- table div end -->
    <?php
    if($user_role == 3)
    { ?>
<div class="row">
      <div class="col-md-12">
          <h5>Under Consultation</h5>
      </div>
    </div>

    <div class="row">
      <div class="table-responsive">
        <table class="table table-bordered border-secondary solution_table">
          <thead>
            <tr>
              <th scope="col">Application ID</th>
              <th scope="col">Solution Name</th>
              <th scope="col">Tags</th>
              <th scope="col">Moved to consultation on</th>
               <th scope="col">Experts Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(count($under_consultation_application)> 0)
            {
              foreach ($under_consultation_application as $key => $application) {
                echo "<tr>";
                echo "<td> " .$application['id']. "</td>";
                echo "<td> " .$application['answer']. "</td>";
                echo "<td> ";
                 // .$application['tags'].'
                $tags_string = $application['tags'];
                if($tags_string)
                {
                  $tags_array = explode(',', $tags_string);
                  foreach ($tags_array as $key => $tag_id) {
                    echo '<span class="custom_badge badge rounded-pill ';
                    echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['mode'];
                     echo '">';
                     echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['name'];
                     echo '</span>';
                  }
                }
                 echo "</td>";
                 echo "<td> ";
                  echo date("d-m-Y h:i A", strtotime($application['to_consultation'])); 
                  echo "</td>";

                   echo "<td>";
                   $expert_name = "";
                   $experts_array = array();
                   $experts_total_array = array();
                   $total_asked_experts = array();
                   foreach ($experts_response as $key => $ind_response) {


                     if($ind_response['application_id'] == $application['id'])
                     {
                      
                      $expert_name = $ind_response['fname'];
                      
                      if (!array_key_exists($expert_name,$experts_array))
                        {

                         
                        $experts_array[$expert_name] = 0;
                        $experts_total_array[$expert_name] = 0;
                        }
                      if($ind_response['response'] == '')
                      {
                        $experts_array[$expert_name];
                        $experts_array[$expert_name] = $experts_array[$expert_name]+1;

                      }
                      $experts_total_array[$expert_name] = $experts_total_array[$expert_name]+1;
                     }
                     
                   }


                   foreach ($experts_array as $key => $pending_response) {

                    $total_asked = $experts_total_array[$key];
                    $total_respond = $total_asked-$pending_response;

                    if($pending_response == 0)
                    {
                      $respond_status = "Complete";
                      //echo $key; echo " - Complete"; echo "<br>";
                    }else{
                       $respond_status = "Pending";
                      //echo $key; echo " - Pending ("; echo $pending_response; echo "/"; 
                      //echo $experts_total_array[$key]; echo ")";
                      //echo "<br>";
                    }
                    echo "$key - $respond_status ($total_respond/$total_asked)<br>";
                   }
                   echo "</td>";


                  echo "<td>";
                  echo anchor("start/consultation/" .$application['id']. "", 'Review', array('class' => 'btn btn-info btn-sm')); 
                  echo "</td>";

                echo "</tr>";
              }

            }else{
              echo '<tr>
              <td colspan="5" style="color: red;">No Application found in under consultation.</td>
            </tr>';
            }
?>
             </tbody>

        </table>
      </div>
    </div> <!-- table div end -->



   <?php }
     ?>
    <div class="row">
      <div class="col-md-12">
          <h5><?php if($user_role == 2){ echo "L1";} else{ echo "L2";} ?> Queue</h5>
      </div>
    </div>
    <div class="row">
      <div class="table-responsive">
        <table class="table table-bordered border-secondary solution_table">
          <thead>
            <tr>
              <th scope="col">Application ID</th>
              <th scope="col">Solution Name</th>
              <!-- <th scope="col">Existing Reviewer</th> -->
              <th scope="col">Tags</th>
              <th scope="col">Submit on</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            if(count($queue_applications_list)>0)
            {
              foreach ($queue_applications_list as $key => $application) {
                echo "<tr>";
                echo "<td> " .$application['id']. "</td>";
                echo "<td> " .$application['answer']. "</td>";
                /*
                echo "<td>";
                 if($application['previous_l1_fname'])
                 {
                  echo "L1 - ";
                  echo $application['previous_l1_fname'];
                  echo " ";
                  echo $application['previous_l1_lname'];
                  echo "<br>";
                  echo "L2 - ";
                  echo $application['previous_l2_fname'];
                  echo " ";
                  echo $application['previous_l2_lname'];
                 }
                 echo "</td>";
                 */
                echo "<td> ";
                 // .$application['tags'].'
                $tags_string = $application['tags'];
                if($tags_string)
                {
                  $tags_array = explode(',', $tags_string);
                  foreach ($tags_array as $key => $tag_id) {
                    echo '<span class="custom_badge badge rounded-pill ';
                    echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['mode'];
                     echo '">';
                     echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['name'];
                     echo '</span>';
                  }
                }
                 echo "</td>";
                 echo "<td>";
                echo date("d-m-Y h:i A", strtotime($application['submitted_on']));
                echo " UTC </td>";
                echo "<td>";
                 echo anchor("pull/" .$application['id']. "", 'Pull', array('class' => 'btn btn-primary btn-sm')); 

                echo "</td>";
                echo "</tr>";
              }
            }else{
              echo '<tr>
              <td colspan="6" style="color: red;">No new application found for review.</td>
            </tr>';
            }
            ?>
            
          </tbody>

        </table>
      </div>
    </div> <!-- table div end -->


 <?php
    if($user_role == 3)
    { ?>

<div class="row">
      <div class="col-md-12">
          <h5>Waiting for Clarifications</h5>
      </div>
    </div>


    <div class="row">
      <div class="table-responsive">
        <table class="table table-bordered border-secondary solution_table">
          <thead>
            <tr>
              <th scope="col">Application ID</th>
              <th scope="col">Solution Name</th>
              <th scope="col">Tags</th>
              <th scope="col">Moved to clarifications on</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(count($clarifications_application)> 0)
            {
              foreach ($clarifications_application as $key => $application) {
                echo "<tr>";
                echo "<td> " .$application['id']. "</td>";
                echo "<td> " .$application['answer']. "</td>";
                echo "<td> ";
                 // .$application['tags'].'
                $tags_string = $application['tags'];
                if($tags_string)
                {
                  $tags_array = explode(',', $tags_string);
                  foreach ($tags_array as $key => $tag_id) {
                    echo '<span class="custom_badge badge rounded-pill ';
                    echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['mode'];
                     echo '">';
                     echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['name'];
                     echo '</span>';
                  }
                }
                 echo "</td>";
                 echo "<td> ";
                  echo date("d-m-Y h:i A", strtotime($application['to_clarifications']));
                  $clarifications_days = $application['clarifications_days'];
                  echo " UTC<br>";
                  $allow_till = strtotime("+" .$clarifications_days. " days", strtotime($application['to_clarifications']));
                  $time_remaining = $allow_till - strtotime("now");
                  $allow_till_datetime = new DateTime(date("Y-m-d h:i A", $allow_till));
                  $diff = $allow_till_datetime->diff(new DateTime()); 
                  echo "<font color='";
                  if($time_remaining > 0)
                  {
                    echo "blue'>";
                  }else{
                    echo "red'>- ";
                  }
                  
                  if(is_numeric($diff->m) && $diff->m > 0)
                  {
                    echo $diff->m.' Months ';
                  }
                  echo $diff->d.' Days '; 
                  echo $diff->h.' Hours '; 
                  echo $diff->i.' Minutes Left'; 
                  echo "</font>";
                 
                  // echo date("d-m-Y h:i A", strtotime($allow_till));
                 
                 echo "</td>";
                echo "</tr>";
              }

            }else{
              echo '<tr>
              <td colspan="5" style="color: red;">No Application found in under clarifications.</td>
            </tr>';
            }
?>
             </tbody>

        </table>
      </div>
    </div> <!-- table div end -->


<?php } ?>

  </div>
</div>
<!-- Modal code start -->
<div class='modal fade' id='create_new_application' tabindex='-1' aria-labelledby='screate_new_application_label' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <h3 class='modal-title' id='create_new_application_label'>Create a new application</h3>
      </div>
      <div class='modal-body'>
        <p class='small pt-2'>Only one digital solution can be submitted in each application. If you have multiple digital solutions, please create separate applications for each.</p>
        <?php 
        echo form_open('form/new', 'id="new_application_form"');
        ?>
        <div class='mb-3'>
          <input type='text' class='form-control' id='project_name' name='project_name' placeholder='Enter name of the digital solution' required>
        </div>
        <div class='form-check'>
          <input class='form-check-input' type='checkbox' value='' id='terms_1'>
          <label class='form-check-label' for='terms_1'>
            I hereby declare that I am authorized to apply on behalf of the person(s) and/or organization(s) that own this digital solution.
          </label>
        </div>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>Cancel</button>
        <?php

        echo '<button class="btn btn-outline-secondary" id="app_start_submit" type="submit" disabled>Create New Application</button>';
        echo form_close();

        ?>


      </div>
    </div>
  </div>
</div>



<!-- To handle create new application -->
<script type="text/javascript">
  var terms_1 = document.getElementById('terms_1');
  terms_1.addEventListener('click', checked, false);

  function checked() {

    if (terms_1.checked) {
      document.getElementById('app_start_submit').removeAttribute('disabled');
      document.getElementById('app_start_submit').removeAttribute('class');
      document.getElementById('app_start_submit').setAttribute('class', 'btn btn-primary');
    } else {
      document.getElementById('app_start_submit').removeAttribute('class');
      document.getElementById('app_start_submit').setAttribute('class', 'btn btn-outline-secondary');
      document.getElementById('app_start_submit').setAttribute('disabled', 'disabled');
    }

  }
</script>

<?php 
$force_model = $this->input->get('force_model');
if($force_model == 1)
{
  ?>
  <script type="text/javascript">
    window.onload = () => {
      $('#create_new_application').modal('show');
    }
  </script>

  <?php
}

?>

