<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel" style="text-transform: uppercase;"><i class="fa fa-user"></i>
        Cập nhật phiếu chi</h4>
</div>
<div class="modal-body">
    <div class="form-horizontal">
        <div class="form-group">
            <div class="col-sm-3">
                <label for="group-name">Ghi chú</label>
            </div>
            <div class="col-sm-9">
                <input type="text" id="edit_notes" class="form-control"
                       placeholder="Nhập ghi chú" value="<?php echo $_detail_payment['notes'] ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <label for="group-name">Hình thức chi</label>
            </div>
            <div class="col-sm-9">
                <select class="form-control" id="edit_type_id">
                    <?php
                    $list = cms_getListPaymentType();
                    foreach ($list as $key=>$item) : ?>
                        <option <?php if($_detail_payment['type_id']== $key) echo 'selected'?> value="<?php echo $key; ?>"><?php echo $item; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <label for="group-name">Số tiền chi</label>
            </div>
            <div class="col-sm-9">
                <input type="text" id="edit_total_money" class="txtMoney form-control"
                       placeholder="Nhập số tiền chi" value="<?php echo cms_encode_currency_format($_detail_payment['total_money']) ?>">
                <span style="color: red; font-style: italic;" class="error error-edit-total-money"></span>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <label for="edit_payment_date">Ngày chi</label>
            </div>
            <div class="col-sm-9">
                <input type="text" id="edit_payment_date" name="payment_date"
                       class="form-control txttimes datepk" value="<?php echo $_detail_payment['payment_date'] ?>" placeholder="Hôm nay">
                <span style="color: red;" class="error error-edit-payment_date"></span>
            </div>
            <script>
                $('#edit_payment_date').datetimepicker({
                    autoclose: true
                });
            </script>
        </div>
        <div class="form-group">
            <div class="col-md-12">
                <div class="zoomin jumbotron text-center" id="img_upload"
                     style="border-radius: 0; margin-bottom: 10px; padding: 15px 20px;">
                    <img src="public/templates/uploads/<?php if (isset($_detail_payment['payment_image'])) echo $_detail_payment['payment_image'] ?>" height="200">
                    <h3>Upload hình ảnh chứng từ liên quan</h3>
                    <small style="font-size: 14px; margin-bottom: 5px; display: inline-block;">(Để
                        tải và hiện thị nhanh, mỗi ảnh lên có dung lượng tối đa 10MB.)
                    </small>
                    <p>
                    <center>
                        <div id='edit_payment_img_preview' style="display: none;"></div>
                        <form id="edit_payment_image_upload_form" method="post" enctype="multipart/form-data"
                              action='product/upload_img' autocomplete="off">
                            <div class="file_input_container">
                                <div class="upload_button"><input type="file" name="photo"
                                                                  id="edit_payment_photo"
                                                                  class="file_input"/></div>
                            </div>
                            <br clear="all">
                        </form>
                    </center>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary btn-sm" onclick="cms_update_payment(<?php echo $_detail_payment['ID'].','.$page ?>);" ><i class="fa fa-check"></i> Cập nhật
    </button>
    <button type="button" class="btn btn-default btn-sm btn-close" data-dismiss="modal"><i
                class="fa fa-undo"></i> Bỏ qua
    </button>
</div>
<script>
    $('#edit_payment_photo').on('change', function () {
        $("#edit_payment_img_preview").html('');
        $("#edit_payment_image_upload_form").ajaxForm({
            target: '#edit_payment_img_preview'
        }).submit();
    });
</script>