<div class="data-tables datatable mt-4">
    <table id="dataTableQueue" class="text-center table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Created At</th>
                <th></th>
                <th></th>

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
                    <button class="btn btn-xs btn-rounded btn-hers" data-toggle="modal" data-target="#modalAddMedicalRecord"><i class="fa fa-plus mr-1"></i>Medical Record</button>
                    <?php  endif ?>
                    </td>
                    <td> 
                        <?php
                        if ($i == 1) :
                        ?>
                        <select name="status_queue" id="status_queue" class="form-control form-control-sm">
                            <option value="waiting" class="text-danger"><span class="text-danger">Waiting</span></option>
                            <option value="progress"><span class="text-warning">Progress</span></option>
                            <option value="paid"><span class="text-info">Paid</span></option>
                            <option value="done"><span class="text-success">Done</span></option>
                            
                        </select>
                        <?php $i++; endif ?>
                    </td>


                </tr>


            <?php endforeach ?>
        </tbody>
    </table>
</div>