<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
        $role = $this->session->userdata('role');

        if ($role == 'doctor') {
            return;
        } else {
            $this->session->set_flashdata('warning', "You Don't Have Access");
            redirect(base_url() . 'auth/login');
            return;
        }
    }

    public function index()
    {
        $data['title']          = 'Doctor';
        $data['page_title']     = 'Doctor - Hers Clinic';
        $data['nav_title']      = 'doctor';
        $data['detail_title']   = 'doctor';

        $id = $this->session->userdata('id_doctor');
        $data['dataDoctor']     = $this->home->where('id', $id)->first();


        $this->home->table      = 'product';
        $data['dataTherapy']    = $this->home->where('id_store', $this->session->userdata('id_store'))
            ->where('id_category', '102001')->get();
        $data['page']           = 'pages/doctor/index';

        $this->view_doctor($data);
    }

    public function loadDataQueue()
    {
        $this->home->table = 'queue';
        $data['queue'] = $this->home->select([
            'queue.id', 'queue.id_customer', 'queue.status',
            'customer.name', 'customer.phone', 'queue.created_at'
        ])
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->where('queue.status', 'waiting')
            ->where('queue.id_store', $this->session->userdata('id_store'))
            ->orWhere('queue.status', 'on_consult')
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->where('queue.id_store', $this->session->userdata('id_store'))
            ->join('customer')
            ->get();



        //print_r($data['queue']);
        $this->load->view('pages/doctor/data/table_queue', $data);
    }

    public function updateDataQueue()
    {
        $this->home->table = 'queue';
        $data['queue'] = $this->home->select([
            'queue.id', 'queue.id_customer', 'queue.status',
            'customer.name', 'customer.phone', 'queue.created_at'
        ])
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->where('queue.status', 'waiting')
            ->orWhere('queue.status', 'on_consult')
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->join('customer')
            ->get();

        $i = 0;
        foreach ($data['queue'] as $row) {
            if ($i == 0) {
                if ($this->home->where('queue.id', $row->id)->update(
                    [
                        'status'    => 'on_consult'
                    ]
                )) {

                    echo json_encode(array(
                        'statusCode'    => 200
                    ));
                } else {
                    echo json_encode(array(
                        'statusCode'    => 201
                    ));
                }
            }

            $i++;
        }
    }

    public function loadDataQueueProgress($page = null, $perPage = null)
    {
        if ($perPage != null) {
            $this->home->perPage = $perPage;
        }


        $this->home->table = 'queue';
        $data['queue'] = $this->home->select([
            'queue.id', 'queue.id_customer', 'queue.status',
            'customer.name', 'customer.phone', 'queue.created_at'
        ])
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->where('queue.status', 'on_progress')
            ->where('queue.id_store', $this->session->userdata('id_store'))
            ->join('customer')
            ->paginate($page)
            ->get();

        $data['total_rows'] = $this->home->select([
            'queue.id', 'queue.id_customer', 'queue.status',
            'customer.name', 'customer.phone', 'queue.created_at'
        ])
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->where('queue.status', 'on_progress')
            ->where('queue.id_store', $this->session->userdata('id_store'))
            ->join('customer')
            ->count();

        $data['pagination'] = $this->home->makePagination(base_url() . 'doctor/home/loadDataQueueProgress/', 4, $data['total_rows']);


        echo json_encode([
            'html'      => $this->load->view('pages/doctor/data/table_queue_progress', $data, true),
            'pagination' => $data['pagination']
        ]);
    }

    public function searchDataQueueProgress($keyword, $page = null)
    {
        $this->home->table = 'queue';
        $data['queue']          = $this->home->select([
            'queue.id', 'queue.id_customer', 'queue.status',
            'customer.name', 'customer.phone', 'queue.created_at'
        ])
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->where('queue.status', 'on_progress')
            ->where('queue.id_store', $this->session->userdata('id_store'))
            ->join('customer')
            ->like('customer.name', urldecode($keyword))
            ->paginate($page)
            ->get();

        $data['total_rows']     = $this->home->select([
            'queue.id', 'queue.id_customer', 'queue.status',
            'customer.name', 'customer.phone', 'queue.created_at'
        ])
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->where('queue.status', 'on_progress')
            ->where('queue.id_store', $this->session->userdata('id_store'))
            ->join('customer')
            ->like('customer.name', urldecode($keyword))
            ->count();

        $data['pagination'] = $this->home->makePagination(
            base_url() . 'doctor/home/searchDataQueueProgress/' . urldecode($keyword) . '/',
            5,
            $data['total_rows']
        );

        echo json_encode([
            'html'      => $this->load->view('pages/doctor/data/table_queue_progress', $data, true),
            'pagination' => $data['pagination']
        ]);
    }

    public function updateToPaid($id_queue)
    {
        $this->home->table = 'queue';
        if ($this->input->is_ajax_request()) {
            $data = [
                'status'    => 'paid'
            ];

            if ($this->home->where('id', $id_queue)->update($data)) {
                $this->output->set_output(json_encode([
                    'statusCode'    => 200,
                    'msg'           => 'Patients has been added to payment!'
                ]));
            } else {
                $this->output->set_output(json_encode([
                    'statusCode'    => 201
                ]));
            }
        } else {
            echo '<h3>FORBIDDEN</h3>';
        }
    }
}

/* End of file Home.php */
