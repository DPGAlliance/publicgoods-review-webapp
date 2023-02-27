<!doctype html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="<?php echo base_url('assets/images/favicon.png'); ?>" />
  <!-- Bootstrap CSS -->
  <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
  <link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet">
  <!-- Bootstrap Font Icon CSS -->
  <link rel="stylesheet" href="<?php echo base_url('assets/bsfonts/font/bootstrap-icons.css'); ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.bootstrap5.min.css'); ?>">


    <link rel="stylesheet" href="<?php echo base_url('assets/InterWeb/inter.css'); ?>">


  <script src="<?php echo base_url('assets/js/jquery-3.6.0.min.js'); ?>"></script>

  <!-- Select 2 -->
  
  <link rel="stylesheet" href="<?php echo base_url('assets/select2/select2.min.css'); ?>" type="text/css"/>
  <link rel="stylesheet" href="<?php echo base_url('assets/select2/select2-bootstrap-5-theme.min.css'); ?>" type="text/css"/>
  <script type="text/javascript" src="<?php echo base_url('assets/select2/select2.full.min.js'); ?>"></script>

  <title><?php echo $page_title; ?></title>
</head>
<body class="reviewer_screen">
  <div class="container-fluid custom-container">
    <!-- Content here -->
    <div class="row pt-3 ">
      <div class="col pb-1">
        <img src="<?php echo base_url('assets/images/logo/dpga_logo_2.svg'); ?>" class="img-fluid" alt="DPGA Logo">
      </div><!-- Logo column finish -->
      <div class="col pt-3 header_heading">
        DPGA WEBAPP
      </div>
      <div class="col govind pt-3">
        <!-- <h1><?php echo $page_heading; ?></h1> -->
        <?php
        /*
        if($this->session->userdata('user_id') > 0)
        { 
          if(uri_string() == 'reviewer')
          {
            echo '<div class="d-flex flex-row-reverse bd-highlight ">';
            echo "<div class='p-1 bd-highlight'>
            <a class='btn btn-danger btn-sm' href='" .base_url('logout'). "' role='button'>Log out</a>
            </div>";
            echo "<div class='p-1 bd-highlight'>
            <a class='btn btn-primary btn-sm' href='" .base_url('reviewer/directory'). "' role='button'>Directory</a>
            </div>";
            echo '</div>';
          }
          

          if(uri_string() == 'reviewer' OR uri_string() == 'reviewer/directory')
          {
            
          }
        }
  */
        if($this->session->userdata('user_id') > 0 && uri_string() == 'process/review')
        {
          
          echo '<div class="d-flex flex-row-reverse bd-highlight">';
            echo '<div class="p-1 bd-highlight">';
          //echo '<i class="bi bi-emoji-smile" style="font-size: 2.3rem; color: cornflowerblue;"></i>';
          echo '</div>';
         
         echo '<div class="p-2 bd-highlight text-end">';
          //echo "<strong>" .$user_details[0]['fname']. " " .$user_details[0]['lname']. "</strong>";
          //echo "<br>";
          //echo $reviewer_type;
          //echo '</div>';
          
          echo '</div>';
        } else if($this->session->userdata('user_id') > 0)
        {
          echo "<div class='text-end'>";
          //echo "Welcome, " .$user_details[0]['fname']. "  <strong>";
            // echo "<br>";
          //echo $this->session->userdata('user_role_details');
          //echo "</strong>";
          echo "</div>";

          echo '<div class="d-flex flex-row-reverse bd-highlight ">';
            

        
            
          



            echo "<div class='p-1 bd-highlight'>
            <a class='btn btn-danger btn-sm' href='" .base_url('logout'). "' role='button'>Log out</a>
            </div>";
            echo "<div class='p-1 bd-highlight'>
            <a class='btn btn-primary btn-sm' href='" .base_url('reviewer/directory'). "' role='button'>Directory</a>
            </div>";
            echo "<div class='p-1 bd-highlight'>
            <a class='btn btn-primary btn-sm' href='" .base_url('reviewer'). "' role='button'>Dashboard</a>
            </div>";
            echo '</div>';
            
         
        }
        if($this->session->userdata('user_id') == 0 && uri_string() == 'process/review')
        {
          
          // echo "<h3>$page_heading</h3>";

        }

        
        ?>
      </div><!-- Header column finish -->
    </div><!-- Header row finish -->

    <div class="row">
  <div class="col-md-12">
    <?php 
    if($this->session->userdata('user_id') == 0 && uri_string() == 'process/review')
        {
          
          echo "<div class='alert alert-danger' role='alert'>
          Please <a href='" .base_url('signup'). "'>create an account</a> to save your application. Already have an account? <a href='" .base_url('login'). "'>Log in here</a>.
          </div>";

        }

        if($this->session->userdata('user_id') > 0 && $this->session->userdata('email_verified') == 0 && uri_string() == 'form')
        {
          echo "<div class='alert alert-danger mt-3' role='alert'>
          Please verify your email. <a href='" .base_url('verify'). "'>Click here to verify</a> </div>";

        } 

        if($this->session->userdata('user_id') > 0 && uri_string() == 'reviewer')
        {
          
          echo "<p class='pt-3'>Welcome, " .$user_details[0]['fname']. " (" .$user_details[0]['email']. ") <strong>";
          if($user_role == 2)
          {
            echo " L1 ";
          } else if ($user_role == 3)
          {
            echo " L2 ";
          }
          echo "Reviewer</strong><br>";
          /*
          echo "Current Time:";
          echo date("d-m-Y h:i A", strtotime("now"));
        echo " UTC</p>";
        */
          if($this->session->userdata('email_verified') == 0)
          {
            echo "<div class='alert alert-danger mt-3' role='alert'>
            Please verify your email. <a href='" .base_url('verify'). "'>Click here to verify</a> </div>";
          }
        }
    ?>
  </div>
</div>
