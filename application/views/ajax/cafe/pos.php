
<h2 style="text-align: center;">
    <?php
    if ($table_id == 0) {
        echo 'Pos';
    } else {
        $table = cms_finding_tablebyID($table_id);
		$table_note="";
        if (!empty($table)) {
            echo $table_note='Bàn: ' . $table['table_name'] .' - KV: '.$table['area_name'];
            if ($table['table_status'] == 1)
                echo ' - ' . cms_ConvertDateTime($table['updated']);
        }
    }
    ?>
</h2>

<div class="row">
    <div class="orders-act">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="collapse" id="show_more">
                        <?php if($table_id!=0){ ?>
                        <div class="row cafe_col">
                            <div class="col-md-3 hidden-xs">
                                <label>Đổi/Gộp bàn</label>
                            </div>
                            <div class="col-md-9">
                                <select id="change_table_id" class="form-control">
                                    <option value="-1">Đổi/Gộp bàn</option>
                                    <?php foreach ($data['tables'] as $item) :?>
                                        <option <?php echo $item['table_status']==1 ? 'class="table_avaiable"' :'' ?> value="<?php echo $item['ID']; ?>">Bàn: <?php echo $item['table_name']; ?> - Khu vực: <?php echo $item['area_name']; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row cafe_col">

                            <div class="col-md-3 hidden-xs">
                                <label>Khách hàng</label>
                            </div>
                            <div class="col-md-9">
                                <div class="col-md-12 padd-0" style="position: relative;display: flex">
                                    <input id="search-box-cys" class="form-control"
                                           type="text"
                                           placeholder="<?php if (isset($data['_order']['customer_id']) && $data['_order']['customer_id'] > 0) echo cms_getNamecustomerbyID($data['_order']['customer_id']); else echo 'Tìm khách hàng'; ?>"
                                           style="border-radius: 3px 0 0 3px !important;"><span
                                            style="color: red; position: absolute; right: 40px; top:5px; "
                                            class="del-cys"></span>
                                    <div id="cys-suggestion-box"
                                         style="top:34px;border: 1px solid #444; display: none; overflow-y: auto;background-color: #fff; z-index: 2 !important; position: absolute; left: 0; width: 100%; padding: 5px 0px; max-height: 400px !important;">
                                        <div class="search-cys-inner"></div>
                                    </div>
                                    <button type="button" data-toggle="modal"
                                            data-target="#create-cust"
                                            class="btn btn-primary"
                                            style="border-radius: 0 3px 3px 0; box-shadow: none; padding: 7px 11px;">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row cafe_col">
                            <div class="col-md-3 hidden-xs">
                                <label>Ghi chú</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="note-order" class="form-control" placeholder="Nhập ghi chú"
                                       value="<?php echo isset($data['_order']['notes']) ? $data['_order']['notes'] : $table_note ?>">
                            </div>
                        </div>
                        <div class="row cafe_col">
                            <div class="col-md-3">
                                <label>Tổng tiền</label>
                            </div>
                            <div class="col-md-9">
                                <div class="total-money">
                                    0
                                </div>
                            </div>
                        </div>
                        <div class="row cafe_col">
                            <div class="col-md-3">
                                <label>Giảm giá (F7)</label>
                            </div>
                            <div class="col-md-9" style="display: flex;">
                                <button onclick="cms_change_discount_order()" class="toggle-discount-order">vnđ
                                </button>
                                <button onclick="cms_change_discount_order()" style="display: none;"
                                        class="toggle-discount-order">%
                                </button>
                                <input type="text"
                                       class="toggle-discount-order form-control text-right discount-percent-order"
                                       placeholder="0" style="display:none;border-radius: 0 !important;">
                                <input type="text"
                                       class="toggle-discount-order form-control text-right txtMoney discount-order"
                                       placeholder="0" style="border-radius: 0 !important;"
                                       value="<?php echo isset($data['_order']['coupon']) ? cms_encode_currency_format($data['_order']['coupon']) : 0; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row cafe_col">
                        <div class="col-md-3">
                            <label>Tổng cộng</label>
                        </div>
                        <div class="col-md-9" style="display: flex">
                            <div class="total-after-discount">
                                0
                            </div>
                            <button type="button" class="btn btn-primary" data-toggle="collapse" href="#show_more" role="button" aria-expanded="false" aria-controls="show_more" style="margin-left: 30px;"><i
                                        class="fas fa fa-align-justify"></i> Chi tiết
                            </button>
                        </div>
                    </div>
                    <div class="row cafe_col">
                        <div class="col-md-3">
                            <label>Khách đưa (F8)</label>
                        </div>
                        <div class="col-md-9">
                            <input type="text"
                                   class="form-control text-right txtMoney customer-pay"
                                   placeholder="0" style="border-radius: 0 !important;"
                                   value="<?php echo isset($data['_order']['customer_pay']) ? cms_encode_currency_format($data['_order']['customer_pay']) : 0; ?>">
                        </div>
                    </div>
                    <div class="row cafe_col">
                        <div class="col-md-3">
                            <label class="debt">Tiền thừa</label>
                        </div>
                        <div class="col-md-9">
                            <div class="debt">0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="order-search" style="margin: 10px 0px; position: relative;">
                <input type="text" class="form-control"
                       placeholder="Nhập mã sản phẩm hoặc tên sản phẩm (F2)"
                       id="search-pro-box">
            </div>
            <div class="product-results">
                <table class="table table-bordered table-striped">
                    <thead>
                    <tr>
                        <th>Mã hàng</th>
                        <th>Tên sản phẩm</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-center">Giá bán</th>
                        <th class="text-center">Thành tiền</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="pro_search_append">
                    <?php
                    if (!empty($_list_products))
                        foreach ($_list_products as $product): ?>
                            <tr id="spinner-<?php echo $product['ID']; ?>" data-id="<?php echo $product['ID']; ?>">
                                <td><?php echo $product['prd_code']; ?></td>
                                <td><?php echo $product['prd_name']; ?><input type="text" class="form-control note_product_order" placeholder="Ghi chú" value="<?php echo $product['note']; ?>"/>                                    
                                </td>
                                <td class="text-center">
                                    <div class="input-group spinner" style="display: flex;">
                                        <button class="btn btn-default" type="button"><i class="fa fas fa-minus"></i></button>
                                        <input style="width: 40px;"
                                               type="text"
                                               class="txtNumber form-control quantity_product_order text-center"
                                               value="<?php echo $product['quantity']; ?>">
                                        <button class="btn btn-default" type="button"><i class="fa fas fa-plus"></i></button>
                                    </div>
                                    <script>

                                        $('.spinner .btn:last-of-type').on('click', function() {

                                            $(this).parents('#spinner-<?php echo $product['ID']; ?> .spinner').find('input').val( parseInt($('#spinner-<?php echo $product['ID']; ?> .spinner input').val(), 10) + 1);

                                            cms_load_infor_order();

                                        });

                                        $('.spinner .btn:first-of-type').on('click', function() {

                                            $val = parseInt($('#spinner-<?php echo $product['ID']; ?> .spinner input').val(), 10) - 1;

                                            $(this).parents('#spinner-<?php echo $product['ID']; ?> .spinner').find('input').val($val < 1 ? 1 : $val);

                                            cms_load_infor_order();

                                        });

                                    </script>
                                </td>
                                <td style="display: none;" class="printed"><?php echo $product['printed']; ?></td>
                                <td style="max-width: 100px;" class="text-center output">
                                    <input type="text" <?php if ($product['prd_edit_price'] == 0) echo 'disabled'; ?>
                                           style="min-width:80px;max-height: 22px;"
                                           class="txtMoney form-control text-center price-order"
                                           value="<?php echo cms_encode_currency_format($product['prd_sell_price']); ?>">
                                </td>
                                <td class="text-center total-money"><?php echo cms_encode_currency_format($product['quantity'] * $product['prd_sell_price']); ?></td>
                                <td class="text-center"><i class="fa fa-trash-o del-pro-order"></i></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-12">
            <div class="btn-groups pull-left" style="margin-bottom: 50px;">
                <button type="button" class="btn btn-primary save"
                        onclick="cms_save_order(0,<?php echo $table_id; ?>)"><i
                            class="fa fa-check"></i> Thanh toán
                </button>
                <button type="button" class="btn btn-primary save"
                        onclick="cms_save_order(1,<?php echo $table_id; ?>)"><i
                            class="fa fa-print"></i> Thanh toán và in
                </button>
            </div>
            <div class="btn-groups pull-right" style="margin-bottom: 50px;">

                <button type="button" class="btn btn-primary save"
                        onclick="cms_save_table(0,<?php echo $table_id; ?>)"><i
                            class="fa fa-check"></i> Order (F9)
                </button>
                <button type="button" class="btn btn-primary save"
                        onclick="cms_save_table(1,<?php echo $table_id; ?>)"><i class="fa fa-print"></i> Order và
                    in (F10)
                </button>
                <a>
                    <button type="button" onclick="cms_load_list_table('-1',1)" class="btn btn-default save"><i
                                class="fa fa-arrow-left"></i> Quay lại
                    </button>
                </a>
            </div>
        </div>
    </div>
</div>
<?php if (isset($data['_order']['customer_id'])) {
    if ($data['_order']['customer_id'] > 0) {
        ?>
        <script>
            cms_selected_cys(<?php echo $data['_order']['customer_id']; ?>);
        </script>
        <?php
    }
} ?>

<script>
    $(function () {
        $("#search-pro-box").autocomplete({
            minLength: 1,
            source: 'cafe/cms_autocomplete_products/',
            focus: function (event, ui) {
                $("#search-pro-box").val(ui.item.prd_code);
                return false;
            },
            select: function (event, ui) {
                cms_select_product_sell(ui.item.ID);
                $("#search-pro-box").val('');
                return false;
            }
        }).keyup(function (e) {
            if (e.which === 13) {
                cms_autocomplete_enter_sell();
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
    cms_load_infor_order();
    $('#change_table_id').on('change', function () {
        if ($('tbody#pro_search_append tr').length == 0) {
            $('.ajax-error-ct').html('Xin vui lòng chọn ít nhất 1 sản phẩm cần xuất trước khi chuyển bàn. Xin cảm ơn!').parent().fadeIn().delay(1000).fadeOut('slow');
            document.getElementById('change_table_id').selectedIndex = 0;
        }else{
            var result = confirm("Bạn có chắc chắn muốn chuyển bàn");
            if (result == true) {
                $customer_id = typeof $('#search-box-cys').attr('data-id') === 'undefined' ? 0 : $('#search-box-cys').attr('data-id');
                $store_id = $('#store_id').val();
                $note = $('#note-order').val();
                $discount = cms_decode_currency_format($('input.discount-order').val());
                $customer_pay = cms_decode_currency_format($('.customer-pay').val());
                $detail = [];
                $('tbody#pro_search_append tr').each(function () {
                    $id = $(this).attr('data-id');
                    $quantity = $(this).find('input.quantity_product_order').val();
                    $printed = $(this).find('td.printed').text();
                    $price = cms_decode_currency_format($(this).find('input.price-order').val());
                    $detail.push(
                        {id: $id, quantity: $quantity, price: $price, discount: 0,printed:$printed}
                    );
                });
                var $to = $('#change_table_id').val();

                $data = {
                    'data': {
                        'table_id': $to,
                        'customer_id': $customer_id,
                        'store_id': $store_id,
                        'notes': $note,
                        'coupon': $discount,
                        'customer_pay': $customer_pay,
                        'detail_order': $detail
                    }
                };

                var $param = {
                    'type': 'POST',
                    'url': 'cafe/cms_change_table/' + <?php echo $table_id; ?> + '/' + $to,
                    'data':$data,
                    'callback': function (data) {
                        if (isNaN(parseInt(data))){
                            $('.ajax-error-ct').html(data).parent().fadeIn().delay(1000).fadeOut('slow');
                        } else if(data==1) {
                            $('.ajax-success-ct').html('Chuyển bàn thành công!').parent().fadeIn().delay(1000).fadeOut('slow');
                        }else if(data==2){
                            $('.ajax-success-ct').html('Gộp bàn thành công!').parent().fadeIn().delay(1000).fadeOut('slow');
                        }
                    }
                };
                cms_adapter_ajax($param);

                setTimeout(function () {
                    cms_load_pos($to);
                }, 1000);
            } else {
                document.getElementById('change_table_id').selectedIndex = 0;
            }
        }


    });
</script>

