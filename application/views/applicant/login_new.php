

<div class="col-md-6 pt-5">
  <div class="row justify-content-center">
    <div class="col-10">
      LOGIN
      <h3>Login to your DPGA Account</h3>
      <p class="grey_text_color">Don't have an account? <a href="<?php echo base_url('signup'); ?>">Sign up here</a></p>
      <?php 
      echo $this->session->flashdata('message');  
      ?>
      <?php
      echo form_open('login/process', 'id="dpga_login_form"');
      ?>
      <div class="mb-3">
        <label for="email" class="form-label">Email ID</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email id" required>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
      </div>

      <div class="form-check">
        <input class="form-check-input" type="checkbox" value="yes" id="remember_me" name="remember_me">
        <label class="form-check-label" for="remember_me">
          Remember Me
        </label>
      </div>

      <div class="d-grid gap-2 pt-2">
        <button class="btn btn-primary" type="submit">Login</button>
      </div>
      <p class="grey_text_color mt-4">Forgot your password? <a href="<?php echo base_url('reset'); ?>">Reset password</a></p>

      
    </div>
  </div>


 

<div class="fixed-bottom ">
   <div class="row justify-content-end">
    <?php

 echo '<div class="d-flex flex-row-reverse bd-highlight">';
         
         echo '<div class="p-2 bd-highlight">';
          echo "<a href='" .base_url('pages/legal'). "' style='color:grey;' >Legal</a>";
          echo '</div>';

         
          

          echo '</div>';

    ?>
</div>




</div>
</div>

</div>
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

</body>

</html>