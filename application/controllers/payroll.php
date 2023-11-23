<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class payroll extends CI_Controller
{
    private $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = $this->cms_authentication->check();
    }
    public function index()
    {
        $data['seo']['title'] = "Phần mềm quản lý bán hàng";
        $data['data']['user'] = $this->auth;
        $data['template'] = 'payroll/index';
        $store = $this->db->from('stores')->get()->result_array();
        $data['data']['store'] = $store;
        $store_id = $this->db->select('store_id')->from('users')->where('id', $this->auth['id'])->limit(1)->get()->row_array();
        $data['data']['store_id'] = $store_id['store_id'];
        $payroll_data = $this->db->get('payroll')->result_array();
        $data['payroll'] = $payroll_data;
        // echo "<pre>";
        // print_r( $data['payroll']);die;

        $this->load->view('layout/index', isset($data) ? $data : null);
    }

    public function cms_save_payroll()
    {
        $data = $this->input->post('data');
        $this->db->order_by('id', 'DESC');
        $max_payment_code = $this->db->get('payroll')->row();

        if ($max_payment_code) {
            $max_code = (int)(str_replace('BL', '', $max_payment_code->ticket_number)) + 1;
        } else {
            $max_code = 1;
        }
    
        if ($max_code < 10)
            $data['ticket_number'] = 'BL000000' . ($max_code);
        else if ($max_code < 100)
            $data['ticket_number'] = 'BL00000' . ($max_code);
        else if ($max_code < 1000)
            $data['ticket_number'] = 'BL0000' . ($max_code);
        else if ($max_code < 10000)
            $payment['ticket_number'] = 'BL000' . ($max_code);
        else if ($max_code < 100000)
            $data['ticket_number'] = 'BL00' . ($max_code);
        else if ($max_code < 1000000)
            $data['ticket_number'] = 'BL0' . ($max_code);
        else if ($max_code < 10000000)
            $data['ticket_number'] = 'BL' . ($max_code);

        $this->db->insert('payroll', $data);
        echo $this->messages = "1";
    }

    
    public function cms_update_payroll($id)
    {
        $data = $this->input->post('data');
        $this->db->where('id', $id)->update('payroll', $data);
        echo $this->messages = "1";
    }

    public function cms_delete_payroll($id){
        $this->db->where('id', $id)->delete('payroll');
        echo $this->messages = "1";
    }

    public function cms_paging_payroll($page = 1)
    {
        $option = $this->input->post('data');
        $config = $this->cms_common->cms_pagination_custom();
        $option['date_to'] = date('Y-m-d', strtotime($option['date_to'] . ' +1 day'));
           
        if ($option['date_from'] != '' && $option['date_to'] != '') { 
            $this->db->where('date >=', $option['date_from']);
            $this->db->where('date <=', $option['date_to']);
        }
        if (!empty($option['keyword'])) {
            $this->db->like('ticket_number', $option['keyword']);
        }
        if ($option['option1'] != '-1') {
            $this->db->where('payment_method', $option['option1']);
        }
            $payroll_data = $this->db->get('payroll')->result_array();
            $data['_list_payment'] = $payroll_data;
            

        $this->load->view('ajax/payroll/list_payroll', isset($data) ? $data : null);
    }

}
