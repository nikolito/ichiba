<?php
///home/yoshiga/ogiNikki/dateterms.csvをdateterms_originalとしてコピーしておく。
$data = file("/home/yoshiga/ogiNikki/userDic/date.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($data as $val) {
	$valcomma = explode(",", $val);
	#print $valcomma[0];
	//コストは直接指定する
	#$list[] =  $valcomma[0].",4785,4785,7996,名詞,普通名詞,一般,*,*,*,".mb_convert_kana(@$valcomma[1], "C").",".$valcomma[0].",".$valcomma[0].",".mb_convert_kana(@$valcomma[1], "C").",".$valcomma[0].",".mb_convert_kana(@$valcomma[1], "C").",和,*,*,*,*,OGI_DATE";
	$list[] =  $valcomma[0].",,,,名詞,普通名詞,一般,*,*,*,".mb_convert_kana(@$valcomma[1], "C").",".$valcomma[0].",".$valcomma[0].",".mb_convert_kana(@$valcomma[1], "C").",".$valcomma[0].",".mb_convert_kana(@$valcomma[1], "C").",和,*,*,*,*,OGI_DATE"."\n";
}
	
$datafile = '/home/yoshiga/ogiNikki/userDic/ogiDateNoC.csv';
$dataval = '';
if (is_writable($datafile)) {
	file_put_contents($datafile, $list, LOCK_EX);
} else {
	trigger_error("ファイルを開ける状態ではありません。");
}

?>