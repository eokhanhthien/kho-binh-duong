<div class="orders">
    <div class="breadcrumbs-fixed panel-action">
        <div class="row">
            <div class="orders-act">
                <div class="col-md-4 col-md-offset-2">
                    <div class="left-action text-left clearfix">
                        <h2>Danh sách đơn hàng</h2>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right-action text-right">
                        <div class="btn-groups">
<!--                            <a href="/orders">-->
<!--                                <button type="button" class="btn btn-primary"-->
<!--                                "><i class="fa fa-shopping-cart"></i> Đặt hàng</button></a>-->
                            <button type="button" class="btn btn-primary" onclick="cms_vsell_order();"><i
                                    class="fa fa-desktop"></i> Bán hàng
                            </button>
<!--                            <button type="button" class="btn btn-success"><i class="fa fa-download"></i> Xuất-->
<!--                                Excel-->
<!--                            </button>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="main-space orders-space"></div>
    <div class="orders-content">
        <div class="product-sear panel-sear">
            <div class="form-group col-md-3 padd-0">
                <input type="text" class="form-control" id="order-search"
                       placeholder="Nhập mã đơn hàng để tìm kiếm">
            </div>
            <div class="form-group col-md-9 padd-0" style="padding-left: 5px;">
                <div class="col-md-9 padd-0">
                    <div class="col-md-3 padd-0">
                        <select id="search-option-1" class="form-control">
                            <option value="0">Đơn hàng</option>
                            <option value="1">Đơn hàng đã xóa</option>
                            <option value="2">Đơn hàng còn nợ</option>
                        </select>
                    </div>
                    <div class="col-md-4 padd-0" style="padding-left: 5px;">
                        <div class="input-daterange input-group" id="datepicker">
                            <input type="text" class="input-sm form-control" id="search-date-from" placeholder="Từ ngày"
                                   name="start"/>
                            <span class="input-group-addon">to</span>
                            <input type="text" class="input-sm form-control" id="search-date-to" placeholder="Đến ngày"
                                   name="end"/>
                        </div>
                    </div>
                    <div class="col-md-5 padd-0" style="padding-left: 5px;">
                        <button style="box-shadow: none;" type="button" class="btn btn-primary btn-large"
                                onclick="cms_paging_order(1)"><i class="fa fa-search"></i> Tìm kiếm
                        </button>
                        <button  style="box-shadow: none;" type="button" class="btn btn-primary btn-large"  onclick="exportToExcel()">
                             <i class="fa fa-download"></i> Xuất Excel
                        </button>
                        <button  style="box-shadow: none;" type="button" class="btn btn-primary btn-large"  onclick="printOders()">
                             <i class="fa fa-print"></i> In
                        </button>
                    </div>
                </div>
                <div class="col-md-3 padd-0" style="padding-left: 5px;">
                    <div class="btn-group order-btn-calendar">
                        <button type="button" onclick="cms_order_week()" class="btn btn-default">Tuần</button>
                        <button type="button" onclick="cms_order_month()" class="btn btn-default">Tháng</button>
                        <button type="button" onclick="cms_order_quarter()" class="btn btn-default">Quý</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="orders-main-body" id="list-order-data">
        </div>
    </div>
</div>

<style id="table_style" type="text/css">
    
    table
    {
        border: 1px solid #ccc;
        border-collapse: collapse;
    }
    table th
    {
        background-color: #F7F7F7;
        color: #333;
        font-weight: bold;
        padding: 10px;
    }
    table th, table td
    {
        padding: 5px;
        border: 1px solid #ccc;
    }
</style>

<script>
function exportToExcel(){
    var tableContent = document.getElementById("list-order-data");
    // Tạo một phần tử HTML ảo để thực hiện các thao tác DOM
    var virtualElement = document.createElement('div');
    virtualElement.innerHTML = tableContent.innerHTML;

    // Lọc và xóa các thẻ tr có class là "btn-stt", "btn-action", hoặc "tr-hide"
    var rowsToRemove = virtualElement.querySelectorAll('th select,th.btn-stt, th.btn-action,th.btn-checkbox,td.btn-stt, td.btn-action,td.btn-checkbox ,tr.tr-hide,div.summany-info');
    for (var i = 0; i < rowsToRemove.length; i++) {
        rowsToRemove[i].parentNode.removeChild(rowsToRemove[i]);
    }

    // Lấy nội dung đã được lọc và xóa
    var filteredTableContent = virtualElement.innerHTML;

    // In kết quả sau khi lọc và xóa
    // console.log(filteredTableContent);
    let a = document.createElement('a');
	a.href = `data:application/vnd.ms-excel, ${encodeURIComponent(filteredTableContent)}`
	a.download = 'Danh sach don hang' + '.xls'
	a.click()
}

function printOders()
{
        var tableContent = document.getElementById("list-order-data");
        // Tạo một phần tử HTML ảo để thực hiện các thao tác DOM
        var virtualElement = document.createElement('div');
        virtualElement.innerHTML = tableContent.innerHTML;

        // Lọc và xóa các thẻ tr có class là "btn-stt", "btn-action", hoặc "tr-hide"
        var rowsToRemove = virtualElement.querySelectorAll('th select,th.btn-stt, th.btn-action,th.btn-checkbox,td.btn-stt, td.btn-action,td.btn-checkbox ,tr.tr-hide,div.summany-info');
        for (var i = 0; i < rowsToRemove.length; i++) {
            rowsToRemove[i].parentNode.removeChild(rowsToRemove[i]);
        }

        // Lấy nội dung đã được lọc và xóa
        var filteredTableContent = virtualElement.innerHTML;

        var printWindow = window.open('', '', 'height=800,width=1200');
        printWindow.document.write('<html><head><title>Danh sách đơn hàng</title>');

        var table_style = document.getElementById("table_style").innerHTML;
        printWindow.document.write('<style type = "text/css">');
        printWindow.document.write(table_style);
        printWindow.document.write('</style>');
        printWindow.document.write('</head>');
        //Print the DIV contents i.e. the HTML Table.
        printWindow.document.write('<body>');
        // var divContents = document.getElementById("table-orders").innerHTML;
        printWindow.document.write(filteredTableContent);
        printWindow.document.write('</body>');
 
        printWindow.document.write('</html>');
        printWindow.document.close();
        printWindow.print();
}



</script>