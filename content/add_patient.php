<?php include '../header2.php';include '../plugins/funcDateThai.php';  ?>
<script language="javascript">
function fncSubmit()
	{
	 if(document.form1.user_pwd.value != document.form1.user_pwd2.value)
		{
			alert('การยืนยันรหัสผ่านไม่ตรงกัน กรุณาตรวจสอบ');
			document.form1.user_pwd.focus();		
			return false;
		}else{	
			return true;
			document.form1.submit();
		}
}
</script>
<section class="content-header">
               <h1> <font color="blue">เพิ่มผู้ป่วย</font></h1>
</section>
			<?php 
                        function __autoload($class_name) {
    include '../class/'.$class_name.'.php';
}
                $mydata=new TablePDO();
                $read='../connection/conn_DB.txt';
                $mydata->para_read($read);
			 $mydata->conn_PDO();
                         if(null !==(filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED))){ 
			 $user_idGet=filter_input(INPUT_GET, 'id', FILTER_SANITIZE_ENCODED);
                         
                          /*if(filter_input(INPUT_GET, 'method', FILTER_SANITIZE_STRING)=='update_user'){
                             $status= filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);
                         }elseif(filter_input(INPUT_GET, 'method', FILTER_SANITIZE_STRING)=='edit'){
                         $status=$_SESSION['Status'];}*/
			 $sql="select * from  patient where persion_id='$user_idGet'";
			 
                         $mydata->imp_sql($sql);
                         $edit_patient=$mydata->select_a();
			 }
			   ?> 
<section class="content">
<div class="row">
          <div class="col-lg-12">
              <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">เพิ่มผู้ป่วย</h3>
                    </div>
                <div class="panel-body">
                    <div class="col-lg-4 col-xs-12">
                        <div class="row well well-sm">   
                    <form name='form1' class="navbar-form navbar-left"  action='../process/prcpatient.php' method='post' enctype="multipart/form-data" OnSubmit="return fncSubmit();">
                        <b>หมายเลขประจำตัวคนไข้ </b>
                        <div class="form-group">	
                            <input type="text" name='clinic_no' size="15" id='clinic_no' class='form-control' value='<?php if(!empty($user_idGet)){ echo $edit_patient['clinic_no'];}?>'>
			 </div> 
                        <b>คำนำหน้าชื่อ</b>
                        <div class="form-group">
                            <select name="pname" class="form-control select2">
                                <option value="">เลือกคำนำหน้าชื่อ</option>
                                <?php
                                $sql_pname = "select * from pname";
                                $mydata->imp_sql($sql_pname);
                                $resultGet=$mydata->select();
                                for($i=0;$i<count($resultGet);$i++) {
                                    if($resultGet[$i]['pcode']==$edit_patient['pname_code']){$selected='selected';}else{$selected='';}
                                    echo "<option value='".$resultGet[$i]['pcode']."' $selected>".$resultGet[$i]['pname']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <b>ชื่อ </b>
                        <div class="form-group">	
                            <input type="text" name='fname' size="15" id='fname' class='form-control' value='<?php if(!empty($user_idGet)){ echo $edit_patient['fname'];}?>'>
			 </div> 
                        <b>นามสกุล </b>
                        <div class="form-group">	
                            <input type="text" name='lname'   id='lname' class='form-control' value='<?php if(!empty($user_idGet)){ echo $edit_patient['lname'];}?>'>
			 </div> 
                        <b>เลขบัตรประชาชาน </b>
                        <div class="form-group">	
                            <input type="text" name='cid' size="15" id='cid' class='form-control' value='<?php if(!empty($user_idGet)){ echo $edit_patient['cid'];}?>'>
			 </div> 
                        <?php if(!empty($user_idGet)){ $date = $edit_patient['birth']; }else{$date = date('Y-m-d');}?>
                        <script type="text/javascript">
                        $(function() {
                        $( "#datepicker" ).datepicker( regional );
                        $( "#datepicker" ).datepicker("setDate", new Date("<?=$date?>"));
                        });
                        </script>
                                            
                        <b>วันเกิด </b>
                        <div class="form-group">	
                            <input type="text" name='birth' size="15" id='datepicker' class='form-control' value=''>
			 </div> 
                        <b>อายุ </b>
                        <div class="form-group">	
                            <input type="text" name='age' size="15" id='age' class='form-control' value='<?php if(!empty($user_idGet)){ echo $edit_patient['age'];}?>'>
			 </div> 
                        <b>บ้านเลขที่ </b>
                        <div class="form-group">	
                            <input type="text" name='house_no' size="15" id='house_no' class='form-control' value='<?php if(!empty($user_idGet)){ echo $edit_patient['house_no'];}?>'>
			 </div> 
                        <b>หมู่ที่ </b>
                        <div class="form-group">	
                            <input type="text" name='mo' size="15" id='mo' class='form-control' value='<?php if(!empty($user_idGet)){ echo $edit_patient['mo'];}?>'>
			 </div> 
                        <div class="form-group">	
                            <?php
                            include 'address.php';
                            ?>
			 </div> 
                        <div class="form-group">
        <label>ประเทศ &nbsp;</label><br>
	<select name='country' id='country'class='form-control' required >
			<?php 		
				echo "<option value=''>เลือกประเทศ</option>";			
		 		if($edit_patient['country']=="1"){$selected='selected';}
				if($edit_patient['country']=="2"){$selected2='selected';}
                                if($edit_patient['country']=="3"){$selected3='selected';}
				echo "<option value='1'  $selected>ไทย</option>";	
				echo "<option value='2'  $selected2>ลาว</option>";
                                echo "<option value='3'  $selected3>พม่า</option>";
				?>
			</select>
                        </div>
                        <div class="form-group">
        <label>สาขา &nbsp;</label><br>
	<select name='clinic_zone' id='clinic_zone'class='form-control' required >
			<?php 		
				echo "<option value=''>เลือกสาขา</option>";			
		 		if($edit_patient['clinic_zone']=="1"){$selected='selected';}
				if($edit_patient['clinic_zone']=="2"){$selected2='selected';}
				echo "<option value='1'  $selected>นาอ้อ</option>";	
				echo "<option value='2'  $selected2>กกดู่</option>";						
				?>
			</select>
                        </div>
 <?PHP 
	if(!empty($user_idGet)){
		echo "<input type='hidden' name='id' value='$user_idGet'>";
		echo "<input type='hidden' name='method' value='update_patient'>";
                ?>
            <input type="hidden" name="check" value="plus">
        <p><button  class="btn btn-warning" id='save'> แก้ไข </button > <input type='reset' class="btn btn-danger"   > </p>
	<?php }  else {?>
        <input type="hidden" name="method" value="add_patient">
        <input type="hidden" name="check" value="plus">
         <p><button  class="btn btn-success" id='save'> บันทึก </button > <input type='reset' class="btn btn-danger"   > </p>
              <?php } ?>
		</form>
                        </div>
      </div> <?php
      $mydata->close_PDO();
      ?>
    </div>
              </div>
    </div>
  </div>
        
 
</section>
<?php include '../footer2.php';?>