<div class="modal fade bd-example-modal-lg" id="modalAddToCart">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title title_modal"></h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_modal" value="">
                <input type="hidden" id="title_modal" value="">
                <input type="hidden" id="stock_modal" value="">
                <input type="hidden" id="price_temp" value="">
                <input type="hidden" id="price_modal" value="">
                <input type="hidden" id="purchase_price_modal" value="">
                <input type="hidden" id="disc_sebelum" value="">
                <input type="hidden" id="category_modal" value="">
                <div class="form-group">
                    <label for="qty_modal">Quantity</label>
                    <input type="text" class="form-control" id="qty_modal" name="qty_modal" value="1" aria-describedby="emailHelp">
                    <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                </div>

                <!-- <div class="form-group">
                    <label for="exampleInputEmail1">Discount</label>
                    <input type="text" class="form-control" id="exampleInputEmail1" id="discount" placeholder="" aria-describedby="emailHelp">
                    <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                </div> -->


                <label for="" class="mt-2">Discount</label>
                <div class="row">
                    <?php
                    $i = 0;
                    foreach ($discount as $row) : ?>
                        <div class="col-6">
                            <div class="s-sw-title">
                                <span><?= $row->title_discount; ?>&nbsp; <?= $row->value; ?>%</span>
                                <div class="s-swtich">

                                    <input type="checkbox" id="switch<?= $i; ?>" value="<?= $row->value; ?>" />
                                    <label for="switch<?= $i; ?>">Toggle</label>
                                </div>
                            </div>
                        </div>
                    <?php $i++;
                    endforeach ?>
                </div>






            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-rounded btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm btn-rounded btn-hers" id="btnModalAddToCart">Add This Item</button>
            </div>

        </div>
    </div>
</div>