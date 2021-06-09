<script>
    const base_url = $('body').data('url');
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
        $('#struk').attr('src', base_url + 'cashier/struk/' + $('#number_invoice').text());

        //show modal add to cart
        $(document).on('click', '#btnAddToCart', function() {

            let id = $(this).data('id');
            let id_category = $(this).data('category');
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
            $('#category_modal').val(id_category);

            //temp price
            $('#price_temp').val(price);





        });

        //add to cart
        $(document).on('click', '#btnModalAddToCart', function() {
            let id = $('#id_modal').val();
            let id_category = $('#category_modal').val();
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
                url: base_url + 'cashier/insert/' + id + '/' + id_category + '/' + qty + '/' + price + '/' + title + '/' + stock + '/' + discount + '/' + price_sebelum,
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
        $(document).on('change', '#itemCode', function() {
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
        let kali = 0;
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
            let id_customer = $('.customer-id-space').val();
            $('#invoice').val(invoice);
            $('#discount_total').val(disc);
            $('#subtotal').val(subtot);
            $('#id_customer').val(id_customer);

        });


        //add transaction to db
        $(document).on('submit', '#formPay', function(e) {

            e.preventDefault();
            let data = $(this).serialize();
            let number_invoice = $('#invoice').val();
            let id_queue = $('#id_queue_val').val();
            $.ajax({
                method: "POST",
                url: id_queue == '' ? base_url + 'cashier/pay' : base_url + 'cashier/pay/' + id_queue,
                data: data,
                beforeSend: function() {

                },
                success: function(data) {
                    var data = JSON.parse(data);

                    if (data.statusCode == 200) {
                        $('#modalPay').modal('hide');
                        //$('#struk').attr('src', '<?= base_url("cashier/struk/") ?>' + number_invoice);
                        $('#cash_payment_val').text($('#cash_payment').val());
                        $('#money_change_val').text($('#money_change').val());
                        $('.btnAddToCart').attr('disabled', true);
                        $("#btnPay").attr('disabled', true);
                        $("#btnReset").show();
                        $('#itemCode').attr('readonly', true);
                        $('#formPay')[0].reset();
                        cetakStruk();
                        $('.status_pay').val(1);


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
        const treatment = '102001';
        const product = '102002';
        const btnFilterTreatment = document.querySelector('.btnFilterTreatment');
        const btnFilterProduct = document.querySelector('.btnFilterProduct');
        const btnBackToDefault = document.querySelector('.btnBackToDefault');
        const inputSearchItems = document.getElementById('searchItems');
        const space_items = document.querySelector('.space_items');
        const items = document.querySelector('.items');


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
                        items.innerHTML = data.html;


                        btnFilterTreatment.classList.remove('btn-hers-outline');
                        btnFilterTreatment.classList.add('btn-hers');

                        btnFilterProduct.classList.remove('btn-hers');
                        btnFilterProduct.classList.add('btn-hers-outline');


                        $('.items').slimScroll({
                            height: '630px'
                        });

                        kali = 0;
                        $('#btnLoadMoreData').attr('data-category', treatment);
                        let countItem = $('.hitung-item').length;

                        if (countItem < 8) {
                            $('#btnLoadMoreData').remove();
                        }


                    } else {
                        alert('fail');
                    }
                }
            });
        });

        btnFilterProduct.addEventListener('click', function() {
            let id = this.dataset.id;

            $.ajax({
                method: "POST",
                url: base_url + 'cashier/filter/' + id,
                beforeSend: function() {

                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        items.innerHTML = data.html;



                        btnFilterProduct.classList.remove('btn-hers-outline');
                        btnFilterProduct.classList.add('btn-hers');

                        btnFilterTreatment.classList.remove('btn-hers');
                        btnFilterTreatment.classList.add('btn-hers-outline');

                        $('.items').slimScroll({
                            height: '630px'
                        });

                        kali = 0;

                        $('#btnLoadMoreData').attr('data-category', product);

                        let countItem = $('.hitung-item').length;

                        if (countItem < 8) {
                            $('#btnLoadMoreData').remove();
                        }
                    } else {
                        alert('fail');
                    }
                }
            });
        });

        btnBackToDefault.addEventListener('click', function() {
            $.ajax({
                method: "POST",
                url: base_url + 'cashier/filter',
                beforeSend: function() {

                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        items.innerHTML = data.html;

                        btnFilterProduct.classList.remove('btn-hers');
                        btnFilterProduct.classList.add('btn-hers-outline');

                        btnFilterTreatment.classList.remove('btn-hers');
                        btnFilterTreatment.classList.add('btn-hers-outline');

                        $('.items').slimScroll({
                            height: '630px'
                        });

                        kali = 0;
                    }
                }
            });
        });

        inputSearchItems.addEventListener('keyup', function() {
            let searchVal = this.value;
            let searchValTemp = searchVal.split(' ').join('%20');

            if (searchValTemp != '') {


                $.ajax({
                    method: "POST",
                    url: base_url + 'cashier/search/' + searchValTemp,
                    beforeSend: function() {

                    },
                    success: function(response) {
                        let data = JSON.parse(response);

                        if (data.statusCode == 200) {
                            items.innerHTML = data.html;

                            $('.items').slimScroll({
                                height: '630px'
                            });

                            kali = 0;


                            let countItem = $('.hitung-item').length;

                            if (countItem < 8) {
                                $('#btnLoadMoreData').remove();
                            }

                            btnFilterProduct.classList.remove('btn-hers');
                            btnFilterProduct.classList.add('btn-hers-outline');

                            btnFilterTreatment.classList.remove('btn-hers');
                            btnFilterTreatment.classList.add('btn-hers-outline');
                        }
                    }
                });
            } else {
                $.ajax({
                    method: "POST",
                    url: base_url + 'cashier/filter',
                    beforeSend: function() {

                    },
                    success: function(response) {
                        let data = JSON.parse(response);

                        if (data.statusCode == 200) {
                            items.innerHTML = data.html;

                            $('.items').slimScroll({
                                height: '630px'
                            });

                            kali = 0;

                            btnFilterProduct.classList.remove('btn-hers');
                            btnFilterProduct.classList.add('btn-hers-outline');

                            btnFilterTreatment.classList.remove('btn-hers');
                            btnFilterTreatment.classList.add('btn-hers-outline');
                        }
                    }
                });
            }
        });


        //load more data
        let perPage = 8 / 2;
        let total_pages = Number('<?php isset($total_product) ? print $total_product : "" ?>');

        $(document).on('click', '#btnLoadMoreData', function() {

            let page = Number($('#btnLoadMoreData').data('page'));
            let jenis_kategori = $('#btnLoadMoreData').data('category');
            let search_val = $('#searchItems').val();
            let searchValTemp = search_val.split(' ').join('%20');
            let status_pay = Number($('.status_pay').val());
            //console.log(search_val);
            if (page < total_pages - 1) {

                kali = kali + 2;
                page = page * kali;

                if (jenis_kategori == undefined) {
                    if (search_val != '') {
                        loadMoreData(page, status_pay, '', searchValTemp);
                    } else {
                        loadMoreData(page, status_pay);


                    }
                } else {
                    loadMoreData(page, status_pay, jenis_kategori);

                }




            }

            $(this).remove();
            console.log(kali);





        });

        // $('.items').scroll(function() {

        //     if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {


        //         if (perPage < total_pages - 1) {
        //             loadMoreData(perPage);
        //             perPage = perPage * 2;
        //         }

        //         console.log($(this).scrollTop() + $(this).innerHeight());
        //         console.log($(this)[0].scrollHeight);



        //     }
        // });

        //cms
        //data pelanggan

        //condition if modal customer show
        $('#modalCustomer').on('shown.bs.modal', function() {
            loadDataCustomer();


        });

        $('.collapse').on('shown.bs.collapse', function() {
            $('#btnAddCustomer').text('Cancel');
        });

        $('.collapse').on('hidden.bs.collapse', function() {
            $('#btnAddCustomer').html('<i class="fa fa-plus mr-2"></i>New Customer');

        });

        //add data customer
        $(document).on('submit', '#formAddCustomer', function(e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                url: base_url + 'customer/insert',
                method: "POST",
                data: data,
                beforeSend: function() {

                },
                success: function(response) {
                    let data = JSON.parse(response);
                    if (data.statusCode == 200) {
                        loadDataCustomer();
                        $('#formAddCustomer')[0].reset();
                    } else if (data.statusCode == 201) {
                        // do something
                    } else {
                        if (data.error == true) {
                            if (data.name_error != '') {
                                $('#name_error').html(data.name_error);

                            } else {
                                $('#name_error').html('');
                            }

                            if (data.phone_error != '') {
                                $('#phone_error').html(data.phone_error);
                            } else {
                                $('#phone_error').html('');
                            }
                        }
                    }

                }
            });
        });

        //show form edit
        $(document).on('click', '#btnEditCustomer', function() {
            let id = $(this).data('id');

            if ($('.data-space' + id).is(':hidden')) {
                $('.edit-space' + id).hide();
                $('#btnSubmitEditCustomer' + id).hide();
                $('.data-space' + id).show();
                $('.edit-btn-space' + id + ' #btnEditCustomer').removeClass('fa-chevron-down').addClass('fa-chevron-right');
            } else {
                $('.edit-space' + id).show();
                $('#btnSubmitEditCustomer' + id).show();
                $('.data-space' + id).hide();
                $('.edit-btn-space' + id + ' #btnEditCustomer').removeClass('fa-chevron-right').addClass('fa-chevron-down');

            }



        });



        //update data customer
        $(document).on('click', '.btnSubmitEditCustomer', function() {
            let id = $(this).data('id');
            let name = $('#nameEditCustomer' + id).val();
            let phone = $('#phoneEditCustomer' + id).val();
            let email = $('#emailEditCustomer' + id).val();

            $.ajax({
                url: base_url + 'customer/update/' + id + '/' + name + '/' + phone + '/' + email,
                method: "POST",
                beforeSend: function() {

                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        //alert('berhasil');
                        $('.data-space' + id + ' .customer-name').text(name);
                        $('.data-space' + id + ' .customer-phone').text(phone);
                        $('.data-space' + id + ' .customer-email').text(email);
                        $('.edit-space' + id).hide();
                        $('#btnSubmitEditCustomer' + id).hide();
                        $('.data-space' + id).show();
                        $('.edit-btn-space' + id + ' #btnEditCustomer').removeClass('fa-chevron-down').addClass('fa-chevron-right');
                    }
                }
            });
        });

        jQuery(document).on('click', '#rowCustomer', function() {
            let id = jQuery(this).data('id');
            let name = jQuery(this).data('name');
            let id_queue = jQuery(this).data('queue');


            jQuery.ajax({
                url: base_url + 'customer/add_treatment/' + id_queue,
                method: "POST",
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        $('.customer-name-space').text(name);
                        $('.customer-id-space').val(id);


                        loadDataTableCart('cashier/loadDataTableCart');
                        loadTotVal('cashier/loadTotVal');
                        let cekStock = data.sisaStock;
                        $('.button' + id + ' button').data('stock', cekStock);
                        $('.button' + id + ' button').attr('data-stock', cekStock);
                        $('#sisaStock' + id + ' span').text('Stock : ' + cekStock);
                        $('#btnPay').removeAttr('disabled');
                        $('#itemCode').focus();
                        clearFormAddToCart();
                        $('#loading').hide();
                        $('#modalCustomer').modal('hide');
                        $('#id_queue_val').val(id_queue);
                    }
                }
            });
        });
    });



    //function helper
    function updateDataCustomer(id) {
        $(document).on('submit', '#formEditCustomer' + id, function(e) {
            e.preventDefault();

            alert('ok');
        });
    }

    //load data customer
    function loadDataCustomer() {
        $.ajax({
            url: base_url + 'customer',
            method: "POST",
            beforeSend: function() {

            },
            success: function(response) {
                let data = JSON.parse(response);
                $('.customers-data').html(data.html);
                $('#dataTable3').DataTable({
                    responsive: true,
                    "order": [
                        [3, "asc"]
                    ], //or asc 
                    "columnDefs": [{
                        "targets": 3,
                        "type": "date-eu"
                    }],
                });

                $('.sorting_desc').hide();
                $('.sorting_1').hide();
                $('.sorting').hide();

                if (data.countCustomer > 1) {
                    $('.titleModalCustomer').text(data.countCustomer + ' Customers');
                } else if (data.countCustomer == 1) {
                    $('.titleModalCustomer').text(data.countCustomer + ' Customer');
                } else {
                    $('.titleModalCustomer').text('No Customers');
                }

            }
        });
    }

    function cetakStruk() {
        $('.frame_space').html('<iframe src="' + base_url + '/cashier/struk/' + $('#number_invoice').text() + '"' +
            'id="struk" name="struk" frameborder="0" style="display: none;"></iframe>');
        window.frames['struk'].print();
    }

    function loadFrames() {
        document.getElementById('struk').contentDocument.location.reload(true);
    }
    //load more data
    function loadMoreData(page, status_pay, category = '', search = '') {
        let link_url = base_url + 'cashier/loadMoreData?page=' + page;

        if (category != '') {
            link_url = base_url + 'cashier/loadMoreData?page=' + page + '&category=' + category;
        }

        if (search != '') {
            link_url = base_url + 'cashier/loadMoreData?page=' + page + '&search=' + search;
        }
        $.ajax({
            url: link_url,
            type: "GET",
            beforeSend: function() {

            },

            success: function(response) {
                let data = JSON.parse(response);

                if (data.statusCode == 200) {
                    $('.items').append(data.html);

                    $('.items').slimScroll({
                        height: '630px'
                    });

                    let countItem = $('.hitung-item').length;


                    if (countItem >= data.total_product) {
                        $('#btnLoadMoreData').hide();
                    }

                    if (status_pay == 1) {
                        $('.btnAddToCart').attr('disabled', true);
                    }
                }
            }
        });
    }


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

        let invoice = 'TR' + year.toString() + month.toString() + date.toString() + random.toString();

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
                    $('#struk').attr('src', base_url + 'cashier/struk/' + $('#number_invoice').text());
                    $('.customer-name-space').text('New Customer');
                    $('.customer-id-space').val("");
                    $('.status_pay').val(0);

                }
            }
        });
    }

    function clearFormAddToCart() {
        $('#qty_modal').val("1");
    }
</script>