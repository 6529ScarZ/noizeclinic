<?php

function __autoload($class_name) {
    include 'class/'.$class_name.'.php';
}

ignore_user_abort();
set_time_limit(0);
ini_set('max_execution_time', 0);

$conn_DBMAIN= new EnDeCode();
$read="connection/conn_DB.txt";
$conn_DBMAIN->para_read($read);
$conn_DBMAIN->conn_PDO();
$conv=new convers_encode();

$this_m=date('M');
$befor_m=$this_m-1;
//$year=date('Y');
//$st_date="$year-$befor_m-01";
//$en_date="$year-$this_m-01";
$year='2016';
$month='12';
$st_date="2016-12-01";
$en_date="2016-12-31";

$conn_DBHOS= new EnDeCode();
$read="connection/conn_DBHosxp.txt";
$conn_DBHOS->para_read($read);
$conn_DBHOS->conn_PDO();

//////////////////import BILLDISP//////////////
$sql="SELECT 
(SELECT hospitalcode FROM opdconfig LIMIT 1 ) AS providerID,
  CONCAT(ov.vn,RIGHT(CONCAT('000',ov.doctor),3))AS dispenseID,
  ov.vn  AS invoice_no ,
  ov.hn,
  pt.cid AS PID, 
 CONCAT( ov.vstdate,' ',ov.vsttime) AS prescription_date ,
 IFNULL(CONCAT(ov.vstdate,'T',st.service16),REPLACE((DATE_ADD(CONCAT(ov.vstdate,'T',st.service12),INTERVAL 1 MINUTE )),' ',' ') )AS dispensed_date,
  (SELECT  CONCAT('ว',d.licenseno) as p FROM doctor d WHERE d.code=ov.doctor) AS prescriber,
  (SELECT COUNT(*) FROM  opitemrece o LEFT OUTER JOIN income i  ON o.income = i.income LEFT OUTER JOIN income_report2 i2 ON i.group2=i2.group_id  WHERE o.vn=ov.vn AND i2.cscd_group IN(3,4,5)) AS item_count,
  IFNULL(CAST((SELECT sum(o.sum_price) FROM  opitemrece o LEFT OUTER JOIN income i  ON o.income = i.income LEFT OUTER JOIN income_report2 i2 ON i.group2=i2.group_id  WHERE o.vn=ov.vn AND i2.cscd_group IN(3,4,5)) AS DECIMAL(10,2)),'0.00') AS charg_amount,
  IFNULL(CAST((SELECT sum(o.sum_price) FROM  opitemrece o LEFT OUTER JOIN income i  ON o.income = i.income LEFT OUTER JOIN income_report2 i2 ON i.group2=i2.group_id  WHERE o.vn=ov.vn AND i2.cscd_group IN(3,4,5) AND o.paidst='02' ) AS DECIMAL(10,2)),'0.00') AS claim_amount,
  IFNULL(CAST((SELECT sum(o.sum_price) FROM  opitemrece o LEFT OUTER JOIN income i  ON o.income = i.income LEFT OUTER JOIN income_report2 i2 ON i.group2=i2.group_id  WHERE o.vn=ov.vn AND i2.cscd_group IN(3,4,5) AND o.paidst='03' ) AS DECIMAL(10,2)),'0.00') AS paid_amount,
  CAST(0 AS DECIMAL(10,2)) AS other_amount,
  'HP' AS reimbuser,
  'CS' AS benefit_plan,
  '1' AS dispense_status
  
FROM
  ovst ov 
  LEFT OUTER JOIN patient pt 
    ON pt.hn = ov.hn 
  LEFT OUTER JOIN kskdepartment sp 
    ON sp.depcode = ov.cur_dep 
  LEFT OUTER JOIN vn_stat vt 
    ON vt.vn = ov.vn 
   LEFT OUTER JOIN service_time st ON ov.vn=st.vn 
 WHERE ov.vstdate BETWEEN '$st_date' and '$en_date' and ov.pttype = '23' and isnull (ov.an)
ORDER BY ov.vstdate, ov.vsttime";
$conn_DBHOS->imp_sql($sql);
$query1=$conn_DBHOS->select();

$sql2="SELECT 
  op.hos_guid,   
  CONCAT(ov.vn,RIGHT(CONCAT('000',ov.doctor),3))AS dispenseID,
  '1' as productCategory ,op.icode as HospitalDrugID ,
  t1.tpu_code as drugID,
  t1.strength as dfsCode,
   concat(d.name,' ',d.strength) as dfstext,
 d.units as PackSize,
 op.drugusage as singcode,
 d1.name1 as sigText,
 op.qty as quantity,
 op.unitprice as UnitPrice,
  op.sum_price as Chargeamount,
 op.unitprice as ReimbPrice,
 op.sum_price as ReimbAmount,
'' as ProDuctselectionCode,
'' as refill,
opn.presc_reason as claimControl,
'' as ClaimCategory,
CONCAT( ov.vstdate,' ',ov.vsttime) AS prescription_date
 
  
FROM
  opitemrece op 
  LEFT OUTER JOIN ovst ov on ov.vn = op.vn
  LEFT OUTER JOIN patient pt ON pt.hn = ov.hn 
  LEFT OUTER JOIN kskdepartment sp ON sp.depcode = ov.cur_dep 
  LEFT OUTER JOIN vn_stat vt ON vt.vn = ov.vn 
   LEFT OUTER JOIN service_time st ON ov.vn=st.vn 
  LEFT OUTER JOIN income i  ON op.income = i.income
  LEFT OUTER JOIN income_report2 i2 ON i.group2=i2.group_id
  LEFT OUTER JOIN drugitems d on op.icode = d.icode
  left outer join tmt_tpu_code t1 on d.tpu_code_list = t1.tpu_code
  left outer join drugusage d1 on op.drugusage = d1.drugusage
  LEFT OUTER JOIN ovst_presc_ned opn ON opn.vn=op.vn and opn.icode=op.icode
   WHERE ov.vstdate BETWEEN '$st_date' and '$en_date' and ov.pttype = '23' and isnull (ov.an) and i2.cscd_group IN(3,4,5) 
ORDER BY ov.vstdate, ov.vsttime
";
$conn_DBHOS->imp_sql($sql2);
$query2=$conn_DBHOS->select();

$data="";
$count_qr1=count($query1);
for($i=0;$i<$count_qr1;$i++){
    if ($i != 0) {
               $data.=", ";
            } 
     $data.="('".$query1[$i]['providerID']."','".$query1[$i]['dispenseID']."','".$query1[$i]['invoice_no']."','".$query1[$i]['hn']."','".$query1[$i]['PID']."','".$query1[$i]['prescription_date']."','".$query1[$i]['dispensed_date']."','".
        $query1[$i]['prescriber']."','".$query1[$i]['item_count']."','".$query1[$i]['charg_amount']."','".$query1[$i]['claim_amount']."','".
            $query1[$i]['paid_amount']."','".$query1[$i]['other_amount']."','".$query1[$i]['reimbuser']."','".$query1[$i]['benefit_plan']."','".$query1[$i]['dispense_status']."','0')";
    }
    $table="billdisp";
    $chk="chk";

 $inert_disp=$conn_DBMAIN->insert_update_new($table, $data, $chk);  

 $data="";
$count_qr2=count($query2);
for($i=0;$i<$count_qr2;$i++){
	if ($i != 0) {
               $data.=", ";
            } 
    $PackSize=$conv->tis620_to_utf8($query2[$i]['PackSize']);
    $sigText=$conv->tis620_to_utf8($query2[$i]['sigText']);
    $data.="('".$query2[$i]['hos_guid']."','".$query2[$i]['dispenseID']."','".$query2[$i]['productCategory']."','".$query2[$i]['HospitalDrugID']."','".$query2[$i]['drugID']."','".$query2[$i]['dfsCode']."','".$query2[$i]['dfstext']."','".$PackSize."','".
            $query2[$i]['singcode']."','".$sigText."','".$query2[$i]['quantity']."','".$query2[$i]['UnitPrice']."','".$query2[$i]['Chargeamount']."','".$query2[$i]['ReimbPrice']."','".$query2[$i]['ReimbAmount']
            ."','".$query2[$i]['ProDuctselectionCode']."','".$query2[$i]['refill']."','".$query2[$i]['claimControl']."','".$query2[$i]['ClaimCategory']."','".$query2[$i]['prescription_date']."','0')";
    }
    $table="billdisp_item";
    $chk="chk";
    $inert_dispitem=$conn_DBMAIN->insert_update_new($table, $data, $chk);

/*    if(($inert_disp and $inert_dispitem)==FALSE){
        $conn_DBHOS->close_PDO();
        $conn_DBMAIN->close_PDO();
        exit();
}else{*/
    
///////////////Import BILLTRANS///////////////////
$sql="SELECT 
  '14644' AS Station, '' AS AuthCode, DATE_FORMAT(CONCAT(ov.vstdate,' ',ov.vsttime), '%Y-%m-%d %h:%i:%s') AS DTTran, 
  (SELECT     hospitalcode   FROM    opdconfig   LIMIT 1) AS HCode ,
  vn.vn AS  InvNo,
  IFNULL(r.rcpno,'') AS BillNo,
  vn.hn AS HN,
  '' AS MemberNo,
 CAST(vn.income AS DECIMAL(10,2))  AS  Amount,
 CAST(vn.inc06 AS DECIMAL(10,2)) AS Paid,
  '' AS VerCode ,
  '' AS Tflag  
  
FROM
  vn_stat vn 
  LEFT JOIN rcpt_print r ON vn.vn=r.vn
  JOIN ovst ov ON vn.vn=ov.vn
 WHERE ov.vstdate BETWEEN '$st_date' and '$en_date' and ov.pttype = '23' and isnull (ov.an)
ORDER BY ov.vstdate, ov.vsttime;";
$conn_DBHOS->conn_PDO();
$conn_DBHOS->imp_sql($sql);
$query1=$conn_DBHOS->select();

$sql2="SELECT 
    op.hos_guid,op.vn AS InvNo,
   i2.cscd_group AS BillMuad, 
  CAST(op.sum_price AS DECIMAL(10,2)) AS Amount, 
  '0.00'  AS Paid,DATE_FORMAT(CONCAT(ov.vstdate,' ',ov.vsttime), '%Y-%m-%d %h:%i:%s') AS DTTran
FROM
  opitemrece op 
  LEFT JOIN vn_stat v ON op.vn = v.vn 
	JOIN ovst ov ON v.vn=ov.vn
  LEFT JOIN income i1 ON i1.income=op.income 
  LEFT JOIN income_report2 i2 ON  i1.group2=i2.group_id  
  
WHERE op.vn IN 
  (SELECT 
    ov.vn 
  FROM
    ovst ov 
  WHERE ov.vstdate BETWEEN '$st_date' and '$en_date' and ov.pttype = '23' and isnull (ov.an));";
$conn_DBHOS->imp_sql($sql2);
$query2=$conn_DBHOS->select();

$data="";
$count_qr1=count($query1);
for($i=0;$i<$count_qr1;$i++){
	if ($i != 0) {
               $data.=", ";
            } 
     $data.="('".$query1[$i]['Station']."','".$query1[$i]['AuthCode']."','".$query1[$i]['DTTran']."','".$query1[$i]['HCode']."','".$query1[$i]['InvNo']."','".$query1[$i]['BillNo']."','".$query1[$i]['HN']."','".
        $query1[$i]['MemberNo']."','".$query1[$i]['Amount']."','".$query1[$i]['Paid']."','".$query1[$i]['VerCode']."','".
            $query1[$i]['Tflag']."','0')";
    }   
 $table="billtran";
    $chk="chk";
 $inert_tran=$conn_DBMAIN->insert_update_new($table, $data, $chk); 
    
$data="";
$count_qr2=count($query2);
for($i=0;$i<$count_qr2;$i++){
    if ($i != 0) {
               $data.=", ";
            } 
    $data.="('".$query2[$i]['hos_guid']."','".$query2[$i]['InvNo']."','".$query2[$i]['BillMuad']."','".$query2[$i]['Amount']."','".$query2[$i]['Paid']."','".$query2[$i]['DTTran']."','0')";
    }
    $table="billtran_item";
    $chk="chk";
$inert_tranitem=$conn_DBMAIN->insert_update_new($table, $data, $chk);  

/*if(($inert_tran and $inert_tranitem)==FALSE){
    $conn_DBHOS->close_PDO();
    $conn_DBMAIN->close_PDO();
   exit();
}else{*/
//////////////Import MIS///////////////////

$sql="SELECT SUBSTR(ov.vstdate,1,7) AS vstmonth,
(select COUNT(ov.vn ) from vn_stat ov where ov.sex='1' and ov.vstdate LIKE '$year-$month%') as man,
(select COUNT(ov.vn ) from vn_stat ov where ov.sex='2' and ov.vstdate LIKE '$year-$month%') as woman
from vn_stat ov ,patient pt ,ovst ovst 
where  ov.vn=ovst.vn and pt.hn=ov.hn  and ov.hn=pt.hn and ov.vstdate LIKE '$year-$month%'
 and ov.age_y>= 0 
 and ov.age_y<= 200 
GROUP BY SUBSTR(ov.vstdate,1,7)";
$conn_DBHOS->imp_sql($sql);
$query1=$conn_DBHOS->select();

$sql2="select SUBSTR(a.vstdate,1,7) as vstmonth,i.code3,count(a.pdx) as pdx_count ,i.name as icdname 
from vn_stat a 
left outer join icd101 i on i.code=a.pdx
where SUBSTR(a.vstdate,1,7) LIKE '$year-$month%'
and a.pdx<>'' and a.pdx is not null 
group by i.code3 
order by pdx_count desc 
limit 10
";
$conn_DBHOS->imp_sql($sql2);
$query2=$conn_DBHOS->select();

$sql3="select SUBSTR(ov.vstdate,1,7) as vstmonth,IF((SUBSTR(ov.aid,1,2)in(42,41,67,39,43)),SUBSTR(ov.aid,1,2),'00') as province,
COUNT(ov.hn) as count_patient
from vn_stat ov ,patient pt ,ovst ovst 
where  ov.vn=ovst.vn and pt.hn=ov.hn and ov.hn=pt.hn 
 and SUBSTR(ov.vstdate,1,7) LIKE '$year-$month%'
 and ov.age_y>= 0 
 and ov.age_y<= 200 
GROUP BY province ORDER BY count_patient desc";
$conn_DBHOS->imp_sql($sql3);
$query3=$conn_DBHOS->select();

$sql4="select a.admdate
,(select count(*) from ipt where regdate=a.admdate and ward='01') 'admit_m1'
,(select count(*) from ipt where regdate=a.admdate and ward='04') 'admit_m2'
,(select count(*) from ipt where regdate=a.admdate and ward='02') 'admit_w'
,(select count(*) from ipt where regdate=a.admdate) 'admit_total'
,(select count(*) from ipt where dchdate=a.admdate and ward='01') 'dch_m1'
,(select count(*) from ipt where dchdate=a.admdate and ward='04') 'dch_m2'
,(select count(*) from ipt where dchdate=a.admdate and ward='02') 'dch_w'
,(select count(*) from ipt where dchdate=a.admdate) 'dch_total'
,(select count(*) from ipt where regdate<=a.admdate and ward='01' and (dchdate>a.admdate or dchdate is null)) 'stable_m1'
,(select count(*) from ipt where regdate<=a.admdate and ward='04' and (dchdate>a.admdate or dchdate is null)) 'stable_m2'
,(select count(*) from ipt where regdate<=a.admdate and ward='02' and (dchdate>a.admdate or dchdate is null)) 'stable_w'
,(select count(*) from ipt where regdate<=a.admdate and (dchdate>a.admdate or dchdate is null)) 'stable_total'
from (select vstdate 'admdate'
from ovst
where vstdate like '$year-$month%'
group by vstdate) a";
$conn_DBHOS->imp_sql($sql4);
$query4=$conn_DBHOS->select();

$sql5="select a.admdate
,(select count(*) from an_stat an where an.regdate=a.admdate and an.sex='1') 'admit_m'
,(select count(*) from an_stat an where an.regdate=a.admdate and an.sex='2') 'admit_w'
,(select count(*) from an_stat an where an.regdate=a.admdate) 'admit_total'
,(select count(*) from an_stat an where an.dchdate=a.admdate and an.sex='1') 'dch_m'
,(select count(*) from an_stat an where an.dchdate=a.admdate and an.sex='2') 'dch_w'
,(select count(*) from an_stat an where an.dchdate=a.admdate) 'dch_total'
,(select count(*) from an_stat an where an.regdate<=a.admdate and an.sex='1' and (an.dchdate>a.admdate or an.dchdate is null)) 'stable_m'
,(select count(*) from an_stat an where an.regdate<=a.admdate and an.sex='2' and (an.dchdate>a.admdate or an.dchdate is null)) 'stable_w'
,(select count(*) from an_stat an where an.regdate<=a.admdate and (an.dchdate>a.admdate or an.dchdate is null)) 'stable_total'
from (select vstdate 'admdate'
from ovst
where vstdate like '$year-$month%'
group by vstdate) a;
";
$conn_DBHOS->imp_sql($sql5);
$query5=$conn_DBHOS->select();

$data="";
$count_qr1=count($query1);
for($i=0;$i<$count_qr1;$i++){
    if ($i != 0) {
               $data.=", ";
            } 
     $data.="('".$query1[$i]['vstmonth']."','".$query1[$i]['man']."','".$query1[$i]['woman']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','0')";
 }
 $table="opd_report";
 $chk="chk";
 $inert_opd=$conn_DBMAIN->insert_update_new($table, $data, $chk);  

$data="";
$count_qr2=count($query2);
for($i=0;$i<$count_qr2;$i++){ 
            if ($i != 0) {
               $data.=", ";
            } 
     $icdname=$conv->tis620_to_utf8($query2[$i]['icdname']);
     $vstmonth=$query2[$i]['vstmonth'].'-01';
     $data.="('".$vstmonth."','".$query2[$i]['code3']."','".$query2[$i]['pdx_count']."','".$icdname."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','0')";
    }
    
    $table="opd_report_10dxg";
    $chk="chk";
    $inert_10dxg=$conn_DBMAIN->insert_update_new($table, $data, $chk);
    
	$data="";
    $count_qr3=count($query3);
    for($i=0;$i<$count_qr3;$i++){
        if ($i != 0) {
               $data.=", ";
            }
     $vstmonth=$query3[$i]['vstmonth'].'-01';
     $data.="('".$vstmonth."','".$query3[$i]['province']."','".$query3[$i]['count_patient']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','0')";
    }
    $table="opd_report_5prov";
    $chk="chk";
    $inert_5prov=$conn_DBMAIN->insert_update_new($table, $data, $chk); 
    
	$data="";
    $count_qr4=count($query4);
    for($i=0;$i<$count_qr4;$i++){
        if ($i != 0) {
               $data.=", ";
            }
	$admdate=$query4[$i]['admdate'];
     $data.="('$admdate','".$query4[$i]['admit_m1']."','".$query4[$i]['admit_m2']."','".$query4[$i]['admit_w']."','".$query4[$i]['admit_total']."','".$query4[$i]['dch_m1']."'
             ,'".$query4[$i]['dch_m2']."','".$query4[$i]['dch_w']."','".$query4[$i]['dch_total']."','".$query4[$i]['stable_m1']."','".$query4[$i]['stable_m2']."','".$query4[$i]['stable_w']."'
             ,'".$query4[$i]['stable_total']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','0')";
     }
    $table="ipd_report_stable";
    $chk="chk";
    
     $inert_stable=$conn_DBMAIN->insert_update_new($table, $data, $chk); 
    
	$data="";
    $count_qr5=count($query5);
    
    for($i=0;$i<$count_qr5;$i++){
     if ($i != 0) {
               $data.=", ";
            }
	$admdate=$query5[$i]['admdate'];
     $data.="('$admdate','".$query5[$i]['admit_m']."','".$query5[$i]['admit_w']."','".$query5[$i]['admit_total']."','".$query5[$i]['dch_m']."'
             ,'".$query5[$i]['dch_w']."','".$query5[$i]['dch_total']."','".$query5[$i]['stable_m']."','".$query5[$i]['stable_w']."'
             ,'".$query5[$i]['stable_total']."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."','0')";
    }
    $table="ipd_report_sex";
    $chk="chk";
     
     $inert_sex=$conn_DBMAIN->insert_update_new($table, $data, $chk);  
     
/*if(($inert_opd and $inert_10dxg and $inert_5prov and $inert_stable and $inert_sex)==FALSE){
    $conn_DBHOS->close_PDO();
    $conn_DBMAIN->close_PDO();
    exit();
}}}*/
//////////////end import process////////////
$conn_DBHOS->close_PDO();
$conn_DBMAIN->close_PDO();
