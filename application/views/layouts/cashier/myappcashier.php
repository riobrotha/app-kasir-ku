<script>
    const base_url = 'http://localhost/kasir_online/';
    $(function() {
        $('#table-cart').slimScroll({
            height: '300px',
            axis: 'both'

        });

        $("input[name='qty_modal']").TouchSpin({
            min: 1
        });

        //$('#switch1').prop('checked', false)



        if ($('#dataTableActivity').length) {
            $('#dataTableActivity').DataTable({
                responsive: true
            });
        }

        $('.items').slimScroll({
            height: '630px'
        });

        $('#number_invoice').text(generateInvoice());


        //show modal add to cart
        $(document).on('click', '#btnAddToCart', function() {

            let id = $(this).data('id');
            let qty = 1;
            let price = $(this).data('price');
            let title = $(this).data('title');
            let conv_title = title.split(" ").join("%20");
            let stock = $(this).data('stock');

            $('#modalAddToCart').modal('show');

            $('.title_modal').text(title);
            $('#id_modal').val(id);
            $('#price_modal').val(price);
            $('#title_modal').val(conv_title);
            $('#stock_modal').val(stock);

            //temp price
            $('#price_temp').val(price);





        });

        //add to cart
        $(document).on('click', '#btnModalAddToCart', function() {
            let id = $('#id_modal').val();
            let price = Number($('#price_modal').val());
            let price_temp = Number($('#price_temp').val());
            let title = $('#title_modal').val();
            let stock = $('#stock_modal').val();
            let qty = Number($('#qty_modal').val());
            let result = new Array();
            let potongan = new Array();
            let countDisc = "<?= isset($countDiscount) ? $countDiscount : 0;  ?>";
            let convDisc = Number(countDisc);

            //let hitung = 0;
            if (countDisc != 0) {
                for (var i = 0; i <= convDisc - 1; i++) {
                    let value = $('#switch' + i).prop('checked');
                    if (value == true) {


                        let disc = Number($('#switch' + i).val());

                        if (result[i - 1] != undefined) {
                            potongan[i] = result[i - 1] * disc / 100;
                            result[i] = result[i - 1] - (potongan[i]);
                        } else {
                            potongan[i] = price * disc / 100;
                            result[i] = price - (potongan[i]);
                        }

                        price = result[i];

                        //result[i+1] = result[i];
                        //$('#price_modal').val(result[i]);


                    }

                    //discount[i] = potongan[i-1] + potongan[i+1];

                }
            }


            let discount = potongan.reduce((a, b) => a + b, 0);
            let price_sebelum = price_temp;
            //let discount_sebelum = discount * qty;
            console.log(discount);



            $.ajax({
                method: "POST",
                url: base_url + 'cashier/insert/' + id + '/' + qty + '/' + price + '/' + title + '/' + stock + '/' + discount + '/' + price_sebelum,
                success: function(data) {
                    var data = JSON.parse(data);

                    if (data.statusCode == 200) {
                        loadDataTableCart('cashier/loadDataTableCart');
                        loadTotVal('cashier/loadTotVal');
                        let cekStock = data.sisaStock;
                        $('.button' + id + ' button').data('stock', cekStock);
                        $('.button' + id + ' button').attr('data-stock', cekStock);
                        $('#sisaStock' + id + ' span').text('Stock : ' + cekStock);
                        $('#btnPay').removeAttr('disabled');
                        $('#itemCode').focus();
                        clearFormAddToCart();
                        $('#modalAddToCart').modal('hide');
                    } else if (data.statusCode == 202) {
                        alert(data.msg);
                    }
                }
            });
        });

        //discount

        // for (var i = 0; i <= 1; i++) {
        //     $(document).on('change', '#switch' + i, function() {
        //         let value = $(this).prop('checked');
        //         let price = Number($('#price_modal').val());
        //         let price_temp = Number($('#price_temp').val());
        //         let result;
        //         if (value == true) {
        //             let disc = Number($(this).val());

        //             result = price - (price * disc / 100);
        //             $('#disc_sebelum').val(price * disc / 100);

        //         } else {
        //             let disc = Number($(this).val());
        //             let disc_sebelum = Number($('#disc_sebelum').val());
        //             result = price + disc_sebelum;
        //             $('#disc_sebelum').val("");

        //         }


        //         $('#price_modal').val(result);
        //     });
        // }


        //end add to cart



        //add to cart with itemCode
        $(document).on('keyup', '#itemCode', function() {
            let itemCode = $(this).val();
            let qty = 1;
            if (itemCode.length == 10) {

                $.ajax({
                    method: "POST",
                    url: base_url + 'cashier/insert_by_itemCode/' + itemCode + '/' + qty,
                    success: function(data) {
                        var data = JSON.parse(data);

                        if (data.statusCode == 200) {
                            loadDataTableCart('cashier/loadDataTableCart');
                            loadTotVal('cashier/loadTotVal');
                            $('#itemCode').val("");

                            let cekStock = data.sisaStock;
                            $('.button' + itemCode + ' button').data('stock', cekStock);
                            $('.button' + itemCode + ' button').attr('data-stock', cekStock);
                            $('#sisaStock' + itemCode + ' span').text('Stock : ' + cekStock);
                            $('#btnPay').removeAttr('disabled');
                        } else if (data.statusCode == 202) {
                            alert(data.msg);
                            $('#itemCode').val("");
                        }
                    }
                });


            }
        });
    });

    $(function() {
        //cash payment and set money_change
        $(document).on('keyup', '#cash_payment', function() {
            let bilangan = $(this).val();
            $(this).val(formatRupiah(bilangan));


            let totalValue = $('#totalValueModal').text();
            let totalValueConv = totalValue.split(".").join("");
            let bilanganConv = bilangan.split(".").join("");


            if (bilangan != "") {
                let money_change = Number(bilanganConv) - Number(totalValueConv);
                if (money_change < 0) {
                    $('#money_change').val('-' + formatRupiah(money_change));

                } else {
                    $('#money_change').val(formatRupiah(money_change));

                }
            } else {
                $('#money_change').val("");
            }




        });

        //condition if modal pay show
        $('#modalPay').on('shown.bs.modal', function() {
            //focus on input
            $('#cash_payment').focus();

            let invoice = $('#number_invoice').text();
            let disc = $('#tot_disc').val();
            let subtot = $('#subtotal_cart').val();
            $('#invoice').val(invoice);
            $('#discount_total').val(disc);
            $('#subtotal').val(subtot);

        });


        //add transaction to db
        $(document).on('submit', '#formPay', function(e) {

            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'cashier/pay',
                data: data,
                beforeSend: function() {

                },
                success: function(data) {
                    var data = JSON.parse(data);

                    if (data.statusCode == 200) {
                        $('#modalPay').modal('hide');
                        $('#cash_payment_val').text($('#cash_payment').val());
                        $('#money_change_val').text($('#money_change').val());
                        $('.btnAddToCart').attr('disabled', true);
                        $("#btnPay").attr('disabled', true);
                        $("#btnReset").show();
                        $('#itemCode').attr('readonly', true);
                        $('#formPay')[0].reset();

                    }
                }
            });
        });


        //show detail activity transaction
        $(document).on('click', '#detailTransaction', function(e) {
            e.preventDefault();
            let invoice = $(this).data('invoice');
            $.ajax({
                method: "POST",
                url: base_url + 'activity/detail/' + invoice,
                beforeSend: function() {

                },

                success: function(response) {
                    $('#modal_detail_invoice').html(response);
                    $('#modalDetailInvoice').modal('show');
                }
            });
        });

        //reset transaction
        $(document).on('click', '#btnReset', function() {
            clearCart();
            $(this).hide();
            $('#itemCode').focus();
        });


        //filter item cashier section
        const btnFilterTreatment = document.querySelector('.btnFilterTreatment');
        const space_items = document.querySelector('.space_items');
        btnFilterTreatment.addEventListener('click', function() {
            let id = this.dataset.id;

            $.ajax({
                method: "POST",
                url: base_url + 'cashier/filter/' + id,
                beforeSend: function() {
                    
                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        space_items.innerHTML = data.html;
                        btnFilterTreatment.classList.remove('btn-purple-outline')
                        btnFilterTreatment.classList.add('btn-purple');
                        $('.items').slimScroll({
                            height: '630px'
                        });
                    } else {
                        alert('fail');
                    }
                }
            });
        });



    });


    //function helper
    //load data cart
    function loadDataTableCart(url) {
        $.ajax({
            type: "POST",
            url: base_url + url,
            success: function(response) {
                $('#result-cart').html(response);
                $('#table-cart').slimScroll({
                    height: '300px',


                });
            }
        });
    }

    //load totval
    function loadTotVal(url) {
        $.ajax({
            type: "POST",
            url: base_url + url,
            success: function(response) {
                $('#totalValueModal').text(formatRupiah(response));
                $('#subtotal').val(response);
                $('#total').val(response);

            }
        });
    }


    //format Rupiah
    function formatRupiah(angka, prefix = '') {

        var number_string = angka.toString().replace(/[^,\d]/g, ''),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        //return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        return rupiah;
    }


    //function for generate invoice random based on date
    function generateInvoice() {
        let d = new Date();
        let random = Math.floor(10000 + Math.random() * 90000);

        let year = d.getFullYear();
        let month = d.getMonth() + 1;
        let date = d.getDate();

        let invoice = year.toString() + month.toString() + date.toString() + random.toString();

        return invoice;
    }

    function clearCart() {
        $.ajax({
            method: "POST",
            url: base_url + 'cashier/clear_cart',
            beforeSend: function() {

            },
            success: function(data) {
                var data = JSON.parse(data);

                if (data.statusCode == 200) {
                    loadDataTableCart('cashier/loadDataTableCart');
                    loadTotVal('cashier/loadTotVal');
                    $('#number_invoice').text(generateInvoice());
                    $('.btnAddToCart').removeAttr('disabled');
                    $("#cash_payment_val").text("");
                    $("#money_change_val").text("");
                    $('#itemCode').removeAttr('readonly');

                }
            }
        });
    }

    function clearFormAddToCart() {
        $('#qty_modal').val("1");
    }
</script>