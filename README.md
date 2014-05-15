CodeIgniter SimpleAuth
=======================

SimpleAuth is a CodeIngniter lib that handles users login by comparing encrypted or hashed password and setting two session values. It also can add a history log from each user that successfully logged in.

You must set basic database table names, columns and encryptation mode, as SimpleAuth will look into these fields, search for the user by the username, get its password, encrypt, compare and finally creates session.

Installation
-----------------------

Download or clone this repository and paste `application` folder content into your project's `application` folder.

You may also add `simpleauth` to project's `config/autoload.php` or load on demand by `$this->load->library('simpleauth');`

Configuration
-----------------------
Open `config/auth.php` file.

**User table** section contains which table SimpleAuth will look into searching for users and passwords. It considers that you have at least ID, username and password columns. Any other field will not be used to authenticate a user.

If you need to use Email as username, for example, you can simply change `$config['user_username_column']` to respective email column name. Keep in mind that a session will be created with the authenticated username, in this case, with authenticated email. See more in Details.

`$config['user_table'] 			= "manager_users";`

`$config['user_id_column'] 		= "id";`

`$config['user_username_column'] = "user";`

`$config['user_password_column'] = "password";`

**User password encryptation** contains which Mcrypt cipher CodeIgniters encrypt library should use to decrypt and compare password. Ciphers available to use with SimpleAuth are the same supported by PHP version. You can check all ciphers at http://www.php.net/manual/en/mcrypt.ciphers.php

Alternatively you can set a string with md5 to use md5 hash engine.

A cipher MUST be set as constant, and NOT AS STRING:

In case of md5:
`$config['user_password_encrypt'] = "md5";`

In case of Mcrypt cipher:
`$config['user_password_encrypt'] = MCRYPT_RIJNDAEL_128;`

**Signing log table** section contains which table SimpleAuth will add record of sucessful signed user. This table is  **optional** and you must leave 'userlog_table' empty if you don't want to use this feature.

It simply adds a record to a table containing username of user logged, IP received by `$_SERVER['REMOTE_ADDR']` and current date of attempt.

`$config['user_table'] 				= "manager_userlog";`

`$config['userlog_user_column'] 	= "admin_user";`

`$config['userlog_ip_column'] 		= "ip";`

`$config['userlog_date_column']		= "date";`



**Messages** section holds string values of error messages available, based on error codes:

+ 01 = User not found in table
+ 02 = User found but invalid password

`$config['error_01'] 	= "User does not exists";`
`$config['error_02'] 	= "Invalid user or password";`

Details
-----------------------
This library adds two session values:
`simpleauth_auth` 	= Boolean value if user is logged or not;
`simpleauth_user` 	= String value of username or `user_username_column` value.

SimpleAuth DOES NOT sanitize inputted values. You must check and validate `$username` and `$password` BEFORE calling `$this->simpleauth->authenticate($username, $password);`

There are three public methods that you can access by using `$this->simpleauth->methodname()`:


### is_auth
Returns a boolean value if user is logged or not

### un_auth
Returns true always. Will unset session data and logout a user.

### authenticate(string $username, string $password)
Returns a array with values of success or failure to signin.
These arrays are:


** User does not exists **
`
[
	'success'	=> false,
	'code'		=> 01,
	'message'	=> 'User not found.'
]
`

** Invalid password **
`
[
	'success'	=> false,
	'code'		=> 02,
	'message'	=> 'Invalid password or username.'
]
`

** Successfully signed in **
`
[
	'success'		=> true,
	'log_record'	=> Int or false,
	'code'			=> 10
]
`


Example
-----------------------

You can find an example of usage in `example` folder.

