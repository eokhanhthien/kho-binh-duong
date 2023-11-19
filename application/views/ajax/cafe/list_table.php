<div class="col-md-12 text-center">
    <div class="col-md-4  col-sm-2">
    </div>
    <div class="col-md-1 col-sm-2 col-xs-6">
        <button type="button" class="table_status btn btn-primary"
                onclick="cms_load_list_table('-1')">Toàn bộ
        </button>
    </div>
    <div class="col-md-1 col-sm-2 col-xs-6">
        <button type="button" class="table_status btn btn-primary"
                onclick="cms_load_list_table(0)">Bàn trống
        </button>
    </div>
    <div class="col-md-1 col-sm-2 col-xs-6">
        <button type="button" class="table_status btn btn-primary"
                onclick="cms_load_list_table(1)">Có khách
        </button>
    </div>
    <div class="col-md-1 col-sm-2 col-xs-6">
        <button type="button" class="table_status btn btn-primary"
                onclick="cms_load_pos(0)">Mang về
        </button>
    </div>
    <div class="col-md-1 col-sm-2 col-xs-6">
        <button type="button" class="table_status btn btn-primary"
                onclick="cms_load_pos('-1')">Chọn sản phẩm
        </button>
    </div>
    <div class="col-md-2 col-sm-2">
    </div>
</div>
<div class="col-md-12 col-xs-12 col-xs-12" style="margin-top:10px;">
    <?php foreach ($data['table'] as $item) {
        ?>
        <div class="col-md-2 col-sm-4 col-xs-6 tble<?=$item['area_id']%2;?>"> 
            <div title="<?php echo $item['table_status'] == 1 ? 'Bàn đã có khách' : 'Bàn trống, Chọn bàn số '.$item['table_name'].'?' ?>" onclick="cms_load_pos(<?php echo $item['ID'] ?>)"
                 class="area <?php echo $item['table_status'] == 1 ? 'available' : '' ?>">
                <i><?php echo $item['table_name'] ?></i>
                <b><?php echo $item['area_name'] ?></b>
            </div>
        </div>
        <?php
    } ?>
</div>