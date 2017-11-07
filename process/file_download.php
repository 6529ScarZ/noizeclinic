<?php
function __autoload($class_name) {
    include '../class/'.$class_name.'.php';
}

$download = new download();
$filename=$_GET['name'].".txt";
$path=$_GET['path'];
$download->download_file($filename, $path);
    