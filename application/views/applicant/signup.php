    <div class="row">
      <div class="col-md-8">
        <?php 
        echo $this->session->flashdata('message');  
        ?>
        <?php
        echo form_open('signup/process', 'id="dpga_signup_form"');
        ?>

        <a class="btn btn-warning btn-sm" href="<?php echo base_url('/'); ?>" role="button"> <i class="bi bi-arrow-left"></i> Back to home</a>
        <br><br>
        <div class="mb-3">
         <label for="fname" class="form-label">Name</label>
         <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter your name" required>
       </div>

       <div class="mb-3">
         <label for="email" class="form-label">Email ID</label>
         <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email id" required>
       </div>

       <div class="mb-3">
         <label for="password" class="form-label">Password</label>
         <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" onChange="onChange()" required>
       </div>

       <div class="mb-3">
         <label for="confirm_password" class="form-label">Confirm Password</label>
         <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" onChange="onChange()" required>
         <span id='message'></span>
       </div>


       <button type="submit" class="btn btn-primary">Submit</button>
       <?php
       echo form_close();
       ?>
     </div>
   </div>


   <script type="text/javascript">
     function onChange() {
      const password = document.querySelector('input[name=password]');
      const confirm = document.querySelector('input[name=confirm_password]');
      if (confirm.value === password.value) {
        document.getElementById('message').innerHTML = '';
      } else {
        document.getElementById('message').style.color = 'red';
        document.getElementById('message').innerHTML = 'Password not match.';
      }
    }
  </script>
