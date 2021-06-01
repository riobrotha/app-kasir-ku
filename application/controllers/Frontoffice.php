<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Frontoffice extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $role = $this->session->userdata('role');

        if ($role == 'admin' || $role == 'front_officer') {
            return;
        } else {
            $this->session->set_flashdata('warning', "You Don't Have Access");
            redirect(base_url() . 'auth/login');
            return;
        }
    }

    public function index()
    {
        $data['title']          = 'Front Office';
        $data['page_title']     = 'Front Office - KasirKu';
        $data['nav_title']      = 'front_officer';
        $data['detail_title']   = 'front_officer';

        $data['page']           = 'pages/front-office/index';

        $this->view_cashier($data);
    }

    public function add_queue($id_customer)
    {
        $id = $this->code_generator();

        $data = [
            'id'    => $id,
            'id_customer'   => $id_customer
        ];

        $this->frontoffice->table = 'queue';
        $cekDoubleData = $this->frontoffice->where('id_customer', $id_customer)->where('DATE(created_at)', date('Y-m-d'))->count();

        if ($cekDoubleData < 1) {
            if ($this->frontoffice->add($data) == true) {

                //notification with pusher to admin
                require FCPATH . 'vendor/autoload.php';

                $options = array(
                    'cluster' => 'ap1',
                    'useTLS' => true
                );
                $pusher = new Pusher\Pusher(
                    'cc14b125ee722dc1a2ea',
                    '45829a6d33e9dc1191be',
                    '1197860',
                    $options
                );

                $data['msg']        = 'Queue of Patients has been added!';
                $pusher->trigger('my-channel', 'my-event', $data);


                echo json_encode(array(
                    'statusCode'    => 200,
                    'msg'           => 'Success add to Queue'
                ));
            } else {
                echo json_encode(array(
                    'statusCode'    => 201,
                    'msg'           => 'Oops! Something went wrong!'
                ));
            }
        } else {
            echo json_encode(array(
                'statusCode'    => 202,
                'msg'           => 'This Customer has been added to Queue!'
            ));
        }
    }

    public function remove_queue($id)
    {
        if ($this->input->is_ajax_request()) {
            $this->frontoffice->table = 'queue';
            if ($this->frontoffice->where('id', $id)->delete()) {
                //notification with pusher to admin
                require FCPATH . 'vendor/autoload.php';

                $options = array(
                    'cluster' => 'ap1',
                    'useTLS' => true
                );
                $pusher = new Pusher\Pusher(
                    'cc14b125ee722dc1a2ea',
                    '45829a6d33e9dc1191be',
                    '1197860',
                    $options
                );

                $data['msg'] = 'Queue of Patients has been removed!';
                $pusher->trigger('my-channel', 'my-event', $data);

                echo json_encode(array(
                    'statusCode'        => 200,
                    'msg'               => 'Queue has been removed!',
                    'type'              => 'success',
                ));
            } else {
                echo json_encode(array(
                    'statusCode'        => 201,
                    'msg'               => 'Oops! Something went wrong!',
                    'type'              => 'error',
                ));
            }
        } else {
            echo '<h3>FORBIDDEN</h3>';
        }
    }

    public function insert_patient()
    {
        $id                 = date('Ymdhis') . rand(pow(10, 3 - 1), pow(10, 3) - 1);
        $name               = $this->input->post('name', true);
        $birth_date         = $this->input->post('birth_date', true);
        $identity_number    = $this->input->post('identity_number', true);
        $phone              = $this->input->post('phone', true);
        $email              = $this->input->post('email', true);
        $job                = $this->input->post('job', true);
        $address            = $this->input->post('address', true);

        $birth_date_2       = str_replace('/', '-', $birth_date);

        if (!$this->frontoffice->validate()) {
            $arr = array(
                'error'                     => true,
                'statusCode'                => 400,
                'name_error'                => form_error('name'),
                'birth_date_error'          => form_error('birth_date'),
                'identity_number_error'     => form_error('identity_number'),
                'phone_error'               => form_error('phone'),
                'email_error'               => form_error('email'),
                'job_error'                 => form_error('job'),
                'address_error'             => form_error('address')
            );

            echo json_encode($arr);
        } else {
            $data = array(
                'id'                        => $id,
                'name'                      => $name,
                'birth_date'                => date('Y-m-d',strtotime($birth_date_2)),
                'identity_number'           => $identity_number,
                'phone'                     => $phone,
                'email'                     => $email,
                'job'                       => $job,
                'address'                   => $address

            );

            if ($this->frontoffice->add($data) == true) {
                $this->session->set_flashdata('success', 'Data has been added!');


                echo json_encode(array(
                    'statusCode' => 200,
                    'id_customer' => $id
                ));
            } else {
                $this->session->set_flashdata('error', 'Oops! Something went wrong!');

                echo json_encode(array(
                    'statusCode'    => 201
                ));
            }
        }
    }


    public function code_generator()
    {
        $this->frontoffice->table = 'queue';
        $getMax = $this->frontoffice->select([
            'MAX(id) AS id'
        ])->where('DATE(queue.created_at)', date('Y-m-d'))->first();

        if ($getMax->id) {
            $code_from_db = $getMax->id;

            // Q3004210001
            $temp = (int) substr($code_from_db, 7);
            $temp++;

            $code = 'Q' . date('dmy') . sprintf("%04s", $temp);

            return $code;
        } else {
            $code = 'Q' . date('dmy') . '0001';
            return $code;
        }
    }


    public function loadDataPatients()
    {
        $data['patients'] = $this->frontoffice->orderBy('created_at', 'DESC')->get();
        $this->load->view('pages/front-office/data/table_patients', $data);
    }

    public function loadDataQueue()
    {
        $this->frontoffice->table = 'queue';
        $data['queue'] = $this->frontoffice->select([
            'queue.id',
            'customer.name', 'customer.phone', 'queue.created_at'
        ])
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->join('customer')
            ->get();

        //print_r($data['queue']);
        $this->load->view('pages/front-office/data/table_queue', $data);
    }
}

/* End of file Frontoffice.php */
