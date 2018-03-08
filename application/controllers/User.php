<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';
require_once APPPATH . '/libraries/JWT.php';

use \Firebase\JWT\JWT;

class User extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Users_model');
    }

    public function login_post() {

        $username = $this->post('username');
        $password = $this->post('password');
        $invalidLogin = ['invalid' => $username];

        if (!$username || !$password) $this->response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
        $id = $this->Users_model->login($username, $password);

        if ($id) {

            $token['id'] = $id;
            $token['username'] = $username;
            $date = new DateTime();
            $token['iat'] = $date->getTimestamp();
            $token['exp'] = $date->getTimestamp() + 60*60*5;
            $output['id_token'] = JWT::encode($token, "my Secret key!");
            $this->set_response($output, REST_Controller::HTTP_OK);

        } else {
            $this->set_response($invalidLogin, REST_Controller::HTTP_NOT_FOUND);
        }

    }

}
