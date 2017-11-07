<?php //include'connection/connect_i.php';?>
	<script language="JavaScript">

function Check_txt(){
	if(document.getElementById('province').value==""){
		alert("กรุณาระบุ จังหวัด ด้วย");
		document.getElementById('province').focus();
		return false;
	}
	if(document.getElementById('amphur').value=='No'){
		alert("กรุณาระบุ อำเภอ ด้วย");
		document.getElementById('amphur').focus();
		return false;
	}
	
	if(document.getElementById('district').value==""){
		alert("กรุณาระบุ ตำบล ด้วย");
		document.getElementById('district').focus();
		return false;
	}
}
</script>
    <div class="form-group"> 
                    <label>จังหวัด &nbsp;</label>
                    <select class="form-control select2" style="width: 100%" name='province' id='province' onchange="data_show(this.value,'amphur');">
		<option value="">---โปรดเลือกจังหวัด---</option>
		<?php
		$rstTemp='select * from province Order By PROVINCE_NAME ASC';
                $mydata->imp_sql($rstTemp);
                $resultGet=$mydata->select();
                for($i=0;$i<count($resultGet);$i++) {
                    if($resultGet[$i]['PROVINCE_ID']==$edit_patient['province']){$selected='selected';}else{$selected='';}
		echo "<option value='".$resultGet[$i]['PROVINCE_ID']."' $selected>".$resultGet[$i]['PROVINCE_NAME']."</option>";
		}?>
	</select>
    </div>
        <div class="form-group">
        <label>อำเภอ &nbsp;</label>
	<select class="form-control" name='amphur' id='amphur'onchange="data_show(this.value,'district');">
            <?php if(isset($_REQUEST['method'])=='edit'){
                $rstTemp = "select * from amphur where AMPHUR_ID='".$edit_patient['district']."'";
                $mydata->imp_sql($rstTemp);
                $resultGet=$mydata->select();
                for($i=0;$i<count($resultGet);$i++) {
                if($resultGet[$i]['AMPHUR_ID']==$edit_patient['district']){$selected='selected';}else{$selected='';}
                echo "<option value='".$resultGet[$i]['AMPHUR_ID']."' $selected>".$resultGet[$i]['AMPHUR_NAME']."</option>";
                
                } }  else {?>
            <option value="">---โปรดเลือกอำเภอ---</option>
            <?php }?>
	</select>
	</div>
        <div class="form-group">
        <label>ตำบล &nbsp;</label>  
	<select class="form-control" name='district' id='district'>
            <?php if($_REQUEST['method']=='edit'){
                $rstTemp = "select * from district where DISTRICT_ID='".$edit_patient['sub_dist']."'";
                $mydata->imp_sql($rstTemp);
                $resultGet=$mydata->select();
                for($i=0;$i<count($resultGet);$i++) {
                if($resultGet[$i]['DISTRICT_ID']==$edit_patient['sub_dist']){$selected='selected';}else{$selected='';}
                echo "<option value='".$resultGet[$i]['DISTRICT_ID']."' $selected>".$resultGet[$i]['DISTRICT_NAME']."</option>";
                
                } }  else {?>
		<option value="">---โปรดเลือกตำบล---</option>
                <?php }?>
	</select>
        </div>

<script language="javascript">
// Start XmlHttp Object
function uzXmlHttp(){
    var xmlhttp = false;
    try{
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    }catch(e){
        try{
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }catch(e){
            xmlhttp = false;
        }
    }
 
    if(!xmlhttp && document.createElement){
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}
// End XmlHttp Object

function data_show(select_id,result){
	var url = 'address2.php?select_id='+select_id+'&result='+result;
	//alert(url);
	
    xmlhttp = uzXmlHttp();
    xmlhttp.open("GET", url, false);
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;charset=utf-8"); // set Header
    xmlhttp.send(null);
	document.getElementById(result).innerHTML =  xmlhttp.responseText;
}
//window.onLoad=data_show(5,'amphur'); 
</script>
