<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

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


        $data['page']           = 'pages/doctor/index';

        $this->view_doctor($data);
    }

    public function loadDataQueue()
    {
        $this->home->table = 'queue';
        $data['queue'] = $this->home->select([
            'queue.id',
            'customer.name', 'customer.phone', 'queue.created_at'
        ])
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->join('customer')
            ->get();

        //print_r($data['queue']);
        $this->load->view('pages/doctor/data/table_queue', $data);
    }

    

}

/* End of file Home.php */
