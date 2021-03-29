<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Activity extends MY_Controller
{


    public function __construct()
    {
        parent::__construct();
        //Do your magic here
    }


    public function index()
    {
        $data['title']          = 'Transaction Activity';
        $data['page_title']     = 'Transaction Activity - KasirKu';
        $data['nav_title']      = 'transaction_activity';
        $data['detail_title']   = 'transaction_activity';

        $data['transaction']    = $this->activity->where('MONTH(created_at)', date("m"))
            ->orderBy('created_at', 'DESC')
            ->get();

        $data['page']           = 'pages/activity/index';
        $this->view_cashier($data);
    }

    public function detail($invoice)
    {
        $data['invoice_detail'] = $this->activity->where('invoice', $invoice)->join('user')->first();

        $this->activity->table = 'transaction_detail';
        $data['transaction'] = $this->activity->select([
            'transaction.total', 'transaction.invoice',
            'transaction_detail.qty', 'transaction_detail.subtotal AS subtotal',
            'product.title AS title_product',
            'product.price'
        ])
            ->where('invoice', $invoice)
            ->joinTransaction('transaction')
            ->join('product')
            ->get();
        
        $this->activity->table = 'transaction';
        $data['discount'] = $this->activity->select([
            'transaction.discount_total', 'transaction.subtotal'
        ])
            ->where('invoice', $invoice)
            ->first();

        $data['invoice']    = $invoice;

        $this->output->set_output(show_my_modal('pages/activity/modal/modal_detail', 'modalDetailInvoice', $data, 'lg'));

        //print_r($data['transaction']);
    }

    public function tes()
    {
        $data =
            array(
                'data1' => array(

                    'nama_pegawai'      => 'Rio Pambudhi',
                    'umur'      => '26',
                    'gender'    => 'Laki-laki'

                )
            );

        $this->session->set_userdata($data);

        print_r($this->session->userdata('data1'));
    }
}

/* End of file Activity.php */
