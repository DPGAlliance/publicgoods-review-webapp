<h3>All Users List</h3>
		
				<table class="table" id="list_users">
					<thead>
						<tr>
							<th>User ID</th>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
              <th>Applications</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$role_array = array('1' => 'Admin',
							'2' => 'L1 Reviewer',
							'3' => 'L2 Reviewer',
							'4' => 'Applicant',
							'5' => 'Expert');	

						$status_array = array('0' => 'deactive',
							'1' => 'Active',
							'2' => 'Email verification pending',
							'3' => 'Blocked by admin');				
						foreach ($users_list as $key => $single_user_data) {
							echo "<tr>";
							echo "<td>";
							echo $single_user_data['id'];
							echo "</td>";
							echo "<td>";
							echo $single_user_data['fname'];
							echo " ";
							echo $single_user_data['lname'];
							echo "</td>";

							echo "<td>";
							echo $single_user_data['email'];
							echo "</td>";

							echo "<td>";
							echo $role_array[$single_user_data['role']];
							echo "</td>";

              echo "<td>";
              $app_ids_string = $single_user_data['app_ids'];
              if($app_ids_string != "")
              {
                $app_ids_array = explode(",",$app_ids_string);
                foreach ($app_ids_array as $key => $single_app_id) {
                  echo "<a href='";
                  echo base_url("a/$single_app_id");
                  echo "' target='_blank'>";
                  echo $single_app_id;
                  echo "</a>";
                  echo ", ";
                }
              }
              echo "</td>";

							echo "<td>";
							echo $status_array[$single_user_data['status']];
							//echo $single_user_data['status'];
							echo "</td>";

							echo "<td>";
							echo '<button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#';
							echo "editUserModal_";
							echo $single_user_data['id'];
							echo '" title="Edit User Details"><i class="bi bi-pencil-square"></i></button>';
							
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
<div class="modal fade" id="addNewUserModal" tabindex="-1" aria-labelledby="addNewUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewUserModalLabel">Create New User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php 
        echo form_open('admin/user/create', 'id="create_user"');
        ?>
        <div class="mb-2">
  <label for="user_email" class="form-label">User Email</label>
  <input type="email" class="form-control" id="user_email" name="user_email" placeholder="Enter user email id" required>
</div>

  <div class="mb-2">
  <label for="user_password" class="form-label">User Password</label>
  <input type="text" class="form-control" id="user_password" name="user_password" placeholder="Enter user password" required>
</div>

   <div class="mb-2">
  <label for="user_fullname" class="form-label">User Full Name</label>
  <input type="text" class="form-control" id="user_fullname" name="user_fullname" placeholder="Enter user full name" required>
</div>

   <div class="mb-2">
   	 <label for="user_role" class="form-label">Select Role</label>
<select class="form-select" id="user_role" name="user_role" aria-label="user_role" required>
  <option selected>Select User Role</option>
  <option value="2">L1 reviewer</option>
  <option value="3">L2 reviewer</option>
  <option value="5">Expert</option>
  <option value="4">Applicant</option>

</select>
</div>

<div class="mb-2">
   	 <label for="user_status" class="form-label">Account Status</label>
<select class="form-select" id="user_status" name="user_status" aria-label="user_status" required>
  <option selected>Select User Account Status</option>
  <option value="1">Active</option>
  <option value="2">Email Verification Pending</option>
  <option value="0">Deactive</option>

</select>
</div>

<div class="mb-2">
<div class="form-check">
  <input class="form-check-input" type="checkbox" value="1" id="user_email_notify" name="user_email_notify">
  <label class="form-check-label" for="user_email_notify">
    Send Account Details on User's Email
  </label>
</div>
</div>




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
foreach ($users_list as $key => $single_user_data) {

	?>
<!-- Modal Edit User Start -->
<div class="modal fade" id="editUserModal_<?php echo $single_user_data['id']; ?>" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUserModalLabel">Edit Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
 <?php 
 $form_id = "update_user_" .$single_user_data['id']. "";
        echo form_open('admin/user/update', "id='" .$form_id. "'");
        ?>
        <div class="mb-2">
  <label for="user_email" class="form-label">User Email</label>
  <input type="email" class="form-control" id="user_email" name="user_email" value="<?php echo $single_user_data['email']; ?>" placeholder="Enter user email id" readonly>
</div>

   <div class="mb-2">
  <label for="user_fullname" class="form-label">User Full Name</label>
  <input type="text" class="form-control" id="user_fullname" name="user_fullname" value="<?php echo $single_user_data['fname']; ?>" placeholder="Enter user full name" required>
</div>

   <div class="mb-2">
   	 <label for="user_role" class="form-label">Select Role</label>
<select class="form-select" id="user_role" name="user_role" aria-label="user_role" required>
  <option>Select User Role</option>
  <option value="2" <?php 
  if($single_user_data['role'] == 2)
  {
  	echo "selected";
  }
  ?>
  >L1 reviewer</option>
  <option value="3" 
  <?php 
  if($single_user_data['role'] == 3)
  {
  	echo "selected";
  }
  ?>
  >L2 reviewer</option>
  <option value="5" 
<?php 
  if($single_user_data['role'] == 5)
  {
  	echo "selected";
  }
  ?>
  >Expert</option>
  <option value="4" 
<?php 
  if($single_user_data['role'] == 4)
  {
  	echo "selected";
  }
  ?>
  >Applicant</option>

</select>
</div>

<div class="mb-2">
   	 <label for="user_status" class="form-label">Account Status</label>
<select class="form-select" id="user_status" name="user_status" aria-label="user_status" required>
  <option>Select User Account Status</option>
  <option value="1" 
  <?php 
  if($single_user_data['status'] == 1)
  {
  	echo "selected";
  }
  ?>
  >Active</option>
  <option value="2"
<?php 
  if($single_user_data['status'] == 2)
  {
  	echo "selected";
  }
  ?>
  >Email Verification Pending</option>
  <option value="0" 
<?php 
  if($single_user_data['status'] == 0)
  {
  	echo "selected";
  }
  ?>
  >Deactive</option>

</select>
</div>
<br>
<input type="hidden" name="user_id" value="<?php echo $single_user_data['id']; ?>">

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