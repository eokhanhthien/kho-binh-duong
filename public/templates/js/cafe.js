$(document).ready(function () {
    "use strict";

    $('#customer_birthday').datetimepicker({
        timepicker: false,
        format: 'Y/m/d',
        formatDate: 'Y/m/d',
        autoclose: true,
        defaultDate: '1989/01/01'
    });

    $('#store_id').on('change', function () {
        var store_id = $("#store_id").val();
        var $param = {
            'type': 'POST',
            'url': 'cafe/cms_change_store/' + store_id,
            'callback': function (data) {
                if (data != '1') {
                    $('.ajax-error-ct').html(data).parent().fadeIn().delay(1000).fadeOut('slow');
                } else {
                    $('.ajax-success-ct').html('Đổi chi nhánh làm việc thành công!').parent().fadeIn().delay(1000).fadeOut('slow');
                }
            }
        };
        cms_adapter_ajax($param);
        cms_load_list_area();
        cms_load_list_table('-1', 0);
    });

    $('#area_id').on('change', function () {
        cms_load_list_table('-1', 1);
    });

    cms_paging_area(1);
    cms_load_list_table('-1', 1);
    cms_search_box_customer();
});

$(document).on('ready ajaxComplete', function () {
    cms_func_common();
});

function cms_load_list_product($category_id) {
    $('.category_list').removeClass('active');
    $('#category_'+$category_id).addClass('active');
    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_load_list_product/'+$category_id,
        'data': null,
        'callback': function (data) {
            $('#list_product').html(data);
        }
    };
    cms_adapter_ajax($param);
}

function cms_func_common() {
    "use strict";

    cms_del_pro_order();
    fix_height_sidebar();
    cms_del_icon_click('.del-cys', '#search-box-cys');
    btnClick('.btn-smf', '.btn-sm-after');

    $("input.discount-order").keyup(function () {
        cms_load_infor_order();
    });

    $("input.quantity_product_order").keyup(function () {
        cms_load_infor_order();
    });

    $("input.price-order").keyup(function () {
        cms_load_infor_order();
    });

    $('#vat').on('change', function () {
        cms_load_infor_order();
    });

    $("input.discount-percent-order").keyup(function () {
        cms_load_infor_order();
    });

    $(".customer-pay").keyup(function () {
        var customer_pay;
        if ($('input.customer-pay').val() == '')
            customer_pay = 0;
        else
            customer_pay = cms_decode_currency_format($('input.customer-pay').val());

        var total_after_discount = cms_decode_currency_format($('.total-after-discount').text());
        var debt = total_after_discount - customer_pay;

        if (debt >= 0) {
            $('div.debt').text(cms_encode_currency_format(debt));
            $('label.debt').text('Nợ');
        }
        else {
            $('div.debt').text(cms_encode_currency_format(-debt));
            $('label.debt').text('Tiền thừa');
        }
    });


    $('.ajax-success').popover('show');

    $('ul.pagination li.active').click(function (event) {
        event.preventDefault();
    });

    $(".txtNumber").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $(".txtMoney").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl+A, Command+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $(".txtMoney").keyup(function () {
        if ($(this).val() == '')
            $(this).val(0);
        else {
            var value = cms_decode_currency_format($(this).val());
            $(this).val(cms_encode_currency_format(value));
        }
    });
}

function hotkey(e) {
    var keycode = (e.keyCode ? e.keyCode : e.which);
    //F2
    if (keycode == '113') {
        $('#search-pro-box').focus();
    }
    //F4
    if (keycode == '115') {
        $('#search-box-cys').focus();
    }
    //F7
    if (keycode == '118') {
        $('.discount-order').focus();
    }
    //F8
    if (keycode == '119') {
        $('.customer-pay').focus();
    }
    //F9
    if (keycode == '120') {
        cms_save_orders(3);
    }
    //F10
    if (keycode == '121') {
        cms_save_orders(4);
    }
}

function cms_del_pro_order() {
    $('body').on('click', '.del-pro-order', function () {
        $(this).parents('tr').remove();
        cms_load_infor_order();
        $seq = 0;
        $('tbody#pro_search_append tr').each(function () {
            $seq += 1;
            value_input = $(this).find('td.seq').text($seq);
        });
    });
}

function cms_del_pro_order_sell() {
    $('body').on('click', '.del-pro-sell', function () {
        $(this).parents('tr').remove();
        cms_load_infor_order_return();
        $seq = 0;
        $('tbody#product_sell tr').each(function () {
            $seq += 1;
            value_input = $(this).find('td.seq').text($seq);
        });
    });
}

function cms_adapter_ajax($param) {
    $.ajax({
        url: $param.url,
        type: $param.type,
        data: $param.data,
        async: true,
        success: $param.callback
    });
}

function cms_reset_valCustomer() {
    'use strict';
    $('#customer_code').val('');
    $('#customer_name').val('');
    $('#customer_phone').val('');
    $('#customer_email').val('');
    $('#customer_addr').val('');
    $('#customer_notes').val('');
    $('#customer_birthday').val('');
    $('.customer_gender').val(0);
}


function cms_crCustomer() {
    "use strict";
    var $code = $.trim($('#customer_code').val());
    var $name = $.trim($('#customer_name').val());
    var $customer_image = $('#customer_img_preview').text();
    var $phone = $.trim($('#customer_phone').val());
    var $mail = $.trim($('#customer_email').val());
    var $address = $('#customer_addr').val();
    var $notes = $('#customer_notes').val();
    var $birthday = $('#customer_birthday').val();
    var $gender = 0;
    $('.customer_gender').each(function (index) {
        if ($(this).prop('checked') == true) {
            $gender = $(this).val();
        }
    });
    if ($name.length == 0) {
        $('.error-customer_name').text('Vui lòng nhập tên khách hàng.');
    } else {
        $('.error-group_name').text('');
        if ($phone.length != 0) {
            if (!$.isNumeric($phone)) {
                $('.error-customer_phone').text('Số điện thoại phải là số.');
                return;
            } else {
                $('.error-customer_phone').text('');
            }
        }
        var $data = {
            'data': {
                'customer_code': $code,
                'customer_image': $customer_image,
                'customer_name': $name,
                'customer_phone': $phone,
                'customer_email': $mail,
                'customer_addr': $address,
                'notes': $notes,
                'customer_birthday': $birthday,
                'customer_gender': $gender
            }
        };
        var $param = {
            'type': 'POST',
            'url': 'cafe/cms_crcustomer',
            'data': $data,
            'callback': function (data) {
                if (data > 0) {
                    $('.btn-close').trigger('click');
                    $('.ajax-success-ct').html('Bạn đã tạo mới khách hàng thành công!').parent().fadeIn().delay(1000).fadeOut('slow');
                    $("#search-box-cys").prop('readonly', true).attr('data-id', data).val($name);
                    $(".del-cys").html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');
                    cms_paging_listcustomer(1);
                    cms_reset_valCustomer();
                }
                else {
                    $('.ajax-error-ct').html('Mã khách hàng đã tồn tại, Vui lòng chọn mã khác').parent().fadeIn().delay(1000).fadeOut('slow');
                }
            }
        };
        cms_adapter_ajax($param);
    }
}

function cms_print_order($id_template, $id_order) {
    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_print_order',
        'data': {'data': {'id_template': $id_template, 'id_order': $id_order}},
        'callback': function (data) {
            var mywindow = window.open('', 'In hóa đơn', 'height=800,width=1200');
            if (mywindow == null) {
                alert('Trình duyệt đã ngăn không cho phần mềm In. Vui lòng mở khóa hiển thị In ở góc phải phía trên của trình duyệt');
            } else {
                mywindow.document.writeln(data);
                mywindow.document.close();
                mywindow.focus();
                mywindow.print();
                mywindow.close();
                return true;
            }
        }
    };
    cms_adapter_ajax($param);
}

function cms_print_order_in_create($id_template, $id_order) {
    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_print_order',
        'data': {'data': {'id_template': $id_template, 'id_order': $id_order}},
        'callback': function (data) {
            var mywindow = window.open('', 'In hóa đơn', 'height=800,width=1200');
            if (mywindow == null) {
                alert('Trình duyệt đã ngăn không cho phần mềm In. Vui lòng mở khóa hiển thị In ở góc phải phía trên của trình duyệt');
            } else {
                mywindow.document.writeln(data);
                mywindow.document.close();
                mywindow.focus();
                mywindow.print();
                mywindow.close();
                cms_vsell_order();
                return true;
            }
        }
    };
    cms_adapter_ajax($param);
}

function cms_print_table_in_pos($id_template, $id_order) {
    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_print_table',
        'data': {'data': {'id_template': $id_template, 'id_order': $id_order}},
        'callback': function (data) {
            var mywindow = window.open('', 'In hóa đơn', 'height=800,width=1200');
            if (mywindow == null) {
                alert('Trình duyệt đã ngăn không cho phần mềm In. Vui lòng mở khóa hiển thị In ở góc phải phía trên của trình duyệt');
            } else {
                mywindow.document.writeln(data);
                mywindow.document.close();
                mywindow.focus();
                mywindow.print();
                mywindow.close();
                location.reload();
                return true;
            }
        }
    };
    cms_adapter_ajax($param);
}

function cms_print_order_in_pos($id_template, $id_order) {
    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_print_order',
        'data': {'data': {'id_template': $id_template, 'id_order': $id_order}},
        'callback': function (data) {
            var mywindow = window.open('', 'In hóa đơn', 'height=800,width=1200');
            if (mywindow == null) {
                alert('Trình duyệt đã ngăn không cho phần mềm In. Vui lòng mở khóa hiển thị In ở góc phải phía trên của trình duyệt');
            } else {
                mywindow.document.writeln(data);
                mywindow.document.close();
                mywindow.focus();
                mywindow.print();
                mywindow.close();
                location.reload();
                return true;
            }

        }
    };
    cms_adapter_ajax($param);
}

function cms_create_area($cont) {
    'use strict';
    var $area_name = $.trim($('#area_name').val());
    var $number_table = $('#number_table').val();
    var $store_id = $('#list_store').val();
    var $data = {'data': {'area_name': $area_name, 'number_table': $number_table, 'store_id': $store_id}};
    if ($area_name.length == 0) {
        alert('Nhập tên khu vực.');
    } else {
        var $param = {
            'type': 'POST',
            'url': 'cafe/cms_create_area',
            'data': $data,
            'callback': function (data) {
                if (data == '1') {
                    cms_paging_area(1);
                    cms_load_list_area();
                    $('.ajax-success-ct').html('Tạo khu vực thành công.').parent().fadeIn().delay(1000).fadeOut('slow');
                    $('#area_name').val('');
                    $('#number_table').val('');
                    if ($cont == 1)
                        $('.btn-close').trigger('click');
                } else if (data == '0') {
                    $('.ajax-error-ct').html('Tên khu vực đã tồn tại trong hệ thống. Vui lòng chọn tên khác.').parent().fadeIn().delay(1000).fadeOut('slow');
                } else {
                    $('.ajax-error-ct').html('Opps! Something went wrong. please try again!').parent().fadeIn().delay(1000).fadeOut('slow');
                }
            }
        };
        cms_adapter_ajax($param);
    }
}

function cms_load_list_area() {
    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_load_list_area',
        'data': null,
        'callback': function (data) {
            $('#prd_group_id').html(data);
        }
    };
    cms_adapter_ajax($param);
}

function cms_load_list_area() {
    $store_id = $('#store_id').val();
    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_load_list_area/' + $store_id,
        'data': null,
        'callback': function (data) {
            $('#area_id').html(data);
        }
    };
    cms_adapter_ajax($param);
}

function cms_load_pos($table_id) {
    if($table_id == '-1'){
        $('#left_pos').hide();
        // $('#right_pos').removeClass('col-md-6').addClass('col-md-12');
        $('#right_pos').show();
    }else{
        $('.pos').addClass('col-md-6');
        $('#right_pos').show();

        var $param = {
            'type': 'POST',
            'url': 'cafe/cms_load_pos/' + $table_id,
            'data': null,
            'callback': function (data) {
                $('.content').html(data);
            }
        };
        cms_adapter_ajax($param);
    }
}

function cms_load_list_table($status, $area) {
    $('.pos').removeClass('col-md-6');
    $('#right_pos').hide();

    $store_id = $('#store_id').val();
    if ($area != 0) {
        $area_id = $('#area_id').val();
    } else {
        $area_id = '-1';
    }

    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_load_list_table/' + $store_id + '/' + $area_id + '/' + $status,
        'data': null,
        'callback': function (data) {
            $('.content').html(data);
        }
    };
    cms_adapter_ajax($param);
}

function cms_autocomplete_enter_sell() {
    $barcode = $("#search-pro-box").val();
    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_check_barcode/' + $barcode,
        'data': null,
        'callback': function (data) {
            if (data > 0) {
                cms_select_product_sell(data);
                $(this).val('');
            }
        }
    };
    cms_adapter_ajax($param);
}

function cms_search_box_customer() {
    $("body").on('keyup ajaxComplete', '#search-box-cys', function () {
        $('#cys-suggestion-box').show();
        $key = $(this).val();
        if ($key.length == 0) {
            $('#cys-suggestion-box').hide();
        } else {
            var $param = {
                'type': 'POST',
                'url': 'cafe/cms_search_box_customer/',
                'data': {'data': {'keyword': $key}},
                'callback': function (data) {
                    if (data.length != 0) {
                        $('.search-cys-inner').html(data);
                    } else {
                        $('.search-cys-inner').html('Không có kết quả phù hợp');
                    }
                }
            };
            cms_adapter_ajax($param);
        }
    });
}

function cms_select_product_sell($id) {
    if ($('tbody#pro_search_append tr').length != 0) {
        $flag = 0;
        $('tbody#pro_search_append tr').each(function () {
            $id_temp = $(this).attr('data-id');
            if ($id == $id_temp) {
                value_input = $(this).find('input.quantity_product_order');
                value_input.val(parseInt(value_input.val()) + 1);
                $flag = 1;
                cms_load_infor_order();
                return false;
            }
        });
        if ($flag == 0) {
            var $seq = parseInt($('td.seq').last().text()) + 1;
            var $param = {
                'type': 'POST',
                'url': 'cafe/cms_select_product/',
                'data': {'id': $id, 'seq': $seq},
                'callback': function (data) {
                    $('#pro_search_append').append(data);
                    cms_load_infor_order();
                }
            };
            cms_adapter_ajax($param);
        }
    } else {
        var $param = {
            'type': 'POST',
            'url': 'cafe/cms_select_product/',
            'data': {'id': $id, 'seq': 1},
            'callback': function (data) {
                $('#pro_search_append').append(data);
                cms_load_infor_order();
            }
        };
        cms_adapter_ajax($param);
    }
}

function cms_selected_cys($id) {
    $name = $('li.data-cys-name-' + $id).text();
    $("#search-box-cys").prop('readonly', true).attr('data-id', $id).val($name);
    $(".del-cys").html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');
    $('#cys-suggestion-box').hide();
}

function cms_selected_mas($id) {
    $name = $('li.data-cys-name-' + $id).text();
    $("#search-box-mas").prop('readonly', true).attr('data-id', $id).val($name);
    $(".del-mas").html('<i class="fa fa-minus-circle" aria-hidden="true"></i>');
    $('#mas-suggestion-box').hide();
}

function cms_save_order(type, $table_id) {
    if ($('tbody#pro_search_append tr').length == 0) {
        $('.ajax-error-ct').html('Xin vui lòng chọn ít nhất 1 sản phẩm cần xuất trước khi lưu đơn hàng. Xin cảm ơn!').parent().fadeIn().delay(1000).fadeOut('slow');
    } else {
        $customer_id = typeof $('#search-box-cys').attr('data-id') === 'undefined' ? 0 : $('#search-box-cys').attr('data-id');
        $store_id = $('#store_id').val();
        $note = $('#note-order').val();
        $discount = cms_decode_currency_format($('input.discount-order').val());
        $customer_pay = cms_decode_currency_format($('.customer-pay').val());
        $detail = [];
        $('tbody#pro_search_append tr').each(function () {
            $id = $(this).attr('data-id');
            $quantity = $(this).find('input.quantity_product_order').val();
            $price = cms_decode_currency_format($(this).find('input.price-order').val());
             $notes = $(this).find('input.note_product_order').val();
            $detail.push(
                {id: $id, quantity: $quantity, price: $price, discount: 0,note:$notes}
            );
        });

        $data = {
            'data': {
                'table_id': $table_id,
                'customer_id': $customer_id,
                'notes': $note,
                'coupon': $discount,
                'customer_pay': $customer_pay,
                'detail_order': $detail
            }
        };

        var $param = {
            'type': 'POST',
            'url': 'cafe/cms_save_order/' + $store_id,
            'data': $data,
            'callback': function (data) {
                if (isNaN(parseInt(data))) {
                    $('.ajax-error-ct').html('Lỗi. ' + data).parent().fadeIn().delay(1000).fadeOut('slow');
                } else if (data == '0') {
                    $('.ajax-error-ct').html('Oops! This system is errors! please try again.').parent().fadeIn().delay(1000).fadeOut('slow');
                } else if (data == '-1') {
                    $('.ajax-error-ct').html('Vui lòng chọn khách hàng để có thể bán nợ').parent().fadeIn().delay(1000).fadeOut('slow');
                } else {
                    $('.save').hide();
                    if (type == 0) {
                        $('.ajax-success-ct').html('Đã lưu thành công đơn hàng.').parent().fadeIn().delay(1000).fadeOut('slow');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        cms_print_order_in_pos(1, data);
                    }
                }
            }
        };
        cms_adapter_ajax($param);
    }
}

function cms_save_table(type, $table_id) {
    if ($('tbody#pro_search_append tr').length == 0) {
        $('.ajax-error-ct').html('Xin vui lòng chọn ít nhất 1 sản phẩm cần xuất trước khi lưu đơn hàng. Xin cảm ơn!').parent().fadeIn().delay(1000).fadeOut('slow');
    } else {
        $customer_id = typeof $('#search-box-cys').attr('data-id') === 'undefined' ? 0 : $('#search-box-cys').attr('data-id');
        $store_id = $('#store_id').val();
        $note = $('#note-order').val();
        $discount = cms_decode_currency_format($('input.discount-order').val());
        $customer_pay = cms_decode_currency_format($('.customer-pay').val());
        $detail = [];
        $('tbody#pro_search_append tr').each(function () {
            $id = $(this).attr('data-id');
            $quantity = $(this).find('input.quantity_product_order').val();
            $printed = $(this).find('td.printed').text();
            $price = cms_decode_currency_format($(this).find('input.price-order').val());
              $notes = $(this).find('input.note_product_order').val();
            $detail.push(
                {id: $id, quantity: $quantity, price: $price, discount: 0,printed:$printed,note:$notes}
            );
        });

        $data = {
            'data': {
                'table_id': $table_id,
                'customer_id': $customer_id,
                'notes': $note,
                'coupon': $discount,
                'customer_pay': $customer_pay,
                'detail_order': $detail
            }
        };

        var $param = {
            'type': 'POST',
            'url': 'cafe/cms_save_table/' + $store_id,
            'data': $data,
            'callback': function (data) {
                if (isNaN(parseInt(data))) {
                    $('.ajax-error-ct').html('Lỗi. ' + data).parent().fadeIn().delay(1000).fadeOut('slow');
                } else if (data == '0') {
                    $('.ajax-error-ct').html('Oops! This system is errors! please try again.').parent().fadeIn().delay(1000).fadeOut('slow');
                } else if (data == '-1') {
                    $('.ajax-error-ct').html('Vui lòng chọn khách hàng để có thể bán nợ').parent().fadeIn().delay(1000).fadeOut('slow');
                } else {
                    $('.save').hide();
                    if (type == 0) {
                        $('.ajax-success-ct').html('Đã lưu thành công.').parent().fadeIn().delay(1000).fadeOut('slow');
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        cms_print_table_in_pos(7, data);
                    }
                }
            }
        };
        cms_adapter_ajax($param);
    }
}

function cms_del_temp_area($id, $page) {
    var conf = confirm('Bạn chắc chắn muốn xóa khu vực này?');
    if (conf) {
        var $param = {
            'type': 'POST',
            'url': 'cafe/cms_del_temp_area/' + $id,
            'data': null,
            'callback': function (data) {
                if (data == '1') {
                    cms_paging_area($page);
                    $('.ajax-success-ct').html('Xóa khu vực thành công.').parent().fadeIn().delay(1000).fadeOut('slow');
                } else if (data == '0') {
                    $('.ajax-error-ct').html('Oops! This system is errors! please try again.').parent().fadeIn().delay(1000).fadeOut('slow');
                }
            }
        };
        cms_adapter_ajax($param);
    }
}

function cms_change_discount_order() {
    $('.toggle-discount-order').toggle(200);
}


function cms_paging_area($page) {
    $store_id = $('#store_id').val();
    $data = {'data': {'store_id': $store_id}};
    var $param = {
        'type': 'POST',
        'url': 'cafe/cms_paging_area/' + $page,
        'data': $data,
        'callback': function (data) {
            $('#area_list').html(data);
        }
    };
    cms_adapter_ajax($param);
}


function cms_load_infor_order() {
    $total_money = 0;
    $('tbody#pro_search_append tr').each(function () {
        $quantity_product = $(this).find('input.quantity_product_order').val();
        $price = cms_decode_currency_format($(this).find('input.price-order').val());
        $total = $price * $quantity_product;
        $total_money += $total;
        $(this).find('td.total-money').text(cms_encode_currency_format($total));
    });
    if ($('#vat').val() > 0) {
        $total_money = $total_money + ($total_money * $('#vat').val()) / 100;
    }

    $('div.total-money').text(cms_encode_currency_format($total_money));

    if ($('input.discount-percent-order').val() != '' && $('input.discount-percent-order').val() != 0) {
        $discount = $total_money * $('input.discount-percent-order').val() / 100;
        $discount = isNaN($discount) ? 0 : $('input.discount-order').val(cms_encode_currency_format($discount));
    }

    if ($('input.discount-order').val() == '')
        $discount = 0;
    else
        $discount = cms_decode_currency_format($('input.discount-order').val());

    if ($discount > $total_money) {
        $('input.discount-order').val($total_money);
        $discount = $total_money;
    }

    $total_after_discount = $total_money - $discount;

    $('.total-after-discount').text(cms_encode_currency_format($total_after_discount));
    $('input.customer-pay').val(cms_encode_currency_format($total_after_discount));
    $('div.debt').text(0);
}

function cms_encode_currency_format(obs) {
    return obs.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function cms_decode_currency_format(obs) {
    if (obs == '')
        return 0;
    else
        return parseInt(obs.replace(/,/g, ''));
}

function fix_height_sidebar() {
    var wdth_main = $('.main-content').height(),
        wdth_sidebar = $(".sidebar").height();
    if (wdth_main > wdth_sidebar) {
        $('.sidebar').height(wdth_main);
    }
}

function btnClick(beforClick, afterClick) {
    $("body").on('click', beforClick, function () {
        $(afterClick).trigger('click');
    });
}

function is_match(pass1, pass2) {
    if (pass1 == pass2) return true;

    return false;
}

function cms_undo_item(id) {
    $('tr.edit-tr-item-' + id).hide();
    $('tr.tr-item-' + id).show();
}

function tab_click_act(act) {
    $('.act').not(this).hide();
    $('.' + act + '-act').show();
}

function cms_javascript_redirect(url) {
    window.location.assign(url);
}

function cms_javascrip_fullURL() {
    return window.location.href;
}

function cms_get_valCheckbox(obj, type) {
    var vals = 0;
    var types = (type == 'class') ? '.' : '#';
    if ($(types + obj).prop('checked') == true) {
        vals = 1;
    }

    return vals;
}

Number.prototype.formatMoney = function (c, d, t) {
    var n = this,
        c = isNaN(c = Math.abs(c)) ? 2 : c,
        d = d == undefined ? "." : d,
        t = t == undefined ? "," : t,
        s = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

function cms_del_icon_click(obs, attach) {
    $('body').on('click', obs, function () {
        $(this).html('').parent().find(attach).val('').removeAttr('data-id').prop('readonly', false);
    })
}