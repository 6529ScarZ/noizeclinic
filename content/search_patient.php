<?php

//session_save_path("../session/");session_start(); 
//include '../header2.php';include '../plugins/funcDateThai.php';
?>
<div class="row">
          <div class="col-lg-12">
                <div class="box box-success box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">
                      <img src='images/Document_Upload.ico' width='25'> 
                     ข้อมูล BILLDISP ที่ได้รับการนำเข้าแล้ว</h3>
                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body">
                    <?php
//                    function __autoload($class_name) {
//    include 'class/'.$class_name.'.php';
//}
$conn_DB2= new TablePDO();
$read="connection/conn_DB.txt";
$conn_DB2->para_read($read);
$conn_DB2->conn_PDO();
                    $sql="select p1.clinic_no,p2.pname,p1.fname,p1.lname,p1.age,p1.house_no,p1.mo,d.DISTRICT_NAME,a.AMPHUR_NAME,p3.PROVINCE_NAME
FROM district d
LEFT OUTER JOIN amphur a on a.AMPHUR_ID=d.AMPHUR_ID
LEFT OUTER JOIN province p3 on p3.PROVINCE_ID=d.PROVINCE_ID
RIGHT OUTER JOIN patient p1 on d.DISTRICT_ID=p1.sub_dist
LEFT OUTER JOIN pname p2 on p2.pcode=p1.pname_code";
                    $conn_DB2->imp_sql($sql);
        $conn_DB2->select();
$column=array("OPD NO.","คำนำหน้าชื่อ","ชื่อ","นามสกุล","อายุ","บ้านเลขที่","หมู่","ตำบล","อำเภอ","จังหวัด");
$conn_DB2->imp_columm($column);  
$conn_DB2->createPDO_TB();
                    ?>
                </div>
                </div>
          </div>
</div>
 <?php //include 'footer2.php';?>
