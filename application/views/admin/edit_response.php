<h3>Edit / Update Response</h3>
		
				
<table class="table table-sm">
  <tr>
    <td>Application ID:</td><td><?php echo $application_id; ?></td>
  </tr>
  <tr>
    <td>Solution Name:</td><td><?php echo $solution_name; ?></td>
  </tr>
  <tr>
    <td>Section:</td><td><?php echo $section_name; ?></td>
  </tr>
  
</table>

<?php 
$question_id = $ind_question_details[0]['id'];
$question_placeholder = $ind_question_details[0]['placeholder'];
$answer = "";
if(count($get_response_details) == 1)
{
  $answer = $get_response_details[0]['answer'];
}
$q_type = $ind_question_details[0]['type'];
echo form_open('admin/response/update', 'id="admin_update_question"');


if($ind_question_details[0]['type'] == "text")
{
  
  echo '<div class="mb-2">';
  echo "<label for='$question_id' class='form-label'>Question: ";
  echo $ind_question_details[0]['name'];
  echo "</label>";
  echo "<p style='color: grey;'>";
  echo $ind_question_details[0]['description'];
  echo "</p>";
  echo "<input type='text' class='form-control' id='$question_id' name='$question_id' value='$answer' placeholder='$question_placeholder'>";
  echo "</div>";
}

if($ind_question_details[0]['type'] == "textbox")
{
  
  echo '<div class="mb-2">';
  echo "<label for='$question_id' class='form-label'>Question: ";
  echo $ind_question_details[0]['name'];
  echo "</label>";
  echo "<p style='color: grey;'>";
  echo $ind_question_details[0]['description'];
  echo "</p>";
  echo "<textarea class='form-control' id='$question_id' name='$question_id' rows='3' placeholder='$question_placeholder'>$answer</textarea>";

  echo "</div>";
}

if($ind_question_details[0]['type'] == "single_select")
{
  $available_options_string = $ind_question_details[0]['options'];
  $available_options_array = explode(",", $available_options_string);
  echo '<div class="mb-2">';
  echo "<label for='$question_id' class='form-label'>Question: ";
  echo $ind_question_details[0]['name'];
  echo "</label>";
  echo "<p style='color: grey;'>";
  echo $ind_question_details[0]['description'];
  echo "</p>";

  echo "<select class='form-select' id='$question_id' name='$question_id' aria-label='$question_id'>";
foreach ($available_options_array as $key => $single_option) {
  echo '<option value="';
  echo $single_option;
  echo '"';
  if($single_option == $answer){
    echo " selected";
  }
  echo '>';
  echo $single_option;
  echo '</option>';
}
echo "</select>";
echo "</div>";
}


if($ind_question_details[0]['type'] == "multiple_select")
{
  $available_options_string = $ind_question_details[0]['options'];
  $available_options_array = explode(",", $available_options_string);
  $already_selected_items_array = explode(",", $answer);
  echo '<div class="mb-2">';
  echo "<label for='$question_id' class='form-label'>Question: ";
  echo $ind_question_details[0]['name'];
  echo "</label>";
  echo "<p style='color: grey;'>";
  echo $ind_question_details[0]['description'];
  echo "</p>";

  echo "<select class='form-select' id='multiple-select-1' name='" .$question_id. "[]' aria-label='$question_id' multiple>";
foreach ($available_options_array as $key => $single_option) {
  echo '<option value="';
  echo $single_option;
  echo '"';
  if (in_array($single_option, $already_selected_items_array)) {
                      echo " selected";
  }
  echo '>';
  echo $single_option;
  echo '</option>';
}
echo "</select>";
echo "</div>";
}





echo "<br>";
echo "<input type='hidden' name='q_type' value='$q_type'>";
echo "<input type='hidden' name='application_id' value='$application_id'>";
echo "<input type='hidden' name='section_id' value='$section_id'>";
echo "<input type='hidden' name='question_id' value='$question_id'>";
echo '<button class="btn btn-primary" type="submit">Submit</button>';
echo form_close();

?>
        

	</div>
</div><!-- col-10 end -->
</div><!-- row end -->
</div><!-- container-fluid end -->


<?php
if($ind_question_details[0]['type'] == "multiple_select")
{
  ?>
<script type="text/javascript">
  $( '#multiple-select-1' ).select2( {theme: "bootstrap-5",width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,} );
  $( '#consultant' ).select2( {theme: "bootstrap-5",width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,} );$( '#new_consultant_list' ).select2( {theme: "bootstrap-5",width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,} );</script>

<?php
}
  ?>



