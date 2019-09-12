<?php
$workDir = "/your-directory/ogiNikki/";
$data = file($workDir."userDic/date.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($data as $val) {
	$valArray = explode(",", $val);
	
	// Setting values for the dictionary without cost values.
	$list[] =  $valArray[0].",,,,名詞,普通名詞,一般,*,*,*,".mb_convert_kana(@$valcomma[1], "C").",".$valcomma[0].",".$valcomma[0].",".mb_convert_kana(@$valcomma[1], "C").",".$valcomma[0].",".mb_convert_kana(@$valcomma[1], "C").",和,*,*,*,*,OGI_DATE"."\n";
}
	
$datafile = $workDir."userDic/ogiDateNoC.csv";
$dataval = '';
if (is_writable($datafile)) {
	file_put_contents($datafile, $list, LOCK_EX);
} else {
	trigger_error("Failed to open the file.");
}

?>
