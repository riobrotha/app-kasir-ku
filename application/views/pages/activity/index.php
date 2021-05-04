<div class="main-content-inner">
    <div class="container-fluid">
        <div class="row">


            <!-- Statistics area start -->
            <div class="col-lg-12 mt-5">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Activity for Today</h4>
                        <!-- <div id="user-statistics"></div> -->
                        <div class="data-tables datatable-dark mt-4">
                            <table id="dataTableActivity" class="text-center table table-hover">
                                <thead class="text-capitalize">
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice</th>
                                        <th>Cash Payment</th>
                                        <th>Money Change</th>
                                        <th>Total</th>
                                        <th>Created At</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($transaction as $row) : ?>
                                        <tr>
                                            <td><?= $i; ?></td>
                                            <td><strong><a href="#" class="text-hers" id="detailTransaction" data-invoice="<?= $row->invoice; ?>"><?= $row->invoice; ?></a></strong></td>
                                            <td>Rp&nbsp;<?= number_format($row->cash_payment, 0, ',', '.') ?>,-</td>
                                            <td>Rp&nbsp;<?= number_format($row->money_change, 0, ',', '.') ?>,-</td>
                                            <td>Rp&nbsp;<?= number_format($row->total, 0, ',', '.') ?>,-</td>
                                            <td><?= date_format(new DateTime($row->created_at), 'd/m/Y   H:i '); ?>&nbsp;WIB</td>
                                        </tr>
                                    <?php $i++;
                                    endforeach ?>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Total:</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Rp&nbsp;<?= number_format(
                                                        array_sum(array_column($transaction, 'total')),
                                                        0,
                                                        ',',
                                                        '.'
                                                    );  ?>,-</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>





                    </div>


                </div>
            </div>
        </div>
        <!-- Statistics area end -->





    </div>
</div>
</div>