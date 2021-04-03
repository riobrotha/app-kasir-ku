<div class="modal fade bd-example-modal-lg" id="modalPay">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pay This Invoice (Rp&nbsp;<span id="totalValueModal"><?= number_format($totalCart, 0, ',', '.') ?></span>,-)</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">

                <form action="#" method="POST" id="formPay">
                    <input type="hidden" name="invoice" id="invoice" value="">
                    <input type="hidden" name="discount_total" id="discount_total" value="">
                    <input type="hidden" name="subtotal" id="subtotal" value="">
                    <input type="hidden" name="total" id="total" value="<?= $totalCart; ?>">

                    <div class="form-group">
                        <label for="title_category">Cash Payment</label>
                        <input type="text" class="form-control" name="cash_payment" id="cash_payment" aria-describedby="cashPaymeny" placeholder="Cash Amount" autofocus>
                        <span id="cash_payment_error"></span>
                    </div>

                    <div class="form-group">
                        <label for="title_category">Money Change</label>
                        <input type="text" class="form-control" name="money_change" id="money_change" aria-describedby="moneyChange" placeholder="Money Change" readonly>
                        
                    </div>




            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-rounded btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-sm btn-rounded btn-purple btn-pay">Pay</button>
            </div>
            </form>
        </div>
    </div>
</div>