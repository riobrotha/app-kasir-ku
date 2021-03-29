<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Report extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $role = $this->session->userdata('role');

        if ($role == 'admin') {
            return;
        } else {
            $this->session->set_flashdata('warning', "You Don't Have Access");
            redirect(base_url() . 'auth/login');
            return;
        }
    }

    public function index()
    {
        $this->sales();
    }

    public function sales()
    {
        $data['title']          = 'Report Sales Based Invoice';
        $data['page_title']     = 'Report Sales Based Invoice';
        $data['nav_title']      = 'report';
        $data['detail_title']   = 'report_sales';
        $data['page']           = 'pages/admin/report/sales/index';

        $this->view($data);
    }

    public function requestSales($selectFilter)
    {

        if ($selectFilter == "date") {
            $start_from     = $this->input->post('tgl_start');
            $end_period     = $this->input->post('tgl_end');

            $start = explode("/", $start_from);
            $end   = explode("/", $end_period);

            $tgl_start = $start[2] . "-" . $start[1] . "-" . $start[0];
            $tgl_end = $end[2] . "-" . $end[1] . "-" . $end[0];

            $data['content']    = $this->report->where('DATE(created_at) >=', $tgl_start)
                ->where('DATE(created_at) <=', $tgl_end)
                ->get();

            if (count($data['content']) > 0) {

                echo json_encode([
                    'statusCode'        => 200,
                    'content'           => $this->load->view('pages/admin/report/sales/data/table', $data, true)
                ]);
            } else {
                echo json_encode([
                    'statusCode'        => 201,
                    'content'           => 'Data Not Found'
                ]);
            }
        } else if ($selectFilter == "month") {
            $month = $this->input->post('month', true);
            $year  = $this->input->post('year', true);

            $data['content']    = $this->report->where('MONTH(created_at)', $month)
                ->where('YEAR(created_at)', $year)
                ->get();

            if (count($data['content']) > 0) {
                echo json_encode([
                    'statusCode'        => 200,
                    'content'           => $this->load->view('pages/admin/report/sales/data/table', $data, true)
                ]);
            } else {
                echo json_encode([
                    'statusCode'        => 201,
                    'content'           => 'Data Not Found'
                ]);
            }
        } else {
            $year   = $this->input->post('year2', true);

            $data['content']    = $this->report
                ->where('YEAR(created_at)', $year)
                ->get();

            if (count($data['content']) > 0) {
                echo json_encode([
                    'statusCode'        => 200,
                    'content'           => $this->load->view('pages/admin/report/sales/data/table', $data, true)
                ]);
            } else {
                echo json_encode([
                    'statusCode'        => 201,
                    'content'           => 'Data Not Found'
                ]);
            }
        }
    }

    public function salesProduct()
    {
        $data['title']          = 'Report Sales Based Products';
        $data['page_title']     = 'Report Sales Based Products';
        $data['nav_title']      = 'report';
        $data['detail_title']   = 'report_sales_product';
        $data['page']           = 'pages/admin/report/sales/product/index';

        $this->view($data);
    }

    public function requestSalesProduct($filter)
    {
        if ($filter == "date") {
            $start_from = $this->input->post('tgl_start', true);
            $end_period = $this->input->post('tgl_end', true);

            $start = explode("/", $start_from);
            $end   = explode("/", $end_period);

            $tgl_start = $start[2] . "-" . $start[1] . "-" . $start[0];
            $tgl_end = $end[2] . "-" . $end[1] . "-" . $end[0];

            $this->report->table = 'transaction_detail';
            $data['content']     = $this->report->select([
                'product.title', 'product.price', 'SUM(transaction_detail.qty) AS qty', 'SUM(transaction_detail.subtotal) AS subtotal',
                'transaction.discount_total'
            ])
                ->join('product')
                ->joinTransaction('transaction')
                ->where('DATE(transaction.created_at) >=', $tgl_start)
                ->where('DATE(transaction.created_at) <=', $tgl_end)
                ->groupBy('transaction_detail.id_product')
                ->get();

            $this->report->table = 'transaction';
            $data['total_discount'] = $this->report->select([
                'transaction.discount_total'
            ])
                ->where('DATE(transaction.created_at) >=', $tgl_start)
                ->where('DATE(transaction.created_at) <=', $tgl_end)
                ->get();

            $data['get_total_discount'] = array_sum(array_column($data['total_discount'], 'discount_total'));

            if (count($data['content']) > 0) {
                echo json_encode([
                    'statusCode'        => 200,
                    'content'           => $this->load->view('pages/admin/report/sales/product/data/table', $data, true)
                ]);
            } else {
                echo json_encode([
                    'statusCode'        => 201,
                    'content'           => 'Data Not Found'
                ]);
            }
        } else if ($filter == "month") {
            $month = $this->input->post('month', true);
            $year  = $this->input->post('year', true);

            $this->report->table = 'transaction_detail';
            $data['content']        = $this->report->select([
                'product.title', 'product.price', 'SUM(transaction_detail.qty) AS qty', 'SUM(transaction_detail.subtotal) AS subtotal',
                'transaction.discount_total'
            ])
                ->join('product')
                ->joinTransaction('transaction')
                ->where('MONTH(transaction.created_at)', $month)
                ->where('YEAR(transaction.created_at)', $year)
                ->groupBy('transaction_detail.id_product')
                ->get();

            $this->report->table = 'transaction';
            $data['total_discount'] = $this->report->select([
                'transaction.discount_total'
            ])
                ->where('MONTH(transaction.created_at)', $month)
                ->where('YEAR(transaction.created_at)', $year)
                ->get();

            $data['get_total_discount'] = array_sum(array_column($data['total_discount'], 'discount_total'));

            if (count($data['content']) > 0) {
                echo json_encode([
                    'statusCode'        => 200,
                    'content'           => $this->load->view('pages/admin/report/sales/product/data/table', $data, true)
                ]);
            } else {
                echo json_encode([
                    'statusCode'        => 201,
                    'content'           => 'Data Not Found'
                ]);
            }
        } else {
            $year = $this->input->post('year2', true);

            $this->report->table = 'transaction_detail';
            $data['content']     = $this->report->select([
                'product.title', 'product.price', 'SUM(transaction_detail.qty) AS qty', 'SUM(transaction_detail.subtotal) AS subtotal',
                'transaction.discount_total'
            ])
                ->join('product')
                ->joinTransaction('transaction')
                ->where('YEAR(transaction.created_at)', $year)
                ->groupBy('transaction_detail.id_product')
                ->get();

            $this->report->table = 'transaction';
            $data['total_discount'] = $this->report->select([
                'transaction.discount_total'
            ])
                ->where('YEAR(transaction.created_at)', $year)
                ->get();

            $data['get_total_discount'] = array_sum(array_column($data['total_discount'], 'discount_total'));

            if (count($data['content']) > 0) {
                echo json_encode([
                    'statusCode'        => 200,
                    'content'           => $this->load->view('pages/admin/report/sales/product/data/table', $data, true)
                ]);
            } else {
                echo json_encode([
                    'statusCode'        => 201,
                    'content'           => 'Data Not Found'
                ]);
            }
        }
    }

    public function tracking_product()
    {
        $data['title']          = 'Tracking Products Report';
        $data['page_title']     = 'Tracking Products Report';
        $data['nav_title']      = 'report';
        $data['detail_title']   = 'report_tracking_product';
        $data['page']           = 'pages/admin/report/tracking_order/index';

        $this->view($data);
    }

    /**
     * 
     * method to access report tracking product stock
     * 
     */
    public function requestTrackingProduct()
    {
        //get value from input with method post
        $month = $this->input->post('month', true);
        $year  = $this->input->post('year', true);

        //query to get tracking product
        $this->report->table    = 'product';
        $data['content']        = $this->report->select([
            'product.title', 'product_in.stock_in AS stock_in', 'product_in.created_at', 'product.stock AS stock',
             'SUM(transaction_detail.qty) AS stock_out'
        ])
        ->join2('product_in')
        ->join2('transaction_detail')
        ->joinBetweenTransaction()
        ->where('MONTH(product_in.created_at)', $month)
        ->where('YEAR(product_in.created_at)', $year)
        ->groupBy('product.title')
        ->get();


        
        //condition if query is available and not null
        if(count($data['content']) > 0) {
            echo json_encode([
                'statusCode'        => 200,
                'content'           => $this->load->view('pages/admin/report/tracking_order/data/table', $data, true)
            ]);
        } else {
            echo json_encode([
                'statusCode'        => 201,
                'content'           => 'Data Not Found'
            ]);
        }
    }

    /**
     * 
     * method report sales perday
     * 
     * 
     */

     public function salesPerDays()
     {
         $data['title']              = 'Sales Report Perdays';
         $data['page_title']         = 'Sales Report Perdays';
         $data['nav_title']          = 'report';
         $data['detail_title']       = 'report_sales_perdays';
         $data['page']               = 'pages/admin/report/sales/perdays/index';

         $this->view($data);
     }
     

     /**
      * 
      * method to access report sales perdays
      *
      *
      */

     public function requestSalesPerDays()
     {
         //get value from input with method post
         $month         = $this->input->post('month', true);
         $year          = $this->input->post('year', true);

         //query to get report sales perdays
         $this->report->table       = 'transaction';
         $data['content']           = $this->report->select([
             'transaction.invoice', 'SUM(transaction.total) AS total', 
             'transaction.created_at'
         ])
         ->where('MONTH(transaction.created_at)', $month)
         ->where('YEAR(transaction.created_at)', $year)
         ->groupBy('DATE(transaction.created_at)')
         ->get();

         if(count($data['content']) > 0) {
             echo json_encode([
                 'statusCode'       => 200,
                 'content'          => $this->load->view('pages/admin/report/sales/perdays/data/table', $data, true)
             ]);
         } else {
             echo json_encode([
                 'statusCode'       => 201,
                 'content'          => 'Data Not Found'
             ]);
         }
     }
}

/* End of file Report.php */
