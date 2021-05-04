<div class="main-content-inner">
    <div class="container-fluid">
        <div class="row">


            <!-- Statistics area start -->
            <div class="col-lg-8 col-md-6 mt-5">
                <div class="card mt-4">
                    <div class="card-body">
                        <h4 class="header-title">List of Patients Today&nbsp;(<?= date('d M Y'); ?>)</h4>

                        <div class="tableQueue">
                            
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistics area end -->
            <!-- Advertising area start -->
            <div class="col-lg-4 col-md-6 mt-5">
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
            </div>
            <!-- Advertising area end -->




        </div>
    </div>
</div>