<?php
$wordDir = "/home/yoshiga/ogiNikki/";
$data1 = file($wordDir."userDic/place.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data2 = file($wordDir."userDic/placeSaga.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data3 = file($wordDir."userDic/placeChakuto.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data4 = file($wordDir."userDic/placeSagaJisha.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data5 = file($wordDir."userDic/placeJisha.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$dataAll = array_merge($data1, $data2, $data3, $data4, $data5);
sort($dataAll, SORT_NATURAL);
$data = array_unique($dataAll);

foreach ($data as $val) {
	$valArray = explode(",", $val);
	$list[] =  $valArray[0].",,,,名詞,固有名詞,地名,一般,*,*,,,".$valArray[0].",,".$valArray[0].",,固,*,*,*,*,OGI_PLACE"."\n";
}

$datafile = '$wordDir."userDic/ogiPlaceNoC.csv';
$dataval = '';
if (is_writable($datafile)) {
	file_put_contents($datafile, $list, LOCK_EX);
} else {
	trigger_error("ファイルを開ける状態ではありません。");
}

?>
