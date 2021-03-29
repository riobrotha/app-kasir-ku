<?php $this->load->view('layouts/_alert'); ?>
<div class="data-tables datatable-dark mt-4">
    <table id="dataTableReportTrackingProduct" class="text-center table table-hover">
        <thead class="text-capitalize">
            <tr>
                <th>#</th>
                <th>Title of Product</th>
                <th>Stock In</th>
                <th>Current Stock</th>
                <th>Stock Out</th>

            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($content as $row) : ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $row->title; ?></td>
                    <td><?= $row->stock_in; ?></td>
                    <td><?= $row->stock; ?></td>
                    <td><?= $row->stock_out == "" ? "-" : $row->stock_out; ?></td>




                </tr>
            <?php endforeach ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Total:</th>
                <th></th>
                <th><?= array_sum(array_column($content, 'stock_in')); ?></th>
                <th><?= array_sum(array_column($content, 'stock')); ?></th>
                <th><?= array_sum(array_column($content, 'stock_out')); ?></th>
                
            </tr>
        </tfoot>
    </table>

</div>