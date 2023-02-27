    <div class="row">
        <div class="col-md-8">
            <?php 
            echo $this->session->flashdata('message');  
            ?>
            <?php
            echo form_open('login/process', 'id="dpga_login_form"');
            ?>

            <a class="btn btn-warning btn-sm" href="<?php echo base_url('/'); ?>" role="button"> <i class="bi bi-arrow-left"></i> Back to home</a>
            <br><br>

            <div class="mb-3">
               <label for="email" class="form-label">Email ID</label>
               <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email id" required>
           </div>

           <div class="mb-3">
               <label for="password" class="form-label">Password</label>
               <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
           </div>


           <button type="submit" class="btn btn-primary">Submit</button>
           <?php
           echo form_close();
           ?>
       </div>
   </div>
