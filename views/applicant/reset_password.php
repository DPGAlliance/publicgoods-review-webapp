    <div class="row">
      <div class="col-md-8">
        <?php 
        echo $this->session->flashdata('message');  
        ?>
        <?php
        echo form_open('reset/password/process', 'id="dpga_recover_password_form"');
        ?>

        <div class="mb-3">
         <label for="password" class="form-label">Password</label>
         <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" onChange="onChange()" required>
       </div>

       <div class="mb-3">
         <label for="confirm_password" class="form-label">Confirm Password</label>
         <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm your password" onChange="onChange()" required>
         <span id='message'></span>
       </div>

       <input type="hidden" name="token" value="<?php echo $token; ?>">
       <input type="hidden" name="applicant_id" value="<?php echo $applicant_id; ?>">
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
