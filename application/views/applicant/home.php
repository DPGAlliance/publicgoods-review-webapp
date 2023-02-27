    <div class="row">
      <div class="col-md-12">
        <?php 
        echo $this->session->flashdata('message');  
        ?>
        
        <div class="d-flex bd-highlight">
  <div class="p-2 flex-grow-1 bd-highlight"><h5>My Applications</h5></div>
  <div class="p-2 bd-highlight">
  <?php 
echo '<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#create_new_application"><i class="bi bi-plus"></i> Create new application</button>';
  ?>
  </div>
</div>

</div>
</div>
<div class="row">
<div class="table-responsive">
        <table class="table table-bordered border-secondary solution_table">
          <thead>
            <tr>
              <th scope="col">Solution Name</th>
              <th scope="col">Application ID</th>
              <th scope="col">Timestamps</th>
              <th scope="col">Status</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            if(count($all_applications) == 0)
            {
              echo "<tr><td colspan='4'>No application found</td></tr>";
            }else{
              $applicant_status_array = array_column($all_app_status_for_applicant, 'applicant');
              $applicant_status_key_array = array_column($all_app_status_for_applicant, 'id');
              $parent_id_array = array();

              foreach ($all_applications as $key => $ind_app) {
                
                if($ind_app['parent_id'] > 0)
                {
                  $parent_id_array[$ind_app['parent_id']] = $ind_app['id'];
                }
                
              }

              
              foreach ($all_applications as $key => $single_application) {
                echo "<tr >";
                echo "<td >" .$single_application['answer']. "</td>";
                echo "<td>";
                

                $single_application_id = $single_application['id'];
                echo anchor(base_url("a/$single_application_id"), $single_application_id, array('title' => 'Click here to see application details',
                  'style' => 'text-decoration-line: underline',
                  'target' => '_blank'));

                echo "</td>";
                echo "<td>";
                
                echo '<font color="grey">Date Created:</font> ';
                echo date("d-m-Y h:i A", strtotime($single_application['added_on']));
                echo " UTC";
                echo "<br>";
                echo "<br>";
                echo '<font color="grey">Date Submitted:</font> ';
                echo date("d-m-Y h:i A", strtotime($single_application['submitted_on']));
                echo " UTC";
                echo "<br>";
                echo "<br>";
                echo '<font color="grey">Date Reviewed:</font> ';
                if($single_application['review_complete_on'])
                {
                  echo date("d-m-Y h:i A", strtotime($single_application['review_complete_on']));
                  echo " UTC";
                  
                }
                echo "<br>";
                  echo "<br>";
                

                echo '<font color="grey">Date of Expiry:</font> ';
                if($single_application['expire_on'])
                {
                  echo date("d-m-Y h:i A", strtotime($single_application['expire_on']));
                  echo " UTC";
                  
                }
                echo "<br>";
                  echo "<br>";




 echo " </td>";

 echo "<td>";

if($single_application['status'] == 0)
      {
        echo '<span class="badge bg-danger">INCOMPLETE</span>';
        echo '<br>';
        echo '<div class="status_explainer">Your application is incomplete or has not been submitted by you yet.</div>';
      }else if($single_application['status'] == 6)
      {
        echo '<span class="badge bg-warning">CLARIFICATIONS REQUIRED</span>';
        echo '<br>';
        echo '<div class="status_explainer">Please submit additional clarifications required by the review team by ';
        $clarifications_asked_on = $single_application['to_clarifications'];
        $clarifications_days = $single_application['clarifications_days'];
        echo date('d-m-Y h:i A', strtotime($clarifications_asked_on. " + " .$clarifications_days. " days"));
                echo ' UTC </div>';
      }else if($single_application['status'] == 7)
      {
        echo '<span class="badge bg-secondary">INELIGIBLE</span>';
        echo '<br>';
        echo '<div class="status_explainer">We have reviewed your application and found that it does not meet the DPG standard.</div>';
      }else if($single_application['status'] == 8)
      {
        echo '<span class="badge bg-success">DPG</span>';
        echo '<br>';
        echo '<div class="status_explainer">We have reviewed your application and found that it meets the DPG standard.</div>';

      }else if($single_application['status'] == 9)
      {
        echo '<span class="badge bg-danger">EXPIRED</span>';
        echo '<br>';
        echo '<div class="status_explainer">Your application has expired. To renew DPG status of your application, please submit latest information about your solution.</div>';
      }else{
        echo '<span class="badge bg-primary">UNDER REVIEW</span>';
        echo '<br>';
        echo '<div class="status_explainer">We are reviewing your application.</div>';
      }
  echo " </td>";     /*
      echo "<td>";
      $key_id = array_search($single_application['status'],$applicant_status_key_array,true);
      echo $applicant_status_array[$key_id];
      echo "</td>";
     
      echo "<td>";
      if($single_application['submitted_on'])
      {
        echo date("d-m-Y h:i A", strtotime($single_application['submitted_on']));
        echo " UTC";
      }
      echo "</td>";
       */
      echo "<td>";


if($single_application['status'] == 0)
      {
        $link = "resume/" .$single_application['id']. "";
        echo "<a class='btn btn-primary btn-sm' href='" .base_url($link). "' role='button'>Resume Application</a>";
      }else if($single_application['status'] == 6)
      {
       $link = "process/clarifications/" .$single_application['id']. "";
        echo "<a class='btn btn-primary btn-sm' href='" .base_url($link). "' role='button'>View & Respond</a>"; 
      }else if($single_application['status'] == 8)
      {
       

        $days_number = $dpga_limits[0]['to_refresher'];
        $app_expire_days   = $dpga_limits[0]['app_expire_days'];

        $filter_string = "-$days_number day";
        $allow_after =  strtotime($filter_string, strtotime($single_application['expire_on']));
        if($allow_after < time())
        {
          
          if (array_key_exists($single_application['id'],$parent_id_array))
          {
             $parent_id = $single_application['parent_id'];
             $temp_app_id = $parent_id_array[$single_application['id']];
             $temp_app_public_link = base_url("a/$temp_app_id");
            echo "<div class='status_explainer'>A renewal application with ";
              echo anchor(base_url("a/$temp_app_id"), $temp_app_id, array('title' => 'Click here to see application details',
                  'target' => '_blank'));
             echo " was created.</div>";
          }
        else
          {
            $link = "refresher/" .$single_application['id']. "";
            echo "<a class='btn btn-primary btn-sm' href='" .base_url($link). "' role='button'>Create Renewal Application</a>";
            echo "<br>";
            echo "<div class='status_explainer'>To ensure continuity of DPG status & to remain listed on the DPG Registry, please click on “Create Renewal Application” & submit the same with updated information.</div>";
          }
          
          
        }else{
          $link = "#";
          echo "<a class='btn btn-secondary btn-sm disabled' href='" .base_url($link). "' role='button' disabled>Create Renewal Application</a>";
          echo "<br>";
          echo "<div class='status_explainer'>You’ll be able to create a renewal application a $days_number days before expiry. We’ll send an email reminder.</div>";
        }
      }else if($single_application['status'] == 9)
      {
          $link = "process/viewmode/" .$single_application['id']. "";
          echo "<a class='btn btn-primary btn-sm' href='" .base_url($link). "' role='button'>View</a>";
          
          if (array_key_exists($single_application['id'],$parent_id_array))
          {
            $parent_id = $single_application['parent_id'];
             $temp_app_id = $parent_id_array[$single_application['id']];
             $temp_app_public_link = base_url("a/$temp_app_id");
            echo "<div class='status_explainer'>A renewal application with ";
              echo anchor(base_url("a/$temp_app_id"), $temp_app_id, array('title' => 'Click here to see application details',
                  'target' => '_blank'));
             echo " was created.</div>";
          }else{
            $link = "refresher/" .$single_application['id']. "";
          echo "<a class='btn btn-primary btn-sm' href='" .base_url($link). "' role='button'>Create Renewal Application</a>";
          echo "<br>";
          echo "<div class='status_explainer'>To reinstate DPG status & get listed again on the DPG Registry, please click on “Create Renewal Application” & submit the same with updated information.</div>"; 
          }



          
      }else{
        $link = "process/viewmode/" .$single_application['id']. "";
          echo "<a class='btn btn-primary btn-sm' href='" .base_url($link). "' role='button'>View</a>";
      }

      echo "</td>";
      echo "</tr>";
    }
  }

  ?>
  <tr>
  </tr>
</tbody>

</table>
</div>
</div>
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

