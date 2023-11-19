<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.6/select2.min.css">
<style>
    .select2-choice {
        border: none !important;
        background: none !important;
    }

    .select2-arrow {
        display: none !important;
    }
</style>
<div class="orders">
    <div class="breadcrumbs-fixed panel-action">
        <div class="row">
            <div class="orders-act">
                <div class="col-md-4 col-md-offset-2">
                    <div class="left-action text-left clearfix">
                        <h2>Danh sách bảng lương</h2>
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
                <input type="text" class="form-control" id="payment-search" placeholder="Nhập mã phiếu để tìm kiếm">
            </div>
            <div class="form-group col-md-9 padd-0" style="padding-left: 5px;">
                <div class="col-md-8 padd-0">
                    <div class="col-md-4 padd-0">
                        <select class="form-control" id="search-option-1">
                            <option value="-1">Hình thức chi</option>
                            <?php
                            $list = cms_getListPaymentType();
                            foreach ($list as $key => $item) : ?>
                                <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-5 padd-0" style="padding-left: 5px;">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="input-sm form-control" id="search-date-from" placeholder="Từ ngày" name="start" />
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" id="search-date-to" placeholder="Đến ngày" name="end" />
                        </div>
                    </div>
                    <div class="col-md-3 padd-0" style="padding-left: 5px;">
                        <button style="box-shadow: none;" type="button" class="btn btn-primary btn-large" onclick="cms_paging_payment(1)"><i class="fa fa-search"></i> Tìm kiếm
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel" style="text-transform: uppercase;"><i class="fa fa-user"></i>
                    Tạo bảng lương</h4>
            </div>
            <div class="modal-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Số tiền nhập</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="money_import" class="txtMoney form-control" placeholder="Nhập số tiền nhập">
                            <span style="color: red; font-style: italic;" class="error error-money-import"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Số tiền thực</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="money_real" class="txtMoney form-control" placeholder="Nhập số tiền thực">
                            <span style="color: red; font-style: italic;" class="error error-money-real"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Tên nhân viên nhập</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-control" id="employee_id">
                                <?php
                                $list = cms_getListPaymentType();
                                foreach ($list as $key => $item) : ?>
                                    <option value="<?php echo $key; ?>"><?php echo $item; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span style="color: red; font-style: italic;" class="error error-employee"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="payroll_date">Ngày chi</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" id="payroll_date" name="payroll_date" class="form-control txttimes datepk" value="" placeholder="Hôm nay">
                            <span style="color: red;" class="error error-payroll_date"></span>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Số phiếu</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="number" id="ticket_number" class="form-control" placeholder="Nhập số phiếu">
                        </div>
                    </div> -->
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Hình thức thanh toán</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-control" id="payment_method">
                                <option value="1">Chuyển khoản</option>
                                <option value="2">Công nợ</option>
                                <option value="3">Tiền mặt</option>
                            </select>
                        </div>
                        <span style="color: red; font-style: italic;" class="error error-payment-method"></span>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Trạng thái</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-control" id="status">
                                <option value="1">Thành công</option>
                                <option value="2">Chưa nộp</option>

                            </select>
                            <span style="color: red; font-style: italic;" class="error error-status"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Hoa hồng</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-control" id="commission">
                                <?php for ($i = 10; $i <= 100; $i = $i + 10) { ?>
                                    <option value="<?= $i ?>"><?= $i ?>%</option>
                                <?php } ?>
                            </select>
                            <span style="color: red; font-style: italic;" class="error error-commission"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Tên khách hàng</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-control" id="customer_id">
                                <option value="">--- Chọn khách hàng ---</option>
                                <?php $customers = cms_getListCustomer();
                                foreach ($customers as $customer) { ?>
                                    <option value="<?= $customer['ID'] ?>"><?= $customer['customer_name'] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span style="color: red; font-style: italic;" class="error error-customer_id"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">SDT khách hàng</label>
                        </div>
                        <div class="col-sm-9">
                            <select class="form-control" id="customer_phones">
                                <option value="">--- Chọn SĐT khách hàng ---</option>
                                <?php $customers = cms_getListCustomer();
                                foreach ($customers as $customer) { ?>
                                    <option value="<?= $customer['ID'] ?>"><?= $customer['customer_phone'] ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label for="group-name">Ghi chú</label>
                        </div>
                        <div class="col-sm-9">
                            <textarea name="" class="form-control" id="note" cols="30" rows="4"></textarea>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" onclick="cms_save_payroll();"><i class="fa fa-check"></i> Lưu
                </button>
                <button type="button" class="btn btn-default btn-sm btn-close" data-dismiss="modal"><i class="fa fa-undo"></i> Bỏ qua
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/3.4.6/select2.min.js"></script>
<script>
    $('#customer_id').select2();
    $('#customer_phones').select2();
    $(function() {
        $('#customer_id').on('change', function() {
            let customer_id = $(this).val();
            $('#customer_phones').val(customer_id).trigger("change");
        });

        $('#customer_phones').on('change', function() {
            let customer_id = $(this).val();
            $('#customer_id').val(customer_id).trigger("change");
        });
    });
</script>