<div class="modal fade bd-example-modal-lg" id="modalAddMedicalRecord">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titleModalAddMedicalRecord">Add Medical Record Form</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="#" method="POST" id="formAddMedicalRecord">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="medical_record_number">Medical Record Number</label>
                                <input type="text" class="form-control" name="medical_record_number" id="medical_record_number" aria-describedby="medicalRecord" placeholder="Medical Record Number" value="" readonly>

                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="anamnesa">Anamnesa</label>
                                <textarea class="form-control" name="anamnesa" id="anamnesa" rows="3" placeholder="Anamnesa"></textarea>
                                <span id="anamnesa_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="pemeriksaan">Pemeriksaan</label>
                                <textarea class="form-control" name="pemeriksaan" id="pemeriksaan" rows="3" placeholder="Pemeriksaan"></textarea>
                                <span id="pemeriksaan_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="diagnosa">Diagnosa</label>
                                <textarea class="form-control" name="diagnosa" id="diagnosa" rows="3" placeholder="Diagnosa"></textarea>
                                <span id="diagnosa_error"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="teraphy"></label>
                                <select name="teraphy" id="teraphy" class="form-control">

                                </select>
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
        </div>
    </div>
</div>