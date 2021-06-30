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
        //$data['customer']       = $this->customer->orderBy('created_at', 'DESC')->get();
        //$data['countCustomer']  = $this->customer->count();

        $this->customer->table    = 'queue';
        $data['customer']         = $this->customer->select([
            'customer.id AS id', 'customer.name',
            'customer.phone', 'customer.email',
            'queue.id AS id_queue', 'queue.created_at'
        ])
            ->join('customer')
            ->where('status', 'on_progress')
            ->where('queue.id_store', $this->session->userdata('id_store'))
            ->where('DATE(queue.created_at)', date('Y-m-d'))
            ->get();

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


    public function insert()
    {
        $name = $this->input->post('name', true);
        $phone = $this->input->post('phone', true);
        $email = $this->input->post('email', true);

        if (!$this->customer->validate()) {
            $array = [
                'error'         => true,
                'statusCode'    => 400,
                'name_error'    => form_error('name'),
                'phone_error'   => form_error('phone')
            ];

            echo json_encode($array);
        } else {
            $data = [
                'id'        => date('Ymdhis') . rand(pow(10, 3 - 1), pow(10, 3) - 1),
                'id_store'  => $this->session->userdata('id_store'),
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
        if ($this->input->is_ajax_request()) {
            $data = [
                'name'      => urldecode($name),
                'phone'     => $phone,
                'email'     => $email
            ];

            if ($this->customer->where('id', $id)->update($data)) {
                echo json_encode([
                    'statusCode'        => 200
                ]);
            }
        } else {
            echo '<h4>FORBIDDEN</h4>';
        }
    }


    public function add_treatment($id_queue)
    {
        $this->customer->table = 'medical_records_detail';
        $medical_records_detail = $this->customer->select([
            'medical_records_detail.id_therapies', 'queue.id', 'medical_records_detail.id_items'
        ])
            ->where('medical_records_detail.id_queue', $id_queue)
            ->join('queue')
            ->first();

        $this->customer->table = 'therapies_detail';
        $treatment = $this->customer->select([
            'product.id AS id_product', 'product.stock', 'product.price', 'product.purchase_price',
            'product.title',
        ])
            ->where('therapies_detail.id_therapies', $medical_records_detail->id_therapies)
            ->join('product')
            ->get();

        $this->customer->table = 'items_detail';
        $item = $this->customer->select([
            'product.id AS id_product', 'product.stock', 'product.price', 'product.purchase_price',
            'product.title', 'items_detail.qty'
        ])
            ->where('items_detail.id_items', $medical_records_detail->id_items)
            ->join('product')
            ->get();

        $data = array();
        $data_stock = array();
        foreach ($treatment as $row) {
            array_push(
                $data,
                [
                    'id'        => $row->id_product,
                    'qty'       => 1,
                    'price'     => $row->price,
                    'name'      => $row->title,
                    'option'    => array(
                        'stock'         => $row->stock,
                        'price_temp'    => $row->price,
                        'discount_temp' => 0,
                        'purchase_price'=> $row->purchase_price * 1
                    )
                ]
            );
        }

        foreach ($item as $row2) {
            if ($row2->stock > 0) {
                $stock = (int) $row2->stock;
                $quantity = (int) $row2->qty;

                $sisaStock = $stock - $quantity;
                $this->session->set_userdata('stock' . $row2->id_product, $sisaStock);
                array_push(
                    $data_stock,
                    [
                        'idProduct'     => $row2->id_product,
                        'sisaStock'     => $this->session->userdata('stock' . $row2->id_product)
                    ]
                );

                array_push(
                    $data,
                    [
                        'id'        => $row2->id_product,
                        'qty'       => $row2->qty,
                        'price'     => $row2->price,
                        'name'      => $row2->title,
                        'option'    => array(
                            'stock'         => $row2->stock,
                            'price_temp'    => $row2->price,
                            'discount_temp' => 0,
                            'purchase_price'=> $row2->purchase_price * $row2->qty
                        )
                    ]
                );
            }
        }

        $add = $this->cart->insert($data);
        if ($add) {

            
            echo json_encode(array(
                'statusCode'    => 200,
                'stock'         => 0,
                'sisaStock'     => $data_stock
            ));
        } else {
            //print_r($medical_records_detail);
            echo json_encode(array(
                'statusCode' => 201,
                'id_therapies'  => $medical_records_detail->id_therapies
            ));
        }


    }


    public function tes()
    {
        $this->customer->table = 'medical_records_detail';
        $therapies = $this->customer->select([
            'medical_records_detail.id_therapies', 'queue.id AS id'
        ])
            ->where('medical_records_detail.id_queue', 'Q011406210001')
            //->join2('medical_records_detail')
            ->join('queue')
            ->first();

        print_r($therapies);
    }
}

/* End of file Customer.php */
