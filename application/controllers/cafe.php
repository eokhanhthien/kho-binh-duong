<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Cafe extends CI_Controller
{
    private $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = $this->cms_authentication->check();
    }

    /*
     * Cấu hình hệ thống
    /****************************************/
    public function index()
    {
        if ($this->auth == null || !in_array(19, $this->auth['group_permission']))
            $this->cms_common_string->cms_redirect(CMS_BASE_URL . 'backend');
        else {
            $data['seo']['title'] = "Phần mềm quản lý bán hàng";
            $user = $this->db->select('users.id, username, email, display_name, user_status, group_name ')->from('users')->join('users_group', 'users_group.id = users.group_id')->get()->result_array();
            $data['data']['template'] = $this->db->select('content')->from('templates')->where('id', 1)->limit(1)->get()->row_array();
            $data['data']['list_template'] = $this->db->from('templates')->get()->result_array();
            $data['data']['_user'] = $user;
            $data['data']['user'] = $this->auth;
            $store = $this->db->from('stores')->get()->result_array();
            $data['data']['store'] = $store;
            $area = $this->db->where(['store_id' => $this->auth['store_id'], 'deleted' => 0])->from('area')->get()->result_array();
            $data['data']['area'] = $area;
            $table = $this->db
                ->select('cms_table.ID,table_name,area_name,table_status,area_id')
                ->from('area')
                ->join('table', 'table.area_id=area.ID', 'INNER')
                ->where(['store_id' => $this->auth['store_id'], 'cms_area.deleted' => 0, 'cms_table.deleted' => 0])
                ->get()
                ->result_array();
            $data['data']['table'] = $table;

            $data['data']['product'] =
                $this->db
                    ->select('prd_code,prd_name,ID,prd_sell_price,prd_image_url')
                    ->from('products')
                    ->where(['cms_products.deleted' => 0, 'prd_status' => 1])
                    ->get()
                    ->result_array();

            $this->cms_nestedset->set('products_group');
            $sls_group = $this->cms_nestedset->dropdown('products_group', NULL, 'manufacture');
            $data['sls_group'] = $sls_group;
            $this->load->view('layout/cafe', isset($data) ? $data : null);
        }
    }

    public function cms_autocomplete_products()
    {
        $data = $this->input->get('term');
        $products = $this->db
            ->from('products')
            ->where('(prd_code like "%' . $data . '%" or prd_name like "%' . $data . '%") and prd_status = 1 and deleted =0 ')
            ->get()
            ->result_array();
        echo json_encode($products);
    }

    public function cms_select_product()
    {
        $id = $this->input->post('id');
        $product = $this->db
            ->select('products.ID,prd_code,prd_unit_name,prd_name, prd_sell_price, prd_image_url,prd_edit_price')
            ->from('products')
            ->where(['products.ID' => $id, 'deleted' => 0, 'prd_status' => 1])
            ->join('products_unit', 'products_unit.ID=products.prd_unit_id', 'LEFT')
            ->get()
            ->row_array();
        if (isset($product) && count($product) != 0) {
            ob_start(); ?>
            <tr id="spinner-<?php echo $product['ID']; ?>" data-id="<?php echo $product['ID']; ?>">
                <td><?php echo $product['prd_code']; ?></td>
                <td><?php echo $product['prd_name']; ?>
                    <input type="text" class="form-control note_product_order" placeholder="Ghi chú" value=""/>                    
                </td>
                <td class="text-center">
                    <div class="input-group spinner" style="display: flex;">
                        <button class="btn btn-default" type="button"><i class="fa fas fa-minus"></i></button>
                        <input style="width: 40px;"
                               type="text"
                               class="txtNumber form-control quantity_product_order text-center" 
                               value="1">
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
                <td style="display: none;" class="printed">0</td>
                <td style="max-width: 100px;" class="text-center output">
                    <input type="text" <?php if ($product['prd_edit_price'] == 0) echo 'disabled'; ?>
                           style="min-width:80px;max-height: 22px;"
                           class="txtMoney form-control text-center price-order"
                           value="<?php echo cms_encode_currency_format($product['prd_sell_price']); ?>"></td>
                <td class="text-center total-money"><?php echo cms_encode_currency_format($product['prd_sell_price']); ?></td>
                <td class="text-center"><i class="fa fa-trash-o del-pro-order"></i></td>
            </tr>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            echo $html;
        }
    }

    public function cms_load_list_area($store_id)
    {
        $areas = $this->db->from('area')->where(['store_id' => $store_id, 'deleted' => 0])->get()->result_array();
        ob_start();
        echo '<option value="-1" selected="selected">Tất cả</option>';
        echo '<optgroup label="Chọn danh mục">';
        if ($areas)
            foreach ($areas as $val) :
                ?>
                <option
                        value="<?php echo $val['ID']; ?>"><?php echo $val['area_name']; ?>
                </option>
            <?php
            endforeach;
        echo '</optgroup>';
        $html = ob_get_contents();
        ob_end_clean();
        echo $this->messages = $html;
    }

    public function cms_load_list_table($store_id, $area_id, $status)
    {
        if ($status > '-1') {
            if ($area_id > 0) {
                $table = $this->db
                    ->select('cms_table.ID,table_name,area_name,table_status,area_id')
                    ->from('area')
                    ->join('table', 'table.area_id=area.ID', 'INNER')
                    ->where('table_status', $status)
                    ->where(['store_id' => $store_id, 'area_id' => $area_id, 'cms_area.deleted' => 0, 'cms_table.deleted' => 0])
                    ->get()
                    ->result_array();
            } else {
                $table = $this->db
                    ->select('cms_table.ID,table_name,area_name,table_status,area_id')
                    ->from('area')
                    ->where('table_status', $status)
                    ->join('table', 'table.area_id=area.ID', 'INNER')
                    ->where(['store_id' => $store_id, 'cms_area.deleted' => 0, 'cms_table.deleted' => 0])
                    ->get()
                    ->result_array();
            }

        } else {
            if ($area_id > 0) {
                $table = $this->db
                    ->select('cms_table.ID,table_name,area_name,table_status,area_id')
                    ->from('area')
                    ->join('table', 'table.area_id=area.ID', 'INNER')
                    ->where(['store_id' => $store_id, 'area_id' => $area_id, 'cms_area.deleted' => 0, 'cms_table.deleted' => 0])
                    ->get()
                    ->result_array();
            } else {
                $table = $this->db
                    ->select('cms_table.ID,table_name,area_name,table_status,area_id')
                    ->from('area')
                    ->join('table', 'table.area_id=area.ID', 'INNER')
                    ->where(['store_id' => $store_id, 'cms_area.deleted' => 0, 'cms_table.deleted' => 0])
                    ->get()
                    ->result_array();
            }
        }


        $data['data']['table'] = $table;
        $this->load->view('ajax/cafe/list_table', isset($data) ? $data : null);
    }

    public function cms_load_list_product($category_id)
    {
        if ($category_id == 0) {
            $data['data']['product'] =
                $this->db
                    ->select('prd_code,prd_name,ID,prd_sell_price,prd_image_url')
                    ->from('products')
                    ->where(['cms_products.deleted' => 0, 'prd_status' => 1])
                    ->get()
                    ->result_array();
        } else {
            $temp = $this->getCategoriesByParentId($category_id);
            $temp[] = $category_id;

            $data['data']['product'] =
                $this->db
                    ->select('prd_code,prd_name,ID,prd_sell_price,prd_image_url')
                    ->from('products')
                    ->where_in('prd_group_id', $temp)
                    ->where(['cms_products.deleted' => 0, 'prd_status' => 1])
                    ->get()
                    ->result_array();
        }

        $this->load->view('ajax/cafe/list_product', isset($data) ? $data : null);
    }

    function getCategoriesByParentId($category_id)
    {
        $category_data = array();

        $category_query = $this->db
            ->from('products_group')
            ->where('parentid', $category_id)
            ->get();

        foreach ($category_query->result() as $category) {
            $category_data[] = $category->ID;
            $children = $this->getCategoriesByParentId($category->ID);

            if ($children) {
                $category_data = array_merge($children, $category_data);
            }
        }

        return $category_data;
    }

    public function cms_load_pos($table_id)
    {
        $order = $this->db
            ->from('table_order')
            ->where(['deleted' => 0, 'table_id' => $table_id])
            ->order_by('created desc')
            ->get()
            ->row_array();

        $data['data']['tables'] = $this->db
            ->select('cms_table.ID as ID, table_name,area_name,table_status,area_id')
            ->from('table')
            ->join('area', 'area.ID=table.area_id', 'INNER')
            ->where(['cms_table.deleted' => 0,'cms_area.deleted' => 0])
            ->where('cms_table.ID <>',$table_id)
            ->order_by('cms_table.created desc')
            ->get()
            ->result_array();

        $data['_list_products'] = array();
        if (isset($order) && count($order)) {
            $list_products = json_decode($order['detail_order'], true);
            foreach ($list_products as $product) {
                $_product = cms_finding_productbyID($product['id']);
                $_product['quantity'] = $product['quantity'];
                $_product['printed'] = $product['printed'];
                 $_product['prd_sell_price'] = $product['price'];
                $_product['note'] = $product['note'];
                $data['_list_products'][] = $_product;
            }
        }

        $data['data']['_order'] = $order;
        $data['table_id'] = $table_id;

        $this->load->view('ajax/cafe/pos', isset($data) ? $data : null);
    }

    public function cms_save_table($store_id)
    {
        if ($store_id == $this->auth['store_id']) {
            $table = $this->input->post('data');
            $table['order_date'] = gmdate("Y:m:d H:i:s", time() + 7 * 3600);
            $this->db->trans_begin();
            $user_init = $this->auth['id'];

            foreach ($table['detail_order'] as $item) {
                $product = $this->db->from('products')->where('ID', $item['id'])->get()->row_array();
                if ($product['prd_edit_price'] == 0)
                    $item['price'] = $product['prd_sell_price'];

                $detail_order[] = $item;
            }

            if ($table['coupon'] == 'NaN')
                $table['coupon'] = 0;

            $table['user_init'] = $user_init;
            $table['store_id'] = $store_id;
			$table['updated'] = gmdate("Y:m:d H:i:s", time() + 7 * 3600);
            $table['detail_order'] = json_encode($detail_order);

            $this->db->select_max('order_code')->like('order_code', 'OD');
            $max_output_code = $this->db->get('table_order')->row();
            $max_code = (int)(str_replace('OD', '', $max_output_code->order_code)) + 1;
            if ($max_code < 10)
                $table['order_code'] = 'OD000000' . ($max_code);
            else if ($max_code < 100)
                $table['order_code'] = 'OD00000' . ($max_code);
            else if ($max_code < 1000)
                $table['order_code'] = 'OD0000' . ($max_code);
            else if ($max_code < 10000)
                $table['order_code'] = 'OD000' . ($max_code);
            else if ($max_code < 100000)
                $table['order_code'] = 'OD00' . ($max_code);
            else if ($max_code < 1000000)
                $table['order_code'] = 'OD0' . ($max_code);
            else if ($max_code < 10000000)
                $table['order_code'] = 'OD' . ($max_code);

            $this->db->where(['table_id'=> $table['table_id'],'deleted'=>0])->update('table_order', ['deleted' => 1]);
            $this->db->insert('table_order', $table);
            $id = $this->db->insert_id();

            $this->db->where(['ID'=> $table['table_id'],'table_status' => 0])->update('table', ['table_status' => 1]);
			
			$this->db->where(['ID'=> $table['table_id'],'table_status' => 1])->update('table', ['updated' => gmdate("Y:m:d H:i:s", time() + 7 * 3600)]);
			
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo $this->messages = "0";
            } else {
                $this->db->trans_commit();
                echo $this->messages = $id;
            }
        } else
            echo $this->messages = "0";
    }

    public function cms_change_table($table_from,$table_to)
    {
        $table = $this->input->post('data');
        if ($table['store_id'] == $this->auth['store_id']) {
            $this->db->trans_begin();
            $user_init = $this->auth['id'];
            $today = gmdate("Y:m:d H:i:s", time() + 7 * 3600);

            $check_table_from = $this->db
                ->select('updated,table_status')
                ->from('table')
                ->where(['cms_table.deleted' => 0])
                ->where('cms_table.ID',$table_from)
                ->get()
                ->row_array();

            $check_table_to = $this->db
                ->select('ID,updated,table_status')
                ->from('table')
                ->where(['cms_table.deleted' => 0])
                ->where('cms_table.ID',$table_to)
                ->get()
                ->row_array();

            if($check_table_to['table_status']==0){
            //Chuyển bàn
                if($check_table_from['table_status']==0){
                    $this->db
                        ->where(['ID'=> $table_to,'table_status' => 0])
                        ->update('table', ['table_status' => 1,'updated'=>$today]);
                }else{
                    $this->db
                        ->where(['ID'=> $table_to,'table_status' => 0])
                        ->update('table', ['table_status' => 1,'updated'=>$check_table_from['updated']]);

                    $this->db
                        ->where(['ID'=> $table_from,'table_status' => 1])
                        ->update('table', ['table_status' => 0]);

                    $this->db->where(['table_id'=> $table_from,'deleted'=>0])->update('table_order', ['deleted' => 1]);
                }

                $table['order_date'] = $today;
				$table['updated'] = $today;
                foreach ($table['detail_order'] as $item) {
                    $product = $this->db->from('products')->where('ID', $item['id'])->get()->row_array();
                    if ($product['prd_edit_price'] == 0)
                        $item['price'] = $product['prd_sell_price'];

                    $detail_order[] = $item;
                }

                if ($table['coupon'] == 'NaN')
                    $table['coupon'] = 0;

                $table['user_init'] = $user_init;
                $table['detail_order'] = json_encode($detail_order);

                $this->db->select_max('order_code')->like('order_code', 'OD');
                $max_output_code = $this->db->get('table_order')->row();
                $max_code = (int)(str_replace('OD', '', $max_output_code->order_code)) + 1;
                if ($max_code < 10)
                    $table['order_code'] = 'OD000000' . ($max_code);
                else if ($max_code < 100)
                    $table['order_code'] = 'OD00000' . ($max_code);
                else if ($max_code < 1000)
                    $table['order_code'] = 'OD0000' . ($max_code);
                else if ($max_code < 10000)
                    $table['order_code'] = 'OD000' . ($max_code);
                else if ($max_code < 100000)
                    $table['order_code'] = 'OD00' . ($max_code);
                else if ($max_code < 1000000)
                    $table['order_code'] = 'OD0' . ($max_code);
                else if ($max_code < 10000000)
                    $table['order_code'] = 'OD' . ($max_code);

                $this->db->insert('table_order', $table);
                $id = $this->db->insert_id();

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    echo $this->messages = "0";
                } else {
                    $this->db->trans_commit();
                    echo $this->messages = 1;
                }
            }else{
            //Gộp bàn
                if($check_table_from['table_status']==1){
                    $this->db
                        ->where(['ID'=> $table_from,'table_status' => 1])
                        ->update('table', ['table_status' => 0]);
                }

                $order = $this->db
                    ->from('table_order')
                    ->where(['deleted' => 0, 'table_id' => $table_to])
                    ->order_by('created desc')
                    ->get()
                    ->row_array();

                $detail_order = array();
                if (isset($order) && count($order)) {
                    $list_product_to = json_decode($order['detail_order'], true);
                    foreach ($list_product_to as $product_to) {
                        foreach ($table['detail_order'] as $key=>$product_from) {
                            if($product_to['id']==$product_from['id']){
                                $product_to['quantity'] += $product_from['quantity'];
                                $product_to['printed'] += $product_from['printed'];
                                unset($table['detail_order'][$key]);
                                break;
                            }
                        }

                        $detail_order[] = $product_to;
                    }

                    foreach ($table['detail_order'] as $product) {
                        $detail_order[] = $product;
                    }

                    $table_update['detail_order'] = json_encode($detail_order);
                    $table_update['user_upd'] = $user_init;
					$table_update['updated'] = $today;
                    $this->db->where(['deleted' => 0, 'table_id' => $table_to])->update('table_order', $table_update);
                    $this->db->where(['deleted' => 0, 'table_id' => $table_from])->update('table_order', ['deleted'=>1]);
                }

                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    echo $this->messages = "0";
                } else {
                    $this->db->trans_commit();
                    echo $this->messages = 2;
                }
            }
        } else
            echo $this->messages = "0";
    }

    public function cms_save_order($store_id)
    {
        if ($store_id == $this->auth['store_id']) {
            $order = $this->input->post('data');
            $detail_order_temp = $order['detail_order'];
            $order['sell_date'] = gmdate("Y:m:d H:i:s", time() + 7 * 3600);
            $this->db->trans_begin();
            $user_init = $this->auth['id'];
            $total_price = 0;
            $total_origin_price = 0;
            $total_quantity = 0;

            $order['order_status'] = 1;

            foreach ($order['detail_order'] as $item) {
                $product = $this->db->from('products')->where('ID', $item['id'])->get()->row_array();
                $inventory_quantity = $this->db->select('quantity')->from('inventory')->where(['store_id' => $store_id, 'product_id' => $item['id']])->get()->row_array();

                if (!empty($inventory_quantity)) {
                    if ($product['prd_allownegative'] == 0 && $inventory_quantity['quantity'] < $item['quantity']) {
                        $this->db->trans_rollback();
                        echo $this->messages = $product['prd_code'] . ' đang còn tồn chỉ ' . $inventory_quantity['quantity'] . ' sản phẩm';
                        return;
                    } else {
                        $this->db->where(['store_id' => $store_id, 'product_id' => $item['id']])->update('inventory', ['quantity' => $inventory_quantity['quantity'] - $item['quantity'], 'user_upd' => $user_init]);
                    }
                } else {
                    if ($product['prd_allownegative'] == 0) {
                        $this->db->trans_rollback();
                        echo $this->messages = $product['prd_code'] . ' đang hết hàng.';
                        return;
                    } else {
                        $inventory = ['store_id' => $store_id, 'product_id' => $item['id'], 'quantity' => -$item['quantity'], 'user_init' => $user_init];
                        $this->db->insert('inventory', $inventory);
                    }
                }

                if ($product['prd_edit_price'] == 0)
                    $item['price'] = $product['prd_sell_price'];

                $sls['prd_sls'] = $product['prd_sls'] - $item['quantity'];
                $total_price += ($item['price'] - $item['discount']) * $item['quantity'];
                $total_origin_price += $product['prd_origin_price'] * $item['quantity'];
                $total_quantity += $item['quantity'];
                $this->db->where('ID', $item['id'])->update('products', $sls);
                $detail_order[] = $item;
            }


            if ($order['coupon'] == 'NaN')
                $order['coupon'] = 0;

            $order['total_price'] = $total_price;
            $order['total_origin_price'] = $total_origin_price;
            $order['total_money'] = $total_price - $order['coupon'];
            $order['total_quantity'] = $total_quantity;
            $order['lack'] = $total_price - $order['customer_pay'] - $order['coupon'] > 0 ? $total_price - $order['customer_pay'] - $order['coupon'] : 0;
            $order['user_init'] = $this->auth['id'];
            $order['store_id'] = $store_id;
            $order['payment_method'] = 1;
            $order['detail_order'] = json_encode($detail_order);
			
            $this->db->select_max('output_code')->like('output_code', 'PX')->where('input_id', 0);
            $max_output_code = $this->db->get('orders')->row();
            $max_code = (int)(str_replace('PX', '', $max_output_code->output_code)) + 1;
            if ($max_code < 10)
                $order['output_code'] = 'PX000000' . ($max_code);
            else if ($max_code < 100)
                $order['output_code'] = 'PX00000' . ($max_code);
            else if ($max_code < 1000)
                $order['output_code'] = 'PX0000' . ($max_code);
            else if ($max_code < 10000)
                $order['output_code'] = 'PX000' . ($max_code);
            else if ($max_code < 100000)
                $order['output_code'] = 'PX00' . ($max_code);
            else if ($max_code < 1000000)
                $order['output_code'] = 'PX0' . ($max_code);
            else if ($max_code < 10000000)
                $order['output_code'] = 'PX' . ($max_code);

            if ($order['customer_id'] < 1 && $order['lack'] > 0) {
                $this->db->trans_rollback();
                echo $this->messages = "-1";
                return;
            }

            $this->db->insert('orders', $order);
            $id = $this->db->insert_id();
			$order['updated'] = date("Y-m-d H:i:s");
            $this->db->where('ID', $order['table_id'])->update('table', ['table_status' => 0]);
            $this->db->where(['table_id'=> $order['table_id'],'deleted'=>0])->update('table_order', ['deleted' => 1]);

            if ($total_price == 0)
                $percent_discount = 0;
            else
                $percent_discount = $order['coupon'] / $total_price;

            $receipt = array();
            $receipt['order_id'] = $id;
            $this->db->select_max('receipt_code')->like('receipt_code', 'PT');
            $max_receipt_code = $this->db->get('receipt')->row();
            $max_code = (int)(str_replace('PT', '', $max_receipt_code->receipt_code)) + 1;
            if ($max_code < 10)
                $receipt['receipt_code'] = 'PT000000' . ($max_code);
            else if ($max_code < 100)
                $receipt['receipt_code'] = 'PT00000' . ($max_code);
            else if ($max_code < 1000)
                $receipt['receipt_code'] = 'PT0000' . ($max_code);
            else if ($max_code < 10000)
                $receipt['receipt_code'] = 'PT000' . ($max_code);
            else if ($max_code < 100000)
                $receipt['receipt_code'] = 'PT00' . ($max_code);
            else if ($max_code < 1000000)
                $receipt['receipt_code'] = 'PT0' . ($max_code);
            else if ($max_code < 10000000)
                $receipt['receipt_code'] = 'PT' . ($max_code);

            $receipt['type_id'] = 3;
            $receipt['store_id'] = $store_id;
            $receipt['receipt_date'] = $order['sell_date'];
            $receipt['notes'] = $order['notes'];
            $receipt['total_money'] = $order['customer_pay'] - $total_price + $order['coupon'] < 0 ? $order['customer_pay'] : $total_price - $order['coupon'];
            $receipt['user_init'] = $order['user_init'];
            $this->db->insert('receipt', $receipt);

            $temp = array();
            $temp['transaction_code'] = $order['output_code'];
            $temp['transaction_id'] = $id;
            $temp['customer_id'] = isset($order['customer_id']) ? $order['customer_id'] : 0;
            $temp['date'] = $order['sell_date'];
            $temp['notes'] = $order['notes'];
            $temp['user_init'] = $order['user_init'];
            $temp['type'] = 3;
            $temp['store_id'] = $order['store_id'];
            $canreturn_temp = array();
            $canreturn_temp['store_id'] = $order['store_id'];
            $canreturn_temp['order_id'] = $id;
            $canreturn_temp['user_init'] = $order['user_init'];
            foreach ($detail_order_temp as $item) {
                $report = $temp;
                $stock = $this->db->select('quantity')->from('inventory')->where(['store_id' => $temp['store_id'], 'product_id' => $item['id']])->get()->row_array();
                $product = $this->db->from('products')->where('ID', $item['id'])->get()->row_array();
                if ($product['prd_edit_price'] == 0)
                    $item['price'] = $product['prd_sell_price'];

                $report['origin_price'] = $product['prd_origin_price'] * $item['quantity'];
                $report['product_id'] = $item['id'];
                $report['discount'] = $percent_discount * $item['quantity'] * $item['price'];
                $report['price'] = $item['price'];
                $report['output'] = $item['quantity'];
                $report['stock'] = $stock['quantity'];
                $report['total_money'] = ($report['price'] * $report['output']) - $report['discount'];
                $this->db->insert('report', $report);

                $canreturn = $canreturn_temp;
                $canreturn['product_id'] = $item['id'];
                $canreturn['price'] = $item['price'] - $percent_discount * $item['price'];
                $canreturn['quantity'] = $item['quantity'];
                $this->db->insert('canreturn', $canreturn);
            }


            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo $this->messages = "0";
            } else {
                $this->db->trans_commit();
                echo $this->messages = $id;
            }
        } else
            echo $this->messages = "0";
    }

    public function cms_print_table()
    {
        if ($this->auth == null)
            $this->cms_common_string->cms_redirect(CMS_BASE_URL . 'backend');
        else{
            $data_post = $this->input->post('data');
            $data_template = $this->db->select('content')->from('templates')->where('id', $data_post['id_template'])->limit(1)->get()->row_array();
            $data_order = $this->db->from('table_order')->where('ID', $data_post['id_order'])->get()->row_array();
            $customer_name = '';
            $customer_phone = '';
            $customer_address = '';
            if ($data_order['customer_id'] != null) {
                $customer_name = cms_getNamecustomerbyID($data_order['customer_id']);
                $customer_phone = cms_getPhonecustomerbyID($data_order['customer_id']);
                $customer_address = cms_getAddresscustomerbyID($data_order['customer_id']);
            }

            $table='';
            if($data_order['table_id']==0){
                $table = 'Pos';
            }else{
                $temp = cms_finding_tablebyID($data_order['table_id']);
                $table = $temp['table_name'].' - KV: '.$temp['area_name'];
            }

            $data_template['content'] = str_replace("{Ngay_Xuat}", $data_order['order_date'], $data_template['content']);
            $data_template['content'] = str_replace("{Khach_Hang}", $customer_name, $data_template['content']);
            $data_template['content'] = str_replace("{DT_Khach_Hang}", $customer_phone, $data_template['content']);
            $data_template['content'] = str_replace("{DC_Khach_Hang}", $customer_address, $data_template['content']);
            $data_template['content'] = str_replace("{Phuc_Vu}", cms_getNameAuthbyID($data_order['user_init']), $data_template['content']);
            $data_template['content'] = str_replace("{Ban}", $table, $data_template['content']);
            $data_template['content'] = str_replace("{Ma_Don_Hang}", $data_order['order_code'], $data_template['content']);
            $data_template['content'] = str_replace("{Ghi_Chu}", $data_order['notes'], $data_template['content']);

            $detail = '';
            $detail2 = '';
            $number = 1;
            if (isset($data_order) && count($data_order)) {
                $list_products = json_decode($data_order['detail_order'], true);
                foreach ($list_products as $product) {
                   
                        $prd = cms_finding_productbyID($product['id']);
                        $quantity = $product['quantity'];
                        $product['printed'] = $product['quantity'];
                        $total = $quantity * $product['price'];
                          if($product['price']!=$prd['prd_sell_price']){
                        $prd['prd_name'].=' <b style="text-decoration: line-through;">'.number_format($prd['prd_sell_price']).'</b>';
                    }
                        if($product['note']!=''){
                            $prd['prd_name'].='<br/><i>('.$product['note'].')</i>';
                        }
                        $detail = $detail . '<tr><td style="text-align:center;">' . $number++ . '</td><td  style="text-align:center;">' . $prd['prd_name'] . '</td><td style = "text-align:center">' . $quantity . '</td ><td style = "text-align:center">' . $prd['prd_unit_name'] . '</td ><td  style="text-align:center;">' . $this->cms_common->cms_encode_currency_format($product['price']) . '</td><td style="text-align:center;">' . $this->cms_common->cms_encode_currency_format($total) . '</td ></tr>';
                        $detail2 = $detail2 . '
                <tr>
                    <td>' . $prd['prd_name'] . '</td>
                    <td style = "text-align:center">' . $quantity . '</td >
                    <td style="text-align:center;">' . $this->cms_common->cms_encode_currency_format($total) . '</td >
                </tr>';
                    

                    $detail_order[] = $product;
                }
            }

            $order['detail_order'] = json_encode($detail_order);
            $order['user_upd'] = $this->auth['id'];
            $this->db->where('ID', $data_post['id_order'])->update('table_order', $order);

            $table = '<table border="1" style="width:100%;font-size: 11px;border-collapse:collapse;">
                    <tbody >
                        <tr >
                            <td style="text-align:center;"><strong >STT</strong ></td >
                            <td style="text-align:center;"><strong >Tên sản phẩm</strong ></td >
                            <td style="text-align:center;"><strong >SL</strong ></td >
                            <td style="text-align:center;"><strong >ĐVT</strong ></td >
                            <td style="text-align:center;"><strong >Đơn giá</strong ></td >
                            <td style="text-align:center;"><strong >Thành tiền</strong ></td >
                        </tr >' . $detail . '
                    </tbody >
                 </table >';

            $table2 = '<table border="1" style="width:100%;font-size: 11px;border-collapse:collapse;">
                    <tbody >
                        <tr >
                            <td style="text-align:center;"><strong >Tên SP</strong ></td >
                            <td style="text-align:center;"><strong >SL</strong ></td >
                            <td style="text-align:center;"><strong >Thành tiền</strong ></td >
                        </tr >' . $detail2 . '
                    </tbody >
                 </table >';

            $data_template['content'] = str_replace("{Chi_Tiet_San_Pham}", $table, $data_template['content']);
            $data_template['content'] = str_replace("{Chi_Tiet_San_Pham2}", $table2, $data_template['content']);

            echo $this->messages = $data_template['content'];
        }
    }

    public function cms_crcustomer()
    {
        $data = $this->input->post('data');
        $data = $this->cms_common_string->allow_post($data, ['customer_code', 'customer_name', 'customer_phone', 'customer_email', 'customer_addr', 'notes','customer_image', 'customer_birthday', 'customer_gender']);
        $data['customer_birthday'] = gmdate("Y-m-d H:i:s", strtotime(str_replace('/', '-', $data['customer_birthday'])) + 7 * 3600);
        $data['created'] = gmdate("Y:m:d H:i:s", time() + 7 * 3600);
        $data['user_init'] = $this->auth['id'];
        if ($data['customer_code'] == '') {
            $this->db->select_max('customer_code')->like('customer_code', 'KH');
            $max_customer_code = $this->db->get('customers')->row();
            $max_code = (int)(str_replace('KH', '', $max_customer_code->customer_code)) + 1;
            if ($max_code < 10)
                $data['customer_code'] = 'KH00000' . ($max_code);
            else if ($max_code < 100)
                $data['customer_code'] = 'KH0000' . ($max_code);
            else if ($max_code < 1000)
                $data['customer_code'] = 'KH000' . ($max_code);
            else if ($max_code < 10000)
                $data['customer_code'] = 'KH00' . ($max_code);
            else if ($max_code < 100000)
                $data['customer_code'] = 'KH0' . ($max_code);
            else if ($max_code < 1000000)
                $data['customer_code'] = 'KH' . ($max_code);

            $this->db->insert('customers', $data);
            $id = $this->db->insert_id();
            echo $this->messages = $id;
        } else {
            $count = $this->db->where('customer_code', $data['customer_code'])->from('customers')->count_all_results();
            if ($count > 0) {
                echo $this->messages = "0";
            } else {
                $this->db->insert('customers', $data);
                $id = $this->db->insert_id();
                echo $this->messages = $id;
            }
        }
    }

    public function cms_change_store($store_id)
    {
        if ($this->auth == null)
            $this->cms_common_string->cms_redirect(CMS_BASE_URL . 'backend');

        $user_id = $this->auth['id'];
        $this->db->where('ID', $user_id)->update('users', ['store_id' => $store_id]);
        echo $this->messages = "1";
    }

    public function cms_search_box_customer()
    {
        $data = $this->input->post('data');
        $customer = $this->db->like('customer_name', $data['keyword'])->or_like('customer_phone', $data['keyword'])->or_like('customer_email', $data['keyword'])->or_like('customer_code', $data['keyword'])->from('customers')->get()->result_array();
        $data['data']['customers'] = $customer;
        $this->load->view('ajax/orders/search_box_customer', isset($data) ? $data : null);
    }

    public function cms_check_barcode($keyword)
    {
        $products = $this->db->from('products')->where(array('prd_status' => '1', 'deleted' => '0', 'prd_code' => $keyword))->get()->result_array();
        if (count($products) == 1)
            echo $products[0]['ID'];
        else
            echo 0;
    }

    public function cms_print_order()
    {
        if ($this->auth == null)
            $this->cms_common_string->cms_redirect(CMS_BASE_URL . 'backend');

        $data_post = $this->input->post('data');
        $data_template = $this->db->select('content')->from('templates')->where('id', $data_post['id_template'])->limit(1)->get()->row_array();
        $data_order = $this->db->from('orders')->where('ID', $data_post['id_order'])->get()->row_array();
        $customer_name = '';
        $customer_phone = '';
        $customer_address = '';
        $debt = 0;
        if ($data_order['customer_id'] != null) {
            $customer_name = cms_getNamecustomerbyID($data_order['customer_id']);
            $customer_phone = cms_getPhonecustomerbyID($data_order['customer_id']);
            $customer_address = cms_getAddresscustomerbyID($data_order['customer_id']);
            $order = $this->db
                ->select('sum(lack) as debt')
                ->from('orders')
                ->where(['deleted' => 0, 'order_status' => 1, 'lack >' => 0, 'customer_id' => $data_order['customer_id']])
                ->get()
                ->row_array();
            $debt = $order['debt'];
        }

        $user_name = '';
        if ($data_order['customer_id'] != null)
            $user_name = cms_getNameAuthbyID($data_order['user_init']);

        $data_template['content'] = str_replace("{Ten_Cua_Hang}", cms_getNamestockbyID($data_order['store_id']), $data_template['content']);
        $data_template['content'] = str_replace("{Ngay_Xuat}", $data_order['sell_date'], $data_template['content']);
        $data_template['content'] = str_replace("{Khach_Hang}", $customer_name, $data_template['content']);
        $data_template['content'] = str_replace("{DT_Khach_Hang}", $customer_phone, $data_template['content']);
        $data_template['content'] = str_replace("{DC_Khach_Hang}", $customer_address, $data_template['content']);
        $data_template['content'] = str_replace("{Thu_Ngan}", $user_name, $data_template['content']);
        $data_template['content'] = str_replace("{Tong_Tien_Hang}", $this->cms_common->cms_encode_currency_format($data_order['total_price']), $data_template['content']);
        $data_template['content'] = str_replace("{Chiec_Khau}", $this->cms_common->cms_encode_currency_format($data_order['coupon']), $data_template['content']);
        $data_template['content'] = str_replace("{Tong_Tien}", $this->cms_common->cms_encode_currency_format($data_order['total_money'] - $data_order['coupon']), $data_template['content']);
        $data_template['content'] = str_replace("{Khach_Dua}", $this->cms_common->cms_encode_currency_format($data_order['customer_pay']), $data_template['content']);
        $data_template['content'] = str_replace("{Con_No}", $this->cms_common->cms_encode_currency_format($data_order['lack']), $data_template['content']);
        $data_template['content'] = str_replace("{Ma_Don_Hang}", $data_order['output_code'], $data_template['content']);
        $data_template['content'] = str_replace("{Ghi_Chu}", $data_order['notes'], $data_template['content']);
        $data_template['content'] = str_replace("{So_Tien_Bang_Chu}", cms_convert_number_to_words($data_order['lack']), $data_template['content']);
        $data_template['content'] = str_replace("{Cong_No}", $this->cms_common->cms_encode_currency_format($debt), $data_template['content']);

        $detail = '';
        $detail2 = '';
        $number = 1;
        if (isset($data_order) && count($data_order)) {
            $list_products = json_decode($data_order['detail_order'], true);
            foreach ($list_products as $product) {
                $prd = cms_finding_productbyID($product['id']);
                $quantity = $product['quantity'];
                $total = $quantity * $product['price'];
                  if($product['price']!=$prd['prd_sell_price']){
                        $prd['prd_name'].=' <b style="text-decoration: line-through;">'.number_format($prd['prd_sell_price']).'</b>';
                    }
                if($product['note']!=''){
                        $prd['prd_name'].='<br/><i>('.$product['note'].')</i>';
                    }
                $detail = $detail . '<tr><td style="text-align:center;">' . $number++ . '</td><td  style="text-align:center;">' . $prd['prd_name'] . '</td><td style = "text-align:center">' . $quantity . '</td ><td style = "text-align:center">' . $prd['prd_unit_name'] . '</td ><td  style="text-align:center;">' . $this->cms_common->cms_encode_currency_format($product['price']) . '</td><td style="text-align:center;">' . $this->cms_common->cms_encode_currency_format($total) . '</td ></tr>';
                $detail2 = $detail2 . '
                <tr>
                    <td>' . $prd['prd_name'] . '</td>
                    <td style = "text-align:center">' . $quantity . '</td >
                    <td style="text-align:center;">' . $this->cms_common->cms_encode_currency_format($total) . '</td >
                </tr>';
            }
        }

        $table = '<table border="1" style="width:100%;font-size: 11px;border-collapse:collapse;">
                    <tbody >
                        <tr >
                            <td style="text-align:center;"><strong >STT</strong ></td >
                            <td style="text-align:center;"><strong >Tên sản phẩm</strong ></td >
                            <td style="text-align:center;"><strong >SL</strong ></td >
                            <td style="text-align:center;"><strong >ĐVT</strong ></td >
                            <td style="text-align:center;"><strong >Đơn giá</strong ></td >
                            <td style="text-align:center;"><strong >Thành tiền</strong ></td >
                        </tr >' . $detail . '
                    </tbody >
                 </table >';

        $table2 = '<table border="1" style="width:100%;font-size: 11px;border-collapse:collapse;">
                    <tbody >
                        <tr >
                            <td style="text-align:center;"><strong >Tên SP</strong ></td >
                            <td style="text-align:center;"><strong >SL</strong ></td >
                            <td style="text-align:center;"><strong >Thành tiền</strong ></td >
                        </tr >' . $detail2 . '
                    </tbody >
                 </table >';

        $data_template['content'] = str_replace("{Chi_Tiet_San_Pham}", $table, $data_template['content']);
        $data_template['content'] = str_replace("{Chi_Tiet_San_Pham2}", $table2, $data_template['content']);

        echo $this->messages = $data_template['content'];
    }

    public function cms_create_area()
    {
        if ($this->auth == null)
            $this->cms_common_string->cms_redirect(CMS_BASE_URL . 'backend');
        else {
            $this->db->trans_begin();

            $data = $this->input->post('data');
            $area = $this->db->from('area')->where(['area_name' => $data['area_name'], 'store_id' => $data['store_id'], 'deleted' => 0])->get()->row_array();
            if (!empty($area) && count($area)) {
                echo $this->messages = '0';
                return;
            } else {
                $data['user_init'] = $this->auth['id'];
                $this->db->insert('area', $data);
                $id = $this->db->insert_id();
                $table = array();
                $table['user_init'] = $this->auth['id'];
                for ($i = 1; $i <= $data['number_table']; $i++) {
                    $table['table_name'] = $i;
                    $table['area_id'] = $id;
                    $this->db->insert('table', $table);
                }
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                echo $this->messages = "0";
            } else {
                $this->db->trans_commit();
                echo $this->messages = "1";
            }
        }
    }

    public function cms_paging_area($page = 1)
    {
        $config = $this->cms_common->cms_pagination_custom();

        $total_area = $this->db
            ->from('area')
            ->join('stores', 'stores.ID=area.store_id', 'INNER')
            ->where('cms_area.deleted', 0)
            ->count_all_results();
        $data['_list_area'] = $this->db
            ->select('cms_area.ID,area_name,number_table,store_name')
            ->from('area')
            ->where('cms_area.deleted', 0)
            ->join('stores', 'stores.ID=area.store_id', 'INNER')
            ->limit($config['per_page'], ($page - 1) * $config['per_page'])
            ->order_by('cms_area.created', 'desc')
            ->get()
            ->result_array();

        $config['base_url'] = 'cms_paging_area';
        $config['total_rows'] = $total_area;
        $config['per_page'] = 10;
        $this->pagination->initialize($config);
        $_pagination_link = $this->pagination->create_links();
        $data['total_area'] = $total_area;
        if ($page > 1 && ($total_area - 1) / ($page - 1) == 10)
            $page = $page - 1;

        $data['page'] = $page;
        $data['_pagination_link'] = $_pagination_link;
        $this->load->view('ajax/cafe/list_area', isset($data) ? $data : null);
    }

    public function cms_del_temp_area($id)
    {
        $id = (int)$id;
        $check_area = $this->db->from('area')->where(['deleted' => 0, 'ID' => $id])->get()->row_array();
        if (!isset($check_area) || count($check_area) == 0) {
            echo $this->messages = '0';
            return;
        } else {
            $this->db->where(['deleted' => 0, 'ID' => $id])->update('area', ['deleted' => '1', 'user_upd' => $this->auth['id']]);
            echo $this->messages = '1';
        }
    }
}

