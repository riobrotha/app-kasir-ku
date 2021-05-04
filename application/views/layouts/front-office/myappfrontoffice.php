<script>
    const base_url = $('body').data('url');
    $(function() {

        $('[data-toggle="tooltip"]').tooltip();
        //load data patients
        loadDataPatients();

        //load data queue
        loadDataQueue();

        //add to queue
        $(document).on('click', '#btnAddToQueue', function() {
            let id_customer = $(this).data('id');

            $.ajax({
                method: "POST",
                url: base_url + 'frontoffice/add_queue/' + id_customer,
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
                            positionClass: 'topCenter',
                            timeout: 2000
                        });

                        loadDataQueue();
                    } else if (data.statusCode == 202) {
                        VanillaToasts.create({
                            title: 'Warning!',
                            text: data.msg,
                            type: 'warning',
                            positionClass: 'topCenter',
                            timeout: 2000
                        });
                    } else {
                        VanillaToasts.create({
                            title: 'Error!',
                            text: data.msg,
                            type: 'warning',
                            positionClass: 'topCenter',
                            timeout: 2000
                        });
                    }

                    $('#loading').hide();
                }
            });
        });

        //remove queue
        $(document).on('click', '#btnDeleteQueue', function() {
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
            }).then(function(result) {
                if(result.isConfirmed) {
                    $.ajax({
                        method: "POST",
                        url: base_url + 'frontoffice/remove_queue/' + id,
                        beforeSend: function() {
                            $('#loading').show();
                        },

                        success: function(data) {
                            loadDataQueue();
                            $('#loading').hide();
                        }
                    })
                }
            });
        });


        //condition if modal add patient showed
        $('#modalAddPatient').on('shown.bs.modal', function() {
            $('.date_birth').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true
            });
        });


        //submit data patient to db
        $(document).on('submit', '#formAddPatient', function(e) {
            e.preventDefault();

            let data = $(this).serialize();

            $.ajax({
                method: "POST",
                url: base_url + 'frontoffice/insert_patient',
                data: data,
                beforeSend: () => {
                    $('#loading').show();
                },

                success: (data) => {
                    var data = JSON.parse(data);
                    $('#loading').hide();
                    if (data.statusCode == 200) {

                        Swal.fire({
                            title: 'You want to add this customer into queue?',
                            text: "If you click yes, the customer will be added into queue",
                            icon: 'info',
                            showCancelButton: true,
                            confirmButtonColor: '#2D4A49',


                            confirmButtonText: "Yes, I'm sure",
                            cancelButtonText: 'Cancel',
                            showClass: {
                                popup: 'animate__animated animate__fadeInDown'
                            },
                            hideClass: {
                                popup: 'animate__animated animate__fadeOutUp'
                            }
                        }).then((result) => {
                            $.ajax({
                                method: "POST",
                                url: base_url + 'frontoffice/add_queue/' + data.id_customer,
                                beforeSend: function() {
                                    $('#loading').show();
                                },
                                success: function(response) {
                                    let data2 = JSON.parse(response);

                                    if (data2.statusCode == 200) {
                                        loadDataQueue();

                                        VanillaToasts.create({
                                            title: 'Success',
                                            text: 'Customer Has Been Added Into Queue!',
                                            type: 'success',
                                            positionClass: 'topCenter',
                                            timeout: 3000
                                        });
                                    } else if (data2.statusCode == 202) {

                                    } else {

                                    }

                                    $('#loading').hide();
                                }
                            });
                        });


                        $('#formAddPatient')[0].reset();
                        loadDataPatients();

                        VanillaToasts.create({
                            title: 'Success',
                            text: 'Data Has Been Added!',
                            type: 'success',
                            positionClass: 'topCenter',
                            timeout: 3000
                        });

                        clearFormAddPatient();

                        $('#modalAddPatient').modal('hide');
                    } else if (data.statusCode == 201) {
                        //do something
                        console.log('something wrong!');
                    } else {
                        if (data.error == true) {
                            if (data.name_error != '') {
                                $('#name_error').html(data.name_error);
                                $('#name_patient').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#name_error').html('');
                                $('#name_patient').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.birth_date_error != '') {
                                $('#birth_date_error').html(data.birth_date_error);
                                $('#birth_date_patient').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#birth_date_error').html('');
                                $('#birth_date_patient').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.identity_number_error != '') {
                                $('#identity_number_error').html(data.identity_number_error);
                                $('#identity_number_patient').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#identity_number_error').html('');
                                $('#identity_number_patient').removeClass('is-invalid').addClass('is-valid');
                            }



                            if (data.phone_error != '') {
                                $('#phone_error').html(data.phone_error);
                                $('#phone_patient').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#phone_error').html('');
                                $('#phone_patient').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.email_error != '') {
                                $('#email_error').html(data.email_error);
                                $('#email_patient').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#email_error').html('');
                                $('#email_patient').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.job_error != '') {
                                $('#job_error').html(data.job_error);
                                $('#job_patient').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#job_error').html('');
                                $('#job_patient').removeClass('is-invalid').addClass('is-valid');
                            }

                            if (data.address_error != '') {
                                $('#address_error').html(data.address_error);
                                $('#address_patient').removeClass('is-valid').addClass('is-invalid');
                            } else {
                                $('#address_error').html('');
                                $('#address_patient').removeClass('is-invalid').addClass('is-valid');
                            }
                        }
                    }
                }
            });
        });




    });


    function loadDataPatients() {
        $.ajax({
            url: base_url + 'frontoffice/loadDataPatients',
            method: "GET",
            beforeSend: function() {

            },
            success: function(response) {
                $('.tableListPatients').html(response);
                $('#dataTableListPatients').DataTable({
                    responsive: true,
                    "order": [
                        [3, "desc"]
                    ], //or asc 
                    "columnDefs": [{
                        "targets": 3,
                        "type": "date-eu"
                    }],
                });
            }
        });


    }

    function loadDataQueue() {
        $.ajax({
            url: base_url + 'frontoffice/loadDataQueue',
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

    function clearFormAddPatient() {
        $('#name_error').html('');
        $('#name_patient').removeClass('is-valid');
        $('#name_patient').removeClass('is-invalid');

        $('#birth_date_error').html('');
        $('#birth_date_patient').removeClass('is-valid');
        $('#birth_date_patient').removeClass('is-invalid');

        $('#identity_number_error').html('');
        $('#identity_number_patient').removeClass('is-valid');
        $('#identity_number_patient').removeClass('is-invalid');



        $('#phone_error').html('');
        $('#phone_patient').removeClass('is-valid');
        $('#phone_patient').removeClass('is-invalid');

        $('#email_error').html('');
        $('#email_patient').removeClass('is-valid');
        $('#email_patient').removeClass('is-invalid');

        $('#job_error').html('');
        $('#job_patient').removeClass('is-valid');
        $('#job_patient').removeClass('is-invalid');

        $('#address_error').html('');
        $('#address_patient').removeClass('is-valid');
        $('#address_patient').removeClass('is-invalid');
    }
</script>