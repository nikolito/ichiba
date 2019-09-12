<?php  //words/jinmeiPatterns.csvからMecab辞書用データを作成する．
$data1 = file("/home/yoshiga/ogiNikki/userDic/terms.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data3 = file("/home/yoshiga/ogiNikki/userDic/ogiTerms.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
#print_r($data1); print_r($data2); exit;

foreach ($data1 as $d1val) {
	$d1valArray = explode(",", $d1val);
	$data1List[] = $d1valArray[0];
	#$list[] = $data1List[0].",4785,4785,7996,名詞,普通名詞,一般,*,*,*,,".$data1List[0].",".$data1List[0].",,".$data1List[0].",,和,*,*,*,*,OGI_TERMS";
	$list[] = $data1List[0].",,,,名詞,普通名詞,一般,*,*,*,,".$data1List[0].",".$data1List[0].",,".$data1List[0].",,和,*,*,*,*,OGI_TERMS"."\n";
}

foreach ($data3 as $val) {
	$valcomma = explode(",", $val);
	#print $valcomma[0];
	
	if ($valcomma[5] != "*") {
		Switch ($valcomma[1]) {
			case "接頭辞":
				#$list[] = $valcomma[0].",5688,5688,5580,".$valcomma[1].",".$valcomma[2].",".$valcomma[3].",*,*,*,,".$valcomma[0].",".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_TERMS";
				$list[] = $valcomma[0].",,,,".$valcomma[1].",".$valcomma[2].",".$valcomma[3].",*,*,*,,".$valcomma[0].",".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_TERMS"."\n";
				break;
			case "接尾辞":
				#$list[] = $valcomma[0].",5771,5771,7129,".$valcomma[1].",".$valcomma[2].",".$valcomma[3].",*,*,*,,".$valcomma[0].",".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_TERMS";
				$list[] = $valcomma[0].",,,,".$valcomma[1].",".$valcomma[2].",".$valcomma[3].",*,*,*,,".$valcomma[0].",".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_TERMS"."\n";
				break;
			case "助詞":
				#$list[] = $valcomma[0].",803,803,6933,".$valcomma[1].",".$valcomma[2].",".$valcomma[3].",*,*,*,,".$valcomma[0].",".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_TERMS";
				$list[] = $valcomma[0].",,,,".$valcomma[1].",".$valcomma[2].",".$valcomma[3].",*,*,*,,".$valcomma[0].",".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_TERMS"."\n";
				break;
			case "名詞":
			default:
				#$list[] = $valcomma[0].",4785,4785,7996,".$valcomma[1].",".$valcomma[2].",".$valcomma[3].",*,*,*,,".$valcomma[0].",".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_TERMS";
				$list[] = $valcomma[0].",,,,".$valcomma[1].",".$valcomma[2].",".$valcomma[3].",*,*,*,,".$valcomma[0].",".$valcomma[0].",,".$valcomma[0].",,和,*,*,*,*,OGI_TERMS"."\n";
				break;
		}
	}
}

sort($list, SORT_NATURAL);
$list2 = array_unique($list);

	
$datafile = '/home/yoshiga/ogiNikki/userDic/ogiTermsNoC.csv';
if (is_writable($datafile)) {
	file_put_contents($datafile, $list2, LOCK_EX);
} else {
	trigger_error("ファイルを開ける状態ではありません。");
}
?>
