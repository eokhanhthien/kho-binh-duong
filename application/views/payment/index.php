<div class="orders">
    <div class="breadcrumbs-fixed panel-action">
        <div class="row">
            <div class="orders-act">
                <div class="col-md-4 col-md-offset-2">
                    <div class="left-action text-left clearfix">
                        <h2>Danh sách phiếu chi</h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right-action text-right">
                        <div class="btn-groups">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-payment">Tạo mới
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-space orders-space"></div>
    <div class="orders-content">
        <div class="product-sear panel-sear">
            <div class="form-group col-md-3 padd-0">
                <input type="text" class="form-control" id="payment-search"
                       placeholder="Nhập mã phiếu để tìm kiếm">
            </div>
            <div class="form-group col-md-9 padd-0" style="padding-left: 5px;">
                <div class="col-md-8 padd-0">
                    <div class="col-md-4 padd-0">
                        <select class="form-control" id="search-option-1">
                            <option value="-1">Hình thức chi</option>
                            <?php
                            $list = cms_getListPaymentType();
                            foreach ($list as $key=>$item) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5 padd-0" style="padding-left: 5px;">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="input-sm form-control" id="search-date-from" placeholder="Từ ngày"
                                   name="start"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" id="search-date-to" placeholder="Đến ngày"
                                   name="end"/>
                        </div>
                    </div>
                    <div class="col-md-3 padd-0" style="padding-left: 5px;">
                        <button style="box-shadow: none;" type="button" class="btn btn-primary btn-large"
                                onclick="cms_paging_payment(1)"><i class="fa fa-search"></i> Tìm kiếm
                        </button>
                    </div>
                </div>
                <div class="col-md-4 padd-0" style="padding-left: 5px;">
                    <div class="btn-group order-btn-calendar">
                        <button type="button" onclick="cms_payment_week()" class="btn btn-default">Tuần</button>
                        <button type="button" onclick="cms_payment_month()" class="btn btn-default">Tháng</button>
                        <button type="button" onclick="cms_payment_quarter()" class="btn btn-default">Quý</button>
                        <button type="button" onclick="cms_payment_year()" class="btn btn-default">Năm</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="payment-main-body">
        </div>
    </div>
</div>

<!-- Start create group -->
<div class="modal fade" id="create-payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="text-transform: uppercase;"><i class="fa fa-user"></i>
                    Tạo phiếu chi</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Ghi chú</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="notes" class="form-control"
                                   placeholder="Nhập ghi chú">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Hình thức chi</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-control" id="type_id">
                                <?php
                                $list = cms_getListPaymentType();
                                foreach ($list as $key=>$item) : ?>
                                    <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Số tiền chi</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="total_money" class="txtMoney form-control"
                                   placeholder="Nhập số tiền chi">
                            <span style="color: red; font-style: italic;" class="error error-total-money"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="payment_date">Ngày chi</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="payment_date" name="payment_date"
                                   class="form-control txttimes datepk" value="" placeholder="Hôm nay">
                            <span style="color: red;" class="error error-payment_date"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="jumbotron text-center" id="img_upload"
                                 style="border-radius: 0; margin-bottom: 10px; padding: 15px 20px;">
                                <h3>Upload hình ảnh chứng từ liên quan</h3>
                                <small style="font-size: 14px; margin-bottom: 5px; display: inline-block;">(Để
                                    tải và hiện thị nhanh, mỗi ảnh lên có dung lượng tối đa 10MB.)
                                </small>
                                <p>
                                <center>
                                    <div id='payment_img_preview' style="display: none;"></div>
                                    <form id="payment_image_upload_form" method="post" enctype="multipart/form-data"
                                          action='product/upload_img' autocomplete="off">
                                        <div class="file_input_container">
                                            <div class="upload_button"><input type="file" name="photo"
                                                                              id="payment_photo"
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
                <button type="button" class="btn btn-primary btn-sm" onclick="cms_save_payment();" ><i class="fa fa-check"></i> Lưu
                </button>
                <button type="button" class="btn btn-default btn-sm btn-close" data-dismiss="modal"><i
                        class="fa fa-undo"></i> Bỏ qua
                </button>
            </div>
        </div>
    </div>
</div>
<!-- end create function -->

<!-- Start create group -->
<div class="modal fade" id="update-payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div id="update_payment_content" class="modal-content">

        </div>
    </div>
</div>
<!-- end create function -->