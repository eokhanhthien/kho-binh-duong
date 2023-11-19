<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th class="text-center">Ngày thu</th>
        <th class="text-center">Kho thu</th>
        <th class="text-center">Thanh toán</th>
        <th class="text-center">Còn nợ</th>

        
    </tr>
    </thead>
    <tbody>
    <?php 

    if (isset($_list_payment) && count($_list_payment)) :
        foreach ($_list_payment as $key => $item) :
            ?>
           <tr>                
                
                
                <td class="text-center"><?php echo ($item['ngay'] != '0000-00-00') ? gmdate("d/m/Y", strtotime(str_replace('-', '/', $item['ngay'])) + 7 * 3600) : '-'; ?></td>
                <td class="text-center"><?php echo cms_getNamestockbyID($item['store_id']); ?></td>
                <td class="text-center" style="background-color: #F2F2F2;"><?php echo cms_encode_currency_format($item['money']); ?></td>
                
                <td class="text-center" style="background-color: #F2F2F2;"><?php echo cms_encode_currency_format(cms_getTotalCongNo($item['customer_id'],$item['ngay'])); ?></td>

                
            </tr>
            
        <?php endforeach;
    else :
        echo '<tr><td colspan="4" class="text-center">Không có dữ liệu</td></tr>';
    endif;

    ?>
    </tbody>
</table>
<div class="alert alert-info summany-info clearfix" role="alert">    
    <div class="pull-right ajax-pagination">
        <?php echo $_pagination_link; ?>
    </div>
</div>