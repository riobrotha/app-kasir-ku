<?php
$total = array_sum(array_column($sumTotal, 'total'));
$subtotal = array_sum(array_column($sumTotal, 'subtotal'));
//$netSales = $total - $subtotal;

?>

<div class="main-content-inner">
    <!-- sales report area start -->
    <div class="sales-report-area mt-5 mb-5">
        <div class="row">
            <div class="col-md-3">
                <div class="single-report mb-xs-30">
                    <div class="s-report-inner pr--20 pt--30 mb-3">
                        <div class="icon"><strong style="margin-left: 45px !important;">Rp</strong></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0 mt-1">Gross Sales</h4>
                            <input type="text" class="form-control" id="yearDashboard" style="width: 80px; height: 25px; border-top: 0px; border-left: 0px; border-right: 0px; border-radius: 0px; text-align: center;" value="<?= date('Y'); ?>">
                            <!-- <select class="custome-select border-0 pr-3" name="month" id="month_chart">
                                <?php for ($i = 2000; $i <= 2022; $i++) : ?>
                                    <option value="<?= $i; ?>"><?= $i; ?></option>
                                <?php endfor ?>
                            </select> -->
                        </div>
                        <div class="text-center mb-3 loadDash" style="margin-right: 60px;">
                            <img src="<?= base_url("assets/images/load/load.svg"); ?>" style="width: 100px;" alt="load-animate">
                            <h6 class="mt-2">Please Wait...</h6>
                        </div>

                        <div class="d-flex justify-content-between pb-2">

                            <div class="spaceSalesTotChart">
                                <h2 class="salesTotalChart">IDR&nbsp;<?= number_format($subtotal, 0, ',', '.'); ?></h2>


                                <!-- <?php
                                $arr = array();
                                foreach (getMonth() as $key => $value) {
                                    array_push($arr, array_sum(array_column($sumTotal, 'total')));
                                }

                                $totalSales = array_sum($arr);
                                $avg = $totalSales / count($arr);

                                ?> -->
                                <!-- <span style="font-size: 14px;">Avg : IDR&nbsp;<?= number_format($avg, 0, ',', '.'); ?></span> -->
                            </div>
                        </div>
                    </div>

                    <canvas id="coin_sales1" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-3">
                <div class="single-report mb-xs-30">
                    <div class="s-report-inner pr--20 pt--30 mb-3">
                        <div class="icon"><strong style="margin-left: 45px !important;">Rp</strong></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Net Sales</h4>
                            <!-- <input type="text" class="form-control" id="yearDashboard" style="width: 80px; height: 25px; border-top: 0px; border-left: 0px; border-right: 0px; border-radius: 0px; text-align: center;" value="<?= date('Y'); ?>"> -->

                        </div>
                        <!-- <div class="text-center mb-3 loadDash" style="margin-right: 60px;">
                            <img src="<?= base_url("assets/images/load/load.svg"); ?>" style="width: 100px;" alt="load-animate">
                            <h6 class="mt-2">Please Wait...</h6>
                        </div> -->

                        <div class="d-flex justify-content-between pb-2">

                            <div class="spaceNetSalesTotChart">
                                <h2 class="netSalesTotalChart">IDR&nbsp;<?= number_format($total, 0, ',', '.'); ?></h2>
                            </div>
                        </div>
                    </div>

                    <canvas id="coin_sales2" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-3">
                <div class="single-report">
                    <div class="s-report-inner pr--20 pt--30 mb-3">
                        <div class="icon"><i class="fa fa-cubes" style="margin-left: 45px;"></i></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Incoming Items</h4>
                            <p>24 H</p>
                        </div>
                        <div class="d-flex justify-content-between pb-2">
                            <div class="spaceProductIn">
                                <h2><?= array_sum(array_column($product_in_total, 'stock_in')); ?> <?= array_sum(array_column($product_in_total, 'stock_in')) > 1 ? 'Items' : 'Item' ?></h2>
                                <span>&nbsp;</span>
                            </div>

                        </div>
                    </div>
                    <canvas id="product_in_chart" height="100"></canvas>
                </div>
            </div>
            <div class="col-md-3">
                <div class="single-report mb-xs-30">
                    <div class="s-report-inner pr--20 pt--30 mb-3">
                        <div class="icon"><i class="fa fa-tags" style="margin-left: 49px;"></i></div>
                        <div class="s-report-title d-flex justify-content-between">
                            <h4 class="header-title mb-0">Items Sales</h4>
                            <p>24 H</p>
                        </div>
                        <div class="d-flex justify-content-between pb-2">
                            <div class="spaceItemSales">
                                <h2><?= $items_sales_total->total; ?> <?= $items_sales_total->total > 1 ? 'Items' : 'Item' ?></h2>
                                <span>&nbsp;</span>
                            </div>

                        </div>
                    </div>
                    <canvas id="items_sales_chart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>