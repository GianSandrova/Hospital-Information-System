    <!-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->
    <?php

    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    ?>

    <div class="container">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Manage <b>Fasilitas Kesehatan</b></h2>
                    </div>
                    <div class="col-sm-6">
                        <a href="#addFaskesModal" class="btn btn-success" data-toggle="modal"><i class="fa-solid fa-plus"></i><span>Add New Faskes</span></a>
                        <a href="#deleteFaskesModal" class="btn btn-danger" data-toggle="modal"><i class="fa-solid fa-trash" style="color: #ff0000;"></i><span>Delete</span></a>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>
                            <span class="custom-checkbox">
                                <input type="checkbox" id="selectAll">
                                <label for="selectAll"></label>
                            </span>
                        </th>
                        <th>Nama Faskes</th>
                        <th>Alamat</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($faskesList as $f): ?>
                        <tr>
                            <td>
                                <span class="custom-checkbox">
                                    <input type="checkbox" id="checkbox<?= $f->id ?>" name="options[]" value="<?= $f->id ?>">
                                    <label for="checkbox<?= $f->id ?>"></label>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($f->nama_faskes) ?></td>
                            <td><?= htmlspecialchars($f->alamat) ?></td>
                            <td>
                                <span><?= Html::a('View',['get-faskes','id' => $f->id],['class'=>'label label-primary'])?> </span>
                                <a href="#updateFaskesModal" class="edit" data-toggle="modal" data-id="<?= $f->id ?>"><i class="fa-solid fa-pen-to-square"></i></a>
                                <a href="#deleteFaskesModal" class="delete" data-toggle="modal" data-id="<?= $f->id ?>"><i class="fa-solid fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Add Modal HTML -->
    <div id="addFaskesModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <?php if (Yii::$app->session->hasFlash('error')): ?>
                    <script>
                        $(document).ready(function() {
                            $('#addFaskesModal').modal('show');
                        });
                    </script>
                <?php endif; ?>

                <?php $form = ActiveForm::begin([
                    'id' => 'add-faskes-form',
                    'action' => ['create-faskes']
                ]) ?>
                <div class="modal-header">
                    <h4 class="modal-title">Add Faskes</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <?= $form->field($newFaskes, 'nama_faskes')->textInput(['class' => 'form-control', 'required' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($newFaskes, 'alamat')->textarea(['class' => 'form-control', 'required' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($newFaskes, 'deskripsi')->textarea(['class' => 'form-control', 'required' => true]) ?>
                    </div>
                    <div class="form-group">
                        <?= $form->field($newFaskes, 'logo')->fileInput() ?>
                    </div>
                    <?= $form->field($newFaskes, 'is_aktif')->checkbox() ?>

                    <?= $form->field($newFaskes, 'is_bridging')->checkbox() ?>

                    <?= $form->field($newFaskes, 'ip_address')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($newFaskes, 'user_api')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($newFaskes, 'password_api')->passwordInput(['maxlength' => true]) ?>

                    <?= $form->field($newFaskes, 'longtitud')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($newFaskes, 'latitude')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <?= Html::submitButton('Add', ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <!-- Update Faskes Modal -->
    <div id="updateFaskesModal" class="modal fade">
        <script>
            $('#updateFaskesModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var faskesId = button.data('faskes-id');
                var modal = $(this);

                // Lakukan AJAX request untuk mendapatkan data Faskes
                $.get('/site/get-faskes', {
                    id: faskesId
                }, function(data) {
                    // Isi form dengan data yang diterima
                    modal.find('#faskes-nama_faskes').val(data.nama_faskes);
                    modal.find('#faskes-alamat').val(data.alamat);
                    modal.find('#faskes-deskripsi').val(data.deskripsi);
                    modal.find('#faskes-is_aktif').prop('checked', data.is_aktif == 1);
                    modal.find('#faskes-is_bridging').prop('checked', data.is_bridging == 1);
                    modal.find('#faskes-ip_address').val(data.ip_address);
                    modal.find('#faskes-user_api').val(data.user_api);
                    modal.find('#faskes-longtitud').val(data.longtitud);
                    modal.find('#faskes-latitude').val(data.latitude);

                    // Update action URL
                    modal.find('form').attr('action', '/site/update-faskes?id=' + faskesId);

                    // Show current logo if exists
                    if (data.logo) {
                        modal.find('#current-logo').html('<img src=\"/uploads/' + data.logo + '\" alt=\"Current Logo\" style=\"max-width: 100px;\">');
                    } else {
                        modal.find('#current-logo').empty();
                    }
                });
            });
            $(document).ready(function() {
                $('#updateFaskesModal').modal('show');
            });
        </script>

        <div class="modal-dialog">
            <div class="modal-content">
                <?php $form = ActiveForm::begin([
                    'id' => 'update-faskes-form',
                    'action' => ['update-faskes', 'id' => ''],  // ID will be set by JavaScript
                ]) ?>
                <div class="modal-header">
                    <h4 class="modal-title">Update Faskes</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <?= $form->field($newFaskes, 'nama_faskes')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($newFaskes, 'alamat')->textarea(['rows' => 6]) ?>
                    <?= $form->field($newFaskes, 'deskripsi')->textarea(['rows' => 6]) ?>
                    <?= $form->field($newFaskes, 'logo')->fileInput() ?>
                    <div id="current-logo"></div>
                    <?= $form->field($newFaskes, 'is_aktif')->checkbox() ?>
                    <?= $form->field($newFaskes, 'is_bridging')->checkbox() ?>
                    <?= $form->field($newFaskes, 'ip_address')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($newFaskes, 'user_api')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($newFaskes, 'password_api')->passwordInput(['maxlength' => true]) ?>
                    <?= $form->field($newFaskes, 'longtitud')->textInput(['maxlength' => true]) ?>
                    <?= $form->field($newFaskes, 'latitude')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <!-- Delete Modal HTML -->
    <div id="deleteFaskesModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?= \yii\helpers\Url::to(['get/delete']) ?>" method="post">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Faskes</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this record?</p>
                        <p class="text-warning"><small>This action cannot be undone.</small></p>
                        <input type="hidden" name="id" id="delete-id">
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-danger" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>


    </html>