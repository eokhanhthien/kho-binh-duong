<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="<?php echo CMS_BASE_URL; ?>"/>
	<link href="public/templates/images/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <title><?php echo isset($seo['title']) ? $seo['title'] : 'Phần mềm quản lý bán hàng'; ?></title>
    <link href="public/templates/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/templates/css/bootstrap-datepicker.css" rel="stylesheet">
    <link href="public/templates/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="public/templates/css/font-awesome.min.css" rel="stylesheet">
    <link href="public/templates/css/style.css" rel="stylesheet">
    <link href="public/templates/css/jquery-ui.min.css" rel="stylesheet">
    <link href="public/templates/css/jquery.datetimepicker.css" rel="stylesheet">
    <script src="public/templates/js/jquery.js"></script>
    <script src="public/templates/js/jquery.form.js"></script>
    <script src="public/templates/js/bootstrap.min.js"></script>
    <script src="public/templates/js/jquery-ui.min.js"></script>
    <script src="public/templates/js/html5shiv.min.js"></script>
    <script src="public/templates/js/respond.min.js"></script>
    <script src="public/templates/js/jquery.datetimepicker.full.js"></script>
    <script src="public/templates/js/bootstrap-datepicker.min.js"></script>
    <script src="public/templates/js/bootstrap-datepicker.vi.min.js"></script>
    <script src="public/templates/js/ckeditor.js"></script>
    <script src="public/templates/js/editor.js"></script>
    <script src="public/templates/js/cafe.js"></script>
</head>
<body>
<header>
    <nav id="navbar-container" class="navbar navbar- navbar-fixed-top">
        <div class="container-fluid">
            <button type="button" class="navbar-toggle menu-toggler pull-left" onclick="$('#category').toggleClass('hidden-xs hidden-sm hidden-md')">
                <span class="sr-only">Toggle sidebar</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div class="navbar-header">
                <button type="button" class="navbar-toggle menu-toggler pull-right"
                        onclick="$('#header').toggleClass('hidden-xs hidden-sm hidden-md')">
                    <span class="sr-only">Toggle sidebar</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="collapse navbar-collapse navbar-header hidden-768 col-xs-4 col-md-6 text-right"
                 style="line-height:45px;height:45px;vertical-align:middle;">
                <img src="public/templates/images/logo.png" height="40" style="line-height: 40px"><span style="color: red;font-size: 16px;"> </span>
               <span class="white" style="color:white"> PHẦN MỀM BÁN CAFE - BÀN</span>
            </div>

            <div class="hidden-xs hidden-sm hidden-md navbar-collapse" id="header">
                <ul class="nav navbar-nav navbar-right" id="set-background">
					 <li>
							<label style="margin: 13px 15px; color: white">
								<a target="_blank" href="/"><span class="white"><i class="fa fa-tachometer"></i> Tổng quan</span></a>
							</label>
						</li>
                    <?php if (isset($data['store'])) { ?>
                       
						<li>
                            <label style="margin: 13px 15px; color: white">
                                Cửa hàng
                            </label>
                        </li>
                        <li style="border-right: 1px solid #E1E1E1; padding-right: 15px;">
                            <select id="store_id" class="form-control" style="margin: 8px auto">
                                <?php foreach ($data['store'] as $key => $item) : ?>
                                    <option <?php if ($item['ID'] == $data['user']['store_id']) echo 'selected '; ?>
                                            value="<?php echo $item['ID']; ?>"><?php echo $item['store_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>

                    <?php } ?>
                    
                    <?php if (!empty($data['area'])) { ?>
                        <li>
                            <label data-toggle="modal" data-target="#list-area-modal"
                                   style="margin: 13px 15px; color: white">
                                Khu vực
                            </label>
                        </li>
                        <li style="border-right: 1px solid #E1E1E1; padding-right: 15px;">
                            <select id="area_id" class="form-control" style="margin: 8px auto">
                                <option value="-1">Tất cả</option>
                                <?php foreach ($data['area'] as $key => $item) : ?>
                                    <option value="<?php echo $item['ID']; ?>"><?php echo $item['area_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </li>
                    <?php } ?>
                    <li class="dropdown user-profile">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false"><span
                                    class="hello">Xin chào, </span><?php echo (isset($data['user'])) ?
                                $data['user']['display_name'] : $data['user']['username']; ?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="account"><i class="fa fa-user"></i>Tài khoản</a></li>
                            <li><a href="dashboard"><i class="fa fa-backward"></i>Quay lại</a></li>
                            <li><a href="authentication/logout"><i class="fa fa-power-off"></i>Thoát</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
<section id="pos" class="main" role="main">
    <div class="container-fluid">
        <div class="row">
            <div id="left_pos" class="pos">
                <div class="main-content">
                    <div class="modal fade" id="create-cust" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Tạo mới khách hàng</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-horizontal" id="frm-crcust">
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label for="customer_name">Mã khách hàng</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="customer_code" name="customer_code"
                                                       class="form-control" value=""
                                                       placeholder="Mã khách hàng(tự sinh nếu bỏ trống)">
                                                <span style="color: red; font-style: italic;"
                                                      class="error error-customer_code"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label for="customer_name">Tên Khách hàng</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="customer_name" name="customer_name"
                                                       class="form-control" value=""
                                                       placeholder="Nhập tên khách hàng( bắc buộc )">
                                                <span style="color: red; font-style: italic;"
                                                      class="error error-customer_name"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label for="customer_phone">Số điện thoại</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="customer_phone" name="customer_phone"
                                                       class="form-control" value="" placeholder="">
                                                <span style="color: red; font-style: italic;"
                                                      class="error error-customer_phone"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label for="customer_email">Email</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="customer_email" name="customer_email"
                                                       class="form-control" value=""
                                                       placeholder="Nhập email khách hàng ( ví dụ: kh10@gmail.com )">
                                                <span style="color: red; font-style: italic;"
                                                      class="error error-customer_email"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label for="customer_addr">Địa chỉ</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="customer_addr" name="customer_addr"
                                                       class="form-control"
                                                       value="" placeholder="">
                                                <span style="color: red; font-style: italic;"
                                                      class="error error-customer_addr"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label for="customer_notes">Ghi chú</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="customer_notes" name="customer_notes"
                                                       class="form-control" value=""
                                                       placeholder="">
                                                <span style="color: red; font-style: italic;"
                                                      class="error error-customer_notes"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label for="customer_birthday">Ngày sinh</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="text" id="customer_birthday" name="customer_birthday"
                                                       class="form-control txttimes" value="" placeholder="yyyy-mm-dd">
                                                <span style="color: red;" class="error error-customer_birthday"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-3">
                                                <label for="customer_gender">Giới tính</label>
                                            </div>
                                            <div class="col-sm-9">
                                                <input type="radio" name="gender" checked class="customer_gender"
                                                       value="0"> Nam
                                                <input type="radio" name="gender" class="customer_gender" value="1"> Nữ
                                                <span style="color: red; font-style: italic;"
                                                      class="error error-customer_gender"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="jumbotron text-center" id="img_upload"
                                                     style="border-radius: 0; margin-bottom: 10px; padding: 15px 20px;">
                                                    <h3>Upload hình ảnh khách hàng</h3>
                                                    <small style="font-size: 14px; margin-bottom: 5px; display: inline-block;">
                                                        (Để
                                                        tải và hiện thị nhanh, mỗi ảnh lên có dung lượng tối đa 10MB.)
                                                    </small>
                                                    <p>
                                                    <center>
                                                        <div id='customer_img_preview' style="display: none;"></div>
                                                        <form id="customer_image_upload_form" method="post"
                                                              enctype="multipart/form-data"
                                                              action='product/upload_img' autocomplete="off">
                                                            <div class="file_input_container">
                                                                <div class="upload_button"><input type="file"
                                                                                                  name="photo"
                                                                                                  id="customer_photo"
                                                                                                  class="file_input"/>
                                                                </div>
                                                            </div>
                                                            <br clear="all">
                                                        </form>
                                                    </center>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary btn-sm btn-crcust"
                                            onclick="cms_crCustomer();"><i
                                                class="fa fa-check"></i> Lưu
                                    </button>
                                    <button type="button" class="btn btn-default btn-sm btn-close" data-dismiss="modal">
                                        <i
                                                class="fa fa-undo"></i> Bỏ qua
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-dange ajax-error" role="alert"><span
                                style="font-weight: bold; font-size: 18px;">Thông báo!</span><br>

                        <div class="ajax-error-ct"></div>
                    </div>
                    <div class="alert ajax-success" role="alert"
                         style="width: 350px;background: rgba(92,130,79,0.9); display:none; color: #fff;"><span
                                style="font-weight: bold; font-size: 18px;">Thông báo!</span>
                        <br>

                        <div class="ajax-success-ct"></div>
                    </div>

                    <div class="modal fade" id="list-area-modal" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">Quản lý khu vực</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="tabtable">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs tab-setting" role="tablist"
                                            style="background-color: #EFF3F8; padding: 5px 0 0 15px;">
                                            <li role="presentation" class="active" style="margin-right: 3px;"><a
                                                        href="#list-area"
                                                        aria-controls="list-area"
                                                        role="tab"
                                                        data-toggle="tab"><i
                                                            class="fa fa-list"></i> Danh sách khu vực</a></li>
                                            <li role="presentation"><a href="#create-area" aria-controls="create-area"
                                                                       role="tab"
                                                                       data-toggle="tab"><i class="fa fa-plus"></i> Tạo
                                                    mới khu vực</a>
                                            </li>
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content"
                                             style="padding:10px; border: 1px solid #ddd; border-top: none;">
                                            <div role="tabpanel" class="tab-pane active" id="list-area">
                                                <div id="area_list">
                                                    <div class="text-center"><img
                                                                src="public/templates/images/balls.gif"/></div>
                                                </div>
                                            </div>

                                            <!-- Tab Function -->
                                            <div role="tabpanel" class="tab-pane" id="create-area">
                                                <div class="row form-horizontal">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <div class="col-md-4 text-right">
                                                                <label>Tên khu vực</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" id="area_name" class="form-control"
                                                                       placeholder="Nhập tên khu vực">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-4 text-right">
                                                                <label>Số bàn</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <input type="text" id="number_table"
                                                                       class="txtNumber form-control"
                                                                       placeholder="Nhập tổng số bàn">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-4 text-right">
                                                                <label>Chi nhánh</label>
                                                            </div>
                                                            <div class="col-md-8">
                                                                <select id="list_store" class="form-control">
                                                                    <?php foreach ($data['store'] as $key => $item) : ?>
                                                                        <option <?php if ($item['ID'] == $data['user']['store_id']) echo 'selected '; ?>
                                                                                value="<?php echo $item['ID']; ?>"><?php echo $item['store_name']; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="col-md-8 col-md-offset-4">
                                                                <button type="button" class="btn btn-primary"
                                                                        style="border-radius: 0 3px 3px 0;"
                                                                        onclick="cms_create_area(1);"><i
                                                                            class="fa fa-check"></i> Lưu
                                                                </button>
                                                                <button type="button" class="btn btn-primary "
                                                                        onclick="cms_create_area(0);"><i
                                                                            class="fa fa-floppy-o"></i> Lưu
                                                                    và tiếp tục
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default btn-sm btn-close" data-dismiss="modal">
                                        <i
                                                class="fa fa-undo"></i> Đóng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                    </div>
                </div>
            </div>
            <div id="right_pos" class="pos" style="display: none;">
                <div class="col-md-12 no-padding">
                    <div class="col-md-9 no-padding">
                        <div id="list_product" class="col-md-12">
                            <?php foreach ($data['product'] as $item) {
                                ?>
                                <div onclick="cms_select_product_sell(<?php echo $item['ID'] ?>)" class="img col-md-3"
                                     style="padding: 0px">
                                    <a>
                                        <img src="public/templates/uploads/<?php echo $item['prd_image_url'] == '' ? 'no-image.png' : $item['prd_image_url'] ?>">
                                    </a>
                                    <div class="desc"><?php echo $item['prd_name'] ?></div>
                                    <div class="desc"><?php echo cms_encode_currency_format($item['prd_sell_price']) ?></div>
                                </div>
                                <?php
                            } ?>
                        </div>
                    </div>
                    <div class="col-md-3 col-xs-12 no-padding category hidden-xs hidden-sm hidden-md" id="category" style="background: cornflowerblue;">
                        <ul>
                            <li onclick="cms_load_list_product(0)" class="href active category_list" id="category_0"><a>
                                    <h2 style="margin: 0px;">Tất cả</h2></a></li>
                            <?php
                            foreach ($sls_group as $val) :
                                ?>
                                <li class="href category_list" id="category_<?php echo $val['id'] ?>"
                                    onclick="cms_load_list_product(<?php echo $val['id'] ?>)">
                                    <a><?php echo $val['prd_group_name']; ?></a></li>
                            <?php
                            endforeach;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</body>

<script>
    <?php if(empty($data['area'])){ ?>
    $("#list-area-modal").modal('show');
    <?php } ?>


    document.addEventListener('keyup', hotkey, false);
</script>
<style type="text/css">
    .col-md-2.col-sm-4.col-xs-6.tble1 {
    background: #ddd;
    border: 1px solid #0b87c9;
}
</style>
</html>