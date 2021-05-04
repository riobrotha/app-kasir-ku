<?php

if ($this->session->userdata('role') == 'cashier') {
    //modal bayar transaksi
    $this->load->view('pages/cashier/modal/modal_payment');
}


?>


<?php if ($this->session->userdata('role') == 'cashier') : ?>
    <!-- modal detail invoice -->
    <div id="modal_detail_invoice">

    </div>
<?php endif ?>
<?php


if ($this->session->userdata('role') == 'cashier') {
    //modal add to cart
    $this->load->view('pages/cashier/modal/modal_add_to_cart');
}
?>

<?php

if ($this->session->userdata('role') == 'cashier') {
    //modal cms
    $this->load->view('pages/customer/modal/modal_customer');
}


if ($this->session->userdata('role') == 'front_officer') {
    //modal add patient/customer
    $this->load->view('pages/front-office/modal/modal_add_patient');
}
?>


