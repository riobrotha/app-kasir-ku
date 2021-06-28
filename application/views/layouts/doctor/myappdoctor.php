<script>
    const base_url = $('body').data('url');

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = false;

    var pusher = new Pusher('cc14b125ee722dc1a2ea', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('my-channel');
    var id_store = '<?php echo $this->session->userdata('id_store') ?>';
    channel.bind('my-event', function(data) {
        if (id_store == data.id_store_sess) {
            updateDataQueue();
            VanillaToasts.create({
                title: 'Success!',
                text: data.msg,
                type: 'success',
                positionClass: 'topRight',
                timeout: 2000
            });
        }

    });

    $(function() {

        //load data queue
        updateDataQueue();

        //load data queue progress
        loadDataQueueProgress();

        //cek if page reloaded
        if (performance.navigation.type == 1) {
            destroyAllProduct();
        }


        $(document).on('show.bs.modal', '.modal', function() {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        });

        //focus search select2
        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });

        $('#dataTablePatientsMedicalRecordsHistory').DataTable({
            responsive: true,
        });

        $('#selectPatientsMedicalRecords').select2({
            theme: 'bootstrap4',
            width: '100%',
            allowClear: true,
            placeholder: "Choose the option",

        });

        //show modal product
        $('#modalProduct').on('shown.bs.modal', function() {
            //alert('ok');

            $.ajax({
                method: "GET",
                url: base_url + 'doctor/medicalrecord/showProduct/',
                success: function(response) {

                    $('.products-data').html(response);

                    $('.items').slimScroll({
                        height: '640px'
                    });

                    $(".product-qty").TouchSpin({
                        min: 1
                    });


                    $('.bootstrap-touchspin-down').removeClass('btn-primary').addClass('btn-xs btn-hers');
                    $('.bootstrap-touchspin-up').removeClass('btn-primary').addClass('btn-xs btn-hers');
                }
            });
        });



        //condition if modalProduct has been hidden
        $('#modalAddMedicalRecord').on('hidden.bs.modal', function() {
            destroyAllProduct();
        });


        $(document).on('hidden.bs.modal', '#modal-edit-medical-records', function() {
            destroyAllProduct();
            id_products = [];
        });

        $('#modalAddMedicalRecord').on('shown.bs.modal', function() {
            loadProduct();

        });

        //search the product
        $(document).on('keyup', '#searchProduct', function() {
            var search_key = $(this).val();
            var search_temp = search_key.split(' ').join('%20');
            var page_url = base_url + 'doctor/medicalrecord/searchProduct/' + search_temp;
            if (search_temp == '') {
                //loadProduct();
                $.ajax({
                    method: "GET",
                    url: base_url + 'doctor/medicalrecord/showProduct/',
                    success: function(response) {

                        $('.products-data').html(response);

                        $('.items').slimScroll({
                            height: '640px'
                        });


                        $(".product-qty").TouchSpin({
                            min: 1
                        });


                        $('.bootstrap-touchspin-down').removeClass('btn-primary').addClass('btn-xs btn-hers');
                        $('.bootstrap-touchspin-up').removeClass('btn-primary').addClass('btn-xs btn-hers');
                    }
                });
            } else {
                //loadProduct(page_url)
                $.ajax({
                    method: "GET",
                    url: page_url,
                    beforeSend: function() {
                        $('#loading').show();
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        if (data.statusCode == 200) {
                            $('.products-data').html(data.html);
                            let countItem = $('.hitung-item').length;
                            if (countItem == data.total_product) {
                                $('#btnLoadMoreData').remove();

                            }

                        }


                        $('.items').slimScroll({
                            height: '640px'
                        });

                        $(".product-qty").TouchSpin({
                            min: 1
                        });


                        $('.bootstrap-touchspin-down').removeClass('btn-primary').addClass('btn-xs btn-hers');
                        $('.bootstrap-touchspin-up').removeClass('btn-primary').addClass('btn-xs btn-hers');
                        $('#loading').hide();
                    }
                });
            }
        });

        //select the product
        var id_products = [];
        $(document).on('click', '.product', function() {
            var id = $(this).data('id');
            if ($(this).hasClass('selected')) {

                $(this).removeClass('selected');

                //get index
                let index = id_products.map(function(item) {
                    return item.id_product;
                }).indexOf(id);

                if (index > -1) {
                    id_products.splice(index, 1);
                }
                $.ajax({
                    method: "POST",
                    url: base_url + 'doctor/medicalrecord/destroyProduct/' + index,
                    beforeSend: function() {

                    },
                    success: function(response) {


                        console.log(JSON.stringify(id_products));
                    }
                });
            } else {
                $(this).addClass('selected');
                $('#qty_product_' + id).addClass('select_qty');
                //$('.selected').each(function(i) {

                var products_obj = {
                    'id_product': id,
                    'qty': Number($('#qty_product_' + id).val())
                };
                id_products.push(products_obj);


                //});
                console.log(JSON.stringify(id_products));

                $.ajax({
                    method: "POST",
                    url: base_url + 'doctor/medicalrecord/selectProduct',
                    data: {
                        products: JSON.stringify(id_products.sort())
                    },
                    success: function(data) {
                        //console.log('ok');
                    }
                });

            }

        });

        $(document).on('change', '.product-qty', function() {
            var qty_val = Number($(this).val());
            var id_product = $(this).data('id');

            if ($(this).hasClass('select_qty')) {
                for (var i = 0; i < id_products.length; i++) {
                    if (id_product === id_products[i].id_product) {
                        id_products[i].qty = qty_val;
                    }
                }

            } else {
                $('#product_' + id_product).addClass('selected');
                $('#qty_product_' + id_product).addClass('select_qty');

                var data_obj = {
                    'id_product': id_product,
                    'qty': qty_val
                }

                id_products.push(data_obj);


            }
            $.ajax({
                method: "POST",
                url: base_url + 'doctor/medicalrecord/selectProduct',
                data: {
                    products: JSON.stringify(id_products.sort())
                },
                success: function(data) {
                    //console.log('ok');
                }
            });
            console.log(id_products);
        });

        //add product
        $(document).on('click', '#btnAddProduct', function() {
            var products = [];
            var qty_products = [];
            $('.selected').each(function(i) {
                //console.log(i+ ": " + $(this).data('id'));
                products.push($(this).data('id'));
            });

            $('.select_qty').each(function(i) {
                qty_products.push($(this).val());
            });

            // console.log(JSON.stringify(products));
            // console.log(JSON.stringify(qty_products));

            $.ajax({
                method: "POST",
                url: base_url + 'doctor/medicalrecord/storeProduct',
                data: {
                    products: JSON.stringify(products),
                    qty_products: JSON.stringify(qty_products)
                },
                success: function(data) {
                    var response = JSON.parse(data);

                    var data_products = response.title_products;
                    $('#modalProduct').modal('hide');
                    loadProduct();

                    console.log(id_products);
                    // data_products.forEach((element) => {
                    //     $('#resultProduct').append('<span class="badge badge-dark ml-1" style="font-size: 14px;"><i class="fa fa-times mr-2" id="destroyProduct" data-id="'+ element.id +'" style="font-size: 14px;"></i>' + element.title + '</span>')
                    // });


                }
            })
        });

        //destroy product
        $(document).on('click', '#destroyProduct', function() {
            let id = $(this).data('id');
            console.log(JSON.stringify(id_products));


            //get index
            let index = id_products.map(function(item) {
                return item.id_product;
            }).indexOf(id);

            if (index > -1) {
                id_products.splice(index, 1);
            }
            $.ajax({
                method: "POST",
                url: base_url + 'doctor/medicalrecord/destroyProduct/' + index,
                beforeSend: function() {

                },
                success: function(response) {
                    loadProduct();



                    console.log(JSON.stringify(id_products.sort()));
                }
            });
        });

        //load more data product
        $(document).on('click', '#btnLoadMoreData', function() {
            let next_page = Number($(this).data('page'));
            let search_val = $('#searchProduct').val();
            var search_temp = search_val.split(' ').join('%20');
            next_page += 1;

            if (search_val == '') {
                var page_url = base_url + 'doctor/medicalrecord/showProductMore/' + next_page;
            } else {
                var page_url = base_url + 'doctor/medicalrecord/searchProductMore/' + search_temp + '/' + next_page;

            }

            $.ajax({
                method: "GET",
                url: page_url,
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.statusCode == 200) {
                        $('.items').append(data.html);
                        let countItem = $('.hitung-item').length;

                        if (countItem >= 8) {
                            $('#btnLoadMoreData').remove();
                        }

                        if (countItem == data.total_product) {
                            $('#btnLoadMoreData').remove();

                        }

                        $('#btnLoadMoreData').attr('data-page', next_page);

                        $(".product-qty").TouchSpin({
                            min: 1
                        });


                        $('.bootstrap-touchspin-down').removeClass('btn-primary').addClass('btn-xs btn-hers');
                        $('.bootstrap-touchspin-up').removeClass('btn-primary').addClass('btn-xs btn-hers');
                    }

                }
            });


            //$('#btnLoadMoreData').data('page', next_page);


        });

        //show modal add medical record
        $('#modalAddMedicalRecord').on('shown.bs.modal', function() {
            //bind select2 into input select
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                allowClear: true,
                placeholder: "Choose the option",

            });
            $('#anamnesa').focus();


        });

        //bind id customer to form add medical record
        $(document).on('click', '.btnAddMedicalRecord', function() {
            let id_customer = $(this).data('id');
            let id_queue = $(this).data('idQueue');
            $('#id_customer_rm').val(id_customer);
            $('#id_queue').val(id_queue);
        });


        //add medical record patient
        $(document).on('submit', '#formAddMedicalRecord', function(e) {
            e.preventDefault();
            let data = $(this).serialize();

            console.log(data);
            $.ajax({
                method: "POST",
                url: base_url + 'doctor/medicalrecord/add_medical_record',
                data: data,
                beforeSend: function() {
                    //do something
                },
                success: function(response) {
                    let data2 = JSON.parse(response);

                    if (data2.statusCode == 200) {

                        $('#formAddMedicalRecord')[0].reset();
                        $('.select2').val([]).change();
                        $('#modalAddMedicalRecord').modal('hide');
                        updateDataQueue();
                        loadDataQueueProgress();

                        id_products = [];
                        console.log(id_products);
                        destroyAllProduct();
                    } else if (data2.statusCode == 201) {
                        alert('gagal');
                    } else {
                        if (data2.error == true) {
                            if (data2.anamnesa_error != "") {
                                $('#anamnesa_error').html(data2.anamnesa_error);
                                $('#anamnesa').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#anamnesa_error').html('');
                                $('#anamnesa').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data2.pemeriksaan_error != "") {
                                $('#pemeriksaan_error').html(data2.pemeriksaan_error);
                                $('#pemeriksaan').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#pemeriksaan_error').html('');
                                $('#pemeriksaan').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data2.diagnosa_error != "") {
                                $('#diagnosa_error').html(data2.diagnosa_error);
                                $('#diagnosa').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#diagnosa_error').html('');
                                $('#diagnosa').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data2.therapy_error != "") {
                                $('#therapy_error').html(data2.therapy_error);
                                $('#therapy').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#therapy_error').html('');
                                $('#therapy').removeClass('is-invalid').addClass('is-valid');
                            }

                        }
                    }
                }
            });

        });

        //update status to 'paid' on queue
        $(document).on('click', '#btnAddToPayment', function(e) {
            let id_queue = $(this).data('id');

            $.ajax({
                url: base_url + 'doctor/home/updateToPaid/' + id_queue,
                method: "POST",
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        VanillaToasts.create({
                            title: 'Success!',
                            text: data.msg,
                            type: 'success',
                            positionClass: 'topRight',
                            timeout: 2000
                        });
                        loadDataQueueProgress();
                    } else {
                        alert('something wrong');
                    }

                    $('#loading').hide();

                }

            });

        });


        //select patient to request data medical records
        $(document).on('change', '#selectPatientsMedicalRecords', function() {
            let id = $('#selectPatientsMedicalRecords option:selected').val();


            $.ajax({
                method: "GET",
                url: base_url + 'doctor/medicalrecord/load_data_medical_records/' + id,
                beforeSend: function() {

                },

                success: function(response) {
                    $('.tablePatientsMedicalRecordsHistory').html(response);
                }
            })
        });

        //print data medical records
        $(document).on('click', '#btnPrintMedicalRecord', function() {
            printMedicalRecord();
        });


        //pagination data queue progress
        $(document).on('click', '#pagination li a', function(e) {
            let page_url = $(this).attr('href');
            loadDataQueueProgress(page_url);
            e.preventDefault();
        });


        //search data queue progress
        $(document).on('keyup', '#searchQueueProgress', function() {
            var search_key = $(this).val();
            var search_temp = search_key.split(' ').join('%20');

            var page_url = base_url + 'doctor/home/searchDataQueueProgress/' + search_temp;

            if (search_temp == '') {
                loadDataQueueProgress();
            } else {
                loadDataQueueProgress(page_url);
            }
        });

        /**
         * perPage
         * Show Data Per Page
         */
        $(document).on('change', '#perPageDataQueueProgress', function() {
            let value_option = $('#perPageDataQueueProgress option:selected').val();
            let page_url = base_url + 'doctor/home/loadDataQueueProgress/1/' + value_option;

            loadDataQueueProgress(page_url);
        });




        //edit medical record in data queue progress
        $(document).on('click', '#btnEditMedicalRecord', function() {
            let id_queue = $(this).data('id');

            $.ajax({
                method: "GET",
                url: base_url + 'doctor/medicalrecord/edit?id=' + id_queue,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    $('#modalEditMedicalRecords').html(data.html);
                    $('#modal-edit-medical-records').modal('show');
                    $('.select2edit').select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        allowClear: true,
                        placeholder: "Choose the option",

                    });

                    $('#anamnesa').focus();
                    $('#loading').hide();

                    var data_products = JSON.parse(data.dataProduct);
                    for (var i = 0; i < data_products.length; i++) {
                        id_products.push({
                            'id_product': Number(data_products[i].id_product),
                            'qty': Number(data_products[i].qty)
                        });
                    }

                    console.log(id_products);
                    //id_products.push()


                }
            });
        });

        //update data medical record
        $(document).on('submit', '#formUpdateMedicalRecord', function(e) {
            e.preventDefault();
            let data = $(this).serialize();
            $.ajax({
                method: "POST",
                url: base_url + 'doctor/medicalrecord/update_medical_record',
                data: data,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(response) {
                    let data2 = JSON.parse(response);
                    $('#loading').hide();
                    if (data2.statusCode == 200) {
                        $('#modal-edit-medical-records').modal('hide');
                        VanillaToasts.create({
                            title: 'Success!',
                            text: data2.msg,
                            type: 'success',
                            positionClass: 'topRight',
                            timeout: 2000
                        });
                    } else if (data2.statusCode == 201) {
                        alert('gagal');
                    } else {
                        if (data2.error == true) {
                            if (data2.anamnesa_error != "") {
                                $('#anamnesa_edit_error').html(data2.anamnesa_error);
                                $('#anamnesa_edit').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#anamnesa_edit_error').html('');
                                $('#anamnesa_edit').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data2.pemeriksaan_error != "") {
                                $('#pemeriksaan_edit_error').html(data2.pemeriksaan_error);
                                $('#pemeriksaan_edit').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#pemeriksaan_edit_error').html('');
                                $('#pemeriksaan_edit').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data2.diagnosa_error != "") {
                                $('#diagnosa_edit_error').html(data2.diagnosa_error);
                                $('#diagnosa_edit').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#diagnosa_edit_error').html('');
                                $('#diagnosa_edit').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data2.therapy_error != "") {
                                $('#therapy_edit_error').html(data2.therapy_error);
                                $('#therapy_edit').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#therapy_edit_error').html('');
                                $('#therapy_edit').removeClass('is-invalid').addClass('is-valid');
                            }
                        }
                    }
                }
            })
        });
    });


    function loadDataQueue() {
        $.ajax({
            url: base_url + 'doctor/home/loadDataQueue',
            method: "GET",
            beforeSend: function() {

            },
            success: function(response) {
                $('.tableQueue').html(response);
                $('#dataTableQueue').DataTable({
                    responsive: true,
                });
            }
        });
    }

    function updateDataQueue() {
        $.ajax({
            url: base_url + 'doctor/home/updateDataQueue',
            method: "POST",
            success: function(response) {
                loadDataQueue();
            }
        })
    }

    function loadDataQueueProgress(page_url = false) {

        var base_url2 = base_url + 'doctor/home/loadDataQueueProgress';
        if (page_url == false) {
            var page_url = base_url2;
        }

        $.ajax({
            url: page_url,
            method: "POST",
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(response) {
                var data = JSON.parse(response);
                $('.tableQueueProgress').html(data.html);
                $('#pagination nav').html(data.pagination);
                $('#dataTableQueueProgress').DataTable({
                    responsive: true,
                });

                $('.queue_progress_items').slimScroll({
                    height: '50vh',
                });

                $('#loading').hide();



            }
        });
    }

    function loadProduct(page_url = false) {
        var base_url2 = base_url + 'doctor/medicalrecord/loadProduct';
        if (page_url == false) {
            var page_url = base_url2;
        }
        $.ajax({
            method: "GET",
            url: page_url,
            beforeSend: function() {

            },
            success: function(response) {
                $('.resultProduct').html(response);
            }
        });
    }

    function destroyAllProduct() {
        $.ajax({
            method: "POST",
            url: base_url + 'doctor/medicalrecord/unsetSessProduct',
            beforeSend: function() {

            },
            success: function(response) {

            }
        });
    }

    function printMedicalRecord() {
        window.frames['medical_record_print'].print();
    }
</script>