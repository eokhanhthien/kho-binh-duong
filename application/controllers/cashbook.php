<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Cashbook extends CI_Controller
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
        if ($this->auth == null || !in_array(20, $this->auth['group_permission']))
            $this->cms_common_string->cms_redirect(CMS_BASE_URL . 'backend');

        $data['seo']['title'] = "Phần mềm quản lý bán hàng";

        $stores = $this->db->from('stores')->get()->result_array();
        $data['user'] = $this->auth;
        if (!empty($stores) && count($stores) != 0) {
            $ind = 0;
            $html = '';
            foreach ($stores as $store) {
                $receipt = $this->db->select('sum(total_money) as total_money')->from('receipt')->where(['deleted' => 0, 'store_id' => $store['ID']])->get()->row_array();
                $payment = $this->db->select('sum(total_money) as total_money')->from('payment')->where(['deleted' => 0, 'store_id' => $store['ID']])->get()->row_array();
                $ind++;
                $html .= "<tr class='tr-item-{$store['ID']}'>";
                $html .= '<td class="text-center ind">' . $ind . '</td>';
                $html .= '<td>' . $store['store_name'] . '</td>';
                $html .= '<td>' . cms_encode_currency_format($receipt['total_money']=='' ? 0 : $receipt['total_money']) . '</td>';
                $html .= '<td>' . cms_encode_currency_format($payment['total_money']=='' ? 0 :$payment['total_money']) . '</td>';
                $html .= '<td>' . cms_encode_currency_format($receipt['total_money'] - $payment['total_money']) . '</td>';
                $html .= '</tr>';
            }
            $data['data'] = $html;

        }

        $data['template'] = 'cashbook/index';
        $this->load->view('layout/index', isset($data) ? $data : null);
    }
}

