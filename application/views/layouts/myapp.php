<script>
    const base_url = $('body').data('url');
    

    $(document).ready(function() {
        //load component
        $('[data-toggle="tooltip"]').tooltip();
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: "Choose the option",
            allowClear: true
        });



        $(document).on('show.bs.modal', '.modal', function() {
            var zIndex = 1040 + (10 * $('.modal:visible').length);
            $(this).css('z-index', zIndex);
            setTimeout(function() {
                $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
            }, 0);
        });

        $('#dateAreaa .input-daterange').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            todayBtn: 'linked',
        });

        $('#dateAreaaSalesProduct .input-daterange').datepicker({
            format: 'dd/mm/yyyy',
            autoclose: true,
            todayHighlight: true,
            todayBtn: 'linked',
        });

        $("#datepicker").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true,

        });

        $("#datepicker_year").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true,

        });

        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: "Choose the option",
            allowClear: true
        });


        //category section
        //proses penambahan kategori
        $(document).on('submit', '#formAddCategory', function(e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/category/insert',
                data: data,
                beforeSend: function() {

                },

                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.statusCode == 200) {
                        $('#formAddCategory')[0].reset();
                        loadDataCategory('admin/category/loadTable');

                        VanillaToasts.create({
                            title: 'Success',
                            text: 'Data Has Been Added!',
                            type: 'success',
                            positionClass: 'topCenter',
                            timeout: 3000
                        });
                    } else if (data.statusCode == 201) {
                        //do something
                    } else {
                        if (data.error == true) {
                            if (data.title_error != '') {
                                $('#title_error').html(data.title_error);
                                $('#title_category').removeClass('is-valid').addClass('is-invalid');
                            }
                        }
                    }
                }
            });
        });

        //pemanggilan fungsi load data kategori ketika document ready
        loadDataCategory('admin/category/loadTable');


        //proses memunculkan modal edit kategori
        $(document).on('click', '#btnEditCategory', function(e) {
            let id = $(this).data('id');

            $.ajax({
                method: "POST",
                url: base_url + 'admin/category/edit/' + id,
                beforeSend: function() {
                    //do something
                },

                success: function(response) {
                    $('#modalEditCategory').html(response);
                    $('#modal-edit-category').modal('show');
                }
            });
        });

        //proses perubahan (edit data) kategori
        $(document).on('submit', '#formEditCategory', function(e) {
            e.preventDefault();

            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/category/update',
                data: data,
                beforeSend: function() {

                },

                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.statusCode == 200) {
                        $('#formEditCategory')[0].reset();
                        $('#modal-edit-category').modal('hide');
                        loadDataCategory('admin/category/loadTable');
                    } else if (data.statusCode == 201) {
                        //do something
                    } else {
                        if (data.error == true) {
                            if (data.title_error != '') {
                                $('#title_error').html(data.title_error);
                                $('#title_category').removeClass('is-valid').addClass('is-invalid');
                            }
                        }
                    }
                }
            });
        });

        //proses hapus kategori
        $(document).on('click', '#btnDeleteCategory', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Are You Sure',
                text: "to delete this item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6a56a5',


                confirmButtonText: "Yes, I'm sure",
                cancelButtonText: 'Cancel',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: base_url + 'admin/category/destroy/' + id,
                        beforeSend: function() {

                        },

                        success: function(data) {
                            loadDataCategory('admin/category/loadTable');
                        }
                    });
                }
            })


        });

        //inventory section
        //pemanggilan fungsi load data inventori ketika document ready
        loadDataInventory('admin/inventory/loadTable');

        //show form add inventory
        $(document).on('click', '#btnTambahInventory', function() {
            $('#formAddInventory').fadeIn(500);
            $('#btnTambahInventory').hide();
        });

        //cancel add inventory and hide the form
        $(document).on('click', '#btnCancelTambahInventory', function() {
            $('#formAddInventory').fadeOut(500);
            $('#btnTambahInventory').show();
        });

        //when keyup event change to format rupiah
        $(document).on('keyup', '#price', function() {
            let bilangan = $(this).val();
            $(this).val(formatRupiah(bilangan));
        });

        //proses penambahan inventori
        $(document).on('submit', '#formAddInventory', function(e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/inventory/insert',
                data: data,
                beforeSend: function() {

                },

                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.statusCode == 200) {
                        $('#formAddInventory')[0].reset();
                        loadDataInventory('admin/inventory/loadTable');

                        $('.select2').select2({
                            theme: 'bootstrap4',
                            width: '100%',
                            placeholder: "Choose the option",
                            allowClear: true
                        });

                        let nameFlash = data.nameFlash;
                        let msg = data.msg;

                        //ubah tanda seru menjadi %21
                        let msg_fix = msg.replace(/!/g, '%21');

                        //load Alert Notification
                        loadAlert(nameFlash, msg_fix);

                        //clear error alert
                        clearErrorFormAddInventory();

                        //remove image
                        $('#img-product').remove();
                    } else if (data.statusCode == 201) {
                        //do something
                    } else {
                        if (data.error == true) {
                            if (data.title_error != '') {
                                $('#title_error').html(data.title_error);
                                $('#title').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#title_error').html('');
                                $('#title').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.category_error != '') {
                                $('#category_error').html(data.category_error);
                                $('#category').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#category_error').html('');
                                $('#category').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.stock_error != '') {
                                $('#stock_error').html(data.stock_error);
                                $('#stock').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#stock_error').html('');
                                $('#stock').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.price_error != '') {
                                $('#price_error').html(data.price_error);
                                $('#price').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#price_error').html('');
                                $('#price').removeClass('is-invalid').addClass('is-valid');
                            }
                        }
                    }
                }
            });
        });

        //crop image product
        $image_crop = $('#image_demo').croppie({
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'square' //circle
            },
            boundary: {
                width: 300,
                height: 300
            }
        });

        $image_crop_edit = $('#image_demo_edit').croppie({
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'square' //circle
            },
            boundary: {
                width: 300,
                height: 300
            }
        });

        //proses upload product
        $(document).on('change', '#image', function() {
            let fileName = $(this).val();

            fileName = fileName.substring(fileName.lastIndexOf("\\") + 1, fileName.length);
            $(this).next('.custom-file-label').html(fileName);

            var reader = new FileReader();
            reader.onload = function(event) {
                $image_crop.croppie('bind', {
                    url: event.target.result
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadimageModal').modal('show');


        });

        //proses upload product bagian edit
        $(document).on('change', '#image_edit', function() {
            let fileName = $(this).val();

            fileName = fileName.substring(fileName.lastIndexOf("\\") + 1, fileName.length);
            $(this).next('.custom-file-label').html(fileName);

            var reader = new FileReader();
            reader.onload = function(event) {
                $image_crop_edit.croppie('bind', {
                    url: event.target.result
                }).then(function() {
                    console.log('jQuery bind complete');
                });
            }
            reader.readAsDataURL(this.files[0]);
            $('#uploadEditImageModal').modal('show');


        });


        //add upload image
        $('#crop_image').on('click', function() {
            var nama_product = $('#title').val();
            var fileName = nama_product == "" ? 'default' : nama_product;
            $image_crop.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response) {
                $.ajax({
                    url: base_url + 'admin/inventory/uploadProductImage/' + fileName,
                    type: "POST",
                    data: {
                        "image": response
                    },
                    success: function(data) {
                        var data = JSON.parse(data);
                        $('#uploadimageModal').modal('hide');
                        $('#image_product').val(data.image_name);
                        $('.wadah-image-product').html(data.show_image);

                    }
                });
            });

        });

        //edit upload image
        $('#crop_image_edit').on('click', function() {
            var nama_product = $('#title_edit').val();
            var fileName = nama_product == "" ? 'default' : nama_product;
            $image_crop_edit.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response) {
                $.ajax({
                    url: base_url + 'admin/inventory/uploadProductImage/' + fileName,
                    type: "POST",
                    data: {
                        "image": response
                    },
                    success: function(data) {
                        var data = JSON.parse(data);
                        $('#uploadEditImageModal').modal('hide');
                        $('#image_product_edit').val(data.image_name);
                        $('.wadah-image-product-edit').html(data.show_image);

                    }
                });
            });

        });

        //proses memunculkan modal edit inventori
        $(document).on('click', '#btnEditInventory', function(e) {
            let id = $(this).data('id');

            $.ajax({
                method: "POST",
                url: base_url + 'admin/inventory/edit/' + id,
                beforeSend: function() {
                    //do something
                },

                success: function(response) {
                    $('#modalEditInventory').html(response);
                    $('#modal-edit-inventory').modal('show');
                    $('.select2').select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        placeholder: "Choose the option",
                        allowClear: true
                    });

                    $(document).on('keyup', '#title_edit', function() {
                        let title = $(this).val();
                        $('#slug_edit').val(string_to_slug2(title));
                    });

                    $(document).on('keyup', '#price_edit', function() {
                        let bilangan = $(this).val();
                        $(this).val(formatRupiah(bilangan));
                    });
                }
            });
        });

        //proses hapus inventori
        $(document).on('click', '#btnDeleteInventory', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Are You Sure',
                text: "to delete this item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#BB9A5D',


                confirmButtonText: "Yes, I'm sure",
                cancelButtonText: 'Cancel',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: base_url + 'admin/inventory/destroy/' + id,
                        beforeSend: function() {

                        },

                        success: function(data) {
                            loadDataInventory('admin/inventory/loadTable');
                        }
                    });
                }
            })


        });

        //proses perubahan (edit data) kategori
        $(document).on('submit', '#formEditInventory', function(e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/inventory/update',
                data: data,
                beforeSend: function() {

                },

                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.statusCode == 200) {

                        loadDataInventory('admin/inventory/loadTable');

                        $('.select2').select2({
                            theme: 'bootstrap4',
                            width: '100%',
                            placeholder: "Choose the option",
                            allowClear: true
                        });

                        let nameFlash = data.nameFlash;
                        let msg = data.msg;

                        //ubah tanda seru menjadi %21
                        let msg_fix = msg.replace(/!/g, '%21');

                        //load Alert Notification
                        loadAlert(nameFlash, msg_fix);



                        //remove image
                        //$('#img-product').remove();
                        $('#modal-edit-inventory').modal('hide');
                    } else if (data.statusCode == 201) {
                        //do something
                    } else {
                        if (data.error == true) {
                            if (data.title_error != '') {
                                $('#title_error').html(data.title_error);
                                $('#title_edit').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#title_error').html('');
                                $('#title_edit').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.category_error != '') {
                                $('#category_error').html(data.category_error);
                                $('#category_edit').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#category_error').html('');
                                $('#category_edit').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.stock_error != '') {
                                $('#stock_error').html(data.stock_error);
                                $('#stock_edit').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#stock_error').html('');
                                $('#stock_edit').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.price_error != '') {
                                $('#price_error').html(data.price_error);
                                $('#price_edit').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#price_error').html('');
                                $('#price_edit').removeClass('is-invalid').addClass('is-valid');
                            }
                        }
                    }
                }
            });
        });


        //discount section
        //load Data Discount
        loadDataDiscount('admin/discount/loadTable');

        //proses penambahan discount
        $(document).on('submit', '#formAddDiscount', function(e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/discount/insert',
                data: data,
                beforeSend: function() {

                },

                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.statusCode == 200) {
                        $('#formAddDiscount')[0].reset();
                        loadDataDiscount('admin/discount/loadTable');
                        $('#modalAddDiscount').modal('hide');

                        VanillaToasts.create({
                            title: 'Success',
                            text: 'Data Has Been Added!',
                            type: 'success',
                            positionClass: 'topCenter',
                            timeout: 3000
                        });
                    } else if (data.statusCode == 201) {

                    } else {
                        if (data.title_discount_error != '') {
                            $('#title_discount_error').html(data.title_discount_error);
                            $('#title_discount').removeClass('is-valid').addClass('is-invalid');
                        } else {
                            $('#title_discount_error').html("");
                            $('#title_discount').removeClass('is-invalid').addClass('is-valid');
                        }

                        if (data.value_error != '') {
                            $('#value_error').html(data.value_error);
                            $('#value').removeClass('is-valid').addClass('is-invalid');
                        } else {
                            $('#value_error').html("");
                            $('#value').removeClass('is-invalid').addClass('is-valid');
                        }

                        if (data.tgl_start_error != '') {
                            $('#tgl_start_error').html(data.tgl_start_error);
                            $('#tgl_start').removeClass('is-valid').addClass('is-invalid');
                        } else {
                            $('#tgl_start_error').html("");
                            $('#tgl_start').removeClass('is-invalid').addClass('is-valid');
                        }

                        if (data.tgl_end_error != '') {
                            $('#tgl_end_error').html(data.tgl_end_error);
                            $('#tgl_end').removeClass('is-valid').addClass('is-invalid');
                        } else {
                            $('#tgl_end_error').html("");
                            $('#tgl_end').removeClass('is-invalid').addClass('is-valid');
                        }
                    }
                }
            })
        });


        //load datetimepicker while modal show
        $('#modalAddDiscount').on('shown.bs.modal', function() {
            $('#dateArea .input-daterange').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                todayBtn: 'linked',
            });
        });

        //delete data discount
        $(document).on('click', '#btnDeleteDiscount', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Are You Sure',
                text: "to delete this item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6a56a5',


                confirmButtonText: "Yes, I'm sure",
                cancelButtonText: 'Cancel',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: base_url + 'admin/discount/destroy/' + id,
                        beforeSend: function() {

                        },

                        success: function(data) {
                            loadDataDiscount('admin/discount/loadTable');
                        }
                    });
                }
            })


        });

        //proses memunculkan modal edit diskon
        $(document).on('click', '#btnEditDiscount', function(e) {
            let id = $(this).data('id');

            $.ajax({
                method: "POST",
                url: base_url + 'admin/discount/edit/' + id,
                beforeSend: function() {
                    //do something
                },

                success: function(response) {
                    $('#modalEditDiscount').html(response);
                    $('#modal-edit-discount').modal('show');
                    $('#dateArea .input-daterange').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true,
                        todayHighlight: true,
                        todayBtn: 'linked',
                    });
                }
            });
        });

        //proses perubahan (edit data) discount
        $(document).on('submit', '#formEditDiscount', function(e) {
            e.preventDefault();

            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/discount/update',
                data: data,
                beforeSend: function() {

                },

                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.statusCode == 200) {
                        $('#formEditDiscount')[0].reset();
                        $('#modal-edit-discount').modal('hide');
                        loadDataDiscount('admin/discount/loadTable');
                    } else if (data.statusCode == 201) {
                        //do something
                    } else {
                        if (data.error == true) {
                            if (data.title_discount_error != '') {
                                $('#title_discount_error').html(data.title_discount_error);
                                $('#title_discount').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#title_discount_error').html("");
                                $('#title_discount').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.value_error != '') {
                                $('#value_error').html(data.value_error);
                                $('#value').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#value_error').html("");
                                $('#value').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.tgl_start_error != '') {
                                $('#tgl_start_error').html(data.tgl_start_error);
                                $('#tgl_start').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#tgl_start_error').html("");
                                $('#tgl_start').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.tgl_end_error != '') {
                                $('#tgl_end_error').html(data.tgl_end_error);
                                $('#tgl_end').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#tgl_end_error').html("");
                                $('#tgl_end').removeClass('is-invalid').addClass('is-valid');
                            }
                        }
                    }
                }
            });
        });


        //product in section
        //load data product in
        loadDataProductIn('admin/libraries/loadTable');


        $('#modalAddProductIn').on('shown.bs.modal', function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: "Choose the option",
                allowClear: true
            });
        });

        //add product in to db
        $(document).on('submit', '#formAddProductIn', function(e) {

            e.preventDefault();
            let id_product_in = $('#id_product_in option:selected').val();
            let stock_in = $('#stock_in').val();
            let note = $('#note').val();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/libraries/insert',
                data: {
                    id_product_in: id_product_in,
                    stock_in: stock_in,
                    note: note
                },
                beforeSend: function() {

                },
                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.statusCode == 200) {
                        $('#formAddProductIn')[0].reset();
                        loadDataProductIn('admin/libraries/loadTable');
                        //$('#modalAddProductIn').modal('hide');

                        VanillaToasts.create({
                            title: 'Success',
                            text: 'Data Has Been Added!',
                            type: 'success',
                            positionClass: 'topCenter',
                            timeout: 3000
                        });
                    } else if (data.statusCode == 201) {
                        //do something
                    } else {
                        if (data.error == true) {
                            if (data.id_product_in_error != '') {
                                $('#id_product_in_error').html(data.id_product_in_error);
                                $('#id_product_in').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#id_product_in_error').html("");
                                $('#id_product_in').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.stock_in_error != '') {
                                $('#stock_in_error').html(data.stock_in_error);
                                $('#stock_in').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#stock_in_error').html("");
                                $('#stock_in').removeClass('is-invalid').addClass('is-valid');
                            }
                        }
                    }
                }
            });
        });


        //show modal edit product in
        $(document).on('click', '#btnEditProductIn', function(e) {
            let id = $(this).data('id');

            $.ajax({
                method: "POST",
                url: base_url + 'admin/libraries/edit/' + id,
                beforeSend: function() {
                    //do something
                },

                success: function(response) {
                    $('#modalEditProductIn').html(response);
                    $('#modal-edit-product-in').modal('show');

                    $('.select2').select2({
                        theme: 'bootstrap4',
                        width: '100%',
                        placeholder: "Choose the option",
                        allowClear: true
                    });
                }
            });
        });

        //update data product in
        //proses perubahan (edit data) product in
        $(document).on('submit', '#formEditProductIn', function(e) {
            e.preventDefault();

            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/libraries/update',
                data: data,
                beforeSend: function() {

                },

                success: function(data) {
                    var data = JSON.parse(data);
                    if (data.statusCode == 200) {
                        $('#formEditProductIn')[0].reset();
                        $('#modal-edit-product-in').modal('hide');
                        loadDataProductIn('admin/libraries/loadTable');
                    } else if (data.statusCode == 201) {
                        //do something
                    } else {
                        if (data.error == true) {
                            if (data.id_product_in_error != '') {
                                $('#id_product_in_error').html(data.id_product_in_error);
                                $('#id_product_in').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#id_product_in_error').html("");
                                $('#id_product_in').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.stock_in_error != '') {
                                $('#stock_in_error').html(data.stock_in_error);
                                $('#stock_in').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#stock_in_error').html("");
                                $('#stock_in').removeClass('is-invalid').addClass('is-valid');
                            }
                        }
                    }
                }
            });
        });


        //delete data product in
        $(document).on('click', '#btnDeleteProductIn', function() {
            let id = $(this).data('id');
            let stock = $(this).data('stock');
            Swal.fire({
                title: 'Are You Sure',
                text: "to delete this item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#6a56a5',


                confirmButtonText: "Yes, I'm sure",
                cancelButtonText: 'Cancel',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: base_url + 'admin/libraries/destroy/' + id + '/' + stock,
                        beforeSend: function() {

                        },

                        success: function(data) {
                            loadDataProductIn('admin/libraries/loadTable');
                        }
                    });
                }
            })


        });

        //product out section
        //load data product out
        loadDataProductOut('admin/libraries/loadTableItemsSales');

        //report section
        $(document).on('change', '#selectFilter', function() {
            let value = $('#selectFilter option:selected').val();

            if (value == "date") {
                $('#dateAreaa').show(600);
                $('#monthArea').hide();
                $('#yearArea').hide();
            } else if (value == "month") {
                $('#monthArea').show(600);
                $('#dateAreaa').hide();
                $('#yearArea').hide();
            } else {
                $('#yearArea').show(600);
                $('#monthArea').hide();
                $('#dateAreaa').hide();
            }

            $('.btn-submit-report-sales').show(600);
        });

        //if form has submitted
        $(document).on('submit', '#formReportSales', function(e) {
            e.preventDefault();
            let selectFilter = $('#selectFilter option:selected').val();

            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/report/requestSales/' + selectFilter,
                data: data,
                beforeSend: function() {
                    $('.loadIco').show();
                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        $('.tableReportSales').html(data.content);
                        if ($('#dataTableReportSales').length) {
                            $('#dataTableReportSales').DataTable({
                                responsive: true
                            });
                        }

                        $('.loadIco').hide();
                    } else {
                        $('.tableReportSales').html(data.content);
                        $('.loadIco').hide();
                    }
                }
            });
        });

        $(document).on('change', '#selectFilterSalesProduct', function() {
            let value = $('#selectFilterSalesProduct option:selected').val();

            if (value == "date") {
                $('#dateAreaaSalesProduct').show(600);
                $('#monthAreaSalesProduct').hide();
                $('#yearAreaSalesProduct').hide();
            } else if (value == "month") {
                $('#monthAreaSalesProduct').show(600);
                $('#dateAreaaSalesProduct').hide();
                $('#yearAreaSalesProduct').hide();
            } else {
                $('#yearAreaSalesProduct').show(600);
                $('#monthAreaSalesProduct').hide();
                $('#dateAreaaSalesProduct').hide();
            }

            $('.btn-submit-report-sales-product').show(600);
        });

        $(document).on('submit', '#formReportSalesProduct', function(e) {
            e.preventDefault();
            let filter = $('#selectFilterSalesProduct option:selected').val();

            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/report/requestSalesProduct/' + filter,
                data: data,
                beforeSend: function() {
                    $('.loadIco').show();
                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        $('.tableReportSalesProduct').html(data.content);
                        $('#dataTableReportSalesProduct').DataTable({
                            responsive: true
                        });
                        $('.loadIco').hide();
                    } else {
                        $('.tableReportSalesProduct').html(data.content);
                        $('.loadIco').hide();
                    }

                }
            })
        });

        //tracking product stock report
        $(document).on('submit', '#formReportTrackingProduct', function(e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/report/requestTrackingProduct',
                data: data,
                beforeSend: function() {
                    $('.loadIco').show();
                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        $('.tableReportTrackingProduct').html(data.content);
                        $('#dataTableReportTrackingProduct').DataTable({
                            responsive: true
                        });

                        $('.loadIco').hide();
                    } else {
                        $('.tableReportTrackingProduct').html(data.content);
                        $('.loadIco').hide();
                    }
                }
            });
        });

        //sales report perdays
        $(document).on('submit', '#formReportSalesPerdays', function(e) {
            e.preventDefault();

            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/report/requestSalesPerDays',
                data: data,
                beforeSend: function() {
                    $('.loadIco').show();
                },
                success: function(response) {
                    let data = JSON.parse(response);

                    if (data.statusCode == 200) {
                        $('.tableReportSalesPerdays').html(data.content);
                        $('#dataTableReportSalesPerdays').DataTable({
                            responsive: true
                        });
                        $('.loadIco').hide();
                    } else {
                        $('.tableReportSalesPerdays').html(data.content);
                        $('.loadIco').hide();
                    }
                }
            })
        });

        //end report section

        //export report section
        $(document).on('click', '.btnExportPerDays', function() {
            let month = $('#month option:selected').val();
            let year = $('#datepicker').val();
            let type = $('#selectType option:selected').val();

            window.location.href = base_url + 'admin/report/exportSalesPerDays/' + month + '/' + year + '/' + type;
        });

        $(document).on('click', '.btnExportSalesProduct', function() {
            let tipeExport = $('#selectType option:selected').val();

            if (tipeExport == "excel") {
                let type = $("#selectFilterSalesProduct option:selected").val();
                let startFrom = $('#tgl_start').val();
                let endPeriod = $('#tgl_end').val();

                let cStartFrom = startFrom.split("/").join("-");
                let cEndPeriod = endPeriod.split("/").join("-");


                let month = $('#month option:selected').val();
                let year = $('#datepicker').val();

                let year2 = $('#datepicker_year').val();

                if (type == 'date') {
                    window.location.href = base_url + 'admin/report/exportSalesProduct/' + type + '/' + cStartFrom + '/' + cEndPeriod;

                } else if (type == 'month') {
                    window.location.href = base_url + 'admin/report/exportSalesProduct/' + type + '/0/0/' + month + '/' + year
                } else {
                    window.location.href = base_url + 'admin/report/exportSalesProduct/' + type + '/0/0/0/' + year2
                }
            } else {
                //pdf
                let type = $('#selectFilterSalesProduct option:selected').val();

                let startFrom = $('#tgl_start').val();
                let endPeriod = $('#tgl_end').val();
                let cStartFrom = startFrom.split("/").join("-");
                let cEndPeriod = endPeriod.split("/").join("-");

                let month = $('#month option:selected').val();
                let year = $('#datepicker').val();

                let year2 = $('#datepicker_year').val();

                if (type == 'date') {
                    window.location.href = base_url + 'admin/report/exportPdfReportSalesProduct/' + type + '/' + cStartFrom + '/' + cEndPeriod;
                } else if (type == 'month') {
                    window.location.href = base_url + 'admin/report/exportPdfReportSalesProduct/' + type + '/0/0/' + month + '/' + year;
                } else {
                    window.location.href = base_url + 'admin/report/exportPdfReportSalesProduct/' + type + '/0/0/0/' + year2
                }

            }


        });

        $(document).on('click', '.btnExportSalesInvoice', function() {
            let tipeExport = $('#selectType option:selected').val();

            if (tipeExport == "excel") {
                let type = $('#selectFilter option:selected').val();
                let startFrom = $('#tgl_start').val();
                let endPeriod = $('#tgl_end').val();
                let cStartFrom = startFrom.split("/").join("-");
                let cEndPeriod = endPeriod.split("/").join("-");

                let month = $('#month option:selected').val();
                let year = $('#datepicker').val();

                let year2 = $('#datepicker_year').val();

                if (type == 'date') {
                    window.location.href = base_url + 'admin/report/exportSales/' + type + '/' + cStartFrom + '/' + cEndPeriod;
                } else if (type == 'month') {
                    window.location.href = base_url + 'admin/report/exportSales/' + type + '/0/0/' + month + '/' + year;
                } else {
                    window.location.href = base_url + 'admin/report/exportSales/' + type + '/0/0/0/' + year2
                }
            } else {
                //pdf
                let type = $('#selectFilter option:selected').val();

                let startFrom = $('#tgl_start').val();
                let endPeriod = $('#tgl_end').val();
                let cStartFrom = startFrom.split("/").join("-");
                let cEndPeriod = endPeriod.split("/").join("-");

                let month = $('#month option:selected').val();
                let year = $('#datepicker').val();

                let year2 = $('#datepicker_year').val();

                if (type == 'date') {
                    window.location.href = base_url + 'admin/report/exportPdfReportSales/' + type + '/' + cStartFrom + '/' + cEndPeriod;
                } else if (type == 'month') {
                    window.location.href = base_url + 'admin/report/exportPdfReportSales/' + type + '/0/0/' + month + '/' + year;
                } else {
                    window.location.href = base_url + 'admin/report/exportPdfReportSales/' + type + '/0/0/0/' + year2
                }

            }




        });

        $(document).on('click', '.btnExportTrackingProduct', function() {
            let type = $('#selectType option:selected').val();
            let month = $('#month option:selected').val();
            let year = $('#datepicker').val();

            window.location.href = base_url + 'admin/report/exportTrackingProduct/' + month + '/' + year + '/' + type;
        });
        //end export report section

        //doctor section
        loadDataDoctor('admin/doctor/loadTable');

        //kondisi ketika modal tambah data dokter muncul
        $('#modalAddDoctor').on('shown.bs.modal', function() {
            $('.date_birth').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            });
        });

        //proses penambahan data dokter
        $(document).on('submit', '#formAddDoctor', function(e) {
            e.preventDefault();
            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/doctor/insert',
                data: data,
                beforeSend: function() {

                },

                success: function(data) {
                    var data = JSON.parse(data);

                    if (data.statusCode == 200) {
                        $('#formAddDoctor')[0].reset();
                        loadDataDoctor('admin/doctor/loadTable');

                        VanillaToasts.create({
                            title: 'Success',
                            text: 'Data Has Been Added!',
                            type: 'success',
                            positionClass: 'topCenter',
                            timeout: 3000
                        });

                        clearFormAddDoctor();
                    } else if (data.statusCode == 201) {
                        //do something
                    } else {
                        if (data.error == true) {
                            if (data.name_error != '') {
                                $('#name_error').html(data.name_error);
                                $('#name_doctor').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#name_error').html('');
                                $('#name_doctor').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.birth_date_error != '') {
                                $('#birth_date_error').html(data.birth_date_error);
                                $('#birth_date_doctor').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#birth_date_error').html('');
                                $('#birth_date_doctor').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.identity_number_error != '') {
                                $('#identity_number_error').html(data.identity_number_error);
                                $('#identity_number_doctor').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#identity_number_error').html('');
                                $('#identity_number_doctor').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.idi_number_error != '') {
                                $('#idi_number_error').html(data.idi_number_error);
                                $('#idi_number_doctor').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#idi_number_error').html('');
                                $('#idi_number_doctor').removeClass('is-invalid').addClass('is-valid');
                            }


                            if (data.sip_number_error != '') {
                                $('#sip_number_error').html(data.sip_number_error);
                                $('#sip_number_doctor').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#sip_number_error').html('');
                                $('#sip_number_doctor').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.phone_error != '') {
                                $('#phone_error').html(data.phone_error);
                                $('#phone_doctor').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#phone_error').html('');
                                $('#phone_doctor').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.email_error != '') {
                                $('#email_error').html(data.email_error);
                                $('#email_doctor').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#email_error').html('');
                                $('#email_doctor').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.address_error != '') {
                                $('#address_error').html(data.address_error);
                                $('#address_doctor').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#address_error').html('');
                                $('#address_doctor').removeClass('is-invalid').addClass('is-valid');
                            }


                        }
                    }
                }
            });
        });


        //show modal edit doctor
        $(document).on('click', '#btnEditDoctor', function(e) {
            let id = $(this).data('id');

            $.ajax({
                method: "POST",
                url: base_url + 'admin/doctor/edit/' + id,
                beforeSend: function() {
                    $('#loading').show();
                },

                success: function(response) {
                    $('#loading').hide();

                    $('#modalEditDoctor').html(response);
                    $('#modal-edit-doctor').modal('show');

                    $('.date_birth_edit').datepicker({
                        format: 'dd/mm/yyyy',
                        autoclose: true
                    });
                }
            });
        });

        //proses perubahan data doctor
        $(document).on('submit', '#formEditDoctor', function(e) {
            e.preventDefault();

            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'admin/doctor/update',
                data: data,

                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    var data = JSON.parse(data);

                    if(data.statusCode == 200) {
                        $('#formEditDoctor')[0].reset();
                        $('#modal-edit-doctor').modal('hide');
                        loadDataDoctor('admin/doctor/loadTable');
                        clearFormAddDoctor();
                        $("#loading").hide();
                    }
                }
            })
        });

        //delete doctor data
        $(document).on('click', '#btnDeleteDoctor', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: 'Are You Sure',
                text: "to delete this item?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#BB9A5D',


                confirmButtonText: "Yes, I'm sure",
                cancelButtonText: 'Cancel',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: base_url + 'admin/doctor/destroy/' + id,
                        beforeSend: function() {
                            $("#loading").show();
                        },

                        success: function(data) {
                            $("#loading").hide();
                            loadDataDoctor('admin/doctor/loadTable');
                        }
                    });
                }
            })
        });


    });



    function clearFormAddDoctor() {
        $('#name_error').html('');
        $('#name_doctor').removeClass('is-valid');
        $('#name_doctor').removeClass('is-invalid');

        $('#birth_date_error').html('');
        $('#birth_date_doctor').removeClass('is-valid');
        $('#birth_date_doctor').removeClass('is-invalid');

        $('#identity_number_error').html('');
        $('#identity_number_doctor').removeClass('is-valid');
        $('#identity_number_doctor').removeClass('is-invalid');

        $('#idi_number_error').html('');
        $('#idi_number_doctor').removeClass('is-valid');
        $('#idi_number_doctor').removeClass('is-invalid');

        $('#sip_number_error').html('');
        $('#sip_number_doctor').removeClass('is-valid');
        $('#sip_number_doctor').removeClass('is-invalid');

        $('#phone_error').html('');
        $('#phone_doctor').removeClass('is-valid');
        $('#phone_doctor').removeClass('is-invalid');

        $('#email_error').html('');
        $('#email_doctor').removeClass('is-valid');
        $('#email_doctor').removeClass('is-invalid');

        $('#address_error').html('');
        $('#address_doctor').removeClass('is-valid');
        $('#address_doctor').removeClass('is-invalid');
    }

    //load data section
    //load data doctor
    function loadDataDoctor(url) {
        $.ajax({
            type: "POST",
            url: base_url + url,
            success: function(response) {
                $('.tableDoctor').html(response);
                if ($('#dataTableDoctor').length) {
                    $('#dataTableDoctor').DataTable({
                        responsive: true
                    });
                }

                $('[data-toggle="tooltip"]').tooltip();
            }
        })
    }

    //load data kategori
    function loadDataCategory(url) {
        $.ajax({
            type: "POST",
            url: base_url + url,
            success: function(response) {
                $('.tableCategory').html(response);
                if ($('#dataTable3').length) {
                    $('#dataTable3').DataTable({
                        responsive: true
                    });
                }

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    //load data inventory
    function loadDataInventory(url) {
        $.ajax({
            type: "POST",
            url: base_url + url,
            success: function(response) {
                $('.tableInventory').html(response);

                $('#dataTableInventory').DataTable({
                    responsive: false
                });


                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    //load data discount
    function loadDataDiscount(url) {
        $.ajax({
            type: "POST",
            url: base_url + url,
            success: function(response) {
                $('.tableDiscount').html(response);
                if ($('#dataTableDiscount').length) {
                    $('#dataTableDiscount').DataTable({
                        responsive: true
                    });
                }

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    //load data product in
    function loadDataProductIn(url) {
        $.ajax({
            type: "POST",
            url: base_url + url,
            success: function(response) {
                $('.tableProductIn').html(response);
                if ($('#dataTableProductIn').length) {
                    $('#dataTableProductIn').DataTable({
                        responsive: false
                    });
                }

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    function loadDataProductOut(url) {
        $.ajax({
            type: "POST",
            url: base_url + url,
            success: function(response) {
                $('.tableProductOut').html(response);
                if ($('#dataTableProductOut').length) {
                    $('#dataTableProductOut').DataTable({
                        responsive: true
                    });
                }

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

    //function helper
    function formatRupiah(angka = 0, prefix = '') {

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

    function loadAlert(nameFlash, msg) {
        $.ajax({
            type: "POST",
            url: base_url + 'admin/inventory/loadAlert/' + nameFlash + '/' + msg,
            success: function(response) {
                $('#alertPlace').html(response);
            }
        });
    }

    function clearErrorFormAddInventory() {
        $('#title_error').html('');
        $('#title').removeClass('is-invalid');

        $('#category_error').html('');
        $('#category').removeClass('is-invalid');

        $('#stock_error').html('');
        $('#stock').removeClass('is-invalid');

        $('#price_error').html('');
        $('#price').removeClass('is-invalid');
    }

    function createSlug2(selector) {
        let title = $(selector).val();
        $('#slug').val(string_to_slug2(title));
    }



    function string_to_slug2(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
        var to = "aaaaeeeeiiiioooouuuunc------";
        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes

        return str;
    }
</script>