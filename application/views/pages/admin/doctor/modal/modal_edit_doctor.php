    <form action="#" method="POST" id="formEditDoctor">
        <div class="modal-header">
            <h5 class="modal-title"><?= $title; ?></h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="id" value="<?= $getDoctor->id; ?>">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="name_doctor">Doctor Name</label>
                        <input type="text" class="form-control" name="name" id="name_doctor" aria-describedby="emailHelp" placeholder="Doctor Name" value="<?= $getDoctor->name; ?>">
                        <span id="name_error"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="birth_date_doctor">Birth of Date</label>
                        <input type="text" class="form-control date_birth_edit" name="birth_date" id="birth_date_doctor" aria-describedby="emailHelp" placeholder="dd/mm/yyyy" autocomplete="off" value="<?= date_format(new DateTime($getDoctor->birth_date), "d/m/Y"); ?>">
                        <span id="birth_date_error"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="identity_number_doctor">Identity Number (KTP)</label>
                        <input type="text" class="form-control" name="identity_number" id="identity_number_doctor" aria-describedby="emailHelp" placeholder="ex: 137106......" value="<?= $getDoctor->identity_number; ?>">
                        <span id="identity_number_error"></span>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="idi_number_doctor">IDI Number</label>
                        <input type="text" class="form-control" name="idi_number" id="idi_number_doctor" aria-describedby="emailHelp" placeholder="IDI Number" value="<?= $getDoctor->idi_number; ?>">
                        <span id="idi_number_error"></span>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-group">
                        <label for="sip_number_doctor">SIP Number</label>
                        <input type="text" class="form-control" name="sip_number" id="sip_number_doctor" aria-describedby="emailHelp" placeholder="SIP Number" value="<?= $getDoctor->sip_number; ?>">
                        <span id="sip_number_error"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="phone_doctor">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone_doctor" aria-describedby="emailHelp" placeholder="Phone Number" value="<?= $getDoctor->phone; ?>">
                        <span id="phone_error"></span>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label for="email_doctor">Email</label>
                        <input type="email" class="form-control" name="email" id="email_doctor" aria-describedby="emailHelp" placeholder="Email Address" value="<?= $getDoctor->email; ?>">
                        <span id="email_error"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="address_doctor">Address</label>
                        <textarea class="form-control" name="address" id="address_doctor" rows="3" placeholder="Address"><?= $getDoctor->address; ?></textarea>
                        <span id="address_error"></span>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-rounded btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-rounded btn-hers">Update Data</button>
        </div>
    </form>