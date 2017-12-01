<?php

//session_save_path("../session/");session_start(); 
//include '../header2.php';include '../plugins/funcDateThai.php';
?>
<script src="js/createTable.js" type="text/javascript"></script>
<script language="Javascript" type="text/javascript">
    //var column1 = '{"รายการความเสี่ยงที่รอ RM man มาตรวจสอบ":["เลขที่","รายการ","เกิดขึ้นเมื่อ","ได้รับเมื่อ"]}';
    var column1 = '["OPD NO.","สาขา","เลขบัตรฯ","คำนำหน้าชื่อ","ชื่อ","นามสกุล","อายุ","อายุเต็ม","บ้านเลขที่","หมู่","ตำบล","อำเภอ","จังหวัด"]';
    var tid = 'dbtable1';
    var tid3 = 'dbtable3';
    var tid2 = 'dbtable2';
              var CTb = new createTable(column1,'1',tid);
              CTb.GetTableAjax('JsonData/DT_CR.php','contentTB');
</script>
<div class="row">
          <div class="col-lg-12">
                <div class="box box-success box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">
                      <img src='images/Document_Upload.ico' width='25'> 
                     ค้นหาข้อมูลคนไข้</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body"><div id="contentTB"></div></div>
                </div>
          </div>
</div>
 <?php //include 'footer2.php';?>
