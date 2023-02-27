

<div class="col-md-6 pt-5">
  <div class="row justify-content-center">
    <div class="col-10">
      SIGN UP
      <h3>Create your DPGA account</h3>
      <p class="grey_text_color">Already have an account? <a href="<?php echo base_url('login'); ?>">Log in here</a></p>
      <?php 
      echo $this->session->flashdata('message');  
      ?>
      <?php
      echo form_open('signup/process', 'id="dpga_signup_form"');
      ?>
      <div class="mb-2">
        <label for="email" class="form-label">Email ID</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email id" required>
      </div>

      <div class="mb-2">
        <label for="fname" class="form-label">Name</label>
        <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter your name" required>
      </div>


      <div class="mb-2">
        <label for="password" class="form-label">Create a new password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter a new password" required>
      </div>


      <div class="d-grid gap-2 pt-2">
        <button class="btn btn-primary" type="submit">Create Account</button>
      </div>
      <p class="grey_text_color mt-2">By creating an account, you agree to the <a href="<?php echo base_url("pages/legal"); ?>">terms of use & privacy policies</a></p>

      <p class="text-center">OR</p>
      <?php
      echo form_close();
      ?>

      <div class="d-grid gap-2">
        <a href="<?php echo base_url('new/application'); ?>" class="btn btn-outline-primary" tabindex="-1" role="button" aria-disabled="true">Go to application form</a>
      </div>
      <p class="grey_text_color mt-2">No sign up required to view & edit your application. You will be prompted to create an account & verify your email to complete submitting your application.</p>
    </div>
  </div>
</div>
</div>

</div>
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

</body>

</html>