<?php foreach ($data['product'] as $item) {
    ?>
    <div onclick="cms_select_product_sell(<?php echo $item['ID'] ?>)" class="img col-md-3" style="padding: 0px">
        <a>
            <img src="public/templates/uploads/<?php echo $item['prd_image_url'] == '' ? 'no-image.png' : $item['prd_image_url'] ?>">
        </a>
        <div class="desc"><?php echo $item['prd_name'] ?></div>
        <div class="desc"><?php echo cms_encode_currency_format($item['prd_sell_price']) ?></div>
    </div>

    <?php
} ?>