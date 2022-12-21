<h3>All Sections List</h3>
		
				<table class="table" id="list_sections">
					<thead>
						<tr>
							<th>Visible Order</th>
							<th>Name</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php

						$status_array = array('0' => 'Deactive',
							'1' => 'Active');				
						foreach ($sections_list as $key => $single_section_data) {
							echo "<tr>";
							echo "<td>";
							echo $single_section_data['visible_order'];
							echo "</td>";
							echo "<td>";
              echo "<a href='";
              echo base_url("admin/questions/" .$single_section_data['id']. "");
              echo "'>";
							echo $single_section_data['name'];
              echo "</a>";
							echo "</td>";

							echo "<td>";
							echo $status_array[$single_section_data['status']];
							//echo $single_user_data['status'];
							echo "</td>";

							echo "<td>";
							echo '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#';
							echo "editSectionModal_";
							echo $single_section_data['id'];
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


<!-- Modal Add New User Start -->
<div class="modal fade" id="addNewSectionModal" tabindex="-1" aria-labelledby="addNewSectionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewSectionModalLabel">Create New Section</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
        echo form_open('admin/section/create', 'id="create_section"');
        ?>
        <div class="mb-2">
  <label for="section_name" class="form-label">Section name</label>
  <input type="text" class="form-control" id="section_name" name="section_name" placeholder="Enter section name" required>
</div>

    <div class="mb-2">
  <label for="section_heading" class="form-label">Section heading</label>
  <input type="text" class="form-control" id="section_heading" name="section_heading" placeholder="Enter user section heading" required>
</div>

   <div class="mb-2">
  <label for="section_details" class="form-label">Section details</label>
  <textarea class="form-control" id="section_details" name="section_details" rows="3" required></textarea>
</div>

  <div class="mb-2">
  <label for="section_visible_order" class="form-label">Section visible order</label>
  <input type="number" class="form-control" id="section_visible_order" name="section_visible_order" placeholder="Enter section visible order" min="1" max="100" required>
</div>

<div class="mb-2">
   	 <label for="section_status" class="form-label">Section Status</label>
<select class="form-select" id="section_status" name="section_status" aria-label="section_status" required>
  <option selected>Select Section Status</option>
  <option value="1">Active</option>
  <option value="0">Deactive</option>

</select>
</div>

<br>


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
<!-- Modal Add New User End -->


<?php 
foreach ($sections_list as $key => $single_section_data) {

	?>
<!-- Modal Edit User Start -->
<div class="modal fade" id="editSectionModal_<?php echo $single_section_data['id']; ?>" tabindex="-1" aria-labelledby="editSectionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSectionModalLabel">Edit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
 <?php 
 $form_id = "update_section_" .$single_section_data['id']. "";
        echo form_open('admin/section/update', "id='" .$form_id. "'");
        ?>
        <div class="mb-2">
  <label for="section_name" class="form-label">Section name</label>
  <input type="text" class="form-control" id="section_name" name="section_name" value="<?php echo $single_section_data['name']; ?>" placeholder="Enter section name" required>
</div>

    <div class="mb-2">
  <label for="section_heading" class="form-label">Section heading</label>
  <input type="text" class="form-control" id="section_heading" name="section_heading" value="<?php echo $single_section_data['heading']; ?>" placeholder="Enter section heading" required>
</div>

<div class="mb-2">
  <label for="section_details" class="form-label">Section details</label>
  <textarea class="form-control" id="section_details" name="section_details" rows="3" required><?php echo $single_section_data['details']; ?></textarea>
</div>

  <div class="mb-2">
  <label for="section_visible_order" class="form-label">Section visible order</label>
  <input type="number" class="form-control" id="section_visible_order" name="section_visible_order" value="<?php echo $single_section_data['visible_order']; ?>" placeholder="Enter section visible order" min="1" max="100" required>
</div>

<div class="mb-2">
   	 <label for="section_status" class="form-label">Section Status</label>
<select class="form-select" id="section_status" name="section_status" aria-label="section_status" required>
  <option value="1" 
  <?php 
  if($single_section_data['status'] == 1)
  {
  	echo "selected";
  }
  ?>
  >Active</option>
  <option value="0" 
<?php 
  if($single_section_data['status'] == 0)
  {
  	echo "selected";
  }
  ?>
  >Deactive</option>

</select>
</div>


 

<br>
<input type="hidden" name="section_id" value="<?php echo $single_section_data['id']; ?>">

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