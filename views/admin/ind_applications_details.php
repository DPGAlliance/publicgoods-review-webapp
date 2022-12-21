<h3>Applications Details</h3>
		
				<table class="table solution_table table-sm">
          <?php 
          echo "<tr>";
          echo "<td>Solution Name</td>";
          echo "<td class='bold_column'>$solution_name</td>";
          echo "</tr>";
          echo "<tr>";
          echo "<td>Application ID</td>";
          echo "<td class='bold_column'>$application_id</td>";
          echo "</tr>";
          echo "<tr>";

          echo "<td>Parent Application ID</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['parent_id'])
          {
            echo $ind_application_data[0]['parent_id'];
          }else{
            echo "NA";
          }
          echo "</td>";

          echo "</tr>";

          echo "<tr>";

          echo "<td>Status</td>";
          echo "<td class='bold_column'>$current_application_status ";
          echo '<i class="bi bi-arrow-repeat" data-bs-toggle="modal" data-bs-target="#updateAppDesicionModal"></i>';
          echo "</td>";
          echo "</tr>";
          echo "<tr>";

          echo "<td>Current L1 Reviewer</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['current_l1'])
          {
            echo $ind_application_data[0]['l1_fname'];
            echo " ";
            echo $ind_application_data[0]['l1_lname'];
          }else{
            echo "NA";
          }
          echo " ";
          echo '<i class="bi bi-arrow-repeat" data-bs-toggle="modal" data-bs-target="#updateL1ReviewerModal"></i>';
          echo "</td>";
          echo "</tr>";
          echo "<tr>";

          echo "<td>Current L2 Reviewer</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['current_l2'])
          {
            echo $ind_application_data[0]['l2_fname'];
            echo " ";
            echo $ind_application_data[0]['l2_lname'];
          }else{
            echo "NA";
          }
          echo " ";
          echo '<i class="bi bi-arrow-repeat" data-bs-toggle="modal" data-bs-target="#updateL2ReviewerModal"></i>';
          echo "</td>";
          echo "</tr>";
          echo "<tr>";
          echo "<td>Public URL</td>";
          echo "<td class='bold_column'>";
          echo anchor("a/" . $application_id . "", base_url("a/$application_id"), array('title' => base_url("a/$application_id"), 'class' => "",'target' => "_blank"));
          echo "</td>";

          echo "</tr>";
          echo "<tr>";

          echo "<td>Priority</td>";
          echo "<td class='bold_column'>";
          $tags_array = explode(",",$ind_application_data[0]['tags']);
          if(in_array(1, $tags_array))
          {
            echo "Yes ";
            echo "<a href='";
            echo base_url("admin/application/priority/remove/$application_id");
            echo "'>Remove Priority</a>";
          }else{
            echo "No ";
            echo "<a href='";
            echo base_url("admin/application/priority/add/$application_id");
            echo "'>Add to Priority</a>";
          }
          echo "</td>";
          echo "</tr>";
          echo "<tr>";

          echo "<td>Clarifications Requested</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['clarifications_days'])
          {
            echo "Yes";
          }else{
            echo "No";
          }
          echo "</td>";
          echo "</tr>";
          echo "<tr>";
          

          echo "<td>Date Created</td>";
          echo "<td class='bold_column'>";
          echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['added_on']));
          echo " UTC";
          echo "</td>";

          echo "</tr>";

          echo "<tr>";
          echo "<td>Date Submitted</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['submitted_on'])
          {
            echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['submitted_on']));
            echo " UTC";
          }else{
            echo "NA";
          }
          echo "</td>";
          echo "</tr>";
          echo "<tr>";



          echo "<td>Date L1 Review Started</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['l1_assign_on'])
          {
            echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['l1_assign_on']));
            echo " UTC";
          }else{
            echo "NA";
          }
          echo "</td>";
          echo "</tr>";
          echo "<tr>";

          echo "<td>Date L1 Review Complete</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['l1review_complete_on'])
          {
            echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['l1review_complete_on']));
            echo " UTC";
          }else{
            echo "NA";
          }
          echo "</td>";
          echo "</tr>";

          echo "<tr>";

          echo "<td>Date L2 Review Started</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['l2_assign_on'])
          {
            echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['l2_assign_on']));
            echo " UTC";
          }else{
            echo "NA";
          }
          echo "</td>";

          echo "</tr>";
          echo "<tr>";

          echo "<td>Date L2 Review Completed</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['review_complete_on'])
          {
            echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['review_complete_on']));
            echo " UTC";
          }else{
            echo "NA";
          }
          echo "</td>";

          echo "</tr>";
          echo "<tr>";
          echo "<td>Date of Expiry</td>";
          echo "<td class='bold_column'>";
          if($ind_application_data[0]['expire_on'])
          {
            echo date("d-m-Y h:i A", strtotime($ind_application_data[0]['expire_on']));
            echo " UTC";
          }else{
            echo "NA";
          }
          echo "</td>";

          echo "</tr>";

          echo "<tr>";
          echo "<td>Tags (if any)</td>";
          echo "<td>";
          if($ind_application_data[0]['tags'])
          {
            $tags_array = explode(',', $ind_application_data[0]['tags']);
            foreach ($tags_array as $key => $tag_id) {
                          echo '<span class="custom_badge badge rounded-pill ';
                          echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['mode'];
                           echo '">';
                           echo $all_tags_master_array[array_search($tag_id, array_column($all_tags_master_array, 'id'))]['name'];
                           echo '</span>';
            }
          }else{
            echo "NA";
          }
          echo " ";
          echo '<i class="bi bi-arrow-repeat" data-bs-toggle="modal" data-bs-target="#updateTagsModal"></i>';
          echo "</td>";

          echo "</tr>";

          ?>
          
        </table>
        

        <div class="accordion" id="accordion_application">
          <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingOne">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                <Strong>Application Response & Reviewer Actions</Strong>
              </button>
            </h2>
            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordion_application">
              <div class="accordion-body">
                <table class="table table-sm table-bordered">
                  <thead>
                    <tr>
                      <th>Section Name</th>
                      
                      <th>Filling Status</th>
                      <th>Notes</th>
                      <th>L1 Decision</th>
                      <th>L2 Decision</th>
                      <th>Applicant Response</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $current_s_name = "";
                    $old_s_name = "";
                    $show_clarify_question = 1;
                    foreach ($all_application_response as $key => $single_response) {

                      if($current_s_name != $single_response['section_name'])
                      {
                        echo "<tr>";
                        echo "<td>";
                        echo $single_response['section_name'];
                        echo "</td>";

                      

                      echo "<td>";
                      if($single_response['filling_status'] == 1)
                      {
                        echo "Filled";
                      }else{
                        echo "Not Filled";
                      }
                      echo "</td>";

                      echo "<td>";
                      echo $single_response['notes'];
                      echo "</td>";

                      echo "<td>";
                      if($single_response['r1_status'] == 0)
                      {
                        echo "NA";
                      }else if ($single_response['r1_status'] == 1){
                        echo "Pass";
                      }else{
                        echo "Fail";
                      }
                      echo "</td>";

                      echo "<td>";
                      if($single_response['r2_status'] == 0)
                      {
                        echo "NA";
                      }else if ($single_response['r2_status'] == 1){
                        echo "Pass";
                      }else if ($single_response['r2_status'] == 2){
                        echo "Fail";
                      }else if ($single_response['r2_status'] == 3){
                        echo "Waiting for Clarifications";
                      }else if ($single_response['r2_status'] == 4){
                        echo "Under Consultation";
                      }
                      echo "</td>";


                      echo "<td>";
                      }

                      
                      echo "<font color='blue'>";
                      echo $single_response['q_name'];
                      echo "</font> ";
                      echo "<br>";
                      echo "<font color='gray' style='italic'>";
                      echo $single_response['q_description'];
                      echo "</font>";
                      echo "<br>";
                      echo $single_response['answer'];
                      echo " <a href='";
                      echo base_url("admin/application/response/edit/$application_id/" .$single_response['response_section_id']. "/" .$single_response['response_question_id']. "");
                      echo "'><i class='bi bi-pencil'></i></a>";
                      echo "<br>";
                      echo "<br>";

                      if($single_response['clarify_question'] != "")
                      {
                        
                        if($show_clarify_question % 2 == 0)
                        {
                          echo "<font color='purple' style='italic'>";
                          //echo "CQ: ";
                          echo $single_response['clarify_question'];
                          echo "</font>";
                          echo "<br>";
                          //echo "CA: ";
                          echo $single_response['clarify_response'];
                          echo "<br>";
                        }
                        $show_clarify_question = $show_clarify_question+1;
                      
                      }


                      

                      if($old_s_name == $single_response['section_name'])
                      {
                        echo "</td>";
                        echo "</tr>";
                      }
                      
                      if($current_s_name != $single_response['section_name'])
                      {
                        $old_s_name = $current_s_name;
                      }
                      $current_s_name = $single_response['section_name'];
                    }


                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="accordion-item">
            <h2 class="accordion-header" id="flush-headingTwo">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                <strong>Application Log Details</strong>
              </button>
            </h2>
            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordion_application">
              <div class="accordion-body">
                <table class="table table-sm table-bordered">
                  <thead>
                    <tr>
                          <th>Timestamp</th>
                          <th>Activity</th>
                        </tr>
                  </thead>
                  <tbody>
                    <?php 
                    foreach ($application_logs as $key => $log) {
                      echo "<tr>";
                      
                      echo "<td>";
                      echo date("d-m-Y h:i A", strtotime($log['perform_on']));
                                      echo " UTC";

                       echo "</td>";
                       echo "<td>"; echo $log['comment']; echo "</td>";
                      echo "</tr>";
                    }


            ?>
                  </tbody>
                </table> <!-- log table end -->
              </div> <!-- accordion-body end -->
            </div><!-- accordion flush-collapseTwo end -->
          </div><!-- accordion-item end -->
        </div><!-- accordion_application end -->

	</div>
</div><!-- col-10 end -->
</div><!-- row end -->
</div><!-- container-fluid end -->




<!-- updateAppDesicion Modal Start -->
<div class="modal fade" id="updateAppDesicionModal" tabindex="-1" aria-labelledby="updateAppDesicionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateAppDesicionModalLabel">Change Application Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Current Application Status : <strong><?php echo $current_application_status; ?></strong></p>
<?php 
        echo form_open('admin/application/update/status', 'id="update_app_status"');
        ?>
        
<div class="mb-2">
     <label for="section_id" class="form-label">Choose new application status to override</label>
<select class="form-select" id="application_status" name="application_status" aria-label="application_status" required>
<?php
foreach ($admin_status_array as $key => $app_status_name) {
  echo '<option value="';
  echo $key;
  echo '"';
  if($key == $ind_application_data[0]['status'])
  {
    echo "selected";
  }
  echo '>';
  echo $app_status_name;
  echo '</option>';
}

?>
</select>
</div>
    <font color="red">Please note - A log generated in the system that admin change the application status.</font>  
<br>
<br>
<input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
<input type="hidden" name="update_type" value="application_status">
<?php

        echo '<button class="btn btn-primary" type="submit">Submit</button>';
      echo form_close();
        ?>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- updateAppDesicion Modal End -->




<!-- updateL1ReviewerModal Modal Start -->
<div class="modal fade" id="updateL1ReviewerModal" tabindex="-1" aria-labelledby="updateL1ReviewerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateL1ReviewerModalLabel">Change L1 Reviewer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Current L1 Reviewer : <strong><?php 
      if($ind_application_data[0]['current_l1'])
          {
            echo $ind_application_data[0]['l1_fname'];
            echo " ";
            echo $ind_application_data[0]['l1_lname'];
          }else{
            echo "NA";
          }

        ?></strong></p>
<?php 
        echo form_open('admin/application/update/status', 'id="update_l1_reviewer"');
        ?>
        
<div class="mb-2">
     <label for="l1_reviewer" class="form-label">Choose new L1 Reviewer</label>
<select class="form-select" id="l1_reviewer" name="l1_reviewer" aria-label="l1_reviewer" required>
<?php
foreach ($all_active_reviewers as $key => $active_reviewers) {
  if($active_reviewers['role'] == 2)
  {
  echo '<option value="';
  echo $active_reviewers['id'];
  echo '"';
  if($active_reviewers['id'] == $ind_application_data[0]['current_l1'])
  {
    echo "selected";
  }
  echo '>';
  echo $active_reviewers['fname'];
  echo '</option>';
  }
}

?>
</select>
</div>
    <font color="red">Please note - A log generated in the system that admin override the things</font>  
<br>
<br>
<input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
<input type="hidden" name="update_type" value="update_l1">
<?php

      echo '<button class="btn btn-primary" type="submit">Submit</button>';
      echo form_close();
        ?>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- updateL1ReviewerModal Modal End -->





<!-- updateL2ReviewerModal Modal Start -->
<div class="modal fade" id="updateL2ReviewerModal" tabindex="-1" aria-labelledby="updateL2ReviewerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateL2ReviewerModalLabel">Change L2 Reviewer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Current L2 Reviewer : <strong><?php 
      if($ind_application_data[0]['current_l2'])
          {
            echo $ind_application_data[0]['l2_fname'];
            echo " ";
            echo $ind_application_data[0]['l2_lname'];
          }else{
            echo "NA";
          }

        ?></strong></p>
<?php 
        echo form_open('admin/application/update/status', 'id="update_l2_reviewer"');
        ?>
        
<div class="mb-2">
     <label for="l2_reviewer" class="form-label">Choose new L2 Reviewer</label>
<select class="form-select" id="l2_reviewer" name="l2_reviewer" aria-label="l2_reviewer" required>
<?php
foreach ($all_active_reviewers as $key => $active_reviewers) {
  if($active_reviewers['role'] == 3)
  {
  echo '<option value="';
  echo $active_reviewers['id'];
  echo '"';
  if($active_reviewers['id'] == $ind_application_data[0]['current_l2'])
  {
    echo "selected";
  }
  echo '>';
  echo $active_reviewers['fname'];
  echo '</option>';
  }
}

?>
</select>
</div>
    <font color="red">Please note - A log generated in the system that admin override the things</font>  
<br>
<br>
<input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
<input type="hidden" name="update_type" value="update_l2">
<?php

      echo '<button class="btn btn-primary" type="submit">Submit</button>';
      echo form_close();
        ?>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- updateL2ReviewerModal Modal End -->





<!-- updateTagsModal Modal Start -->
<div class="modal fade" id="updateTagsModal" tabindex="-1" aria-labelledby="updateTagsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="updateTagsModalLabel">Change / Update Tags</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
<?php 
        echo form_open('admin/application/update/tags', 'id="update_tags"');
        //echo "<pre>";
        //print_r($all_tags_master_array);
        //echo "<pre>";
        ?>
        
<div class="mb-2">
     <label for="multiple-select-1" class="form-label">Choose Tags</label>
<select class="form-select" id="multiple-select-1" name="tags[]" aria-label="multiple-select-1" multiple>
<?php

foreach ($all_tags_master_array as $key => $tag_data) {
  
  echo '<option value="';
  echo $tag_data['id'];
  echo '"';
  if (in_array($tag_data['id'], $tags_array)) {
    echo " selected";
  }
  echo '>';
  echo $tag_data['name'];
  echo '</option>';

}


?>
</select>
</div>
    <font color="red">Please note - A log generated in the system that admin override the things</font>  
<br>
<br>
<input type="hidden" name="application_id" value="<?php echo $application_id; ?>">
<?php

      echo '<button class="btn btn-primary" type="submit">Submit</button>';
      echo form_close();
        ?>



      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>

$(document).ready(function() {
  $("#multiple-select-1").select2( {theme: "bootstrap-5",width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
    dropdownParent: $("#updateTagsModal")
  } );
});

</script>
<!-- updateTagsModal Modal End -->

