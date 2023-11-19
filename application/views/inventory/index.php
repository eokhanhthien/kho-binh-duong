<div class="inventory">
    <div class="inventory-content">
        <div class="product-sear panel-sear">
            <div>
                <div class="form-group col-md-3 padd-0">
                    <input type="text" class="form-control txt-sinventory"
                           placeholder="Nhập tên hoặc mã sản phẩm để tìm kiếm">
                </div>
                <input id="modal_product_id" style="display: none;">
                <div class="form-group col-md-9 padd-0" style="padding-left: 5px;">
                    <div class="col-md-12 padd-0">
                        <div class="col-md-9 padd-0">
                            <div class="col-md-3 padd-0">
                                <select class="form-control" id="prd_group_id">
                                    <option value="-1" selected='selected'>-- Danh mục --</option>
                                    <optgroup label="Chọn danh mục">
                                        <?php if (isset($data['_prd_group']) && count($data['_prd_group'])):
                                            foreach ($data['_prd_group'] as $key => $item) :
                                                ?>
                                                <option
                                                    value="<?php echo $item['id']; ?>"><?php echo $item['prd_group_name']; ?></option>
                                            <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </optgroup>
                                    <optgroup label="------------------------">
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-3 padd-0">
                                <select class="form-control search-option-3" id="prd_manufacture_id">
                                    <option value="-1" selected="selected">--Nhà sản xuất--</option>
                                    <optgroup label="Chọn nhà sản xuất">
                                        <?php if (isset($data['_prd_manufacture']) && count($data['_prd_manufacture'])):
                                            foreach ($data['_prd_manufacture'] as $key => $val) :
                                                ?>
                                                <option
                                                    value="<?php echo $val['ID']; ?>"><?php echo $val['prd_manuf_name']; ?></option>
                                            <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </optgroup>
                                    <optgroup label="------------------------">
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-md-3 padd-0">
                                <select class="form-control" id="option_inventory">
                                    <option value="0">--Tất cả--</option>
                                    <option value="1" selected="selected">Chỉ lấy hàng tồn</option>
                                    <option value="2">Hết Hàng</option>
                                </select>
                            </div>
                            <div class="col-md-3 padd-0">
                                <select id="store_id" class="form-control">
                                    <option value="-1">Tất cả kho</option>
                                    <?php foreach ($data['stores'] as $key => $item) :?>
                                        <option <?php if($item['ID']==$data['store_id']) echo 'selected '; ?> value="<?php echo $item['ID']; ?>"><?php echo $item['store_name']; ?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 padd-0" style="padding-left: 5px;">
                            <button style="box-shadow: none;" type="button" class="btn btn-primary btn-large"
                                    onclick="cms_paging_inventory(1)"><i class="fa fa-search"></i> Xem
                            </button>
                        </div>
                    </div>
                    <div class="col-md-1 padd-0" style="padding-left: 1px;">
                    </div>
                </div>
            </div>
        </div>
        <div class="inventory-main-body">
        </div>
    </div>
</div>

<!-- Modal product-->
<div id="myProduct" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="tabbable tabs-left">
                            <div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-inline">

                                            <div class="col-md-12">
                                                <div class="input-daterange input-group" style="width: 25%" id="datepicker">
                                                    <input type="text" class="input-sm form-control" id="history-search-date-from" placeholder="Từ ngày"
                                                           name="start"/>
                                                    <span class="input-group-addon">-</span>
                                                    <input type="text"  class="input-sm form-control" id="history-search-date-to" placeholder="Đến ngày"
                                                           name="end"/>
                                                </div>
                                                <select class="form-control" id="modal_user_id">
                                                    <option value="-1">Lọc theo nhân viên</option>
                                                    <?php foreach ($data['users'] as $item) : ?>
                                                        <option
                                                            value="<?php echo $item['id']; ?>"><?php echo $item['display_name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <select id="modal_store_id" class="form-control"
                                                        style="margin: 8px auto">
                                                    <option value="-1">Lọc theo chi nhánh</option>
                                                    <?php foreach ($data['store'] as $key => $item) : ?>
                                                        <option
                                                            value="<?php echo $item['ID']; ?>"><?php echo $item['store_name']; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <select class="form-control" id="modal_report_type_id">
                                                    <option value="-1">Lọc theo thao tác</option>
                                                    <?php
                                                    $list = cms_getListReporttype();
                                                    foreach ($list as $key => $val) : ?>
                                                        <option
                                                            value="<?php echo $key; ?>"><?php echo $val; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button class="btn btn-primary"
                                                        onclick="cms_paging_product_history(1)">
                                                    <i class="fa fa-search" aria-hidden="true"></i> Tìm kiếm
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt10" id="modal_product_history">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /tabs -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal end product -->