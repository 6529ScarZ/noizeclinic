<?php 
if (isset($_POST['check']) == 'plus') {
include '../header2.php'; }?>
<script language="JavaScript" type="text/javascript">
            var StayAlive = 2; // เวลาเป็นวินาทีที่ต้องการให้ WIndows เปิดออก 
            function KillMe()
            {
                setTimeout("self.close()", StayAlive * 1000);
            }
        </script>
        <body onLoad="KillMe();self.focus();window.opener.location.reload();">
            <DIV  align='center'><IMG src='../images/tororo_hero.gif' width='200'></div>
<section class="content">
    
    <?php
    echo "<p>&nbsp;</p>	";
    echo "<p>&nbsp;</p>	";
    echo "<div class='bs-example'>
	  <div class='progress progress-striped active'>
	  <div class='progress-bar' style='width: 100%'></div>
</div>";
    echo "<div class='alert alert-dismissable alert-success'>
	  <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
	  <a class='alert-link' target='_blank' href='#'><center>กำลังดำเนินการ</center></a> 
</div>";
    if (isset($_POST['check']) == 'plus') {
    function __autoload($class_name) {
    include '../class/'.$class_name.'.php';
}
        $mydata= new TablePDO();
        $read="../connection/conn_DB.txt";
        $mydata->para_read($read);
        $db=$mydata->conn_PDO();
    } else {
        $mydata= new TablePDO();
        $read="connection/conn_DB.txt";
        $mydata->para_read($read);
        $db=$mydata->conn_PDO();
    }
    //$date = new DateTime(null, new DateTimeZone('Asia/Bangkok'));//กำหนด Time zone
        $date = date("Y-m-d H:i:s");
    if (null !== (filter_input(INPUT_POST, 'method'))) {
        $method = filter_input(INPUT_POST, 'method');
        if ($method == 'add_patient'){
            $clinic_no =  filter_input(INPUT_POST,'clinic_no');
            $pname_code =  filter_input(INPUT_POST,'pname');
            $fname =  trim(filter_input(INPUT_POST,'fname'));
            $lname =  trim(filter_input(INPUT_POST,'lname'));
            $cid =  trim(filter_input(INPUT_POST,'cid'));
            $birth =  insert_date(filter_input(INPUT_POST,'birth'));
            $age =  trim(filter_input(INPUT_POST,'age'));
            $house_no =  trim(filter_input(INPUT_POST,'house_no'));
            $mo =  trim(filter_input(INPUT_POST,'mo'));
            $province =  filter_input(INPUT_POST,'province');
            $amphur =  filter_input(INPUT_POST,'amphur');
            $district =  filter_input(INPUT_POST,'district');
            $country =  filter_input(INPUT_POST,'country');
            $clinic_zone =  filter_input(INPUT_POST,'clinic_zone');
            
        $data=array($clinic_no,'',$fname,$lname,$age,$house_no,$mo,'','','',$clinic_zone,$pname_code,$district,$amphur,$province,
            $country,$birth,$cid,$date);
        $table="patient";
        $check_user=$mydata->insert($table, $data);
        if($check_user=false){
        echo "<span class='glyphicon glyphicon-remove'></span>";
        echo "<a href='../content/add_patient.php' >กลับ</a>";
    } else {
        echo" <META HTTP-EQUIV='Refresh' CONTENT='2;URL=../content/add_patient.php'>";
        }
        }elseif ($method == 'update_patient'){
            $persion_id = filter_input(INPUT_POST,'id');
            $clinic_no =  filter_input(INPUT_POST,'clinic_no');
            $pname_code =  filter_input(INPUT_POST,'pname');
            $fname =  trim(filter_input(INPUT_POST,'fname'));
            $lname =  trim(filter_input(INPUT_POST,'lname'));
            $cid =  trim(filter_input(INPUT_POST,'cid'));
            $birth =  insert_date(filter_input(INPUT_POST,'birth'));
            $age =  trim(filter_input(INPUT_POST,'age'));
            $house_no =  trim(filter_input(INPUT_POST,'house_no'));
            $mo =  trim(filter_input(INPUT_POST,'mo'));
            $province =  filter_input(INPUT_POST,'province');
            $amphur =  filter_input(INPUT_POST,'amphur');
            $district =  filter_input(INPUT_POST,'district');
            $country =  filter_input(INPUT_POST,'country');
            $clinic_zone =  filter_input(INPUT_POST,'clinic_zone');
            
        $data=array($clinic_no,$fname,$lname,$age,$house_no,$mo,$clinic_zone,$pname_code,$district,$amphur,$province,
            $country,$birth,$cid,$date);
        $field=array("clinic_no","fname","lname","age","house_no","mo","clinic_zone","pname_code","sub_dist","district"
            ,"province","country","birth","cid","pupdate");
       
        $table="patient";
        $where="persion_id=:persion_id";
        $execute=array(':persion_id' => $persion_id);
        
        $check_user=$mydata->update($table, $data, $where, $field, $execute);  
        
        if($check_user=false){
        echo "<script>alert('การแก้ไขไม่สำเร็จจ้า!')</script>";  
    } 
    }
        
    } elseif (null !== (filter_input(INPUT_GET, 'method'))) {
        $method = filter_input(INPUT_GET, 'method');
        $delete_id=  filter_input(INPUT_GET, 'del_id');

        if($method=='delete_patient') {
//            $sql="select photo from user where user_id='$delete_id'";
//                $mydata->imp_sql($sql);
//                $photo=$mydata->select_a();
//                if(!empty($photo['photo'])){
//                $location="photo/".$photo['photo'];
//                include 'function/delet_file.php';
//                fulldelete($location);}
        $table="patient";
        $where="persion_id=:persion_id";
        $execute=array(':persion_id' => $delete_id);
        $del=$mydata->delete($table, $where, $execute);
        
        if($del==false){
        echo "<span class='glyphicon glyphicon-remove'></span>";
        echo "<a href='?page=content/add_user&id=".$delete_id."' >กลับ</a>";
    } else {
        echo" <META HTTP-EQUIV='Refresh' CONTENT='2;URL=?page=content/list_userAjax'>";
        }
    }
    }
    ?>
</section>
        </body>