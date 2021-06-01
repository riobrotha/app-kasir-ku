<div class="data-tables datatable mt-4">
    <table id="dataTableQueue" class="text-center table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Created At</th>
                <th>Action</th>
                <th>Status</th>

            </tr>
        </thead>
        <tbody>
            <?php
            $i = 1;
            foreach ($queue as $row) : ?>
                <tr id="rowCustomer" data-id="<?= $row->id; ?>" data-name="<?= $row->name; ?>">
                    <td><?= $row->id; ?></td>
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
                    <td><?= $row->created_at; ?></td>
                    <td>
                    <?php if ($i == 1) : ?>
                    <button class="btn btn-xs btn-rounded btn-hers btnAddMedicalRecord" data-toggle="modal" data-target="#modalAddMedicalRecord" data-id-queue="<?= $row->id; ?>" data-id="<?= $row->id_customer ?>"><i class="fa fa-plus mr-1"></i>Medical Record</button>
                    <?php $i++;  endif ?>
                    </td>
                    <td>
                        <select name="status_queue" id="status_queue" class="form-control form-control-sm" disabled>
                            <option value="waiting" class="text-danger" <?= $row->status == "waiting" ? "selected" : "" ?>><span class="text-danger">Waiting</span></option>
                            <option value="on_consult" class="text-grey" <?= $row->status == "on_consult" ? "selected" : "" ?>><span class="text-warning">On Consult</span></option>
                            <option value="on_progress" class="text-warning" <?= $row->status == "on_progress" ? "selected" : "" ?>><span class="text-warning">On Progress</span></option>
                            <option value="paid" class="text-info" <?= $row->status == "paid" ? "selected" : "" ?>><span class="text-info">Paid</span></option>
                            <option value="done" class="text-success" <?= $row->status == "done" ? "selected" : "" ?>><span class="text-success">Done</span></option>
                            
                        </select>
                        
                    </td>


                </tr>


            <?php endforeach ?>
        </tbody>
    </table>
</div>