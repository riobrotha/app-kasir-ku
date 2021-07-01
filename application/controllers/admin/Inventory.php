<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Inventory extends MY_Controller
{
    public function __construct()
	{
		parent::__construct();
		$role = $this->session->userdata('role');

		if ($role == 'admin' || $role == 'admin_store') {
			return;
		} else {
			$this->session->set_flashdata('warning', "You Don't Have Access");
			redirect(base_url() . 'auth/login');
			return;
		}
	}

    public function index()
    {
        $data['content']        = $this->inventory->get();

        //list category
        $this->inventory->table = 'category';
        $data['category']       = $this->inventory->get();
        $data['title']          = 'Inventory';
        $data['page_title']     = 'Inventory - Inventory List - Admin KasirKu';
        $data['nav_title']      = 'library';
        $data['detail_title']   = 'inventory';
        $data['page']           = 'pages/admin/inventory/index';

        $this->view($data);
    }

    public function loadTable()
    {
        $data['content']        = $this->inventory->select([
            'product.id', 'product.title', 'product.stock', 'product.price', 'product.id_store',
            'product.created_at', 'category.title AS category_title', 'store.name'
        ])
            ->where('product.is_available', 1)
            ->where('product.id_store', $this->session->userdata('id_store'))
            ->join('category')
            ->join('store')
            ->orderBy('product.id', 'DESC')->get();
        $this->load->view('pages/admin/inventory/data/table', $data);
    }

    public function loadAlert($nameAlert, $msg)
    {
        $this->session->set_flashdata($nameAlert, $msg);
        $this->load->view('layouts/_alert');
    }

    public function insert()
    {
        $title = $this->input->post('title', true);
        $slug  = $this->input->post('slug', true);
        $category = $this->input->post('category', true);
        $stock = $this->input->post('stock', true);
        $price = $this->input->post('price', true);
        $purchase_price = $this->input->post('purchase_price', true);
        $image = $this->input->post('image_product', true);

        if (!$this->inventory->validate()) {
            $array = array(
                'error'               => true,
                'statusCode'          => 400,
                'title_error'         => form_error('title'),
                'category_error'      => form_error('category'),
                'stock_error'         => form_error('stock'),
                'price_error'         => form_error('price'),
                'purchase_price_error'=> form_error('purchase_price')
            );

            echo json_encode($array);
        } else {
            $data = [
                'id'            => $this->generate_code($category),
                'id_category'   => $category,
                'id_store'      => $this->session->userdata('id_store'),
                'title'         => ucwords($title),
                'slug'          => $slug . '-' . $this->session->userdata('id_store'),
                'stock'         => $stock,
                'price'         => (int) str_replace(".", "", $price),
                'purchase_price'=> (int) str_replace(".", "", $purchase_price),
                'image'         => $image
            ];

            if ($this->inventory->add($data) == true) {

                echo json_encode(array(
                    'statusCode'    => 200,
                    'nameFlash'     => 'success',
                    'msg'           => 'Data has been added!'
                ));
            } else {
                $this->session->set_flashdata('error', 'Oops! Something went wrong!');
                echo json_encode(array(
                    'statusCode'    => 201,
                ));
            }
        }
    }

    public function generate_code($id_category)
    {
        $get_code = getCodeProduct($id_category);
        $last_code = isset($get_code->id) ? $get_code->id : "";

        if ($last_code != "") {
            $urutan = (int) substr($last_code, 6);
        } else {
            $urutan = 0;
        }

        $urutan++;

        $kodeBaru = $id_category . sprintf("%04s", $urutan);
        return $kodeBaru;
    }

    public function uploadProductImage($title)
    {
        if (isset($_POST['image'])) {
            $data       = $_POST['image'];

            $image_array_1 = explode(";", $data);

            $image_array_2 = explode(",", $image_array_1[1]);

            $data = base64_decode($image_array_2[1]);
            $imageName = url_title($title, '-', true) . '-' . date('YmdHis') . '.png';

            file_put_contents('./images/product/' . $imageName, $data);

            echo json_encode(array(
                'image_name'  => $imageName,
                'show_image'  => '<img src="' . base_url("images/product/$imageName") . '" class="img-thumbnail img-product" id="img-product">'
            ));
        }
    }

    public function edit($id)
    {
        $data['title']         = 'Edit Inventory Data';
        $data['getInventory']   = $this->inventory->select([
            'product.title AS title_product', 'product.slug', 'category.title AS title_category', 'product.stock',
            'product.price', 'product.image', 'product.id_category', 'product.id AS id_product', 'product.purchase_price'
        ])
            ->join('category')
            ->where('product.id', $id)
            ->first();

        $this->inventory->table = 'category';
        $data['getCategory'] = $this->inventory->get();

        $this->output->set_output(show_my_modal('pages/admin/inventory/modal/modal_edit_inventory', 'modal-edit-inventory', $data, 'lg'));
    }

    public function update()
    {
        $id    = $this->input->post('id', true);
        $title = $this->input->post('title', true);
        $slug  = $this->input->post('slug', true);
        $category = $this->input->post('category', true);
        $stock = $this->input->post('stock', true);
        $price = $this->input->post('price', true);
        $purchase_price = $this->input->post('purchase_price', true);
        $image = $this->input->post('image_product', true);
        $image_temp = $this->input->post('image_product_temp', true);

        if (!$this->inventory->validate()) {
            $array = array(
                'error'               => true,
                'statusCode'          => 400,
                'title_error'         => form_error('title'),
                'category_error'      => form_error('category'),
                'stock_error'         => form_error('stock'),
                'price_error'         => form_error('price'),
                'purchase_price_error'=> form_error('purchase_price')
            );

            echo json_encode($array);
        } else {
            $data = [
            
                'id_category'   => $category,
                'title'         => ucwords($title),
                'slug'          => $slug,
                'stock'         => $stock,
                'price'         => (int) str_replace(".", "", $price),
                'purchase_price'=> (int) str_replace(".", "", $purchase_price),
                'image'         => $image
            ];

            if ($this->inventory->where('product.id', $id)->update($data)) {

                if($image != $image_temp && $image_temp != "") {
                    $this->inventory->deleteImage($image_temp);
                }
                echo json_encode(array(
                    'statusCode'    => 200,
                    'nameFlash'     => 'success',
                    'msg'           => 'Data has been updated!'
                ));
            } else {
            
                echo json_encode(array(
                    'statusCode'    => 201,
                    'nameFlash'     => 'error',
                    'msg'           => 'Oops! Something went wrong!'
                ));
            }
        }
    }

    public function destroy($id)
    {

        if ($this->input->is_ajax_request()) {
            if ($this->inventory->where('id', $id)->delete()) {
                $this->session->set_flashdata('success', 'Data has been deleted!');
                echo json_encode(array(
                    "statusCode" => 200,

                ));
            } else {
                $this->session->set_flashdata('error', 'Oops! Something went wrong!');
                echo json_encode(array(
                    "statusCode" => 201,
                ));
            }
        } else {
            echo '<h3>FORBIDDEN</h3>';
        }
    }


    public function tes()
    {
        $product_siteba = $this->inventory->where('id_category', '102001')->where('id_store', '2')->get();


        print_r($product_siteba);
        // foreach($product_siteba as $row) {
        //     $data   = [
        //         'id'            => $this->generate_code($row->id_category),
        //         'id_category'   => $row->id_category,
        //         'id_store'      => 2,
        //         'title'         => $row->title,
        //         'slug'          => $row->slug . '-' . '2',
        //         'stock'         => 0,
        //         'price'         => $row->price,
        //         'purchase_price'=> $row->purchase_price,

        //     ];

        //     $this->inventory->add($data);
        // }
    }




    
}

/* End of file Inventory.php */
