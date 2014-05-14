<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['user_table'] 			= "manager_users";
$config['user_id_column'] 		= "id";
$config['user_username_column'] = "user";
$config['user_password_column'] = "password";

$config['userlog_table']		= "manager_userlog";
$config['userlog_user_column'] 	= "admin_user";
$config['userlog_ip_column'] 	= "ip";
$config['userlog_date_column']	= "date";

$config['user_password_encrypt'] = "md5";