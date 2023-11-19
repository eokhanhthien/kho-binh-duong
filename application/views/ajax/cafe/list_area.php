<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th class="text-center">Tên khu vực</th>
        <th class="text-center">Số bàn</th>
        <th class="text-center">Tên chi nhánh</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <?php if (isset($_list_area) && count($_list_area)) :
        foreach ($_list_area as $key => $item) :
            ?>
            <tr>
                <td class="text-center"><?php echo $item['area_name']; ?></td>
                <td class="text-center"><?php echo $item['number_table']; ?></td>
                <td class="text-center"><?php echo $item['store_name']; ?></td>
                <td class="text-center" style="background: #fff;">
                    <i class="fa fa-trash-o" style="color: darkred;" title="Xóa"
                       onclick="cms_del_temp_area(<?php echo $item['ID'] . ',' . $page; ?>)"></i>
                </td>
            </tr>
        <?php endforeach;
    else :
        echo '<tr><td colspan="7" class="text-center">Không có dữ liệu</td></tr>';
    endif;
    ?>
    </tbody>
</table>
<?php if (isset($_pagination_link) && !empty($_pagination_link)) { ?>
    <div class="alert alert-info summany-info clearfix" role="alert"
         style="background: #fff; margin-bottom: 0; border: none;">
        <div class="pull-right ajax-pagination">
            <?php echo $_pagination_link; ?>
        </div>
    </div>
<?php } ?>