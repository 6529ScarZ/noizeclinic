<?php
header('Content-type: text/json; charset=utf-8');

function __autoload($class_name) {
    include '../class/' . $class_name . '.php';
}
$conn_DB = new EnDeCode();
$read = "../connection/conn_DB.txt";
$conn_DB->para_read($read);
$conn_db = $conn_DB->Read_Text();
$conn_DB->conn_PDO();
set_time_limit(0);
$rslt = array();
$series = array();
$sql="select p1.clinic_no,p2.pname,p1.fname,p1.lname,p1.age,p1.house_no,p1.mo,d.DISTRICT_NAME,a.AMPHUR_NAME,p3.PROVINCE_NAME
FROM district d
LEFT OUTER JOIN amphur a on a.AMPHUR_ID=d.AMPHUR_ID
LEFT OUTER JOIN province p3 on p3.PROVINCE_ID=d.PROVINCE_ID
RIGHT OUTER JOIN patient p1 on d.DISTRICT_ID=p1.sub_dist
LEFT OUTER JOIN pname p2 on p2.pcode=p1.pname_code"; 
$conn_DB->imp_sql($sql);
    $num_risk = $conn_DB->select();
    for($i=0;$i<count($num_risk);$i++){
    $series['clinic_no'] = $num_risk[$i]['clinic_no'];
    $series['pname']= $num_risk[$i]['pname'];
    $series['fname']= $num_risk[$i]['fname'];
    $series['lname']= $num_risk[$i]['lname'];
    $series['age']= $num_risk[$i]['age'];
    $series['house_no']= $num_risk[$i]['house_no'];
    $series['mo']= $num_risk[$i]['mo'];
    $series['DISTRICT_NAME']= $num_risk[$i]['DISTRICT_NAME'];
    $series['AMPHUR_NAME']= $num_risk[$i]['AMPHUR_NAME'];
    $series['PROVINCE_NAME']= $num_risk[$i]['PROVINCE_NAME'];
    array_push($rslt, $series);    
    }
print json_encode($rslt);
$conn_DB->close_PDO();