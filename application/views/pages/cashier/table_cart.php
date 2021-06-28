<h1 class="text-hers mb-4" style="text-align: right;">Rp&nbsp;<?= number_format($totalCart, 0, ',', '.') ?>,-</h1>

<?php if (count($cart) > 0) : ?>
    <div id="table-cart">


        <table class="table">

            <tbody>

                <?php $i = 1;
                //$subtotal = 0;
                //$disc = 0;
                foreach ($cart as $row) : ?>
                    <?php $subtotal = array_sum($sub_total); ?>
                    <?php $disc = array_sum($disc_total);  ?>
                    <?php $purchase_price = array_sum($purchase_price_total); ?>

                    <tr>

                        <td><i class="fa fa-trash clear-items text-secondary mr-2" data-rowid="<?= $row['rowid']; ?>"></i><span><?= $row['name']; ?>&nbsp;(<?= $row['qty']; ?>x)(<?= number_format($row['option']['price_temp'], 0, ',', '.') ?>)</span></td>
                        <td style="text-align: right;"><span><?= number_format($row['option']['price_temp'] * $row['qty'], 0, ',', '.') ?>,-</span></td>
                    </tr>
                <?php endforeach ?>
                <tr>
                    <td>Subtotal</td>
                    <td style="text-align: right;"><?= number_format($subtotal, 0, ',', '.') ?>,-</td>
                </tr>
                <tr>
                    <td>Discount Total</td>
                    <td style="text-align: right;">-&nbsp;<?= number_format($disc, 0, ',', '.') ?>,-</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td style="text-align: right;"><?= number_format($totalCart, 0, ',', '.') ?>,-</td>
                </tr>

            </tbody>
        </table>
        <input type="hidden" id="tot_disc" value="<?= $disc; ?>">
        <input type="hidden" id="purchase_price" value="<?= $purchase_price; ?>">
        <input type="hidden" id="subtotal_cart" value="<?= $subtotal; ?>">
    </div>
<?php else : ?>
    <div class="text-center">
        <i class="ti-shopping-cart" style="font-size: 100px; color: var(--secondary-color)"></i>
        <p class="mt-2" style="margin-bottom: 200px;">Tambahkan Item Terlebih Dahulu!</p>
    </div>
<?php endif ?>

<!-- <table class="table">
    <tbody>
        <tr>
            <td>Subtotal</td>
            <td class="text-right"><?= number_format($totalCart, 0, ',', '.') ?>,-</td>
        </tr>
        <tr>
            <td>Total</td>
            <td class="text-right"><?= number_format($totalCart, 0, ',', '.') ?>,-</td>
        </tr>
        <tr>
            
        </tr>
    </tbody>

</table> -->