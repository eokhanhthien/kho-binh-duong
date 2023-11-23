<table class="table table-bordered table-striped" id="payrollTable">
    <thead>
    <tr>
        <th class="text-center">Mã bảng lương</th>
        <th class="text-center">Số tiền nhập</th>
        <th class="text-center">Số tiền thực</th>
        <th class="text-center">Nhân viên</th>
        <th class="text-center">Hoa hồng</th>
        <th class="text-center">Ngày</th>
        <th class="text-center">Phương thức thanh toán</th>
        <th class="text-center">Tình trạng</th>
        <th class="text-center">Khách hàng</th>
        <th class="text-center">số điện thoại KH</th>
        <th class="text-center">Ghi chú</th>
        <!-- <th class="text-center" style="background-color: #fff;">Tổng tiền</th> -->
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($_list_payment) && count($_list_payment)) :
        foreach ($_list_payment as $key => $item) :
            ?>
            <tr>
                <td><?php echo $item['ticket_number']; ?></td>
                <td><?php echo number_format($item['money_import']); ?></td>
                <td><?php echo number_format($item['money_real']); ?></td>
                <?php
                    $users = cms_getListUsers();

                    // Find the user with the matching ID
                    $selectedUser = array_filter($users, function($user) use ($item) {
                        return $user['ID'] == $item['employee'];
                    });

                    // Check if the result is not empty
                    if (!empty($selectedUser)) {
                        $selectedUser = reset($selectedUser); // Get the first element of the filtered array
                        $employeeUsername = $selectedUser['display_name'];

                        // Now, directly replace the content of the <td> with the employee username
                        echo '<td>' . $employeeUsername . '</td>';
                    } else {
                        // If no matching user is found, display an empty <td>
                        echo '<td></td>';
                    }
                    ?>
                <td><?php echo $item['commission']; ?>%</td>
                <td><?php echo $item['date']; ?></td>
                <td><?php echo ($item['payment_method'] == 1) ? 'Chuyển khoản' : (($item['payment_method'] == 2) ? 'Công nợ' : 'Tiền mặt'); ?></td>
                <td><?php echo ($item['status'] == 1) ? 'Thành công' : (($item['status'] == 2) ? 'Chưa nộp' : 'unknown'); ?></td>
                <?php
                    $customers = cms_getListCustomer();

                    $selectedCustomer = array_filter($customers, function($customer) use ($item) {
                        return $customer['ID'] == $item['customer'];
                    });

                    if (!empty($selectedCustomer)) {
                        $selectedCustomer = reset($selectedCustomer); 
                        $customerName = $selectedCustomer['customer_name'];

                        echo '<td>' . $customerName . '</td>';
                    } else {
                        echo '<td></td>';
                    }
                    ?>
                    <?php
                    $customers = cms_getListCustomer();

                    $selectedCustomer = array_filter($customers, function($customer) use ($item) {
                        return $customer['ID'] == $item['customer_phones'];
                    });

                    if (!empty($selectedCustomer)) {
                        $selectedCustomer = reset($selectedCustomer); 
                        $customerName = $selectedCustomer['customer_phone'];

                        echo '<td>' . $customerName . '</td>';
                    } else {
                        echo '<td></td>';
                    }
                    ?>
                <td><?php echo $item['note']; ?></td>
                <td class="text-center" style="background: #fff;">
                    <!-- <i title="In" class="fa fa-print blue" style="margin-right: 5px;"></i> -->
                    <i class="fa fa-pencil-square-o" title="Sửa" data-toggle="modal" data-target="#myModal<?php echo $item['id']; ?>"></i>
                   <span onclick="cms_delete_payroll(<?php echo $item['id']; ?>);"><i class="fa fa-trash-o" style="color: darkred;" title="Xóa" ></i>    </span>  
                </td>

                <div class="modal fade" id="myModal<?php echo $item['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel<?php echo $item['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="myModalLabel<?php echo $item['id']; ?>">Thông tin thanh toán</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-horizontal">
                                    <input type="hidden"  id="id" value="<?= isset($item['id']) ? $item['id'] : '' ?>">
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="group-name">Số tiền nhập</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="money_import_<?= $item['id'] ?>" class="txtMoney form-control money_import_edit" placeholder="Nhập số tiền nhập" value="<?= isset($item['money_import']) ? $item['money_import'] : '' ?>">
                                            <span style="color: red; font-style: italic;" class="error error-money-import_<?= $item['id'] ?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="group-name">Số tiền thực</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="money_real_<?= $item['id'] ?>" class="txtMoney form-control money_real_edit" placeholder="Nhập số tiền thực" value="<?= isset($item['money_real']) ? $item['money_real'] : '' ?>">
                                            <span style="color: red; font-style: italic;" class="error error-money-real_<?= $item['id'] ?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="group-name">Tên nhân viên nhập</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="form-control employee_id_edit" id="employee_id_<?= $item['id'] ?>">
                                                <option value="">--- Chọn nhân viên ---</option>
                                                <?php
                                                $users = cms_getListUsers();
                                                foreach ($users as $user) {
                                                    echo '<option value="' . $user['ID'] . '"' . (isset($item['employee']) && $item['employee'] == $user['ID'] ? ' selected' : '') . '>' . $user['display_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span style="color: red; font-style: italic;" class="error error-employee_<?= $item['id'] ?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="payroll_date">Ngày chi</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <input type="text" id="payroll_date_<?= $item['id'] ?>" name="payroll_date" class="form-control txttimes datepk payroll_date_edit" value="<?= isset($item['date']) ? $item['date'] : '' ?>" placeholder="Hôm nay">
                                            <span style="color: red;" class="error error-payroll_date_<?= $item['id'] ?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="group-name">Hình thức thanh toán</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="form-control payment_method_edit" id="payment_method_<?= $item['id'] ?>">
                                                <option value="1"<?= isset($item['payment_method']) && $item['payment_method'] == 1 ? ' selected' : '' ?>>Chuyển khoản</option>
                                                <option value="2"<?= isset($item['payment_method']) && $item['payment_method'] == 2 ? ' selected' : '' ?>>Công nợ</option>
                                                <option value="3"<?= isset($item['payment_method']) && $item['payment_method'] == 3 ? ' selected' : '' ?>>Tiền mặt</option>
                                            </select>
                                        </div>
                                        <span style="color: red; font-style: italic;" class="error error-payment-method_<?= $item['id'] ?>"></span>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="group-name">Trạng thái</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="form-control status_edit" id="status_<?= $item['id'] ?>">
                                                <option value="1"<?= isset($item['status']) && $item['status'] == 1 ? ' selected' : '' ?>>Thành công</option>
                                                <option value="2"<?= isset($item['status']) && $item['status'] == 2 ? ' selected' : '' ?>>Chưa nộp</option>
                                            </select>
                                            <span style="color: red; font-style: italic;" class="error error-status_<?= $item['id'] ?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="group-name">Hoa hồng</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="form-control commission_edit" id="commission_<?= $item['id'] ?>">
                                                <?php for ($i = 10; $i <= 100; $i = $i + 10) { ?>
                                                    <option value="<?= $i ?>"<?= isset($item['commission']) && $item['commission'] == $i ? ' selected' : '' ?>><?= $i ?>%</option>
                                                <?php } ?>
                                            </select>
                                            <span style="color: red; font-style: italic;" class="error error-commission_<?= $item['id'] ?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="group-name">Tên khách hàng</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="form-control customer_id_edit" id="customer_id_<?= $item['id'] ?>">
                                                <option value="">--- Chọn khách hàng ---</option>
                                                <?php
                                                $customers = cms_getListCustomer();
                                                foreach ($customers as $customer) {
                                                    echo '<option value="' . $customer['ID'] . '"' . (isset($item['customer_id']) && $item['customer_id'] == $customer['ID'] ? ' selected' : '') . '>' . $customer['customer_name'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span style="color: red; font-style: italic;" class="error error-customer_id_<?= $item['id'] ?>"></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="group-name">SDT khách hàng</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <select class="form-control customer_phones_edit" id="customer_phones_<?= $item['id'] ?>">
                                                <option value="">--- Chọn SĐT khách hàng ---</option>
                                                <?php
                                                foreach ($customers as $customer) {
                                                    echo '<option value="' . $customer['ID'] . '"' . (isset($item['customer_phone']) && $item['customer_phone'] == $customer['ID'] ? ' selected' : '') . '>' . $customer['customer_phone'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <span style="color: red; font-style: italic;" class="error error-customer_phone_<?= $item['id'] ?>"></span>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-3">
                                            <label for="group-name">Ghi chú</label>
                                        </div>
                                        <div class="col-sm-9">
                                            <textarea name="" class="form-control note_edit" id="note_<?= $item['id'] ?>" cols="30" rows="4"><?= isset($item['note']) ? $item['note'] : '' ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" onclick="cms_update_payroll(<?php echo $item['id']; ?>);" >Cập nhật</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <!-- Các nút khác có thể được thêm vào đây nếu cần -->
                            </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
                <!-- <td class="text-center"><?php echo $item['payment_code']; ?></td>
                <td class="text-center zoomin">
                    <img height="30"
                         src="public/templates/uploads/<?php echo $item['payment_image']; ?>">
                </td>
                <td class="text-center"><?php echo cms_getNamestockbyID($item['store_id']); ?></td>
                <td class="text-center"><?php echo ($item['payment_date'] != '0000-00-00 00:00:00') ? gmdate("H:i d/m/Y", strtotime(str_replace('-', '/', $item['payment_date'])) + 7 * 3600) : '-'; ?></td>
                <td class="text-center"><?php echo cms_getNameAuthbyID($item['user_init']); ?></td>
                <td class="text-center"><?php echo $item['notes']; ?></td>
                <td class="text-center"><?php echo cms_getNamepaymentTypeByID($item['type_id']); ?></td>
                <td class="text-center"
                    style="background-color: #F2F2F2;"><?php echo cms_encode_currency_format($item['total_money']); ?>
                </td>
                <td class="text-center" style="background: #fff;">
                    <i title="In" onclick="cms_print_payment(6,<?php echo $item['ID'];?>)" class="fa fa-print blue" style="margin-right: 5px;">
                    </i>
                    <?php if($edit_payment==1) { ?>
                        <i class="fa fa-pencil-square-o" title="Sửa"
                           onclick="cms_edit_payment(<?php echo $item['ID'] . ',' . $page; ?>)"></i>
                        <?php
                    }
                    ?>
                    <?php if($delete_payment==1) { ?>
                        <i class="fa fa-trash-o" style="color: darkred;" title="Xóa"
                           onclick="cms_del_temp_payment(<?php echo $item['ID'] . ',' . $page; ?>)"></i>
                        <?php
                    }
                       ?>
                </td> -->
            </tr>
        <?php endforeach;
    else :
        echo '<tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>';
    endif;
    ?>
    </tbody>
</table>
<!-- <div class="alert alert-info summany-info clearfix" role="alert">
    <div class="sm-info pull-left padd-0">
        Tổng phiếu chi: <span><?php echo (isset($total_payment['quantity'])) ? $total_payment['quantity'] : 0; ?></span>
        Tổng tiền: <span><?php echo cms_encode_currency_format((isset($total_payment['total_money']) ? $total_payment['total_money'] : 0)); ?></span>
    </div>
    <div class="pull-right ajax-pagination">
        <?php echo $_pagination_link; ?>
    </div>
</div> -->
