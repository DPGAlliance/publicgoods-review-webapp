<h3>Manage Limits</h3>
    <br>
      <?php 


echo form_open('admin/process/limits', 'id="log_process_filter"');


?>


<div class="mb-3">
  <label for="l1review" class="form-label">Time for L1 Review (in hours)</label>
<input type="number" min="1" max="999" value="<?php echo $limits_data[0]['l1review']; ?>" class="form-control" id="l1review" name="l1review" placeholder="Enter time for L1 Review" required>
</div>


<div class="mb-3">
  <label for="l2review" class="form-label">Time for L2 Review (in hours)</label>
<input type="number" min="1" max="999" value="<?php echo $limits_data[0]['l2review']; ?>" class="form-control" id="l2review" name="l2review" placeholder="Enter time for L2 Review" required>
</div>


<div class="mb-3">
  <label for="to_refresher" class="form-label">Time for Refresher Applications (in days)</label>
<input type="number" min="1" max="364" value="<?php echo $limits_data[0]['to_refresher']; ?>" class="form-control" id="to_refresher" name="to_refresher" placeholder="Enter time for refresher applications" required>
</div>



<div class="mb-3">
  <label for="log_count" class="form-label">Default total rows in logs</label>
<input type="number" min="10" max="999" value="<?php echo $limits_data[0]['log_count']; ?>" class="form-control" id="log_count" name="log_count" placeholder="Enter default rows count for logs" required>
</div>


<div class="mb-3">
  <label for="log_count" class="form-label">Application Expire Duration (in days)</label>
<input type="number" min="10" max="999" value="<?php echo $limits_data[0]['app_expire_days']; ?>" class="form-control" id="app_expire_days" name="app_expire_days" placeholder="Enter duration for application expire" required>
</div>



<div class="mb-3">
  <label for="github_token" class="form-label">Github Token</label>
  <br>
  To  generate classic token - <a href="https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/creating-a-personal-access-token" target="_blank">Read Here</a>
<input type="text" value="<?php echo $limits_data[0]['github_token']; ?>" class="form-control" id="github_token" name="github_token" placeholder="Enter github token" >
</div>



<div class="mb-3">
  <label for="github_repo_name" class="form-label">Github Repo Name</label>
<input type="text" value="<?php echo $limits_data[0]['github_repo_name']; ?>" class="form-control" id="github_repo_name" name="github_repo_name" placeholder="Enter github repo name" >
</div>


<div class="mb-3">
  <label for="github_owner_name" class="form-label">Github Repo Owner Name</label>
<input type="text" value="<?php echo $limits_data[0]['github_owner_name']; ?>" class="form-control" id="github_owner_name" name="github_owner_name" placeholder="Enter github repo owner name" >
</div>



<div class="mb-3">
  <label for="github_main_branch_name" class="form-label">Github Master Branch Name</label>
<input type="text" value="<?php echo $limits_data[0]['github_main_branch_name']; ?>" class="form-control" id="github_main_branch_name" name="github_main_branch_name" placeholder="Enter github branch name" >
</div>









<?php

echo '<button class="btn btn-primary" type="submit">Submit</button>';

echo form_close();

 ?>

  </div>
</div><!-- col-10 end -->
</div><!-- row end -->
</div><!-- container-fluid end -->



