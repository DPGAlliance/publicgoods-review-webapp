<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">

  <title>DPGA WebApp Homepage</title>
</head>
<body>
  <div class="container">
    <!-- Content here -->
    <div class="row">
      <div class="col-sm-8 mx-auto">
        <h1>DPGA WebApp</h1>
        <a href="<?php echo base_url('new/application'); ?>" class="btn btn-primary btn-lg mt-2 mb-2" tabindex="-1" role="button" aria-disabled="true">Click For New Application</a>

        <a href="<?php echo base_url('login'); ?>" class="btn btn-primary btn-lg mt-2 mb-2" tabindex="-1" role="button" aria-disabled="true">Login</a>

        <a href="<?php echo base_url('signup'); ?>" class="btn btn-primary btn-lg mt-2 mb-2" tabindex="-1" role="button" aria-disabled="true">Signup</a>

        <a href="<?php echo base_url('reset'); ?>" class="btn btn-primary btn-lg mt-2 mb-2" tabindex="-1" role="button" aria-disabled="true">Recover Password</a>
      </div>
    </div>
    
  </div>

  <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>