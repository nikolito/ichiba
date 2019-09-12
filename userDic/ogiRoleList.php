<?php 
include_once "settings.php";
$data1 = file($wordDir."userDic/role.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data2 = file($wordDir."userDic/roleSaga.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data = array_merge($data1, $data2);
sort($data);
$dataAll = array_unique($data);

foreach ($dataAll as $val) {
	$valArray = explode(",", $val);
	$list[] =  $valArray[0].",,,,名詞,普通名詞,一般,*,*,*,,,".$valArray[0].",,".$valArray[0].",,和,*,*,*,*,OGI_ROLE"."\n";
}

$datafile = $wordDir."userDic/ogiRoleNoC.csv";
$dataval = '';
if (is_writable($datafile)) {
	file_put_contents($datafile, $list, LOCK_EX);
} else {
	trigger_error("ファイルを開ける状態ではありません。");
}

?>
