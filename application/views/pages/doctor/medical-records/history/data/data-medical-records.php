
<?php
//hitung umur

$birthDate = new DateTime($patient->birth_date);
$today = new DateTime();

if ($birthDate < $today) {
    $umur = $today->diff($birthDate)->y;
}


?>


<div class="text-right">
    <button class="btn btn-rounded btn-sm btn-hers-primary" id="btnPrintMedicalRecord"><i class="ti-printer mr-2"></i>Print</button>
</div>
<div class="text-center mt-4">
    <h3>Medical Record</h3>
</div>

<div class="data-patient mt-5">
    <div class="table-responsive">
        <table class="table table-striped">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td><?= $patient->name; ?></td>
                <td>No RM</td>
                <td>:</td>
                <td><?= isset($noRm->rm_number) ? $noRm->rm_number : "-"; ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td>:</td>
                <td><?= $patient->address; ?></td>
                <td>Email</td>
                <td>:</td>
                <td><?= strtolower($patient->email); ?></td>
            </tr>
            <tr>
                <td>DoB/Age</td>
                <td>:</td>
                <td><?= $umur; ?>&nbsp;Years Old</td>
                <td>Job</td>
                <td>:</td>
                <td><?= ucwords($patient->job); ?></td>
            </tr>
            <tr>
                <td>KTP</td>
                <td>:</td>
                <td><?= $patient->identity_number; ?></td>
                <td>Phone/WA</td>
                <td>:</td>
                <td><?= $patient->phone; ?></td>
            </tr>

        </table>
    </div>

</div>

<div class="data-tables datatable mt-5">
    <table id="dataTablePatientsMedicalRecordsHistory" class="text-center table table-hover">
        <thead>
            <tr>
                <th>Date</th>
                <th>Anamnese</th>
                <th>Diagnose</th>
                <th>Therapy</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($getPatients as $row) : ?>
                <tr>
                    <td><?= date_format(new DateTime($row->created_at), 'd/m/Y') ?></td>
                    <td><?= $row->anamnesa; ?></td>
                    <td><?= $row->diagnosa; ?></td>
                    <td>
                        <?php foreach ($therapies as $row2) : ?>
                            <span class="badge badge-pill badge-info" style="font-size: 13px;"><?= $row2->title; ?></span>
                        <?php endforeach ?>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<iframe src="<?= base_url("doctor/medicalrecord/print/$id_customer") ?>" name="medical_record_print" id="medical_record_print" style="display: none;" frameborder="0"></iframe>