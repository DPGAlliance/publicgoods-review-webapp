<h3>All Questions List</h3>
		
				<table class="table" id="list_questions">
					<thead>
						<tr>
							<th>Visible Order</th>
							<th>Type</th>
							<th>Details</th>
              <th>Section</th>
              <th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$status_array = array('0' => 'Deactive',
							'1' => 'Active');				
						foreach ($questions_list as $key => $single_question_data) {
							echo "<tr>";
							echo "<td>";
							echo $single_question_data['visible_order'];
							echo "</td>";
							echo "<td>";
							echo $single_question_data['type'];
							echo "</td>";

              echo "<td>";
              echo $single_question_data['name'];
              echo "</td>";

                echo "<td>";
              echo $single_question_data['section_name'];
              echo "</td>";

							echo "<td>";
							echo $status_array[$single_question_data['status']];
							//echo $single_user_data['status'];
							echo "</td>";

							echo "<td>";
							echo '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#';
							echo "editQuestionModal_";
							echo $single_question_data['id'];
							echo '" title="Edit Section Details"><i class="bi bi-pencil-square"></i></button>';
							
							echo "</td>";
							
							echo "</tr>";
						}


						 ?>
					</tbody>
				</table>

	</div>
</div><!-- col-10 end -->
</div><!-- row end -->
</div><!-- container-fluid end -->


<!-- Modal addNewQuestionTextModal Start -->
<div class="modal fade" id="addNewQuestionTextModal" tabindex="-1" aria-labelledby="addNewQuestionTextModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewQuestionTextModalLabel">Create New Question (Type: Text)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
        echo form_open('admin/question/create/1', 'id="create_question_text"');
        ?>
       <div class="mb-2">
  <label for="name" class="form-label">Question name</label>
  <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Question name" required>
</div>


<div class="mb-2">
  <label for="placeholder" class="form-label">Question placeholder</label>
  <input type="text" class="form-control" id="placeholder" name="placeholder" value="" placeholder="Enter Question placeholder" required>
</div>

<div class="mb-2">
  <label for="description" class="form-label">Question description</label>
  <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
</div>


  <div class="mb-2">
  <label for="visible_order" class="form-label">Question visible order</label>
  <input type="number" class="form-control" id="visible_order" name="visible_order" value="" placeholder="Enter visible order" min="1" max="100" required>
</div>

<div class="mb-2">
     <label for="section_id" class="form-label">Question Section</label>
<select class="form-select" id="section_id" name="section_id" aria-label="section_id" required>
<?php
foreach ($list_all_sections as $key => $single_section) {
  echo '<option value="';
  echo $single_section['id'];
  echo '"'; 
  echo '>';
  echo $single_section['name'];
  echo '</option>';
}

?>
</select>
</div>
<div class="mb-2">
     <label for="required" class="form-label">Question Required</label>
<select class="form-select" id="required" name="required" aria-label="required">
  <option value="*" 
  <?php 
    echo "selected";
  ?>
  >Required</option>
  <option value="">Not Required</option>

</select>
</div>




<div class="mb-2">
     <label for="status" class="form-label">Question Status</label>
<select class="form-select" id="status" name="status" aria-label="status" required>
  <option value="1" 
  <?php 
    echo "selected";
  ?>
  >Active</option>
  <option value="0">Deactive</option>

</select>
</div>
<br>
<input type="hidden" name="type" value="text">
<input type="hidden" name="options" value="">
<input type="hidden" name="lineheight" value="">
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
<!-- Modal addNewQuestionTextModal End -->



<!-- Modal addNewQuestionTextBoxModal Start -->
<div class="modal fade" id="addNewQuestionTextBoxModal" tabindex="-1" aria-labelledby="addNewQuestionTextBoxModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewQuestionTextBoxModalLabel">Create New Question (Type: TextBox)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
        echo form_open('admin/question/create/2', 'id="create_question_textbox"');
        ?>
       <div class="mb-2">
  <label for="name" class="form-label">Question name</label>
  <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Question name" required>
</div>


<div class="mb-2">
  <label for="placeholder" class="form-label">Question placeholder</label>
  <input type="text" class="form-control" id="placeholder" name="placeholder" value="" placeholder="Enter Question placeholder" required>
</div>

<div class="mb-2">
  <label for="description" class="form-label">Question description</label>
  <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
</div>


  <div class="mb-2">
  <label for="visible_order" class="form-label">Question visible order</label>
  <input type="number" class="form-control" id="visible_order" name="visible_order" value="" placeholder="Enter visible order" min="1" max="100" required>
</div>


<div class="mb-2">
<label for="lineheight" class="form-label">Textbox lineheight</label>
  <input type="number" class="form-control" id="lineheight" min="2" max="15" name="lineheight" value="" placeholder="Enter lineheight" required>
</div>


<div class="mb-2">
     <label for="section_id" class="form-label">Question Section</label>
<select class="form-select" id="section_id" name="section_id" aria-label="section_id" required>
<?php
foreach ($list_all_sections as $key => $single_section) {
  echo '<option value="';
  echo $single_section['id'];
  echo '"'; 
  echo '>';
  echo $single_section['name'];
  echo '</option>';
}

?>
</select>
</div>
<div class="mb-2">
     <label for="required" class="form-label">Question Required</label>
<select class="form-select" id="required" name="required" aria-label="required">
  <option value="*" 
  <?php 
    echo "selected";
  ?>
  >Required</option>
  <option value="">Not Required</option>

</select>
</div>




<div class="mb-2">
     <label for="status" class="form-label">Question Status</label>
<select class="form-select" id="status" name="status" aria-label="status" required>
  <option value="1" 
  <?php 
    echo "selected";
  ?>
  >Active</option>
  <option value="0">Deactive</option>

</select>
</div>
<br>
<input type="hidden" name="type" value="textbox">
<input type="hidden" name="options" value="">
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
<!-- Modal addNewQuestionTextBoxModal End -->





<!-- Modal addNewQuestionSingleSelectModal Start -->
<div class="modal fade" id="addNewQuestionSingleSelectModal" tabindex="-1" aria-labelledby="addNewQuestionSingleSelectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewQuestionSingleSelectModalLabel">Create New Question (Type: Single Select)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
        echo form_open('admin/question/create/3', 'id="create_question_singleselect"');
        ?>
       <div class="mb-2">
  <label for="name" class="form-label">Question name</label>
  <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Question name" required>
</div>


<div class="mb-2">
  <label for="placeholder" class="form-label">Question placeholder</label>
  <input type="text" class="form-control" id="placeholder" name="placeholder" value="" placeholder="Enter Question placeholder" required>
</div>

<div class="mb-2">
  <label for="description" class="form-label">Question description</label>
  <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
</div>


  <div class="mb-2">
  <label for="visible_order" class="form-label">Question visible order</label>
  <input type="number" class="form-control" id="visible_order" name="visible_order" value="" placeholder="Enter visible order" min="1" max="100" required>
</div>


<div class="mb-2">
<label for="options" class="form-label">Select Options(Separated with comma)</label>
<textarea class="form-control" id="options" name="options" rows="3" required></textarea>
</div>


<div class="mb-2">
     <label for="section_id" class="form-label">Question Section</label>
<select class="form-select" id="section_id" name="section_id" aria-label="section_id" required>
<?php
foreach ($list_all_sections as $key => $single_section) {
  echo '<option value="';
  echo $single_section['id'];
  echo '"'; 
  echo '>';
  echo $single_section['name'];
  echo '</option>';
}

?>
</select>
</div>
<div class="mb-2">
     <label for="required" class="form-label">Question Required</label>
<select class="form-select" id="required" name="required" aria-label="required">
  <option value="*" 
  <?php 
    echo "selected";
  ?>
  >Required</option>
  <option value="">Not Required</option>

</select>
</div>




<div class="mb-2">
     <label for="status" class="form-label">Question Status</label>
<select class="form-select" id="status" name="status" aria-label="status" required>
  <option value="1" 
  <?php 
    echo "selected";
  ?>
  >Active</option>
  <option value="0">Deactive</option>

</select>
</div>
<br>
<input type="hidden" name="type" value="single_select">
<input type="hidden" name="lineheight" value="">


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
<!-- Modal addNewQuestionSingleSelectModal End -->



<!-- Modal addNewQuestionMultiSelectModal Start -->
<div class="modal fade" id="addNewQuestionMultiSelectModal" tabindex="-1" aria-labelledby="addNewQuestionMultiSelectModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewQuestionMultiSelectModalLabel">Create New Question (Type: Multi Select)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
        echo form_open('admin/question/create/4', 'id="create_question_multiselect"');
        ?>
       <div class="mb-2">
  <label for="name" class="form-label">Question name</label>
  <input type="text" class="form-control" id="name" name="name" value="" placeholder="Enter Question name" required>
</div>


<div class="mb-2">
  <label for="placeholder" class="form-label">Question placeholder</label>
  <input type="text" class="form-control" id="placeholder" name="placeholder" value="" placeholder="Enter Question placeholder" required>
</div>

<div class="mb-2">
  <label for="description" class="form-label">Question description</label>
  <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
</div>


  <div class="mb-2">
  <label for="visible_order" class="form-label">Question visible order</label>
  <input type="number" class="form-control" id="visible_order" name="visible_order" value="" placeholder="Enter visible order" min="1" max="100" required>
</div>


<div class="mb-2">
<label for="options" class="form-label">Select Options(Separated with comma)</label>
<textarea class="form-control" id="options" name="options" rows="3" required></textarea>
</div>


<div class="mb-2">
     <label for="section_id" class="form-label">Question Section</label>
<select class="form-select" id="section_id" name="section_id" aria-label="section_id" required>
<?php
foreach ($list_all_sections as $key => $single_section) {
  echo '<option value="';
  echo $single_section['id'];
  echo '"'; 
  echo '>';
  echo $single_section['name'];
  echo '</option>';
}

?>
</select>
</div>
<div class="mb-2">
     <label for="required" class="form-label">Question Required</label>
<select class="form-select" id="required" name="required" aria-label="required">
  <option value="*" 
  <?php 
    echo "selected";
  ?>
  >Required</option>
  <option value="">Not Required</option>

</select>
</div>




<div class="mb-2">
     <label for="status" class="form-label">Question Status</label>
<select class="form-select" id="status" name="status" aria-label="status" required>
  <option value="1" 
  <?php 
    echo "selected";
  ?>
  >Active</option>
  <option value="0">Deactive</option>

</select>
</div>
<br>
<input type="hidden" name="type" value="multiple_select">
<input type="hidden" name="lineheight" value="">


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
<!-- Modal addNewQuestionSingleSelectModal End -->


<?php 
foreach ($questions_list as $key => $single_question_data) {

	?>
<!-- Modal Edit User Start -->
<div class="modal fade" id="editQuestionModal_<?php echo $single_question_data['id']; ?>" tabindex="-1" aria-labelledby="editQuestionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editQuestionModalLabel">Edit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
 <?php 
 $form_id = "update_question_" .$single_question_data['id']. "";
        echo form_open('admin/question/update', "id='" .$form_id. "'");
        ?>
        <div class="mb-2">
  <label for="name" class="form-label">Question name</label>
  <input type="text" class="form-control" id="name" name="name" value="<?php echo $single_question_data['name']; ?>" placeholder="Enter Question name" required>
</div>

  <div class="mb-2">
  <label for="placeholder" class="form-label">Question placeholder</label>
  <input type="text" class="form-control" id="placeholder" name="placeholder" value="<?php echo $single_question_data['placeholder']; ?>" placeholder="Enter Question placeholder" required>
</div>

<div class="mb-2">
  <label for="description" class="form-label">Question description</label>
  <textarea class="form-control" id="description" name="description" rows="3" required><?php echo $single_question_data['description']; ?></textarea>
</div>


  <div class="mb-2">
  <label for="visible_order" class="form-label">Question visible order</label>
  <input type="number" class="form-control" id="visible_order" name="visible_order" value="<?php echo $single_question_data['visible_order']; ?>" placeholder="Enter visible order" min="1" max="100" required>
</div>

<?php

// handle multiple_select OR single_select
if($single_question_data['type'] == "single_select" OR $single_question_data['type'] == "multiple_select")
{
  echo '<div class="mb-2">';
  echo '<label for="options" class="form-label">Select Options</label>';
  echo '<textarea class="form-control" id="options" name="options" rows="3" required>';
  echo $single_question_data['options'];echo '</textarea>';
  echo '</div>';
}else{
  echo '<input type="hidden" value="" name="options">';
}

//handle text area
if($single_question_data['type'] == "textbox")
{
echo '<div class="mb-2">';
echo '<label for="lineheight" class="form-label">Textbox lineheight</label>
  <input type="number" class="form-control" id="lineheight" min="2" max="15" name="lineheight" value="';
echo $single_question_data['lineheight'];
echo '" placeholder="Enter lineheight" required>';
echo '</div>';

}else{
  echo '<input type="hidden" value="" name="lineheight">';
}

?>

<div class="mb-2">
     <label for="section_id" class="form-label">Question Section</label>
<select class="form-select" id="section_id" name="section_id" aria-label="section_id" required>
<?php
foreach ($list_all_sections as $key => $single_section) {
  echo '<option value="';
  echo $single_section['id'];
  echo '"'; 
  if($single_question_data['section_id'] == $single_section['id'])
  {
    echo "selected";
  }

  echo '>';
  echo $single_section['name'];
  echo '</option>';
}

?>
</select>
</div>


<div class="mb-2">
     <label for="required" class="form-label">Question Required</label>
<select class="form-select" id="required" name="required" aria-label="required">
  <option value="*" 
  <?php 
  if($single_question_data['required'] == "*")
  {
    echo "selected";
  }
  ?>
  >Required</option>
  <option value="" 
<?php 
  if($single_question_data['required'] == "")
  {
    echo "selected";
  }
  ?>
  >Not Required</option>

</select>
</div>




<div class="mb-2">
   	 <label for="status" class="form-label">Question Status</label>
<select class="form-select" id="status" name="status" aria-label="status" required>
  <option value="1" 
  <?php 
  if($single_question_data['status'] == 1)
  {
  	echo "selected";
  }
  ?>
  >Active</option>
  <option value="0" 
<?php 
  if($single_question_data['status'] == 0)
  {
  	echo "selected";
  }
  ?>
  >Deactive</option>

</select>
</div>


 

<br>
<input type="hidden" name="question_id" value="<?php echo $single_question_data['id']; ?>">

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

<!-- Modal Edit User End -->

<?php 
}

?>