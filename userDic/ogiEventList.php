<?php 
include_once "settings.php";
$data = file($workDir."userDic/event.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data2 = file($workDir."userDic/termsKomonjo.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($data as $val) {
	$valArray = explode(",", $val);
	$list[] =  $valArray[0].",,,,名詞,普通名詞,一般,*,*,*,,,".$valArray[0].",,".$valArray[0].",,和,*,*,*,*,OGI_EVENT"."\n";
}

foreach ($data2 as $val2) {
	$valArray2 = explode(",", $val2);
	if (array_search($valArray2[0], $data) === false) {
		$yomi = mb_convert_kana($valArray2[1], "C");
		$list[] = $valArray2[0].",,,,名詞,普通名詞,一般,*,*,*,".$yomi.",".$valArray2[0].",".$valArray2[0].",".$yomi.",".$valArray2[0].",".$yomi.",和,*,*,*,*,OGI_EVENT"."\n";
	}
}

$datafile = $workDir."userDic/ogiEventNoC.csv";
if (is_writable($datafile)) {
	file_put_contents($datafile, $list, LOCK_EX);
} else {
	trigger_error("Failed to open the file.");
}
?>
