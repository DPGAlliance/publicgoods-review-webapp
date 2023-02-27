
<div class="row">
  <div class="col-md-12">
    <?php 
    echo $this->session->flashdata('message');  
    ?>
  </div>
</div>
<div class="row mb-5 content_row">
  <div class="col-md-3 left_side_box mb-5">
    <div class="left_side_menu_box">
    <p style="font-size: 20px;">Your DPG Application for <strong><?php echo $solution_name; ?></strong>
    </p>
     <p style="font-size: 17px; color: #6B7280;">Application ID: <?php echo $this->session->userdata('application_id'); ?>
    </p>
    <?php
    $all_section_ids_array = array();
    $filled_sections_count = 0;
    foreach ($all_section_data_in_application as $key => $single_section) {
      $is_this_filled = "";
      echo "<div class='left_side_menu_div'>";
      array_push($all_section_ids_array, $single_section['id']);
      if ($single_section['filling_status'] == 0) {
        echo '<i class="bi bi-square" style="font-size: 1.2rem; color: #212180;"></i> ';
      } else {
        if($single_section['clarify_question'] && $single_section['clarify_response'] == "")
        {
          echo '<i class="bi bi-exclamation-triangle-fill" style="font-size: 1.2rem; color: #FFDD63;"></i> ';
        }else{
          echo '<i class="bi bi-check-square-fill" style="font-size: 1.2rem; color: #212180;"></i> ';
          $is_this_filled = "section_filled";
          $filled_sections_count = $filled_sections_count + 1;
        }
        
        
      }
      if ($single_section['section_id'] == $current_section_id) {
        echo "<strong class='left_menu_selected_item'><font color='#212180'>" . $single_section['name'] . "</font></strong>";
      } else {
          // echo $single_section['name'];
        echo anchor("form/proceed/" . $single_section['id'] . "", $single_section['name'], array('title' => $single_section['name'], 'class' => "section_menu_links $is_this_filled"));
      }
      echo "<br>";
      echo "</div>";
    }
    echo "<br>";
    if($application_status == 6)
      {
        $submit_button_text = 'Submit Clarifications';
      }else{
        $submit_button_text = 'Submit Application';
      }
    if ($filled_sections_count == count($all_section_ids_array) && $this->session->userdata('email_verified') == 1) {
      echo '<div class="d-grid gap-2"><button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" data-bs-target="#submit_confirmation">';
        echo $submit_button_text;
      echo '</button></div>';
    } else {
      echo '<div class="d-grid gap-2"><button type="button" class="btn btn-outline-secondary" disabled>';
      echo $submit_button_text;
      echo '</button></div>';
      echo '<p class="small pt-2">You will be able to submit your application when (1) you have completed all sections of the application form and (2) your email ID has been verified.</p>';
    }
    ?>
  </div>

<?php
if($application_status == 0)
{
  ?>

<div class="left_side_excel_upload">
<i class="bi bi-lightbulb-fill"></i> Tip <br>
Use Excel to upload in single click.<br>
<a href="<?php echo base_url("files/sample_application.xlsx"); ?>" target="_blank">Click here to download excel format</a>

<?php
echo form_open_multipart('form/excel', 'id="excel_file_form"');
echo "<br>";

echo '<input type="file" name="excel_file" id="excel_file" accept=".xlsx" />';
echo form_close();
 ?>

</div>
<?php
}
?>


</div>
  <div class="col-md-9 right_side_box" id="content_area">
    <?php 
    $all_question_ids_array = array();
      $all_multiple_select_qids_array = array();
      $all_required_qids_array = array();
      $temp_source = 0;
      $temp_destination = 0;
    if($application_status != 6)
    {

    ?>
    <h3>
      <?php
      echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['heading'];
      ?>
    </h3>
    <p>
      <?php echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['details']; ?>
    </p>
    <hr class="bg-secondary border-1 border-top border-secondary">
    <p>
      <?php
      
      echo form_open('form/save', 'id="dpga_form"');
      
      foreach ($all_section_questions as $key => $single_question) {
        if($single_question['destination'])
        {
          $temp_source = $single_question['id'];
          $temp_destination = $single_question['destination'];
        }

        //get prefilled value if exists
        $this_question_answer = null;
        $already_answered_qids_array = array_column($all_section_answers, 'question_id');
        $qid_key = array_search($single_question['id'], $already_answered_qids_array);
        if (is_numeric($qid_key)) {
          $this_question_answer = $all_section_answers[$qid_key]['answer'];
        }
        //check is this question is required
        if ($single_question['required'] == '*') {
          array_push($all_required_qids_array, $single_question['id']);
        }
        //handle input text
        if ($single_question['type'] == 'text') {
          echo "<div class='mb-5'>
          <label for='" . $single_question['id'] . "' class='form-label'>" . $single_question['name'] . "" . $single_question['required'] . "</label>
          <p class='q_description'>" . $single_question['description'] . "</p>
          <input type='text' class='form-control";
          if (in_array($current_section_id, $this->session->userdata('section_revision')) && $single_question['required'] == '*' && !isset($this_question_answer)) {
            echo " is-invalid";
          }
          echo "' id='" . $single_question['id'] . "' name='" . $single_question['id'] . "'";
          if (isset($this_question_answer)) {
            echo "value='" . $this_question_answer . "'";
          }
          echo  "placeholder='" . $single_question['placeholder'] . "'>
          </div>";
          array_push($all_question_ids_array, $single_question['id']);
        }
        //handle textbox
        if ($single_question['type'] == 'textbox') {
          echo "<div class='mb-5'>
          <label for='" . $single_question['id'] . "' class='form-label'>" . $single_question['name'] . "" . $single_question['required'] . "</label>
          <p class='q_description'>" . $single_question['description'] . "</p>
          <textarea class='form-control";
          if (in_array($current_section_id, $this->session->userdata('section_revision')) && $single_question['required'] == '*' && !isset($this_question_answer)) {
            echo " is-invalid";
          }
          echo "' id='" . $single_question['id'] . "' name='" . $single_question['id'] . "' rows='" . $single_question['lineheight'] . "' placeholder='" . $single_question['placeholder'] . "'";
          if (!isset($this_question_answer)) {
            echo $single_question['disabled'];
          }

          echo ">";
          if (isset($this_question_answer)) {
            echo $this_question_answer;
          }
          echo "</textarea>
          </div>";
          array_push($all_question_ids_array, $single_question['id']);
        }
        //handle signle select
        if ($single_question['type'] == 'single_select') {
          echo "<div class='mb-5'>
          <label for='" . $single_question['id'] . "' class='form-label'>" . $single_question['name'] . "" . $single_question['required'] . "</label>";
          echo "<p class='q_description'>" . $single_question['description'] . "</p>";
          echo "<select class='form-select";
          if (in_array($current_section_id, $this->session->userdata('section_revision')) && $single_question['required'] == '*' && !isset($this_question_answer)) {
            echo " is-invalid";
          }
          echo "' aria-label='" . $single_question['id'] . "' id='" . $single_question['id'] . "' name='" . $single_question['id'] . "' onchange='manage_disable_actions(" . $single_question['id'] . ", " . $single_question['destination'] . ")'>";
          $available_options_string = $single_question['options'];
          $available_options_array = explode(",", $available_options_string);
          echo "<option value=''>" . $single_question['placeholder'] . "</option>";

          foreach ($available_options_array as $key => $select_option) {
            echo "<option value='" . $select_option . "'";
            if (isset($this_question_answer)) {
              if ($this_question_answer == $select_option) {
                echo "selected";
              }
            }
            echo ">" . $select_option . "</option>";
          }
          echo "</select>";
          echo "</div>";
          array_push($all_question_ids_array, $single_question['id']);
        }

        //handle multiple select
        if ($single_question['type'] == 'multiple_select') {
          echo "<div class='mb-5'>
          <label for='" . $single_question['id'] . "' class='form-label'>" . $single_question['name'] . "" . $single_question['required'] . "</label>";
          echo "<p class='q_description'>" . $single_question['description'] . "</p>";
          echo "<select class='form-select";
          if (in_array($current_section_id, $this->session->userdata('section_revision')) && $single_question['required'] == '*' && !isset($this_question_answer)) {
            echo " is-invalid";
          }
          echo "' id='multiple-select-" .$single_question['id']. "' name='" .$single_question['id']. "[]' data-placeholder='" .$single_question['placeholder']. "' multiple";
          if($single_question['id'] == 3)
          {
              // echo 'onchange = manage_disable_actions()';
          }

          echo ">";

          $available_options_string = $single_question['options'];
          $available_options_array = explode(",", $available_options_string);
          if($this_question_answer == '')
          {
            $this_question_answer_array = array();
          }else{
            $this_question_answer_array = explode(",", $this_question_answer);
          }
          foreach ($available_options_array as $key => $select_option) {
            echo "<option value='" . $select_option . "'";
            if (isset($this_question_answer)) {
              if (in_array($select_option, $this_question_answer_array)) {
                echo " selected";
              }
            }
            echo ">" . $select_option . "</option>";
          }
          echo "</select>";

          echo "</div>";
          array_push($all_question_ids_array, $single_question['id']);
          array_push($all_multiple_select_qids_array, $single_question['id']);
        }
      }


      $all_question_ids_string = implode(",", $all_question_ids_array);
      $all_section_ids_string = implode(",", $all_section_ids_array);
      $all_required_qids_string = implode(",", $all_required_qids_array);
      $all_required_qids_string = implode(",", $all_required_qids_array);
      $all_multiple_select_qids_string = implode(",", $all_multiple_select_qids_array);

      echo "<input type='hidden' name='all_question_ids_string' value='" . $all_question_ids_string . "'>";
      echo "<input type='hidden' name='all_section_ids_string' value='" . $all_section_ids_string . "'>";
      echo "<input type='hidden' name='current_section_id' value='" . $current_section_id . "'>";
      echo "<input type='hidden' name='all_required_qids_string' value='" . $all_required_qids_string . "'>";
      echo "<input type='hidden' name='all_multiple_select_qids_string' value='" . $all_multiple_select_qids_string . "'>";
      echo '<div class="d-grid gap-2">
      <button class="btn btn-primary" type="submit">Save</button>
      </div>';
      echo form_close();

    }else{
      //show only response without edit facility
      echo '<h3>';
      echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['heading'];
    echo '</h3>';
      echo '<p>';
            foreach ($all_section_questions as $key => $single_question) {


        //get prefilled value if exists
              $this_question_answer = null;
              $already_answered_qids_array = array_column($all_section_answers, 'question_id');
              $qid_key = array_search($single_question['id'], $already_answered_qids_array);
              if (is_numeric($qid_key)) {
                $this_question_answer = $all_section_answers[$qid_key]['answer'];
              }
        //check is this question is required
              if ($single_question['required'] == '*') {
                array_push($all_required_qids_array, $single_question['id']);
              }
              echo "<div class='reviewer_q_section'>";
              echo "<div class='reviewer_q_details'>";
              echo $single_question['name'];
              echo "</div>";
              echo "<div class='reviewer_q_answer'>";
              echo $this_question_answer;
              echo "</div>";
              echo "</div>";
            }


         
          echo '</p>';
          if($all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['clarify_question'])
          {
            echo '<div class="clarification_questions reviewer_q_section">';
            // echo '<strong>Clarifications Requested</strong><br>';
          
          echo form_open('form/clarifications/save', 'id="dpga_form"');

          echo "<div class='mb-3'>
          <label for='clarify_response' class='form-label'>" . $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['clarify_question'] . "*</label>
          <textarea class='form-control";
          echo "' id='clarify_response' name='clarify_response' rows='4' placeholder='Enter your clarifications'";
         

          echo ">";
          if (isset($all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['clarify_response'])) {
            echo $all_section_data_in_application[$this->session->userdata('current_section_id') - 1]['clarify_response'];
          }
          echo "</textarea>
          </div>";
echo "<input type='hidden' name='current_section_id' value='" . $current_section_id . "'>";
echo '<div class="d-grid gap-2">
      <button class="btn btn-primary" type="submit">Save</button>
      </div>';
      echo form_close();

          echo '</div>';
          }
          


    }
      ?>
    </p>
  </div>
</div>
</div>

<!-- Modal code start -->
<?php
if ($filled_sections_count == count($all_section_ids_array)) {

  if($application_status == 6)
  {
    $model_heading = 'Please confirm to finish submitting your clarifications';
    $terms_1_text = 'I understand that the clarifications provided will be published in the public domain and licensed as such, currently through the Unlicense.';
    $terms_2_text = 'I confirm that the clarifications provided are true and complete to the best of my knowledge.';

  }else{
    $model_heading = 'Please confirm to finish submitting your application';
    $terms_1_text = 'I understand that the information provided in the application form will be published in the public domain and licensed as such, currently through the Unlicense.';
    $terms_2_text = 'I confirm that the information provided is true and complete to the best of my knowledge.';
  }
  echo "<div class='modal fade' id='submit_confirmation' tabindex='-1' aria-labelledby='submit_confirmation_label' aria-hidden='true'>
  <div class='modal-dialog'>
  <div class='modal-content'>
  <div class='modal-header'>
  <h3 class='modal-title' id='submit_confirmation_label'>$model_heading</h3>
  </div>
  <div class='modal-body'>
  <div class='form-check'>
  <input class='form-check-input' type='checkbox' value='' id='terms_1'>
  <label class='form-check-label' for='terms_1'>$terms_1_text</label>
  </div>
  <div class='form-check pt-2'>
  <input class='form-check-input' type='checkbox' value='' id='terms_2'>
  <label class='form-check-label' for='terms_2'>$terms_2_text
  </label>
  </div>
  </div>
  <div class='modal-footer'>
  <button type='button' class='btn btn-outline-secondary' data-bs-dismiss='modal'>Cancel</button>
  <a class='btn btn-outline-secondary disabled' id='final_submit' name='final_submit' href='" .base_url("form/finish"). "' role='button'>$submit_button_text</a>
  </div>
  </div>
  </div>
  </div>";
}
?>

<!-- Modal code end -->

<!-- To warn user on page switch without save -->
<script type="text/javascript">
  // Store form state at page load
  var initial_form_state = $('#dpga_form').serialize();
  // Store form state after form submit
  $('#dpga_form').submit(function() {
    initial_form_state = $('#dpga_form').serialize();
  });
  // Check form changes before leaving the page and warn user if needed
  $(window).bind('beforeunload', function(e) {
    var form_state = $('#dpga_form').serialize();
    if (initial_form_state != form_state) {
      var message = "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
      e.returnValue = message; // Cross-browser compatibility (src: MDN)
      return message;
    }
  });


const excel_file_form = document.querySelector("excel_file_form");
const input = document.querySelector("input");
input.onchange = () => {
  $("#excel_file_form").submit();
};
</script>

<?php 
if ($filled_sections_count == count($all_section_ids_array)) {
  ?>
  <!-- To handle final_submit -->
  <script type="text/javascript">
    var terms_1 = document.getElementById('terms_1');
    terms_1.addEventListener('click', checked, false);
    var terms_2 = document.getElementById('terms_2');

    terms_2.addEventListener('click', checked, false);

    function checked() {

      if (terms_1.checked && terms_2.checked) {
        document.getElementById('final_submit').removeAttribute('disabled');
        document.getElementById('final_submit').removeAttribute('class');
        document.getElementById('final_submit').setAttribute('class', 'btn btn-primary');
      } else {
        document.getElementById('final_submit').removeAttribute('class');
        document.getElementById('final_submit').setAttribute('class', 'btn btn-outline-secondary disabled');
        document.getElementById('final_submit').setAttribute('disabled', 'disabled');
      }

    }
  </script>
<?php } ?>

<!-- Initialize the plugin: -->

<script type="text/javascript">
  <?php 
  foreach ($all_multiple_select_qids_array as $key => $multiple_question_id) {

   echo "$( '#multiple-select-";
   echo $multiple_question_id;
   echo "' ).select2( {";
   echo 'theme: "bootstrap-5",';
   echo "width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
   placeholder: $( this ).data( 'placeholder' ),
   closeOnSelect: false,";
   echo '} );';
 }
 ?>
</script>

<script type="text/javascript">
  <?php 
  if($current_section_id == SECTION_ID)
  {
    ?>
    const multiselect_field_id = "#multiple-select-<?php echo MULTI_SELECT_FIELD_ID; ?>";
    $(document.body).on("change",multiselect_field_id,function(){
     var selected_values_array = $(multiselect_field_id).val();
     const solution_code_repo = <?php echo SOLUTION_CODE_REPO_FIELD_ID; ?>;
     const software_field_selected = selected_values_array.includes('Open Software');
     if(software_field_selected){
      document.getElementById(solution_code_repo).setAttribute('required', 'required');
      var current_value = document.getElementById(solution_code_repo).value;
      if(current_value == '')
      {
        document.getElementById(solution_code_repo).setAttribute('class', 'form-control is-invalid');
      }else{
        document.getElementById(solution_code_repo).removeAttribute('class');
        document.getElementById(solution_code_repo).setAttribute('class', 'form-control');
      }
      
    }else {
      document.getElementById(solution_code_repo).removeAttribute('required');
    }
  });
  <?php } ?>  
</script>


<script type="text/javascript">
  function manage_disable_actions(source_id, destination_id)
  {
    if(source_id == 15 || source_id == 18 || source_id == 28)
    {
      var current_value = document.getElementById(source_id).value;
      if(current_value == 'Yes')
      {
        document.getElementById(destination_id).removeAttribute('disabled');
        document.getElementById(destination_id).setAttribute('required', 'required');
        var destination_value = document.getElementById(destination_id).value;
        if(destination_value == '')
        {
          document.getElementById(destination_id).setAttribute('class', 'form-control is-invalid');
        }else{
          document.getElementById(destination_id).removeAttribute('class');
          document.getElementById(destination_id).setAttribute('class', 'form-control');
        }
      }else{
        document.getElementById(destination_id).removeAttribute('class');
        document.getElementById(destination_id).setAttribute('class', 'form-control');
        document.getElementById(destination_id).removeAttribute('required');
        document.getElementById(destination_id).setAttribute('disabled', 'disabled');
      }
    }

    if(source_id == 22)
    {
      var current_value = document.getElementById(source_id).value;
      var privacy_field_id = <?php echo PRIVACY_FIELD_ID; ?>;
      if(current_value != 'PII data is NOT collected NOT stored and NOT distributed.')
      {
        document.getElementById(destination_id).removeAttribute('disabled');
        document.getElementById(privacy_field_id).removeAttribute('disabled');
        document.getElementById(destination_id).setAttribute('required', 'required');
        document.getElementById(privacy_field_id).setAttribute('required', 'required');
      }else{
        document.getElementById(destination_id).setAttribute('disabled', 'disabled');
        document.getElementById(privacy_field_id).setAttribute('disabled', 'disabled');
        document.getElementById(destination_id).removeAttribute('required');
        document.getElementById(privacy_field_id).removeAttribute('required');
      }
    }

    if(source_id == 25)
    {
      var current_value = document.getElementById(source_id).value;
      var solution_content_id = <?php echo SOLUTION_CONTENT_ID; ?>;
      if(current_value != 'Content is NOT collected NOT stored and NOT distributed.')
      {
        document.getElementById(destination_id).removeAttribute('disabled');
        document.getElementById(solution_content_id).removeAttribute('disabled');
        document.getElementById(destination_id).setAttribute('required', 'required');
        document.getElementById(solution_content_id).setAttribute('required', 'required');
      }else{
        document.getElementById(destination_id).setAttribute('disabled', 'disabled');
        document.getElementById(solution_content_id).setAttribute('disabled', 'disabled');
        document.getElementById(destination_id).removeAttribute('required');
        document.getElementById(solution_content_id).removeAttribute('required');
      }
    }
  }
</script>


<script type="text/javascript">
  <?php 
  if($temp_source > 0)
  {
    ?>
    $( document ).ready(function() {
      var source_id = <?php echo $temp_source; ?>;
      if (document.getElementById(source_id))
      {
        var destination_id = <?php echo $temp_destination; ?>;
        var current_value = document.getElementById(source_id).value;
        if(current_value == 'Yes')
        {
          document.getElementById(destination_id).removeAttribute('disabled');
          document.getElementById(destination_id).setAttribute('required', 'required');
          var destination_value = document.getElementById(destination_id).value;
          if(destination_value == '')
          {
            document.getElementById(destination_id).setAttribute('class', 'form-control is-invalid');
          }else{
            document.getElementById(destination_id).removeAttribute('class');
            document.getElementById(destination_id).setAttribute('class', 'form-control');
          }
        }
      }
    });
  <?php } ?>
</script>

<script type="text/javascript">
 $( document ).ready(function() {
   var source_id = <?php echo $temp_source; ?>; 
   var destination_id = <?php echo $temp_destination; ?>;
   if (document.getElementById(source_id))
   {
    var current_value = document.getElementById(source_id).value;
    if(source_id == 22)
    {
      var privacy_field_id = <?php echo PRIVACY_FIELD_ID; ?>;
      if(current_value != 'PII data is NOT collected NOT stored and NOT distributed.')
      {
        if(current_value != '')
        {
         document.getElementById(destination_id).removeAttribute('disabled');
         document.getElementById(privacy_field_id).removeAttribute('disabled');
         document.getElementById(destination_id).setAttribute('required', 'required');
         document.getElementById(privacy_field_id).setAttribute('required', 'required');
       }
     }
   }else if(source_id == 25){
    var solution_content_id = <?php echo SOLUTION_CONTENT_ID; ?>;
    if(current_value != 'Content is NOT collected NOT stored and NOT distributed.')
    {
      if(current_value != '')
      {
        document.getElementById(destination_id).removeAttribute('disabled');
        document.getElementById(solution_content_id).removeAttribute('disabled');
        document.getElementById(destination_id).setAttribute('required', 'required');
        document.getElementById(solution_content_id).setAttribute('required', 'required');
      }
    }
  }
}
});
</script>
