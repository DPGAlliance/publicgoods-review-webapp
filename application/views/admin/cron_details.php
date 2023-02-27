<h3>Cron Details</h3>
    <br>
    


<div class="mb-3">
  <label for="l1review" class="form-label">Add Late tag L1 (This must run in every one hour)</label>
<input type="text" value="<?php echo base_url("crons/add_late_tag_l1"); ?>" class="form-control" id="add_late_tag_l1">
</div>


<div class="mb-3">
  <label for="l1review" class="form-label">Add Late tag L2 (This must run in every one hour)</label>
<input type="text" value="<?php echo base_url("crons/add_late_tag_l2"); ?>" class="form-control" id="add_late_tag_l2" >
</div>


<div class="mb-3">
  <label for="handle_pending_clarifications" class="form-label">Handle pending clarifications (This must be run one time on every day)</label>
<input type="text" value="<?php echo base_url("crons/handle_pending_clarifications"); ?>" class="form-control" id="handle_pending_clarifications" >
</div>


<div class="mb-3">
  <label for="move_to_expire" class="form-label">Move to expire (This must be run one time on every day)</label>
<input type="text" value="<?php echo base_url("crons/move_to_expire"); ?>" class="form-control" id="move_to_expire" >
</div>


<div class="mb-3">
  <label for="reminder_to_finish_application" class="form-label">Reminder to finish application after 7 days (This must be run one time on every day)</label>
  <br>
  <font color="red">Repeat this for 7/14/28</font>
<input type="text" value="<?php echo base_url("crons/reminder_to_finish_application/7"); ?>" class="form-control" id="reminder_to_finish_application" >
</div>



<div class="mb-3">
  <label for="reminder_for_respond_with_clarifications" class="form-label">Reminder for respond with clarifications - after 7 days (This must be run one time on every day)</label><br>
  <font color="red">Repeat this for 7/14/21/28/35/42/49/56/63/70/77/84/91</font>
<input type="text" value="<?php echo base_url("crons/reminder_for_respond_with_clarifications/7"); ?>" class="form-control" id="reminder_for_respond_with_clarifications" >
</div>


<div class="mb-3">
  <label for="reminder_apply_for_renewal" class="form-label">Reminder to apply for renewal application (This must be run one time on every day)</label>
<input type="text" value="<?php echo base_url("crons/reminder_apply_for_renewal"); ?>" class="form-control" id="reminder_apply_for_renewal" >
</div>


<div class="mb-3">
  <label for="reminder_for_renewal_expired_dpgs" class="form-label">Reminder to apply for renewal application for expired DPGs (This must be run one time on every day)</label>
<input type="text" value="<?php echo base_url("crons/reminder_for_renewal_expired_dpgs"); ?>" class="form-control" id="reminder_for_renewal_expired_dpgs" >
</div>



<div class="mb-3">
  <label for="generate_pr_on_github" class="form-label">Generate PR on Github (This must run in every one hour)</label>
<input type="text" value="<?php echo base_url("crons/generate_pr_on_github"); ?>" class="form-control" id="generate_pr_on_github" >
</div>








  </div>
</div><!-- col-10 end -->
</div><!-- row end -->
</div><!-- container-fluid end -->



