<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| User table
|--------------------------------------------------------------------------
|
| Table which contains user data, such as id, username and
| password. You must add at least these three colums to
| Auth work.
| 
| 'user_table' 				= User table name
| 'user_id_column' 			= Column name for ID
| 'user_username_column' 	= Column name for username
| 'user_password_column' 	= Column name for password
*/
$config['user_table'] 			= "manager_users";
$config['user_id_column'] 		= "id";
$config['user_username_column'] = "user";
$config['user_password_column'] = "password";

/*
|--------------------------------------------------------------------------
| User password encryptation
|--------------------------------------------------------------------------
|
| This defines how should Auth handles the comparison
| between input data and stored data.
| 
| You MUST set a Mcrypt cipher or hash engine.
|
|   For hash engine, auth supports:
|		"md5"				=	PHP native md5 hash
|
|	For Mcrypt cipher:
|
|		MCRYPT_RIJNDAEL_128
|
|
|
|   Any PHP Mcrypt cipher available to your PHP version
|   for more information, check PHP docs: 
|
|	http://www.php.net/manual/en/mcrypt.ciphers.php
|	
|	NOTE: If a cipher, you MUST set it as constant.
|	Eg:
|	$config['user_password_encrypt']	= "md5";
|	$config['user_password_encrypt']	= MCRYPT_RIJNDAEL_128;

*/
$config['user_password_encrypt'] = MCRYPT_RIJNDAEL_128;


/*
|--------------------------------------------------------------------------
| Signin log table (Optional, leave blank if not necessary)
|--------------------------------------------------------------------------
|
| Table that stores log of successful users signin.
| You can totally remove this feature by setting a empty
| string in 'userlog_table' index.
|
| For IP, Auth will make use of $_SERVER['REMOTE_ADDR'] as value.
| 
| For Date, Auth will make use of NOW() MySQL function.
|
| 
|	'userlog_table'			= Signin log table name
|	'userlog_user_column'	= Username column name
|	'userlog_ip_column'		= IP column name
|	'userlog_date_column'	= Date column name
*/
$config['userlog_table']		= "manager_userlog";
$config['userlog_user_column'] 	= "admin_user";
$config['userlog_ip_column'] 	= "ip";
$config['userlog_date_column']	= "date";

/*
|--------------------------------------------------------------------------
| Messages
|--------------------------------------------------------------------------
|
| String values of error messages with based on
| error codes.
| 
|	'error_01'		= User not found in table
|	'error_02'		= User found but invalid password
*/

$config['error_01'] 	= "User does not exists";
$config['error_02'] 	= "Invalid user or password";
