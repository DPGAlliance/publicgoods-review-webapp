<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'smtp.gmail.com', 
    'smtp_port' => 587,
    'smtp_user' => 'noreply@digitalpublicgoods.net',
    'smtp_from' => 'noreply@digitalpublicgoods.net',
    'smtp_display_name' => 'DPGA Admin',
    'smtp_pass' => '',
    'smtp_crypto' => 'tls', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '4', //in seconds
    'charset' => 'iso-8859-1',
    'bcc_batch_mode'=> TRUE,
    'bcc_batch_size' =>'99',
    'wordwrap' => TRUE
);


