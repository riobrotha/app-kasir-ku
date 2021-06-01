<div class="main-content-inner">
    <div class="container-fluid">
        <div class="row">



            <div class="col-lg-6 col-md-6 mt-5">
                <div class="card mt-4">
                    <div class="card-body">
                        <h4 class="header-title mb-3">List of Patients Today&nbsp;(<?= date('d M Y'); ?>)</h4>

                        <p class="mb-2">Filter By Status :</p>
                        <select class="form-control form-control-sm w-25" id="filterStatusQueue">
                            <option value="waiting" class="text-danger">Waiting</option>
                            <option value="on_consult" class="text-dark">On Consult</option>
                            <option value="on_progress" class="text-warning">On Progress</option>
                            <option value="on_progress" class="text-info">Paid</option>
                            <option value="on_progress" class="text-success">Done</option>
                        </select>
                        <div class="tableQueue">

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 mt-5">
                <div class="card mt-4">
                    <div class="card-body">
                        <h4 class="header-title mb-3">On Progress Patients&nbsp;(<?= date('d M Y'); ?>)</h4>

                        <div class="tableQueueProgress">
                            
                        </div>
                        
                    </div>
                </div>
            </div>
            <!-- <div class="col-lg-4 col-md-6 mt-5">
                <div class="card mt-4">

                    <div class="card-body">
                        <div class="avatar-doctor">
                            <div class="text-center">

                                <img src="<?= base_url() ?>/assets/images/author/avatar_doctor.jfif" width="120" class="mx-auto" style="border-radius: 50%; padding-top: 10px; padding-bottom: 10px;" alt="" srcset="">


                                <h4><?= $dataDoctor->name; ?></h4>
                                <h6 class="mt-2"><?= $dataDoctor->email; ?></h6>
                                <button class="btn btn-sm btn-rounded btn-hers-primary w-10 mt-3"><i class="ti-pencil-alt mr-1"></i>Edit Profile</button>
                                <a href="<?= base_url("auth/logout"); ?>" class="btn btn-sm btn-rounded btn-hers-outline ml-2 w-10 mt-3"><i class="fa fa-sign-out mr-1"></i>Log Out</a>
                            </div>

                            <div class="table-responsive" style="margin-top: 60px; padding-top: 10px;">
                                <table class="table text-center">
                                    <tbody>
                                        <tr>
                                            <td style="width: 30%;">IDI Number</td>
                                            <td><?= $dataDoctor->idi_number; ?></td>

                                        </tr>
                                        <tr>
                                            <td style="width: 30%;">SIP Number</td>
                                            <td><?= $dataDoctor->sip_number; ?></td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                        </div>


                    </div>
                </div>
            </div> -->




        </div>
    </div>
</div>