<?php
session_start();
$datapack = $_SESSION['csvExcel'];
$csvHeaders = $_SESSION['csvHeaders'];

$docfile = "csv_export_".date("Ymdhis");
$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=$docfile.csv");

if(is_array($csvHeaders)) { fputcsv($output, $csvHeaders); }
if(is_array($datapack)) { foreach($datapack as $row) { fputcsv($output, $row); } }
fclose($output) or die("Can't close php://output");

?>