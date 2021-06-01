<script>
    const base_url = $('body').data('url');

    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('cc14b125ee722dc1a2ea', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('my-event', function(data) {
        updateDataQueue();

        VanillaToasts.create({
            title: 'Success!',
            text: data.msg,
            type: 'success',
            positionClass: 'topRight',
            timeout: 2000
        });
    });

    $(function() {

        //load data queue
        updateDataQueue();

        //load data queue progress
        loadDataQueueProgress();

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
            $('#id_queue').val(id_queue)
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
        $(document).on('change', '#selectPatientsMedicalRecords', function(){
            let id = $('#selectPatientsMedicalRecords option:selected').val();


            $.ajax({
                method: "GET",
                url: base_url + 'doctor/medicalrecord/load_data_medical_records/' + id,
                beforeSend: function() {

                },

                success: function (response) {
                    $('.tablePatientsMedicalRecordsHistory').html(response);
                }
            })
        });

        //print data medical records
        $(document).on('click', '#btnPrintMedicalRecord', function() {
            printMedicalRecord();
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

    function loadDataQueueProgress() {
        $.ajax({
            url: base_url + 'doctor/home/loadDataQueueProgress',
            method: "GET",
            beforeSend: function() {

            },
            success: function(response) {
                $('.tableQueueProgress').html(response);
                $('#dataTableQueueProgress').DataTable({
                    responsive: true,
                });
            }
        });
    }

    function printMedicalRecord()
    {
        window.frames['medical_record_print'].print();
    }
</script>