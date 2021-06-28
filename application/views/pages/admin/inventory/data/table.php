<div class="data-tables datatable-dark mt-4">
    <table id="dataTableInventory" class="text-center table table-hover">
        <thead class="text-capitalize">
            <tr>
                <th>#</th>
                <th style="width: 5%;">Title</th>
                <th>Category</th>
                <th>Stock</th>
                <th>Price</th>
                <th>Store</th>
                <th>Created At</th>
                <th>QR Code</th>
                <th>Barcode</th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            <?php $i = 1;
            foreach ($content as $row) : ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= $row->title; ?></td>
                    <td><?= $row->category_title; ?></td>
                    <td><?= $row->stock; ?></td>
                    <td>Rp&nbsp;<?= number_format($row->price, 0, ',', '.') ?>,-</td>
                    <td><?= $row->name; ?></td>
                    <td><?= date_format(new DateTime($row->created_at), 'd/m/Y H:i'); ?></td>
                    <td>
                        <img src="<?= base_url("code/qrcode/$row->id"); ?>" alt="qr-code" class="img-fluid">
                    </td>
                    <td>
                        <img src="<?= base_url("code/barcode/$row->id"); ?>" alt="bar-code" class="img-fluid">
                    </td>
                    <td>
                        <button class="btn btn-rounded btn-info" id="btnEditInventory" data-id="<?= $row->id; ?>" data-toggle="tooltip" data-placement="left" title="Edit"><i class="fa fa-edit fa-2x"></i></button>
                        <button class="btn btn-rounded btn-danger ml-2" id="btnDeleteInventory" data-id="<?= $row->id; ?>" data-toggle="tooltip" data-placement="right" title="Delete"><i class="fa fa-trash fa-2x"></i></button>
                    </td>


                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>