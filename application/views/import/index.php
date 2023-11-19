
<div class="row">
    <div class="col-lg-12">
        <h1>Import/Upload Excel file into MySQL using Codeigniter</h1>        
        
<a class="pull-right btn btn-info btn-xs" style="margin: 2px" href="<?php echo HTTP_UPLOAD_IMPORT_PATH;?>upload.xlsx"><i class="fa fa-file-excel-o"></i> Download Format</a>  
<a class="pull-right btn btn-primary btn-xs" style="margin: 2px" href="http://techarise.com/import-excel-file-mysql-codeigniter/"><i class="fa fa-mail-reply"></i> Tutorial</a>          
    </div>
</div><!-- /.row -->



<form action="import/save"
      enctype="multipart/form-data" method="post" accept-charset="utf-8">
    <div style="display: inline-flex">
        <input type="file" name="userfile" id="userfile" class="form-control filestyle"
               value="" data-icon="false">
        <input type="submit" name="importfile" value="Import" id="importfile-id"
               class="btn btn-primary">
    </div>
</form>
