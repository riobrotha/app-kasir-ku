<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Cashier extends MY_Controller
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
        $data['title']          = 'Cashier';
        $data['page_title']     = 'Cashier - KasirKu';
        $data['nav_title']      = 'cashier';
        $data['detail_title']   = 'cashier';

        //product
        $this->cashier->table   = 'product';
        $data['product']        = $this->cashier->where('product.is_available', 1)->get();

        foreach ($data['product'] as $row) {
            $this->session->set_userdata('stock' . $row->id, $row->stock);
        }

        //discount
        $this->cashier->table   = 'discount';
        $data['discount']       = $this->cashier->get();
        $data['countDiscount']  = $this->cashier->count();
        //item
        $data['cart']           = $this->cart->contents();
        $data['totalCart']      = $this->cart->total();
        $data['page']           = 'pages/cashier/index';
        $this->view_cashier($data);
    }

    public function loadDataTableCart()
    {
        //item
        $data['cart']           = $this->cart->contents();
        $data['totalCart']      = $this->cart->total();

        $this->load->view('pages/cashier/table_cart', $data);
    }

    public function insert($id, $qty, $price, $title, $stock, $disc = '', $price_temp = '', $discount_sebelum = '')
    {


        $stock_userdata = $this->session->userdata('stock' . $id);
        if ($stock_userdata <= 0) {
            echo json_encode(
                array(
                    'statusCode'    => 202,
                    'msg'   => 'Out of Stock'
                )

            );
        } else {
            $data = array(
                'id'    => $id,
                'qty'   => $qty,
                'price' => $price,
                'name'  => ucwords(urldecode($title)),
                'option' => array(
                    'stock'         => $stock_userdata,
                    'price_temp'    => $price_temp,
                    'discount_temp' => $disc

                )
            );

            $add = $this->cart->insert($data);
            if ($add) {
                $stock = (int) $stock;
                $quantity = (int) $qty;
                $sisaStock = $stock - $quantity;
                $this->session->set_userdata('stock' . $id, $sisaStock);

                $this->session->set_userdata('discount_total', $disc * $qty);
                $this->session->set_userdata('price_temp', $price_temp);

                echo json_encode(array(
                    'statusCode'    => 200,
                    'stock'         => $stock,
                    'sisaStock'     => $this->session->userdata('stock' . $id)
                ));
            } else {
                echo json_encode(array(
                    'statusCode' => 201,
                ));
            }
        }
    }

    public function insert_by_itemCode($itemCode, $qty)
    {
        $this->cashier->table = 'product';
        $getProduct = $this->cashier->where('product.id', $itemCode)->first();
        $getProductCount = $this->cashier->where('product.id', $itemCode)->count();
        $stock_userdata = $this->session->userdata('stock' . $itemCode);


        if ($getProductCount > 0) {
            if ($stock_userdata <= 0) {
                echo json_encode(
                    array(
                        'statusCode'    => 202,
                        'msg'   => 'Out of Stock'
                    )

                );
            } else {
                $data = array(
                    'id'    => $getProduct->id,
                    'qty'   => $qty,
                    'price' => $getProduct->price,
                    'name'  => ucwords(urldecode($getProduct->title)),
                    'option' => array(
                        'stock' => $stock_userdata,
                        'price_temp'    => $getProduct->price,
                        'discount_temp' => 0
                    )
                );

                $add = $this->cart->insert($data);

                if ($add) {
                    $stock = (int) $this->session->userdata('stock' . $itemCode);
                    $quantity = (int) $qty;
                    $sisaStock = $stock - $quantity;
                    $this->session->set_userdata('stock' . $itemCode, $sisaStock);
                    $this->session->unset_userdata('discount_total');
                    echo json_encode(array(
                        'statusCode'    => 200,
                        'stock'         => $getProduct->stock,
                        'sisaStock'     => $this->session->userdata('stock' . $itemCode)
                    ));
                } else {
                    echo json_encode(array(
                        'statusCode' => 201,
                    ));
                }
            }
        }
    }

    public function show()
    {
        $items = $this->cart->contents();

        echo '<pre>';
        print_r($items);
        echo '</pre>';
    }

    public function clear_cart()
    {
        if (!$this->cart->destroy()) {
            echo json_encode(array(
                'statusCode' => 200,
                'msg'        => 'Cart has been empty!'
            ));
        } else {
            echo json_encode(array(
                'statusCode' => 201,
                'msg'        => 'Oops! Something went wrong!'
            ));
        }
    }

    public function delete($rowid)
    {
        $this->cart->remove($rowid);
    }



    public function loadTotVal()
    {
        echo $this->cart->total();
    }

    public function pay()
    {
        $invoice = $this->input->post('invoice', true);
        // $subtotal = $this->session->userdata('price_temp');
        $subtotal = $this->input->post('subtotal', true);
        $discount_total = $this->input->post('discount_total', true);
        $total = $this->input->post('total', true);
        $money_change = $this->input->post('money_change', true);
        $cash_payment = $this->input->post('cash_payment', true);


        //$subtotal = array();

        // foreach($this->cart->contents() as $row) {
        //     array_push($subtotal, ['price' => $row['option']['price_temp']]);
        // }
        $data = array(
            'invoice'       => $invoice,
            'id_user'       => $this->session->userdata('id'),
            'subtotal'      => $subtotal,
            'discount_total' => $discount_total,
            'total'         => $total,
            'cash_payment'  => (int) str_replace(".", "", $cash_payment),
            'money_change'  => (int) str_replace(".", "", $money_change)
        );

        $this->cashier->table = 'transaction';
        if ($this->cashier->add($data) == true) {
            $this->pay_detail($invoice);
            $this->update_stock();
            echo json_encode(
                array(
                    'statusCode'    => 200,
                )
            );
        } else {
            echo json_encode(
                array(
                    'statusCode'    => 201,
                )
            );
        }
    }

    public function pay_detail($invoice_transaction)
    {
        $data = array();
        foreach ($this->cart->contents() as $row) {
            array_push(
                $data,
                array(
                    'invoice_transaction'   => $invoice_transaction,
                    'id_product'    => $row['id'],
                    'qty'           => $row['qty'],
                    'subtotal'      => $row['option']['price_temp'] * $row['qty']
                )
            );
        }

        $this->cashier->table = 'transaction_detail';
        if ($this->cashier->add_batch($data) == true) {
            //echo 'berhasil';
        } else {
            //echo 'gagal';
        }
    }

    public function update_stock()
    {
        $data = array();
        foreach ($this->cart->contents() as $row) {
            array_push(
                $data,
                array(
                    'id'       => $row['id'],
                    'stock'    => $this->session->userdata('stock' . $row['id'])
                )
            );
        }

        $this->cashier->table = 'product';
        if ($this->cashier->update_batch($data, 'id')) {
            // berhasil
        } else {
            // gagal
        }
    }

    public function filter($jenisItem)
    {
        $this->cashier->table = 'product';
        $data['product']      = $this->cashier->where('product.is_available', 1)
            ->where('product.id_category', $jenisItem)->get();

        if (count($data['product']) > 0) {
            echo json_encode(
                [
                    'statusCode'        => 200,
                    'html'              => $this->load->view('pages/cashier/item', $data, true)
                ]
            );
        } else {
            echo json_encode(
                [
                    'statusCode'        => 201,
                    'html'              => '<h4>Item Not Found</h4>'
                ]
            );
        }
    }
}

/* End of file Cashier.php */
