<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        $role = $this->session->userdata('role');

        if ($role == 'admin' || $role == 'cashier') {
            return;
        } else {
            $this->session->set_flashdata('warning', "You Don't Have Access");
            redirect(base_url() . 'auth/login');
            return;
        }
    }


    public function index()
    {
        $data['customer']       = $this->customer->orderBy('created_at', 'DESC')->get();
        //$data['countCustomer']  = $this->customer->count();

        if (count($data['customer']) > 0) {
            echo json_encode(
                [
                    'statusCode'        => 200,
                    'html'              => $this->load->view('pages/customer/data/table', $data, true),
                    'countCustomer'     => count($data['customer'])
                ]
            );
        } else {
            echo json_encode(
                [
                    'statusCode'        => 201,
                    'html'              => 'Data Not Found',
                    'countCustomer'     => 0
                ]
            );
        }
    }

    public function tes()
    {
        $data['customer']       = $this->customer->orderBy('created_at', 'DESC')->get();

        print_r($data['customer']);
    }

    public function insert()
    {
        $name = $this->input->post('name', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);

        if(!$this->customer->validate()) 
        {
            $array = [
                'error'         => true,
                'statusCode'    => 400,
                'name_error'    => form_error('name'),
                'phone_error'   => form_error('phone')
            ];

            echo json_encode($array);
        }
        else {
            $data = [
                'id'        => date('Ymdhis') . rand(pow(10, 3 - 1), pow(10, 3) - 1),
                'name'      => $name,
                'phone'     => $phone,
                'email'     => $email
            ];
    
            if ($this->customer->add($data) == true) {
                $this->session->set_flashdata('success', 'Data Customer Has Been Added');
    
                echo json_encode(
                    [
                        'statusCode'        => 200,
    
                    ]
                );
            } else {
                $this->session->set_flashdata('error', 'Oops! Something went wrong!');
    
                echo json_encode(
                    [
                        'statusCode'        => 201,
    
                    ]
                );
            }
        }

       
    }

    public function update($id, $name, $phone, $email)
    {
        if($this->input->is_ajax_request()) {
            $data = [
                'name'      => urldecode($name),
                'phone'     => $phone,
                'email'     => $email
            ];

            if($this->customer->where('id', $id)->update($data)) {
                echo json_encode([
                    'statusCode'        => 200
                ]);
            }
        } else {
            echo '<h4>FORBIDDEN</h4>';
        }
    }
}

/* End of file Customer.php */
