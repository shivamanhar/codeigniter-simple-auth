<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Auth {
    private $encrypt       = false;

    public function __construct(){
        $CI =& get_instance();
        $CI->config->load('auth', TRUE);
        $this->encrypt          = $CI->config->item('user_password_encrypt', 'auth');
    }

    public function authenticate($username, $password) {
        $CI =& get_instance();
        $logged_used = false;

        // Check if user exists
        $user_id = $this->get_user_id($username);
        if (!$user_id){
            return array('success' => false, 'code' => 01, 'message' => 'User not found.');
        }            

        // Define which value should be used
        // for compare
        $compare_password = $this->get_user_password($user_id);

        if ($this->encrypt == "md5"){
            $password = md5($password);
        } else {
            $CI->load->library('encrypt');
            $CI->encrypt->set_cipher($this->encrypt);

            // Decrypt
            $compare_password = $CI->encrypt->decode($compare_password);
        }

        // Compare
        if ($password != $compare_password){
            return array('success' => false, 'code' => 02, 'message' => 'Invalid password or username.');
        }            

        // Log insert
        if (!empty($CI->config->item('user_table', 'auth'))){
            $logged_used = ($this->insert_log($username) > 0);
        }

        // Create session
        $CI->load->library('session');
        
        $CI->session->set_userdata('authenticated_admin' , TRUE);
        $CI->session->set_userdata('username_admin', $username);

        return array('success' => true, 'log_entrace' => $logged_used, 'code' => 10);
    }

    public function is_auth(){
    	$CI =& get_instance();
    	$CI->load->library('session');

        $user = $CI->session->userdata('username_admin');

    	return ( $CI->session->userdata('authenticated_admin') && !empty($user));
    }

    public function un_auth(){
    	$CI =& get_instance();
    	$CI->load->library('session');

    	$CI->session->unset_userdata('authenticated_admin');
        $CI->session->unset_userdata('username_admin');
    }

    private function get_user_id($username){
        $CI =& get_instance();
        $data = $CI->db->select($CI->config->item('user_id_column', 'auth'))
                       ->where($CI->config->item('user_username_column', 'auth'), $username)
                       ->get($CI->config->item('user_table', 'auth'))
                       ->result();
        return $data[0]->id;
    }

    private function get_user_password($user_id){
        $CI =& get_instance();
        $data = $CI->db->select($CI->config->item('user_password_column', 'auth'))
                       ->where($CI->config->item('user_id_column', 'auth'), $user_id)
                       ->get($CI->config->item('user_table', 'auth'))
                       ->result();
        return $data[0]->password;
    }

    private function insert_log($username){
        $CI =& get_instance();

        $data = array(
            'date'        => 'NOW()',
            'ip'          => $_SERVER['REMOTE_ADDR'],
            'admin_user'  => $username
        );

        $CI->db->insert($CI->config->item('userlog_table', 'auth'), $data);
        return $CI->db->insert_id();
    }
}