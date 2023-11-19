<table class="table table-bordered table-striped" id="table-orders">
    <thead>
    <tr>
        <th style="border: 1px solid #c5b6b6;" class="btn-stt"></th>
        <th style="border: 1px solid #c5b6b6;" class="text-center">STT</th>
        <th style="border: 1px solid #c5b6b6;" class="text-center">Mã đơn hàng</th>
        <th style="border: 1px solid #c5b6b6;" class="text-center">Kho xuất</th>
        <th style="border: 1px solid #c5b6b6;" class="text-center">Ngày bán</th>
        <th style="border: 1px solid #c5b6b6;" class="text-center">Thu ngân</th>
        <th style="border: 1px solid #c5b6b6;" class="text-center" style="padding: 0px;width: 10%;">
            <span class="tr-hide">Khách hàng</span>
            <select style="text-align:center;text-align-last: center;" id="customer_id">
                <option value="-1">Khách hàng</option>
                <?php foreach ($_list_customer as $item) : ?>
                    <option <?php if ($item == $customer_id) echo 'selected '; ?>
                        value="<?php echo $item; ?>"><?php echo cms_getNamecustomerbyID($item); ?></option>
                <?php endforeach; ?>
            </select>
        </th>
        <th class="text-center" style="padding: 0px;width: 5%;border: 1px solid #c5b6b6;">
            <span class="tr-hide">Trạng thái</span>
            <select style="text-align:center;text-align-last: center;" id="order_status">
                <?php
                $list_order_status = array('-1' => 'Trạng thái', '0' => 'Khởi tạo', '1' => 'Hoàn thành', '2' => 'Xác nhận', '3' => 'Đang giao', '4' => 'Đã giao', '5' => 'Hủy');
                foreach ($list_order_status as $key => $val) : ?>
                    <option <?php if ($key == $order_status) echo 'selected '; ?>
                        value="<?php echo $key; ?>"><?php echo $val; ?></option>
                <?php endforeach; ?>
            </select>
        </th>
        <th class="text-center tr-hide" style="border: 1px solid #c5b6b6;width: 25%;">Danh sách sản phẩm</th>
        <th style="border: 1px solid #c5b6b6;" class="text-center" style="background-color: #fff;width: 5%;">Tổng SL</th>
        <th style="border: 1px solid #c5b6b6;" class="text-center" style="background-color: #fff;">Tổng tiền</th>
        <th style="border: 1px solid #c5b6b6;" class="text-center"><i class="fa fa-clock-o"></i> Nợ</th>
        <th style="border: 1px solid #c5b6b6;" class="btn-action"></th>
        <th style="border: 1px solid #c5b6b6;" class="text-center btn-checkbox"><label class="checkbox" style="margin: 0;">
                <input type="checkbox"
                       class="checkbox chkAll">
                <span
                    style="width: 15px; height: 15px;">
                </span>
            </label>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($_list_orders) && count($_list_orders)) :
        $stt= ($page-1)*10+1;
        foreach ($_list_orders as $key => $item) :
            $list_products = json_decode($item['detail_order'], true);
            ?>
            <tr>
                <td class="btn-stt" style="text-align: center;border: 1px solid #c5b6b6;">
                    <i style="color: #478fca!important;" title="Chi tiết đơn hàng"
                       onclick="cms_show_detail_order(<?php echo $item['ID'];?>)"
                       class="fa fa-plus-circle i-detail-order-<?php echo $item['ID']?>">
                    </i>
                    <i style="color: #478fca!important;" title="Chi tiết đơn hàng"
                       onclick="cms_show_detail_order(<?php echo $item['ID'];?>)"
                       class="fa fa-minus-circle i-hide i-detail-order-<?php echo $item['ID']?>">
                    </i>
                </td>
                <td class="text-center" style="border: 1px solid #c5b6b6;"><?php echo $stt++; ?></td>
                <td class="text-center" style="color: #2a6496; cursor: pointer;border: 1px solid #c5b6b6;"
                    onclick="<?php if ($item['order_status'] != 0)
                        echo 'cms_detail_order(' . $item['ID'];
                    else
                        echo 'cms_edit_order(' . $item['ID'];
                    ?>)"><?php echo $item['output_code']; ?></td>
                <td class="text-center" style="border: 1px solid #c5b6b6;"><?php echo cms_getNamestockbyID($item['store_id']); ?></td>
                <td class="text-center" style="border: 1px solid #c5b6b6;"><?php echo ($item['sell_date'] != '0000-00-00 00:00:00') ? gmdate("H:i d/m/Y", strtotime(str_replace('-', '/', $item['sell_date'])) + 7 * 3600) : '-'; ?></td>
                <td class="text-center" style="border: 1px solid #c5b6b6;"><?php echo cms_getNameAuthbyID($item['user_init']); ?></td>
                <td class="text-center" style="border: 1px solid #c5b6b6;"><?php echo cms_getNamecustomerbyID($item['customer_id']); ?></td>
                <td class="text-center" style="border: 1px solid #c5b6b6;"><?php echo cms_getNamestatusbyID($item['order_status']); ?></td>
                <td class="text-center tr-hide" style="border: 1px solid #c5b6b6;">
                    <?php
                        $sst = 1;
                        foreach ($list_products as $product) {
                            $_product = cms_finding_productbyID($product['id']);
                            $_product['quantity'] = $product['quantity'];
                            $_product['price'] = $product['price'];
                            echo '<b>'.$sst.'.</b> '. $_product['prd_name'].' (SL: '. $_product['quantity'].' )<br>';
                            $sst++;
                        }
                    ?>
                </td>
                <td class="text-center"
                    style="background-color: #F2F2F2;border: 1px solid #c5b6b6;"><?php echo ($item['total_quantity']); ?>
                </td>
                <td class="text-center"
                    style="background-color: #F2F2F2;border: 1px solid #c5b6b6;"><?php echo cms_encode_currency_format($item['total_money']); ?>
                </td>
                <td class="text-center"
                    style="background: #fff;border: 1px solid #c5b6b6;"><?php echo cms_encode_currency_format($item['lack']); ?>
                </td>
                <td class="text-center btn-action" style="background: #fff;border: 1px solid #c5b6b6;width: 5%;">
                    <?php if ($item['order_status'] != 5) { ?>
                        <i title="Sửa" onclick="cms_edit_order(<?php echo $item['ID']; ?>)"
                           class="fa fa-pencil-square-o"
                           style="margin-right: 5px;">
                        </i>
                    <?php }?>
                    <?php if ($item['canreturn'] == 1 && $item['order_status'] == 1) { ?>
                        <i title="Trả hàng" onclick="cms_return_order(<?php echo $item['ID']; ?>)"
                           class="fa fa-reply"
                           style="margin-right: 5px;">
                        </i>
                    <?php }?>
                    <i title="In" onclick="cms_print_order(1,<?php echo $item['ID']; ?>)"
                       class="fa fa-print blue"
                       style="margin-right: 5px;">
                    </i>
                    <i class="fa fa-trash-o" style="color: darkred;" title="<?php if ($option == 1)
                        echo 'Xóa vĩnh viễn';
                    else
                        echo 'Xóa'?>"
                       onclick="<?php if ($option == 1)
                           echo 'cms_del_order';
                       else
                           echo 'cms_del_temp_order'?>(<?php echo $item['ID'] . ',' . $page; ?>)"></i>
                </td>
               
                <td class="text-center btn-checkbox" style="border: 1px solid #c5b6b6;">
                    <label class="checkbox" style="margin: 0;">
                        <input type="checkbox"
                               value="<?php echo $item['ID']; ?>"
                               class="checkbox chk">
                        <span
                            style="width: 15px; height: 15px;">
                        </span>
                    </label>
                </td>
            </tr>
            <tr class="tr-hide" id="tr-detail-order-<?php echo $item['ID']?>">
                <td colspan="15">
                    <div class="tabbable">
                        <ul class="nav nav-tabs">
                            <li class="active">
                                <a data-toggle="tab">
                                    <i class="green icon-reorder bigger-110"></i>
                                    Chi tiết đơn hàng
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active">
                                <div class="alert alert-success clearfix" style="display: flex;">
                                    <div>
                                        <i class="fa fa-cart-arrow-down">
                                        </i>
                                        <span
                                            class="hidden-768">Số lượng SP:
                                        </span>
                                        <label><?php echo $item['total_quantity']; ?>
                                        </label>
                                    </div>
                                    <div class="padding-left-10">
                                        <i class="fa fa-dollar">
                                        </i>
                                        <span
                                            class="hidden-768">Tiền hàng:
                                        </span>
                                        <label><?php echo cms_encode_currency_format($item['total_price']); ?>
                                        </label>
                                    </div>
                                    <div class="padding-left-10">
                                        <i class="fa fa-dollar">
                                        </i>
                                        <span
                                            class="hidden-768">Giảm giá:
                                        </span>
                                        <label><?php echo cms_encode_currency_format($item['coupon']); ?>
                                        </label>
                                    </div>
                                    <div class="padding-left-10">
                                        <i class="fa fa-dollar">
                                        </i>
                                        <span
                                            class="hidden-768">Tổng tiền:
                                        </span>
                                        <label><?php echo cms_encode_currency_format($item['total_money']); ?>
                                        </label>
                                    </div>
                                    <div class="padding-left-10">
                                        <i class="fa fa-clock-o"></i>
                                        <span class="hidden-768">Còn nợ: </span>
                                        <label
                                            ><?php echo cms_encode_currency_format($item['lack']); ?>
                                        </label>
                                    </div>
                                </div>
                                <table class="table table-striped table-bordered table-hover dataTable">
                                    <thead>
                                    <tr role="row">
                                        <th class="text-center">STT</th>
                                        <th class="text-left hidden-768">Mã sản phẩm</th>
                                        <th class="text-left">Tên sản phẩm</th>
                                        <th class="text-center">Hình ảnh</th>
                                        <th class="text-center">Số lượng</th>
                                        <th class="text-center">ĐVT</th>
                                        <th class="text-center">Giá bán</th>
                                        <th class="text-center ">Thành tiền</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $queue = 1;
                                    foreach ($list_products as $product) {
                                        $_product = cms_finding_productbyID($product['id']);
                                        $_product['quantity'] = $product['quantity'];
                                        $_product['price'] = $product['price'];
                                        ?>
                                        <tr>
                                            <td class="text-center width-5 hidden-320 ">
                                                <?php echo $queue++; ?>
                                            </td>
                                            <td class="text-left hidden-768">
                                                <?php echo $_product['prd_code']; ?>
                                            </td>
                                            <td class="text-left ">
                                                <?php
                                                    if($product['price']!=$_product['prd_sell_price']){
                                                        $_product['prd_name'].=' <b style="text-decoration: line-through;">'.number_format($_product['prd_sell_price']).'</b>';
                                                    }
                                                    if($product['note']!=''){
                                                        $_product['prd_name'].=' ('.$product['note'].')';
                                                    }
                                                 echo $_product['prd_name']; ?>
                                            </td>
                                            <td class="text-center zoomin">
                                                <img height="30"
                                                     src="public/templates/uploads/<?php echo $_product['prd_image_url']; ?>">
                                            </td>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $_product['quantity']; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $_product['prd_unit_name']; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $item['order_status'] == 0 ? cms_encode_currency_format($_product['prd_sell_price']) : cms_encode_currency_format($_product['price']); ?>
                                            </td>
                                            <td class="text-center">
                                                <?php echo $item['order_status'] == 0 ? cms_encode_currency_format($_product['prd_sell_price'] * $_product['quantity']) : cms_encode_currency_format($_product['price'] * $_product['quantity']); ?>
                                            </td>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
        <?php endforeach;
    else :
        echo '<tr><td colspan="11" class="text-center">Không có dữ liệu</td></tr>';
    endif;
    ?>
    </tbody>
</table>
<div class="alert alert-info summany-info clearfix" role="alert">
    <div class="sm-info pull-left padd-0">
        Tổng số hóa đơn: <span><?php echo (isset($total_orders['quantity'])) ? $total_orders['quantity'] : 0; ?></span>
        Tổng tiền:
        <span><?php echo cms_encode_currency_format((isset($total_orders['total_money']) ? $total_orders['total_money'] : 0)); ?></span>
        Tổng nợ:
        <span><?php echo cms_encode_currency_format((isset($total_orders['total_debt']) ? $total_orders['total_debt'] : 0)); ?></span>
    </div>
    <div class="pull-right ajax-pagination">
        <?php echo $_pagination_link; ?>
    </div>
</div>
