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
        $data['getPatients']        = $this->medicalrecord
            ->select([
                'medical_records_detail.anamnesa', 'medical_records_detail.diagnosa',
                'medical_records_detail.created_at', 'medical_records_detail.id_therapies'
            ])
            ->join2('medical_records_detail')
            ->where('medical_records.id_customer', $id_customer)->get();
        $data['noRm']               = $this->medicalrecord->select([
            'id'
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

        //to tb medical_records
        $id = $this->medical_record_number();
        $id_doctor = $this->session->userdata('id_doctor');
        $id_queue = $this->input->post('id_queue', true);
        $id_customer = $this->input->post('id_customer', true);
        //$rm_number = $this->medical_record_number_check($id_customer);

        //to tb medical_records_detail
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


            );

            $data_detail = array(
                'id'                    => rand(pow(10, 3 - 1), pow(10, 3) - 1) . date('YmdHis'),
                'id_medical_records'    => $id,
                'anamnesa'              => $anamnesa,
                'pemeriksaan'           => $pemeriksaan,
                'diagnosa'              => $diagnosa,
                'id_therapies'          => $id_therapies,
                'id_queue'              => $id_queue,

            );

            $data_therapies = array(
                'id'        => $id_therapies,
                'note'      => $note
            );

            $check_rm = $this->medicalrecord->where('id_customer', $id_customer)->count();
            if ($check_rm == 0) {
                if ($this->medicalrecord->add($data) == true) {

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
                            $this->medicalrecord->table = 'medical_records_detail';
                            $this->medicalrecord->add($data_detail);


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
            } else {


                $this->medicalrecord->table = 'medical_records';
                $id_medical_records = $this->medicalrecord->select(['id'])
                    ->where('id_customer', $id_customer)->first();
                $data_detail2 = array(
                    'id'                    => rand(pow(10, 3 - 1), pow(10, 3) - 1) . date('YmdHis'),
                    'id_medical_records'    => $id_medical_records->id,
                    'anamnesa'              => $anamnesa,
                    'pemeriksaan'           => $pemeriksaan,
                    'diagnosa'              => $diagnosa,
                    'id_therapies'          => $id_therapies,
                    'id_queue'              => $id_queue,
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

                        $this->medicalrecord->table = 'medical_records_detail';
                        $this->medicalrecord->add($data_detail2);

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

    public function edit()
    {
        $data['id'] = $this->input->get('id');

        $data['title'] = 'Edit Medical Record Form';

        $this->home->table      = 'medical_records';
        $data['dataMedicalRecord'] = $this->home->select([
            'medical_records_detail.id_queue', 'medical_records_detail.anamnesa',
            'medical_records_detail.pemeriksaan', 'medical_records_detail.diagnosa',
            'medical_records_detail.id_therapies'
        ])
            ->join2('medical_records_detail')
            ->where('medical_records_detail.id_queue', $data['id'])
            ->first();


        $this->home->table = 'therapies';
        $data['dataTherapies'] = $this->home->select([
            'therapies.id', 'therapies.note', 'therapies_detail.id_product'
        ])
            ->join2('therapies_detail')
            ->where('therapies.id', $data['dataMedicalRecord']->id_therapies)
            ->get();

        $data['note']         = $this->home->select([
            'therapies.note'
        ])->where('therapies.id', $data['dataMedicalRecord']->id_therapies)->first();

        $this->home->table      = 'product';
        $data['dataTherapy']    = $this->home->where('id_category', '102001')->get();
        $this->output->set_output(show_my_modal('pages/doctor/modal/modal_edit_medical_record', 'modal-edit-medical-records', $data, 'lg'));
    }

    public function update_medical_record()
    {
        $this->medicalrecord->table = 'medical_records_detail';
        $id_therapies = $this->input->post('id_therapies', true);
        $anamnesa = $this->input->post('anamnesa', true);
        $pemeriksaan = $this->input->post('pemeriksaan', true);
        $diagnosa = $this->input->post('diagnosa', true);
        $therapy = $this->input->post('therapy', true);
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
            $data = [
                'anamnesa'      => $anamnesa,
                'pemeriksaan'   => $pemeriksaan,
                'diagnosa'      => $diagnosa,
            ];

            $data_therapies = [
                'note'          => $note
            ];

            if ($this->medicalrecord->where('id_therapies', $id_therapies)->update($data)) {

                $this->medicalrecord->table = 'therapies';
                if ($this->medicalrecord->where('id', $id_therapies)->update($data_therapies)) {

                    $this->medicalrecord->table = 'therapies_detail';
                    $getTherapiesDetail = $this->medicalrecord->select(['id_product'])->where('id_therapies', $id_therapies)->orderBy('id_product')->get();


                    //update multiple select data terapi
                    $data_therapies_detail = [];
                    foreach($getTherapiesDetail as $row) {
                        $data_therapies_detail[] = $row->id_product;
                    }

                    //add
                    foreach($therapy as $therapy_val) {
                        if (!in_array($therapy_val, $data_therapies_detail)) {
                            $this->medicalrecord->add([
                                'id'                => date('YmdHis') . rand(pow(10, 3 - 1), pow(10, 3) - 1),
                                'id_product'        => $therapy_val,
                                'id_therapies'      => $id_therapies
                            ]);
                        }
                    }

                    //delete
                    foreach($data_therapies_detail as $data_therapies_detail_row) {
                        if (!in_array($data_therapies_detail_row, $therapy)) {
                            $this->medicalrecord->where('id_therapies', $id_therapies)
                            ->where('id_product', $data_therapies_detail_row)->delete();
                        }
                    }


                }
                echo json_encode(array(
                    'statusCode'        => 200,
                    'msg'               => 'Medical Record has been updated!'
                ));
            } else {
                echo json_encode(array(
                    'statusCode'        => 201
                ));
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
        $data['getPatients']        = $this->medicalrecord
            ->select([
                'medical_records_detail.anamnesa', 'medical_records_detail.diagnosa',
                'medical_records_detail.created_at', 'medical_records_detail.id_therapies'
            ])
            ->join2('medical_records_detail')
            ->where('medical_records.id_customer', $id_customer)->get();
        $data['noRm']               = $this->medicalrecord->select([
            'id'
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
