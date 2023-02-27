<div class="col-md-6 pt-5">
  <div class="row justify-content-center">
    <div class="col-10">
      <h3>Reset your password</h3>
      <p class="grey_text_color">Don't have an account? <a href="<?php echo base_url('signup'); ?>">Sign up here</a></p>
      <?php 
      echo $this->session->flashdata('message');  
      ?>
      <?php
      echo form_open('reset/process', 'id="dpga_login_form"');
      ?>

      <div class="mb-3">
        <label for="email" class="form-label">Email ID</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email id" required>
      </div>
      <div class="d-grid gap-2 pt-2">
        <button class="btn btn-primary" type="submit">Send password reset link</button>
      </div>
    </div>
  </div>
</div>
</div>

</div>
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

</body>

</html>