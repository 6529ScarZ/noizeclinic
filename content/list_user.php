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
     $sql="select p1.clinic_no,p2.pname,p1.fname,p1.lname,p1.cid,persion_id,persion_id as persion_id2
FROM patient p1
LEFT OUTER JOIN pname p2 on p2.pcode=p1.pname_code "; 
                $column=array("OPD NO.","คำนำหน้าชื่อ","ชื่อ","นามสกุล","เลขบัตรประชาชน","แก้ไข","ลบ");//หากเป็น TB_mng ต้องเพิ่ม แก้ไข,ลบเข้าไปด้วย 
                $mydata2=new TablePDO();
                $mydata2->imp_columm($column);
                $read="connection/conn_DB.txt";
                $mydata2->para_read($read);
                $mydata2->imp_sql($sql);
        $result=$mydata2->select();//เรียกใช้ค่าจาก function ต้องใช้ตัวแปรรับ
        $mydata2->createPDO_TB_edit('patient');//ใส่ process ที่ต้องการสร้าง
        $mydata2->close_PDO();
     ?>
</div>
                </div>
          </div>
</div>
