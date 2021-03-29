<div class="main-content-inner">
    <div class="container-fluid">
        <div class="row">


            <!-- Statistics area start -->
            <div class="col-lg-8 col-md-6 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Treatment & Product Items</h4>
                        <div class="float-left mb-4">
                            <button class="btn btn-rounded btn-xs btn-purple-outline btnFilterTreatment" data-id="102001">Treatment</button>
                            <button class="btn btn-rounded btn-xs btn-purple-outline btnFilterProduct ml-3" data-id="102002">Product</button>
                            <button class="btn btn-rounded btn-xs btn-purple-outline btnFilterProduct ml-3">Back to Default</button>
                        </div>
                        <div class="float-right">
                            <input class="form-control search-items" id="searchItems" placeholder="Search...">
                        </div>
                        <!-- <div id="user-statistics"></div> -->
                        <div class="space_items">
                            <div class="row items">

                                <?php foreach ($product as $row) : ?>
                                    <div class="col-xl-3 col-md-3">
                                        <div class="card custom mb-3">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <img src="<?= !isset($row->image) || $row->image == "" ? base_url("images/product/default-product.png") : base_url("images/product/$row->image"); ?>" style="width: 200px;" alt="">
                                                    <h5 class="card-title mt-3" style="font-size: 16px;"><?= $row->title; ?></h5>
                                                    <p id="sisaStock<?= $row->id; ?>"><span>Stock : <?= $this->session->userdata('stock' . $row->id); ?></span></p>
                                                    <p class="card-text">Rp&nbsp;<?= number_format($row->price, 0, ',', '.') ?>,-</p>
                                                    <div class="button<?= $row->id; ?>">
                                                        <button class="btn btn-rounded btn-xs btn-purple btnAddToCart" id="btnAddToCart" style="margin-top: -15px;" data-id="<?= $row->id; ?>" data-price="<?= $row->price; ?>" data-title="<?= $row->title; ?>" data-stock="<?= $this->session->userdata('stock' . $row->id); ?>"><i class="fa fa-cart-plus fa-lg mr-2"></i>Add to Cart</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach ?>





                            </div>
                        </div>



                    </div>
                </div>
            </div>
            <!-- Statistics area end -->
            <!-- Advertising area start -->
            <div class="col-lg-4 col-md-6 mt-5">
                <div class="card">
                    <div class="card-body">

                        <div class="d-flex">
                        </div>
                        <h4 class="header-title">Detail Transactions (<span id="number_invoice"></span>)</h4>
                        <div class="text-right mb-3">
                            <button type="button" class="btn btn-sm btn-rounded btn-primary" id="btnReset"><i class="fa fa-plus mr-2"></i>New Transaction</button>
                        </div>


                        <div class="form-group row">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="itemCode" placeholder="Item Code" style="border-radius: 33px;" autofocus>
                            </div>

                        </div>
                        <div id="result-cart">
                            <h1 class="text-purple mb-4" style="text-align: right;">Rp&nbsp;<?= number_format($totalCart, 0, ',', '.') ?>,-</h1>

                            <?php if (count($cart) > 0) : ?>
                                <div id="table-cart">
                                    <table class="table">

                                        <tbody>

                                            <?php $i = 1;
                                            foreach ($cart as $row) : ?>

                                                <tr>

                                                    <td><?= $row['name']; ?>&nbsp;(<?= $row['qty']; ?>x)</td>

                                                    <td style="text-align: right;"><?= number_format($row['subtotal'], 0, ',', '.') ?>,-</td>
                                                </tr>


                                            <?php endforeach ?>
                                            <tr>
                                                <td>Subtotal</td>
                                                <td style="text-align: right;"></td>
                                            </tr>
                                            <tr>
                                                <td>Discount Total</td>
                                                <td style="text-align: right;"></td>
                                            </tr>
                                            <tr>
                                                <td>Total</td>
                                                <td style="text-align: right;"></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            <?php else : ?>
                                <div class="text-center">
                                    <i class="ti-alert" style="font-size: 100px;"></i>
                                    <p class="mt-2" style="margin-bottom: 200px;">Tambahkan Item Terlebih Dahulu!</p>
                                </div>
                            <?php endif ?>



                        </div>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Cash Payment</td>
                                    <td class="text-right"><span id="cash_payment_val"></span></td>
                                </tr>
                                <tr>
                                    <td>Money Change</td>
                                    <td class="text-right"><span id="money_change_val"></span></td>
                                </tr>
                                <tr>

                                </tr>
                            </tbody>

                        </table>
                        <?php if (count($cart) > 0) : ?>
                            <div class="text-center">
                                <button class="btn btn-rounded btn-purple mt-3" id="btnPay" style="width: 100%;" data-toggle="modal" data-target="#modalPay">Charge</button>
                            </div>
                        <?php else : ?>
                            <div class="text-center">
                                <button class="btn btn-rounded btn-purple mt-3" id="btnPay" style="width: 100%;" data-toggle="modal" data-target="#modalPay" disabled>Charge</button>
                            </div>
                        <?php endif ?>




                        <!-- <div class="mx-auto">
                            <div class="mt-5">
                                <ul id="keyboard">
                                    <li class="letter">7</li>
                                    <li class="letter">8</li>
                                    <li class="letter">9</li>
                                    <li class="letter clearl">4</li>
                                    <li class="letter">5</li>
                                    <li class="letter">6</li>

                                    <li class="letter clearl">1</li>
                                    <li class="letter ">2</li>
                                    <li class="letter">3</li>
                                    <li class="letter">0</li>
                                    <li class="switch">abc</li>
                                    <li class="return">retur</li>
                                    <li class="delete lastitem"></li>
                                </ul>
                            </div>
                        </div> -->


                    </div>
                </div>
            </div>
            <!-- Advertising area end -->




        </div>
    </div>
</div>