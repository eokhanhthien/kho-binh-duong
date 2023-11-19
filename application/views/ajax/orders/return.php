<div class="breadcrumbs-fixed panel-action">
    <div class="row">
        <div class="orders-act">
            <div class="col-md-4 col-md-offset-2">
                <div class="left-action text-left clearfix">
                    <h2>Nhập trả hàng từ đơn hàng&raquo;<?php echo $data['_order']['output_code']; ?></h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="right-action text-right">
                    <div class="btn-groups">
                        <button type="button" class="btn btn-primary"
                                onclick="cms_save_order_return(1,<?php echo $data['_order']['ID']; ?>)"><i
                                    class="fa fa-check"></i> Lưu
                        </button>
                        <button type="button" class="btn btn-primary"
                                onclick="cms_save_order_return(2,<?php echo $data['_order']['ID']; ?>)"><i
                                    class="fa fa-print"></i> Lưu và in
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

<div class="orders-content check-order">
    <div class="row">
        <div class="col-md-8">
            <div class="product-results">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">STT</th>
                        <th>Mã hàng</th>
                        <th>Tên sản phẩm</th>
                        <th class="text-center">SL bán</th>
                        <th class="text-center">SL nhập trả</th>
                        <th class="text-center">Giá nhập</th>
                        <th class="text-center">Thành tiền</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="product_return">
                    <?php $seq = 1;
                    foreach ($data['_detail_order'] as $product): ?>
                        <tr data-id="<?php echo $product['product_id'] ?>" return-id="<?php echo $product['ID'] ?>">
                            <td class="text-center seq"><?php echo $seq++; ?></td>
                            <td><?php echo $product['prd_code']; ?></td>
                            <td><?php echo $product['prd_name']; ?></td>
                            <td class="text-center"><?php echo $product['quantity']; ?></td>
                            <td class="text-center" style="max-width: 30px;">
                                <input style="max-height: 22px;" type="text"
                                       class="txtNumber form-control quantity_return text-center"
                                       value="<?php echo $product['quantity']; ?>">
                            </td>
                            <td class="text-center" style="max-width: 120px;">
                                <input style="max-height: 22px;" type="text"
                                       class="txtMoney form-control text-center price_return"
                                       value="<?php echo cms_encode_currency_format($product['price']); ?>">
                            </td>
                            <td class="text-center total_price_return">
                                <?php echo cms_encode_currency_format($product['quantity'] * $product['price']); ?></td>
                            <td class="text-center"><i class="fa fa-trash-o del-pro-return"></i></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <br>
            <div class="left-action text-left clearfix">
                <h2>Xuất bán hàng</h2>
            </div>

            <div class="order-search" style="margin: 10px 0px; position: relative;">
                <input type="text" class="form-control" placeholder="Nhập mã sản phẩm hoặc tên sản phẩm"
                       id="search-pro-box">
            </div>
            <script>
                $(function () {
                    $("#search-pro-box").autocomplete({
                        minLength: 1,
                        source: 'orders/cms_autocomplete_products/',
                        focus: function (event, ui) {
                            $("#search-pro-box").val(ui.item.prd_code);
                            return false;
                        },
                        select: function (event, ui) {
                            cms_select_product_order_return(ui.item.ID);
                            $("#search-pro-box").val('');
                            return false;
                        }
                    }).keyup(function (e) {
                        if (e.which === 13) {
                            cms_autocomplete_enter_order_return();
                            $("#search-pro-box").val('');
                            $(".ui-menu-item").hide();
                        }
                    })
                        .autocomplete("instance")._renderItem = function (ul, item) {
                        return $("<li>")
                            .append("<div>" + item.prd_code + " - " + item.prd_name + "</div>")
                            .appendTo(ul);
                    };
                });
            </script>
            <div class="product-results">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th class="text-center">STT</th>
                        <th>Mã hàng</th>
                        <th>Tên sản phẩm</th>
                        <th>Hình ảnh</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">ĐVT</th>
                        <th class="text-center">Giá bán</th>
                        <th class="text-center">Thành tiền</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="product_sell">
                    </tbody>
                </table>
                <div class="alert alert-success" style="margin-top: 30px;" role="alert">Gõ mã hoặc tên sản phẩm vào hộp
                    tìm kiếm để thêm hàng vào đơn hàng
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="morder-info" style="padding: 4px;">
                        <div class="tab-contents" style="padding: 8px 6px;">
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-0">
                                    <label>Khách hàng</label>
                                </div>
                                <div class="col-md-8" style="font-style: italic;">
                                    <?php echo cms_getNamecustomerbyID($data['_order']['customer_id']); ?>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-0">
                                    <label>Ghi chú</label>
                                </div>
                                <div class="col-md-8">
                                    <textarea id="note-order" cols="" class="form-control" rows="3"
                                              style="border-radius: 0;">
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <h4 class="lighter" style="margin-top: 0;">
                        <i class="fa fa-info-circle blue"></i>
                        Thông tin thanh toán
                    </h4>

                    <div class="morder-info" style="padding: 4px;">
                        <div class="tab-contents" style="padding: 8px 6px;">
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4">
                                    <label>Hình thức</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="input-group">
                                        <input type="radio" class="payment-method" name="method-pay" value="1" checked>
                                        Tiền mặt &nbsp;
                                        <input type="radio" class="payment-method" name="method-pay" value="2">
                                        Thẻ&nbsp;
                                        <input type="radio" class="payment-method" name="method-pay" value="3">
                                        CK&nbsp;
                                    </div>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4">
                                    <label>Tiền hàng</label>
                                </div>
                                <div class="col-md-8">
                                    <div id="total_price_return">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-right-0">
                                    <label>Chiết khấu</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text"
                                           class="form-control text-right txtMoney"
                                           id="discount_return"
                                           placeholder="0" style="border-radius: 0 !important;">
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4">
                                    <label>Tổng cộng</label>
                                </div>
                                <div class="col-md-8">
                                    <div id="total_money_return">
                                        0
                                    </div>
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4 padd-right-0">
                                    <label>Thanh toán</label>
                                </div>
                                <div class="col-md-8">
                                    <input type="text"
                                           class="form-control text-right txtMoney"
                                           id="customer_pay_return"
                                           placeholder="0" style="border-radius: 0 !important;">
                                </div>
                            </div>
                            <div class="form-group marg-bot-10 clearfix">
                                <div class="col-md-4">
                                    <label class="debt">Còn nợ</label>
                                </div>
                                <div class="col-md-8">
                                    <div class="debt">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="btn-groups pull-right" style="margin-bottom: 50px;">
                        <button type="button" class="btn btn-primary"
                                onclick="cms_save_order_return(1,<?php echo $data['_order']['ID']; ?>)"><i
                                    class="fa fa-check"></i> Lưu
                        </button>
                        <button type="button" class="btn btn-primary"
                                onclick="cms_save_order_return(2,<?php echo $data['_order']['ID']; ?>)"><i
                                    class="fa fa-print"></i> Lưu và in
                        </button>
                        <button type="button" class="btn btn-default btn-back"
                                onclick="cms_javascript_redirect( cms_javascrip_fullURL() )"><i
                                    class="fa fa-arrow-left"></i> Hủy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>