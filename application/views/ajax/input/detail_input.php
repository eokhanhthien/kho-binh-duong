<div class="breadcrumbs-fixed panel-action">
    <div class="row">
        <div class="orders-act">
            <div class="col-md-4 col-md-offset-2">
                <div class="left-action text-left clearfix">
                    <h2>Phiếu nhập &raquo;<span
                            style="font-style: italic; font-weight: 400; font-size: 16px;"><?php echo $data['_input']['input_code']; ?></span>
                    </h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="right-action text-right">
                    <div class="btn-groups">
                        <button type="button" class="btn btn-primary"  onclick="cms_vsell_input();"><i
                                class="fa fa-plus"></i> Tạo phiếu nhập
                        </button>
                        <button type="button" class="btn btn-primary"  onclick="cms_print_input(3,<?php echo $data['_input']['ID']; ?>)"><i class="fa fa-print"></i> In
                            phiếu nhập
                        </button>
                        <button type="button" class="btn btn-default"
                                onclick="cms_javascript_redirect( cms_javascrip_fullURL() )"><i
                                class="fa fa-arrow-left"></i> Hủy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="main-space orders-space"></div>

<div class="orders-content">
    <div class="row">
        <div class="col-md-7">
            <table class="table table-bordered table-striped" style="margin-top: 30px;">
                <thead>
                <tr>
                    <th class="text-center">STT</th>
                    <th>Mã hàng</th>
                    <th>Tên sản phẩm</th>
                    <th class="text-center">Hình ảnh</th>
                    <th class="text-center">Số lượng</th>
                    <th class="text-center">ĐVT</th>
                    <th class="text-center">Giá nhập</th>
                    <th class="text-center">Thành tiền</th>
                </tr>
                </thead>
                <tbody>
                <?php if (isset($_list_products) && count($_list_products)) :
                    $nstt = 1;
                    foreach ($_list_products as $product) :
                        ?>
                        <tr data-id="<?php echo $product['ID']; ?>">
                            <td class="text-center"><?php echo $nstt++; ?></td>
                            <td><?php echo $product['prd_code']; ?></td>
                            <td><?php echo $product['prd_name']; ?></td>
                            <td class="text-center zoomin"><img height="30"
                                                                src="public/templates/uploads/<?php echo $product['prd_image_url']; ?>">
                            </td>
                            <td class="text-center" style="max-width: 30px;"><?php echo $product['quantity']; ?> </td>
                            <td class="text-center"><?php echo $product['prd_unit_name']; ?> </td>
                            <td class="text-center price-order"><?php echo cms_encode_currency_format($product['price']); ?></td>
                            <td class="text-center total-money"><?php echo cms_encode_currency_format($product['price'] * $product['quantity']); ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
        <div class="col-md-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="morder-info" style="padding: 4px;">
                        <div class="tab-contents" style="padding: 8px 6px;">
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-0" >
                                    <label>Mã phiếu</label>
                                </div>
                                <div class="col-md-8">
                                    <?php echo $data['_input']['input_code']; ?>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-0">
                                    <label>Nhà cung cấp</label>
                                </div>
                                <div class="col-md-8" style="font-style: italic;">
                                    <?php echo cms_getNamesupplierbyID($data['_input']['supplier_id']); ?>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-0">
                                    <label>Kho nhập</label>
                                </div>
                                <div class="col-md-7">
                                    <?php echo cms_getNamestockbyID($data['_input']['store_id']); ?>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-0">
                                    <label>Ngày nhập</label>
                                </div>
                                <div class="col-md-8">
                                    <?php echo ($data['_input']['input_date'] != '0000-00-00 00:00:00') ? gmdate("H:i d/m/Y", strtotime(str_replace('-', '/', $data['_input']['input_date'])) + 7 * 3600) : '-'; ?>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-0">
                                    <label>Người nhập</label>
                                </div>
                                <div class="col-md-8">
                                    <?php echo cms_getNameAuthbyID($data['_input']['user_init']); ?>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-0">
                                    <label>Ghi chú</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea readonly id="note-order" cols="" class="form-control" rows="3"
                                              style="border-radius: 0;"><?php echo $data['_input']['notes']; ?></textarea>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <ul class="nav nav-tabs tab-setting" role="tablist">
                        <li role="presentation" class="active"><a href="#payment-detail" aria-controls="home" role="tab"
                                                                  data-toggle="tab"><i
                                    class="fa fa-user"></i> Thông tin thanh toán</a></li>
                        <li role="presentation"><a href="#history" aria-controls="profile" role="tab"
                                                   data-toggle="tab"><i
                                    class="fa fa-cog"></i> Lịch sử</a></li>
                    </ul>
                    <div class="tab-content" id="detail_payment">
                        <div role="tabpanel" class="tab-pane active" id="payment-detail">
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4">
                                    <label>Hình thức</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input disabled type="radio" class="payment-method" name="method-pay"
                                               value="1" <?php echo ($data['_input']['payment_method'] == 1) ? 'checked' : ''; ?>>
                                        Tiền mặt &nbsp;
                                        <input disabled type="radio" class="payment-method" name="method-pay"
                                               value="2" <?php echo ($data['_input']['payment_method'] == 2) ? 'checked' : ''; ?>>
                                        Thẻ&nbsp;
                                        <input disabled type="radio" class="payment-method" name="method-pay"
                                               value="3" <?php echo ($data['_input']['payment_method'] == 3) ? 'checked' : ''; ?>>
                                        CK&nbsp;
                                    </div>

                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4">
                                    <label>Tiền hàng</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="">
                                        <?php echo cms_encode_currency_format($data['_input']['total_price']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4">
                                    <label>Chiết khấu</label>
                                </div>
                                <div class="col-md-8">
                                    <div><?php echo cms_encode_currency_format($data['_input']['discount']); ?></div>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4">
                                    <label>Tổng cộng</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="">
                                        <?php echo cms_encode_currency_format($data['_input']['total_money']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-right-0">
                                    <label>Khách trả</label>
                                </div>
                                <div class="col-md-8 orange">
                                    <?php echo cms_encode_currency_format($data['_input']['payed']); ?>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4">
                                    <label>Còn nợ</label>
                                </div>
                                <div class="col-md-8">
                                    <div><?php echo cms_encode_currency_format($data['_input']['lack']);
                                        if ($data['_input']['lack'] > 0) { ?>
                                            <button type="button" class="btn btn-primary" onclick="cms_show_payment_input();">
                                                Chi nợ
                                            </button>
                                        <?php } ?></div>
                                </div>
                            </div>
                            <div id="payment_input" style="display: none;width: 280px;">
                                <div class="col-sm-12">
                                    <div>
                                        <input type="radio" class="payment-method" name="payment-method" value="1"
                                               checked="">
                                        Tiền mặt &nbsp;
                                        <input type="radio" class="payment-method" name="payment-method" value="2">
                                        Thẻ&nbsp;
                                        <input type="radio" class="payment-method" name="payment-method" value="3">
                                        CK&nbsp;
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input id="payment_note" class="form-control" type="text"
                                           placeholder="Ghi chú"
                                           style="border-radius: 0 !important;">
                                </div>

                                <div class="col-md-12">
                                    <input id="payment_date" class="form-control datepk" type="text"
                                           placeholder="Ngày thu"
                                           style="border-radius: 0 !important;">
                                </div>
                                <script>$('#payment_date').datetimepicker({
                                        autoclose: true
                                    });
                                </script>
                                <div class="col-md-12">
                                    <div class="col-md-6" style="padding: 0px;">
                                        <input id="payment_money" class="form-control txtMoney" type="text"
                                               placeholder="Số tiền thu" value="<?php echo cms_encode_currency_format($data['_input']['lack']); ?>"
                                               style="border-radius: 0 !important;">
                                    </div>
                                    <div class="col-md-6" style="padding: 0px;display: inline-flex">
                                        <button type="button" class="btn btn-primary" onclick="save_payment_input(<?php echo $data['_input']['ID'] ?>);" title="Đồng ý">
                                            <i class="fa fa-plus"></i>
                                            Chi
                                        </button>
                                        <button type="button" class="btn" title="Hủy" onclick="cms_hide_payment_input();">
                                            <i class="fa fa-times"
                                               style="color: white !important;"></i>
                                            Hủy
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="history">
                            <table id="sample-table-2" class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="center hidden-320">STT</th>
                                    <th>Ngày thanh toán</th>
                                    <th class="text-center">Số tiền</th>
                                    <th class="text-center">Hình thức</th>
                                    <th class="text-center">Xóa</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if (isset($data['_payment']) && count($data['_payment'])) :
                                    $nstt = 1;
                                    foreach ($data['_payment'] as $payment) :
                                        ?>
                                        <tr>
                                            <td class="text-center hidden-320 ng-binding"><?php echo $nstt++; ?></td>
                                            <td class="text-center ng-binding"><?php echo ($payment['payment_date'] != '0000-00-00 00:00:00') ? gmdate("H:i d/m/Y", strtotime(str_replace('-', '/', $payment['payment_date'])) + 7 * 3600) : '-'; ?></td>
                                            <td class="text-right ng-binding"><?php echo cms_encode_currency_format($payment['total_money']); ?></td>
                                            <td class="text-center">
                                                <?php if ($payment['payment_method'] == 1)
                                                    echo 'Tiền mặt';
                                                elseif ($payment['payment_method'] == 2)
                                                    echo 'Thẻ';
                                                else
                                                    echo 'CK';
                                                ?>
                                            </td>
                                            <td class="text-center" style="color: darkred;">
                                                <i title="Xóa" onclick="cms_delete_payment_in_input(<?php echo $data['_input']['ID'].','.$payment['ID']; ?>)"
                                                   class="fa fa-trash-o"></i>
                                            </td>
                                        </tr>
                                    <?php endforeach; endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>