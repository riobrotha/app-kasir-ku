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