<table class="table table-hover table-custom">
    <thead>
    <th>Thời gian</th>
    <th>Nhân viên</th>
    <th>Thao tác liên quan</th>
    <th>Mã phiếu</th>
    <th>Số lượng</th>
    <th>Tại chi nhánh</th>
    </thead>
    <tbody>
    <?php if (isset($data['_list_history']) && count($data['_list_history'])) :
        foreach ($data['_list_history'] as $key => $item) : ?>
            <tr>
                <td><?php echo $item['created']; ?></td>
                <td><?php echo $item['display_name']; ?></td>
                <td><?php echo cms_getNameReportTypeByID($item['type']); ?></td>
                <td><?php echo $item['transaction_code']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td><?php echo $item['store_name']; ?></td>
            </tr>
        <?php endforeach;
    else :
        echo '<tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>';
    endif;
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td colspan="6"><?php echo $display ?>
        </td>
    </tr>
    </tfoot>
</table>
<div class="pull-right ajax-pagination">
    <?php echo $_pagination_link; ?>
</div>