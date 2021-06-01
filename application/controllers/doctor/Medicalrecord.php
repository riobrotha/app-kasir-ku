<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Medicalrecord extends MY_Controller
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
        $data['page_title']          = 'Doctor - Medical Records History';
        $data['nav_title']      = 'medical_records_history';

        $this->medicalrecord->table = 'customer';
        $data['patients']       = $this->medicalrecord->orderBy('created_at', 'DESC')->get();
        $data['page']           = 'pages/doctor/medical-records/history/index';

        $this->view_doctor($data);
    }

    public function load_data_medical_records($id_customer)
    {
        $this->medicalrecord->table = 'medical_records';
        $data['getPatients']        = $this->medicalrecord->where('id_customer', $id_customer)->get();
        $data['noRm']               = $this->medicalrecord->select([
            'rm_number'
        ])->where('id_customer', $id_customer)->first();


        $this->medicalrecord->table = 'therapies_detail';
        foreach ($data['getPatients'] as $row) {
            $data['therapies'] = $this->medicalrecord->select([
                'product.title'
            ])->join('product')->where('therapies_detail.id_therapies', $row->id_therapies)->get();
        }


        $this->medicalrecord->table = 'customer';
        $data['patient']            = $this->medicalrecord->where('id', $id_customer)->first();

        $data['id_customer']        = $id_customer;
        $this->load->view('pages/doctor/medical-records/history/data/data-medical-records', $data);
    }

    public function add_medical_record()
    {
        $digits = 4;
        $id = $this->medical_record_number();
        $id_doctor = $this->session->userdata('id_doctor');
        $id_queue = $this->input->post('id_queue', true);
        $id_customer = $this->input->post('id_customer', true);
        $rm_number = $this->medical_record_number_check($id_customer);
        $anamnesa = $this->input->post('anamnesa', true);
        $pemeriksaan = $this->input->post('pemeriksaan', true);
        $diagnosa = $this->input->post('diagnosa', true);
        $therapy = $this->input->post('therapy', true);
        $id_therapies = date('YmdHis') . rand(pow(10, $digits - 1), pow(10, $digits) - 1);
        $note = $this->input->post('note', true);

        if (!$this->medicalrecord->validate()) {
            $arr = array(
                'error'                 => true,
                'statusCode'            => 400,
                'anamnesa_error'        => form_error('anamnesa'),
                'pemeriksaan_error'     => form_error('pemeriksaan'),
                'diagnosa_error'        => form_error('diagnosa'),
                'therapy_error'         => form_error('therapy[]'),
            );

            echo json_encode($arr);
        } else {


            $data = array(
                'id'            => $id,
                'id_doctor'     => $id_doctor,
                'id_customer'   => $id_customer,
                'id_queue'      => $id_queue,
                'rm_number'     => $rm_number,
                'anamnesa'      => $anamnesa,
                'pemeriksaan'   => $pemeriksaan,
                'diagnosa'      => $diagnosa,
                'id_therapies'  => $id_therapies
            );

            if ($this->medicalrecord->add($data) == true) {
                //add therapies
                $data_therapies = array(
                    'id'        => $id_therapies,
                    'note'      => $note
                );

                $this->medicalrecord->table = 'therapies';
                if ($this->medicalrecord->add($data_therapies) == true) {
                    $data_therapies_detail = array();
                    foreach ($therapy as $row) {

                        array_push($data_therapies_detail, array(
                            'id'            => date('YmdHis') . rand(pow(10, 3 - 1), pow(10, 3) - 1),
                            'id_product'    => $row,
                            'id_therapies'  => $id_therapies
                        ));
                    }

                    if ($this->db->insert_batch('therapies_detail', $data_therapies_detail)) {
                        $this->medicalrecord->table = 'queue';
                        $this->medicalrecord->where('queue.id', $id_queue)
                            ->update([
                                'status'    => 'on_progress'
                            ]);

                        echo json_encode(array(
                            'statusCode'    => 200
                        ));
                    } else {
                        echo json_encode(array(
                            'statusCode'    => 201
                        ));
                    }
                }
            }
        }
    }

    public function medical_record_number()
    {
        $getMax = $this->medicalrecord->select([
            'MAX(id) AS id'
        ])->first();

        if ($getMax->id) {
            $code_from_db = $getMax->id;

            // Q3004210001
            $temp = (int) substr($code_from_db, 0);
            $temp++;

            $code = sprintf("%04s", $temp);

            return $code;
        } else {
            $code = '0001';
            return $code;
        }
    }


    public function medical_record_number_check($id_customer)
    {
        $check = $this->medicalrecord->where('id_customer', $id_customer)->get();
        $getMax = $this->medicalrecord->select([
            'MAX(rm_number) AS rm_number'
        ])->first();

        if (count($check) == 0) {

            $getRm = $getMax->rm_number;
            $temp = (int) substr($getRm, 0);
            $temp++;


            $code = sprintf("%04s", $temp);

            return $code;
        } else {
            $code = $getMax->rm_number;
            return $code;
        }
    }


    public function print($id_customer)
    {

        $this->medicalrecord->table = 'medical_records';
        $data['getPatients']        = $this->medicalrecord->where('id_customer', $id_customer)->get();
        $data['noRm']               = $this->medicalrecord->select([
            'rm_number'
        ])->where('id_customer', $id_customer)->first();


        $this->medicalrecord->table = 'therapies_detail';
        foreach ($data['getPatients'] as $row) {
            $data['therapies'] = $this->medicalrecord->select([
                'product.title'
            ])->join('product')->where('therapies_detail.id_therapies', $row->id_therapies)->get();
        }


        $this->medicalrecord->table = 'customer';
        $data['patient']            = $this->medicalrecord->where('id', $id_customer)->first();
        $this->load->view('pages/doctor/medical-records/history/print/index', $data);
    }
}

/* End of file Medicalrecord.php */
