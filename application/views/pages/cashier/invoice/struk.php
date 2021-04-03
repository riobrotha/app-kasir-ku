<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <style>
        th,
        td,
        span {
            font-size: 13px;
        }

        .items {
            border: 1px dashed #000;
            border-left: 0px;
            border-right: 0px;
            width: 80%;

            margin-top: 20px;
            margin-bottom: 10px;
        }

        .items span {
            margin-left: 2px;
        }

        .table-borderless thead th {
            border-bottom: 1px dashed #000;
        }

        .table-borderless tfoot .tftop {
            border-top: 1px dashed #000;
        }

        .table-borderless tfoot .tfbot {
            border-bottom: 1px dashed #000;
        }
    </style>
    <title>Invoice</title>
</head>

<body>
    <div class="row">
        <div class="col-4">
            <div class="text-center">
                <!-- <p style="font-size: 19px; font-weight: 600; margin-bottom: -2px;" class="mt-3">KasirKu</p> -->
                <img src="<?= base_url("assets/images/logo/logo.jpg") ?>" style="width: 150px; filter: grayscale(100%);">
                <p style="font-size: 13px;">Jl. Raya Siteba No. 26, Padang</p>
                <table class="table table-sm table-borderless" style="margin-bottom: 1px;">
                    <tr>
                        <td>Date : <?= date_format(new DateTime($invoice_detail->created_at), 'd/m/Y'); ?></td>
                        <td>Invoice : <?= $invoice; ?></td>
                    </tr>
                    <tr>
                        <td>Time : <?= date_format(new DateTime($invoice_detail->created_at), 'H:i'); ?>&nbsp;WIB</td>
                        <td>Cashier: Admin</td>
                    </tr>
                </table>
                <!-- ----------------------------------------------<br> -->
                <div class="items mx-auto">
                    <table class="table table-sm table-borderless">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Items</th>
                                <th>Qty</th>
                                <th>SubTotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $i = 1; foreach ($transaction as $row) : ?>
                                <tr>
                                    <td>
                                        <?= $i++; ?>
                                    </td>
                                    <td><?= $row->title_product ?></td>
                                    <td><?= $row->qty; ?>x</td>
                                    <td><?= number_format($row->subtotal, 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td class="tftop"></td>
                                <td class="tftop"></td>
                                <td class="tftop">Subtotal :</td>
                                <td class="tftop"><?= $discount->subtotal != "" ? number_format($discount->subtotal, 0, ',', '.') : 0 ?></td>
                            </tr>
                            <tr>
                                <td class="tfbot"></td>
                                <td class="tfbot"></td>
                                <td class="tfbot">Disc :</td>
                                <td class="tfbot"><?= $discount->discount_total != "" ? number_format($discount->discount_total, 0, ',', '.') : 0 ?></td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>Total :</td>
                                <td><?= number_format($invoice_detail->total, 0, ',', '.') ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <p>Thank You For Shopping :)</p>

            </div>

        </div>

    </div>

















    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    -->
</body>

</html>