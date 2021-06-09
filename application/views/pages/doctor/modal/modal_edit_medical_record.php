    <form action="#" method="POST" id="formUpdateMedicalRecord">
        <div class="modal-header">
            <h5 class="modal-title titleModalUpdateMedicalRecord"><?= $title; ?></h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">

            <input type="hidden" name="id_customer" id="id_customer_rm_edit" value="">
            <input type="hidden" name="id_queue" id="id_queue_edit" value="">
            <input type="hidden" name="id_therapies" id="id_therapies_edit" value="<?=$dataMedicalRecord->id_therapies; ?>">
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="anamnesa">Anamnesa</label>
                        <textarea class="form-control" name="anamnesa" id="anamnesa_edit" rows="3" placeholder="Anamnesa"><?= $dataMedicalRecord->anamnesa; ?></textarea>
                        <span id="anamnesa_edit_error"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="pemeriksaan">Pemeriksaan</label>
                        <textarea class="form-control" name="pemeriksaan" id="pemeriksaan_edit" rows="3" placeholder="Pemeriksaan"><?= $dataMedicalRecord->pemeriksaan; ?></textarea>
                        <span id="pemeriksaan_edit_error"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="diagnosa">Diagnosa</label>
                        <textarea class="form-control" name="diagnosa" id="diagnosa_edit" rows="3" placeholder="Diagnosa"><?= $dataMedicalRecord->diagnosa; ?></textarea>
                        <span id="diagnosa_edit_error"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="selectTherapy">
                        <div class="form-group">
                            <label for="therapy">Therapy</label>
                            <select name="therapy[]" id="therapy_edit" class="form-control select2edit" multiple="multiple">
                                <option></option>
                                <?php foreach ($dataTherapy as $row) : ?>
                                    <option value="<?= $row->id; ?>" <?php foreach ($dataTherapies as $row2) : ?> <?= $row2->id_product == $row->id ? "selected" : "" ?> <?php endforeach ?>>
                                        <?= $row->title; ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                            <span id="therapy_edit_error"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="note">Note's Therapy (Optional)</label>
                        <textarea class="form-control" name="note" id="note" rows="3" placeholder="Note"><?= $note->note; ?></textarea>

                    </div>
                </div>
            </div>





        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-sm btn-rounded btn-secondary" data-dismiss="modal">Close</button>
            <!-- <button type="submit" class="btn btn-sm btn-rounded btn-purple btn-pay">Pay</button> -->
            <button type="submit" class="btn btn-sm btn-rounded btn-hers">Submit</button>
        </div>
    </form>