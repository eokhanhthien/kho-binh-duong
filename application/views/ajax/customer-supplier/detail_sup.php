<div class="breadcrumbs-fixed panel-action">
    <div class="row">
        <div class="customer-act act">
            <div class="col-md-4 col-md-offset-2">
                <div class="left-action text-left clearfix">
                    <h2>Thông tin Nhà cung cấp</h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="right-action text-right">
                    <div class="btn-groups">
                        <button type="button" class="btn btn-primary btn-hide-edit" onclick="cms_edit_cusitem( 'sup')">
                            <i class="fa fa-pencil-square-o"></i> sửa
                        </button>
                        <button type="button" class="btn btn-default btn-hide-edit"
                                onclick="cms_javascript_redirect( cms_javascrip_fullURL() )"><i
                                class="fa fa-arrow-left"></i> Trở về
                        </button>
                        <button type="button" class="btn btn-primary btn-show-edit" style="display:none;"
                                onclick="cms_save_edit_sup()"><i class="fa fa-check"></i> Lưu
                        </button>
                        <button type="button" class="btn btn-default btn-show-edit" style="display:none;"
                                onclick="cms_undo_cusitem('sup')"><i class="fa fa-undo"></i> Hủy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-space orders-space"></div>

<div class="supplier-info col-md-12">
    <?php if (isset($_list_sup) && count($_list_sup)) : ?>
        <div id="item-<?php echo $_list_sup['ID']; ?>" class="supplier-inner tr-item-sup">
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Tên NCC</label>
                        <div class="col-md-8">
                            <?php echo $_list_sup['supplier_name']; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Mã NCC</label>

                        <div class="col-md-8">
                            <?php echo $_list_sup['supplier_code']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Điện thoại</label>

                        <div class="col-md-8">
                            <?php echo ($_list_sup['supplier_phone'] != '') ? $_list_sup['supplier_phone'] : '(chưa có)'; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Email</label>

                        <div class="col-md-8">
                            <?php echo ($_list_sup['supplier_email'] != '') ? $_list_sup['supplier_email'] : '(chưa có)'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Mã số thuế</label>

                        <div class="col-md-8">
                            <?php echo ($_list_sup['tax_code'] != '') ? $_list_sup['tax_code'] : '(chưa có)'; ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Địa chỉ</label>

                        <div class="col-md-8">
                            <?php echo ($_list_sup['supplier_addr'] != '') ? $_list_sup['supplier_addr'] : '(chưa có)'; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Ghi chú</label>

                        <div class="col-md-8">
                            <?php echo ($_list_sup['notes'] != '') ? $_list_sup['notes'] : '(chưa có)'; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="supplier-inner tr-edit-item-sup" style="display: none;">
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Tên NCC</label>
                        <div class="col-md-8">
                            <input type="text" id="supplier_name" class="form-control"
                                   value="<?php echo cms_common_input(isset($_list_sup) ? $_list_sup : [], 'supplier_name'); ?>">
                            <span style="color: red;" class="error error-supplier_name"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Mã NCC</label>
                        <div class="col-md-8">
                            <?php echo $_list_sup['supplier_code']; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Điện thoại</label>

                        <div class="col-md-8">
                            <input type="text" id="supplier_phone" class="form-control"
                                   value="<?php echo cms_common_input(isset($_list_sup) ? $_list_sup : [], 'supplier_phone'); ?>">
                            <span style="color: red;" class="error error-supplier_phone"></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Email</label>
                        <div class="col-md-8">
                            <input type="text" id="supplier_email" class="form-control"
                                   value="<?php echo cms_common_input(isset($_list_sup) ? $_list_sup : [], 'supplier_email'); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Mã số thuế</label>

                        <div class="col-md-8">
                            <input type="text" id="tax_code" class="form-control"
                                   value=" <?php echo cms_common_input(isset($_list_sup) ? $_list_sup : [], 'tax_code'); ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Địa chỉ</label>

                        <div class="col-md-8">
                            <textarea id="supplier_addr"
                                      class="form-control"><?php echo cms_common_input(isset($_list_sup) ? $_list_sup : [], 'supplier_addr'); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12" style="margin-bottom: 10px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="col-md-4 padd-0">Ghi chú</label>

                        <div class="col-md-8">
                            <textarea id="notes"
                                      class="form-control"><?php echo cms_common_input(isset($_list_sup) ? $_list_sup : [], 'notes'); ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="jumbotron text-center" id="img_upload"
                     style="border-radius: 0; margin-bottom: 10px; padding: 15px 20px;">
                    <h3>Upload hình ảnh nhà cung cấp</h3>
                    <small style="font-size: 14px; margin-bottom: 5px; display: inline-block;">(Để
                        tải và hiện thị nhanh, mỗi ảnh lên có dung lượng tối đa 10MB.)
                    </small>
                    <p>
                    <center>
                        <div id='edit_supplier_img_preview' style="display: none;"></div>
                        <form id="edit_supplier_image_upload_form" method="post" enctype="multipart/form-data"
                              action='product/upload_img' autocomplete="off">
                            <div class="file_input_container">
                                <div class="upload_button"><input type="file" name="photo"
                                                                  id="edit_supplier_photo"
                                                                  class="file_input"/></div>
                            </div>
                            <br clear="all">
                        </form>
                    </center>
                    </p>
                </div>
            </div>
        </div>
    <?php else:
    endif;
    ?>
</div>
<div class="col-md-12 padd-0">
    <div class="col-md-4 padd-0">
        <div class="left-action text-left clearfix padd-0">
            <h3 id="input_info" class="padd-0 no-margin" style="margin-top: 10px;"></h3>
        </div>
    </div>
    <div class="col-md-8 padd-0">
        <div class="col-sm-12 padd-0 payment_debt_hide" id="payment_input">
            <div class="col-sm-3 padd-0 line-height34">
                <input type="radio" class="payment-method" name="payment-method" value="1"
                       checked="">
                Tiền mặt &nbsp;
                <input type="radio" class="payment-method" name="payment-method" value="2">
                Thẻ&nbsp;
                <input type="radio" class="payment-method" name="payment-method" value="3">
                CK
            </div>
            <div class="col-md-2 padd-0">
                <input id="payment_note" class="form-control" type="text"
                       placeholder="Ghi chú"
                       style="border-radius: 0 !important;">
            </div>
            <div class="col-md-2 padd-0">
                <input id="payment_date" class="form-control datepk" type="text"
                       placeholder="Ngày chi"
                       style="border-radius: 0 !important;">
            </div>
            <script>$('#payment_date').datetimepicker({
                    autoclose: true
                });
            </script>
            <div class="col-md-2 padd-0">
                <input id="total_payment_money" class="form-control txtMoney" type="text"
                       placeholder="Tổng tiền chi"
                       style="border-radius: 0 !important;">
            </div>
            <div class="col-md-2 padd-0">
                <div class="col-md-12" style="padding: 0px;display: inline-flex">
                    <button type="button" class="btn btn-primary" onclick="save_total_payment_input_in_supplier()"><i
                                class="fa fa-plus"></i> Chi tất cả
                    </button>
                    <button type="button" class="btn" title="Hủy" onclick="cms_paging_input_by_supplier_id(1);">
                        <i class="fa fa-times"
                           style="color: white !important;"></i>
                        Hủy
                    </button>
                </div>
            </div>
        </div>
        <div class="right-action text-right">
            <div class="btn-groups">
                <button type="button" class="btn btn-primary" id="payment_debt_show" onclick="cms_paging_input_debt_by_supplier_id(1)"><i
                        class="fa fa-pencil-square-o"></i> Chi nợ
                </button>
            </div>
        </div>
    </div>
</div>
<div class="inputs-main-body">
</div>
<script>cms_paging_input_by_supplier_id(1);</script>