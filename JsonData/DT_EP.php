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
$sql="select p1.clinic_no,p2.pname,p1.fname,p1.lname,p1.cid,persion_id,persion_id as persion_id2
FROM patient p1
LEFT OUTER JOIN pname p2 on p2.pcode=p1.pname_code"; 
$conn_DB->imp_sql($sql);
    $num_risk = $conn_DB->select();
    for($i=0;$i<count($num_risk);$i++){
    $series['clinic_no'] = $num_risk[$i]['clinic_no'];
    $series['pname']= $num_risk[$i]['pname'];
    $series['fname']= $num_risk[$i]['fname'];
    $series['lname']= $num_risk[$i]['lname'];
    $series['cid']= $num_risk[$i]['cid'];
    $series['persion_id']= $num_risk[$i]['persion_id'];
    $series['persion_id2']= $num_risk[$i]['persion_id2'];
    array_push($rslt, $series);    
    }
print json_encode($rslt);
$conn_DB->close_PDO();