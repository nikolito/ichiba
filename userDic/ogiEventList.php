<?php //Genreのコストファイル生成
$data = file("/home/yoshiga/ogiNikki/userDic/event.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data2 = file("/home/yoshiga/ogiNikki/userDic/termsKomonjo.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($data as $val) {
	$valcomma = explode(",", $val);
	#print $valcomma[0];
	#$list[] =  $valcomma[0].",4785,4785,7996,名詞,普通名詞,一般,*,*,*,,,".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_GENRE";
	$list[] =  $valcomma[0].",,,,名詞,普通名詞,一般,*,*,*,,,".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_EVENT"."\n";
}

foreach ($data2 as $val2) {
	$valcomma2 = explode(",", $val2);
	if (array_search($valcomma2[0], $data) === false) {
		$yomi = mb_convert_kana($valcomma2[1], "C");
		$list[] = $valcomma2[0].",,,,名詞,普通名詞,一般,*,*,*,".$yomi.",".$valcomma2[0].",".$valcomma2[0].",".$yomi.",".$valcomma2[0].",".$yomi.",和,*,*,*,*,OGI_EVENT"."\n";
	}
}

$datafile = "/home/yoshiga/ogiNikki/userDic/ogiEventNoC.csv";
if (is_writable($datafile)) {
	file_put_contents($datafile, $list, LOCK_EX);
} else {
	trigger_error("ファイルを開ける状態ではありません。");
}
?>
