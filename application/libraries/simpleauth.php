<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/*

Auth 1.0:
    https://github.com/fabianofa/codeigniter-simple-auth

The MIT License (MIT)

Copyright (c) 2014 Fabiano Araujo

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
*/

class SimpleAuth {

    // Cipher or hash engine to be used
    private $encrypt       = false;

    /**
     * Construct loading auth config file
     * and setting $encrypt value
     */
    public function __construct(){
        $CI =& get_instance();
        $CI->config->load('simpleauth', TRUE);
        $this->encrypt          = $CI->config->item('user_password_encrypt', 'simpleauth');
    }

    /**
     * Check if user exists, if password is right
     * and add a new row to singin log table if
     * setted
     *
     * This method DOES NOT validates or sanitaze
     * data, so it is expected to receive as param
     * username correctly sanitazed
     *
     * DO NOT send encrypt or hash password as param.
     * This is Auth's job
     *
     * To return values, you might read about it at:
     * https://github.com/fabianofa/codeigniter-simple-auth
     *
     * @param  string $username Clean username
     * @param  string $password Decrypted, no-hashed password
     * @return array            Success, error message and code
     *                                  
     */
    public function authenticate($username, $password) {
        $CI          =& get_instance();
        $logged_used = false;

        // Check if user exists
        $user_id = $this->get_user_id($username);
        if (!$user_id){
            return array('success' => false, 'code' => 01, 'message' => 'User not found.');
        }            

        // Get user password
        $compare_password = $this->get_user_password($user_id);

        // Define how should compare passwords
        if ($this->encrypt == "md5"){
            $password = md5($password);
        } else {
            // Load encrypt lib only if needed
            $CI->load->library('encrypt');
            $CI->encrypt->set_cipher($this->encrypt);

            // Decrypt
            $compare_password = $CI->encrypt->decode($compare_password);
        }

        // Compare passwords
        if ($password != $compare_password){
            return array('success' => false, 'code' => 02, 'message' => 'Invalid password or username.');
        }            

        // Log insert
        if (!empty($CI->config->item('user_table', 'simpleauth'))){
            $logged_used = ($this->insert_log($username) > 0);
        }

        // Create session
        $CI->load->library('session');
        
        $CI->session->set_userdata('authenticated_admin' , TRUE);
        $CI->session->set_userdata('username_admin', $username);

        // Return success array
        return array('success' => true, 'log_entrace' => $logged_used, 'code' => 10);
    }

    /**
     * Returns if user is logged based on session values
     * @return boolean 
     */
    public function is_auth(){
        $CI =& get_instance();
        $CI->load->library('session');

        $user = $CI->session->userdata('username_admin');

        return ( $CI->session->userdata('authenticated_admin') && !empty($user));
    }

    /**
     * Unset session values
     * @return boolean [description]
     */
    public function un_auth(){
        $CI =& get_instance();
        $CI->load->library('session');

        $CI->session->unset_userdata('authenticated_admin');
        $CI->session->unset_userdata('username_admin');

        return true;
    }


    /**
     * Database query to check user ID from a 
     * username
     * @param  string $username Username to look for ID
     * @return int              Respective ID
     */
    private function get_user_id($username){
        $CI =& get_instance();
        $data = $CI->db->select($CI->config->item('user_id_column', 'simpleauth'))
                       ->where($CI->config->item('user_username_column', 'simpleauth'), $username)
                       ->get($CI->config->item('user_table', 'simpleauth'))
                       ->result();
        return $data[0]->id;
    }

    /**
     * Database query to get user password from
     * a user ID
     * @param  int $user_id User ID
     * @return string       Password encrypted
     */
    private function get_user_password($user_id){
        $CI =& get_instance();
        $data = $CI->db->select($CI->config->item('user_password_column', 'simpleauth'))
                       ->where($CI->config->item('user_id_column', 'simpleauth'), $user_id)
                       ->get($CI->config->item('user_table', 'simpleauth'))
                       ->result();
        return $data[0]->password;
    }

    /**
     * Database query to add a success signin log
     * @param  string $username Username just signed in
     * @return int              Autoincrement ID of record
     */
    private function insert_log($username){
        $CI =& get_instance();

        $data = array(
            'date'        => 'NOW()',
            'ip'          => $_SERVER['REMOTE_ADDR'],
            'admin_user'  => $username
        );

        $CI->db->insert($CI->config->item('userlog_table', 'simpleauth'), $data);
        return $CI->db->insert_id();
    }
}