<?php function __autoload($class_name) {
    include '../class/'.$class_name.'.php';
}
$mydata2 = new TablePDO();
$read='../connection/conn_DB.txt';
$mydata2->para_read($read);
$mydata2->conn_PDO();
?>
<?php $result=$_GET['result'];
$select_id=$_GET['select_id'];

if ($result == 'amphur') { 
    echo "<option value=''>---โปรดเลือกอำเภอ---</option>";
    $rstTemp = "select * from amphur Where PROVINCE_ID ='" . $select_id . "' Order By AMPHUR_ID ASC";
    $mydata2->imp_sql($rstTemp);
                $resultGet=$mydata2->select();
                
                for($i=0;$i<count($resultGet);$i++) {
    
if($arr_2['AMPHUR_ID']==$edit_person['empure']){$selected='selected';}else{$selected='';}
      echo "<option value='".$resultGet[$i]['AMPHUR_ID']."'>".$resultGet[$i]['AMPHUR_NAME']."</option>";
}
} ?>

    <?php if ($result == 'district') { ?>
        <?php
        echo "<option value=''>---โปรดเลือกตำบล---</option>";
        $rstTemp = "select * from district Where AMPHUR_ID ='" . $select_id . "'  Order By DISTRICT_ID ASC";
        $mydata2->imp_sql($rstTemp);
                $resultGet=$mydata2->select();
        for($i=0;$i<count($resultGet);$i++) {
            echo "<option value='".$resultGet[$i]['DISTRICT_ID']."'>".$resultGet[$i]['DISTRICT_NAME']."</option>";
}
            } ?>
