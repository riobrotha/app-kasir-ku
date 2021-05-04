<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends MY_Model {

    public $table = 'user';

    public function getDefaultValues()
    {
        return [
            'username'     =>  '',
            'password'     =>  '',
        ];
    }

    public function getValidationRules()
    {
        $validationRules = [
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required'
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required'
            ],
        ];

        return $validationRules;
    }

    public function run($input)
    {
        $query  = $this->where('username', strtolower($input->username))
            ->where('is_active', 1)
            ->first();

        if (!$query) {
            $this->session->set_flashdata('warning', 'Account not found, please contact admin.');
        }
        
        if (!empty($query) && hashEncryptVerify($input->password, $query->password)) {

            $sess_data = [
                'id'           => $query->id,
                'id_doctor'    => $query->id_doctor,
                'name'         => $query->name,
                'username'     => $query->username,
                'role'         => $query->role,
                'is_login'     => true,
            ];

            $this->session->set_userdata($sess_data);
            return true;
        }
        return false;
    }

}

/* End of file Login_model.php */
