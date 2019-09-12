<?php //Genreのコストファイル生成
$data = file("/home/yoshiga/ogiNikki/userDic/quantity.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
sort($data);
$dataAll = array_unique($data);

foreach ($dataAll as $val) {
	$valcomma = explode(",", $val);
	#print $valcomma[0];
	#$list[] =  $valcomma[0].",4785,4785,7996,名詞,普通名詞,一般,*,*,*,,,".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_ROLE";
	$list[] =  $valcomma[0].",,,,名詞,普通名詞,副詞可能,*,*,*,,,".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_QUANTITY"."\n";
}

$datafile = "/home/yoshiga/ogiNikki/userDic/ogiQuantityNoC.csv";
$dataval = '';
if (is_writable($datafile)) {
	file_put_contents($datafile, $list, LOCK_EX);
} else {
	trigger_error("ファイルを開ける状態ではありません。");
}

?>
