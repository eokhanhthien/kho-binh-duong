<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/* Get value after form submited */
if (!function_exists('cms_common_input')) {
    function cms_common_input($obj, $item)
    {
        return (isset($obj[$item]) && !empty($obj[$item])) ? htmlspecialchars($obj[$item]) : '';
    }
}

if (!function_exists('cms_finding_tablebyID')) {
    function cms_finding_tablebyID($id)
    {
        $CI = &get_instance();
        $table = $CI->db
            ->select('table_name,cms_table.updated,table_status,area_name')
            ->where('cms_table.ID', $id)
            ->from('table')
            ->join('area', 'area.ID=table.area_id', 'INNER')
            ->get()
            ->row_array();
        return $table;
    }
}

if (!function_exists('cms_ConvertDateTime')) {

    function cms_ConvertDateTime($date)

    {


        return $date == '' ? '' : date('d/m/Y H:i:s', strtotime($date));
    }
}

/*
 * Render status
/*****************************************/
if (!function_exists('cms_render_html')) {
    function cms_render_html($val, $class, $icon = [], $text = [])
    {
        return ($val == 1) ? "<span class='{$class}'><i class='fa {$icon[0]}'></i> " . $text[0] . "</span>" : "<span class='{$class}'><i class='fa {$icon[1]}'></i> " . $text[1] . "</span>";
    }
}
/*
 * số lượng nhân viên theo nhóm
/*****************************************/
if (!function_exists('cms_getEmployee')) {
    function cms_getEmployee($gid)
    {
        $CI = &get_instance();
        $count = $CI->db->where('group_id', $gid)->from('users')->count_all_results();

        return (!isset($count) && !empty($count)) ? '-' : $count;
    }
}

if (!function_exists('cms_getNameReceiptMethodByID')) {
    function cms_getNameReceiptMethodByID($id)
    {
        $list = ['1' => 'Tiền mặt', '2' => 'Thẻ', '3' => 'CK'];
        return $list[$id];
    }
}

if (!function_exists('cms_fullURL')) {
    function cms_fullURL()
    {
        return "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }
}

if (!function_exists('cms_convert_number_to_words')) {
    function cms_convert_number_to_words($number)
    {
        $hyphen = ' ';
        $conjunction = '  ';
        $separator = ' ';
        $negative = 'âm ';
        $decimal = ' phẩy ';
        $dictionary = array(
            0 => 'Không',
            1 => 'Một',
            2 => 'Hai',
            3 => 'Ba',
            4 => 'Bốn',
            5 => 'Năm',
            6 => 'Sáu',
            7 => 'Bảy',
            8 => 'Tám',
            9 => 'Chín',
            10 => 'Mười',
            11 => 'Mười một',
            12 => 'Mười hai',
            13 => 'Mười ba',
            14 => 'Mười bốn',
            15 => 'Mười năm',
            16 => 'Mười sáu',
            17 => 'Mười bảy',
            18 => 'Mười tám',
            19 => 'Mười chín',
            20 => 'Hai mươi',
            30 => 'Ba mươi',
            40 => 'Bốn mươi',
            50 => 'Năm mươi',
            60 => 'Sáu mươi',
            70 => 'Bảy mươi',
            80 => 'Tám mươi',
            90 => 'Chín mươi',
            100 => 'trăm',
            1000 => 'ngàn',
            1000000 => 'triệu',
            1000000000 => 'tỷ',
            1000000000000 => 'nghìn tỷ',
            1000000000000000 => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . cms_convert_number_to_words(abs($number));
        }

        $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens = ((int)($number / 10)) * 10;
                $units = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . cms_convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = cms_convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= cms_convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}

if (!function_exists('cms_getNamegroupbyID')) {
    function cms_getNamegroupbyID($id)
    {
        $name = 'Chưa có';
        $CI = &get_instance();
        $group = $CI->db->select('prd_group_name')->from('products_group')->where('ID', $id)->get()->row_array();
        if (isset($group) && count($group)) {
            return $name = $group['prd_group_name'];
        }

        return $name;
    }
}
if (!function_exists('cms_getNamemanufacturebyID')) {
    function cms_getNamemanufacturebyID($id)
    {
        $name = 'Chưa có';
        $CI = &get_instance();
        $manufacture = $CI->db->select('prd_manuf_name')->from('products_manufacture')->where('ID', $id)->get()->row_array();
        if (isset($manufacture) && count($manufacture)) {
            $name = $manufacture['prd_manuf_name'];
        }

        return $name;
    }
}

if (!function_exists('cms_getNameunitbyID')) {
    function cms_getNameunitbyID($id)
    {
        $name = 'Chưa có';
        $CI = &get_instance();
        $unit = $CI->db->select('prd_unit_name')->from('products_unit')->where('ID', $id)->get()->row_array();
        if (isset($unit) && count($unit)) {
            $name = $unit['prd_unit_name'];
        }

        return $name;
    }
}

if (!function_exists('cms_getNamecustomerbyID')) {
    function cms_getNamecustomerbyID($id)
    {
        $name = 'Không nhập';
        $CI = &get_instance();
        $customer = $CI->db->select('customer_name')->from('customers')->where('ID', $id)->get()->row_array();
        if (isset($customer) && count($customer)) {
            $name = $customer['customer_name'];
        }

        return $name;
    }
}

if (!function_exists('cms_getAddresscustomerbyID')) {
    function cms_getAddresscustomerbyID($id)
    {
        $name = 'Không nhập';
        $CI = &get_instance();
        $customer = $CI->db->select('customer_addr')->from('customers')->where('ID', $id)->get()->row_array();
        if (isset($customer) && count($customer)) {
            $name = $customer['customer_addr'];
        }

        return $name;
    }
}

if (!function_exists('cms_getPhonecustomerbyID')) {
    function cms_getPhonecustomerbyID($id)
    {
        $name = 'Không nhập';
        $CI = &get_instance();
        $customer = $CI->db->select('customer_phone')->from('customers')->where('ID', $id)->get()->row_array();
        if (isset($customer) && count($customer)) {
            $name = $customer['customer_phone'];
        }

        return $name;
    }
}

if (!function_exists('cms_getNamesupplierbyID')) {
    function cms_getNamesupplierbyID($id)
    {
        $name = 'Không nhập';
        $CI = &get_instance();
        $customer = $CI->db->select('supplier_name')->from('suppliers')->where('ID', $id)->get()->row_array();
        if (isset($customer) && count($customer)) {
            $name = $customer['supplier_name'];
        }

        return $name;
    }
}

if (!function_exists('cms_getNameVATbyID')) {
    function cms_getNameVATbyID($id)
    {
        $list = cms_getListVAT();
        return $list[$id];
    }
}

if (!function_exists('cms_getListVAT')) {
    function cms_getListVAT()
    {
        return array(
            '0' => '0%',
            '5' => '5%',
            '10' => '10%'
        );
    }
}

if (!function_exists('cms_getListReceiptType')) {
    function cms_getListReceiptType()
    {
        return array(
            '3' => 'Thu bán hàng',
            '4' => 'Thu khách lẻ',
            '5' => 'Thu HĐGTGT',
            '6' => 'Thu khác'
        );
    }
}

if (!function_exists('cms_getListPaymentType')) {
    function cms_getListPaymentType()
    {
        return array(
            '2' => 'Chi mua hàng',
            '3' => 'Chi nhập hàng',
            '4' => 'Tiền xăng',
            '5' => 'Thuê xe và gửi hàng',
            '6' => 'Tiền ứng',
            '7' => 'Chi khác'
        );
    }
}

if (!function_exists('cms_getListReporttype')) {
    function cms_getListReporttype()
    {
        return array(
            '1' => 'Tạo sản phẩm mới',
            '2' => 'Nhập hàng',
            '3' => 'Bán hàng',
            '4' => 'Chuyển hàng',
            '5' => 'Xác nhận nhập kho',
            '6' => 'Nhập trả hàng',
            '7' => 'Xuất trả hàng'
        );
    }
}

if (!function_exists('cms_getNameReportTypeByID')) {
    function cms_getNameReportTypeByID($id)
    {
        $list = cms_getListReporttype();
        return $list[$id];
    }
}

if (!function_exists('cms_getNamePaymentTypeByID')) {
    function cms_getNamePaymentTypeByID($id)
    {
        $list = cms_getListPaymentType();
        return $list[$id];
    }
}

if (!function_exists('cms_getNameReceiptTypeByID')) {
    function cms_getNameReceiptTypeByID($id)
    {
        $list = cms_getListReceiptType();
        return $list[$id];
    }
}

if (!function_exists('cms_getNamestatusbyID')) {
    function cms_getNamestatusbyID($id)
    {
        $name = "";
        switch ($id) {
            case '0': {
                    $name = 'Khởi tạo';
                    break;
                }
            case '1': {
                    $name = 'Hoàn thành';
                    break;
                }
            case '2': {
                    $name = 'Xác nhận';
                    break;
                }
            case '3': {
                    $name = 'Đang giao';
                    break;
                }
            case '4': {
                    $name = 'Đã giao';
                    break;
                }
            case '5': {
                    $name = 'Hủy';
                    break;
                }
        }
        return $name;
    }
}

if (!function_exists('cms_finding_productbyID')) {
    function cms_finding_productbyID($id)
    {
        $CI = &get_instance();
        $product = $CI->db
            ->select('products.ID,infor,prd_code,prd_unit_name,prd_name, prd_sell_price, prd_image_url,prd_edit_price')
            ->where('products.ID', $id)
            ->from('products')
            ->join('products_unit', 'products_unit.ID=products.prd_unit_id', 'LEFT')
            ->get()
            ->row_array();
        return $product;
    }
}

if (!function_exists('cms_getNameAuthbyID')) {
    function cms_getNameAuthbyID($id)
    {
        $name = "Không nhập";
        $CI = &get_instance();
        $customer = $CI->db->select('display_name')->from('users')->where('id', $id)->get()->row_array();
        if (isset($customer) && count($customer)) {
            $name = $customer['display_name'];
        }

        return $name;
    }
}

if (!function_exists('cms_getNamestockbyID')) {

    function cms_getNamestockbyID($id)
    {
        $name = "không xác định";
        $CI = &get_instance();
        $customer = $CI->db->select('store_name')->from('stores')->where('ID', $id)->get()->row_array();
        if (isset($customer) && count($customer)) {
            $name = $customer['store_name'];
        }

        return $name;
    }
}

if (!function_exists('cms_encode_currency_format')) {
    function cms_encode_currency_format($priceFloat)
    {
        $symbol_thousand = ',';
        $decimal_place = 0;
        if ($priceFloat == '')
            return $priceFloat;

        if ($priceFloat == 0)
            return 0;

        $price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
        return $price;
    }
}
if (!function_exists('cms_getTotalCongNo')) {

    function cms_getTotalCongNo($customer_id = 0, $ngay = '')
    {
        $total = "0";
        $CI = &get_instance();
        $customer = $CI->db->select('sum(total_money) as total')->from('orders')->where('customer_id', $customer_id)->where('deleted', 0)->where('date(created)<=', $ngay)->get()->row_array();



        if (isset($customer) && count($customer)) {
            $total = $customer['total'];
        }

        $payment = $CI->db->select("sum(cms_receipt.total_money) as money")
            ->from('receipt')
            ->join('orders', 'cms_orders.id=receipt.order_id', 'LEFT')
            ->where('orders.deleted', 0)
            ->where('receipt.deleted', 0)
            ->where('customer_id', $customer_id)
            ->where('date(receipt_date)<=', $ngay)->get()->row_array();

        //print_r($CI->db->last_query()); 
        if (isset($payment) && count($payment)) {
            $total = $total - $payment['money'];
        }
        return $total;
    }
}
if (!function_exists('cms_getListCustomer')) {
    function cms_getListCustomer()
    {
        $CI = &get_instance();
        $customers = $CI->db->select('cms_customers.ID, cms_customers.customer_name, cms_customers.customer_phone')->from('cms_customers')->get()->result_array();
        return $customers;
    }
}
