<?php
$data1 = file("/home/yoshiga/ogiNikki/userDic/place.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data2 = file("/home/yoshiga/ogiNikki/userDic/placeSaga.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data3 = file("/home/yoshiga/ogiNikki/userDic/placeChakuto.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data4 = file("/home/yoshiga/ogiNikki/userDic/placeSagaJisha.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data5 = file("/home/yoshiga/ogiNikki/userDic/placeJisha.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$dataAll = array_merge($data1, $data2, $data3, $data4, $data5);
sort($dataAll, SORT_NATURAL);
$data = array_unique($dataAll);

foreach ($data as $val) {
	$valcomma = explode(",", $val);
	#print $valcomma[0];
	#$list[] =  $valcomma[0].",4792,4792,10466,名詞,固有名詞,地名,一般,*,*,,,".$valcomma[0].",,".$valcomma[0].",,固,*,*,*,*,OGI_PLACETERMS";
	$list[] =  $valcomma[0].",,,,名詞,固有名詞,地名,一般,*,*,,,".$valcomma[0].",,".$valcomma[0].",,固,*,*,*,*,OGI_PLACE"."\n";
}

$datafile = '/home/yoshiga/ogiNikki/userDic/ogiPlaceNoC.csv';
$dataval = '';
if (is_writable($datafile)) {
	file_put_contents($datafile, $list, LOCK_EX);
} else {
	trigger_error("ファイルを開ける状態ではありません。");
}

?>
