<?php $this->load->view('layouts/_alert'); ?>
<div class="data-tables datatable mt-4">
    <table id="dataTable3" class="text-center table table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Created At</th>
                

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($customer as $row) : ?>
                <tr id="rowCustomer" data-id="<?= $row->id; ?>" data-name="<?= $row->name; ?>">
                    <td>
                        <div class="data-space<?= $row->id; ?>">
                            <i class="fa fa-user mr-2"></i><span class="customer-name"><?= $row->name; ?></span>
                        </div>
                        <div class="edit-space<?= $row->id; ?>" style="display: none;">
                            <input type="text" class="form-control form-control-sm" value="<?= $row->name; ?>" id="nameEditCustomer<?= $row->id; ?>">
                        </div>
                    </td>
                    <td>
                        <div class="data-space<?= $row->id; ?>">
                            <i class="ti-mobile mr-2"></i><span class="customer-phone"><?= $row->phone; ?></span>
                        </div>
                        <div class="edit-space<?= $row->id; ?>" style="display: none;">
                            <input type="text" class="form-control form-control-sm" value="<?= $row->phone; ?>" id="phoneEditCustomer<?= $row->id; ?>">
                        </div>
                    </td>
                    <td>
                        <div class="data-space<?= $row->id; ?>">
                            <i class="ti-email mr-2"></i><span class="customer-email"><?= $row->email; ?></span>
                        </div>
                        <div class="edit-space<?= $row->id; ?>" style="display: none;">
                            <input type="text" class="form-control form-control-sm" value="<?= $row->email; ?>" id="emailEditCustomer<?= $row->id; ?>">
                        </div>
                    </td>
                    <td><?= $row->created_at; ?></td>
                    <!-- <td><button class="btn btn-xs btn-purple btn-rounded btnSubmitEditCustomer" id="btnSubmitEditCustomer<?= $row->id; ?>" data-id="<?= $row->id; ?>" style="display: none;"><i class="fa fa-check" style="font-size: 18px;"></i></button></td>
                    <td>
                        <div class="edit-btn-space<?= $row->id; ?>">
                            <i class="fa fa-chevron-right" id="btnEditCustomer" style="color: #6a56a5; font-size: 18px; cursor: pointer;" data-id="<?= $row->id; ?>"></i>
                        </div>
                    </td> -->

                </tr>


            <?php endforeach ?>
        </tbody>
    </table>
</div>