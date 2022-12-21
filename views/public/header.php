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
      <div class="col govind">
        <?php

        if(uri_string() == 'pages/legal')
        {
            echo '<div class="d-flex flex-row-reverse bd-highlight ">';
            echo "<div class='p-1 bd-highlight'>
            <a class='btn btn-link btn-sm' href='";
            echo base_url("login");
            echo "' role='button'>← Go back to Login</a>
            </div>";
            echo '</div>';
        }else{
          echo '<div class="d-flex flex-row-reverse bd-highlight ">';
            echo "<div class='p-1 bd-highlight'>
            <a class='btn btn-link btn-sm' href='https://digitalpublicgoods.net/registry/' role='button'>← Go back to DPG Registry</a>
            </div>";
            echo '</div>';
        }
       
          
        ?>
      </div><!-- Header column finish -->
    </div><!-- Header row finish -->
