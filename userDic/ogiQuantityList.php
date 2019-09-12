<?php 
include_once "settings.php";
$data = file($wordDir."userDic/quantity.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
sort($data);
$dataAll = array_unique($data);

foreach ($dataAll as $val) {
	$valArray = explode(",", $val);
	$list[] =  $valArray[0].",,,,名詞,普通名詞,副詞可能,*,*,*,,,".$valArray[0].",,".$valArray[0].",,和,*,*,*,*,OGI_QUANTITY"."\n";
}

$datafile = $wordDir."userDic/ogiQuantityNoC.csv";
$dataval = '';
if (is_writable($datafile)) {
	file_put_contents($datafile, $list, LOCK_EX);
} else {
	trigger_error("ファイルを開ける状態ではありません。");
}

?>
