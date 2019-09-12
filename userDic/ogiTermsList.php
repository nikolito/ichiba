<?php 
include_once "settings.php";．
$data1 = file($workDir."userDic/terms.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$data3 = file($workDir."userDic/ogiTerms.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($data1 as $d1val) {
	$d1valArray = explode(",", $d1val);
	$data1List[] = $d1valArray[0];
	$list[] = $data1List[0].",,,,名詞,普通名詞,一般,*,*,*,,".$data1List[0].",".$data1List[0].",,".$data1List[0].",,和,*,*,*,*,OGI_TERMS"."\n";
}

foreach ($data3 as $val) {
	$valArray = explode(",", $val);
	
	if ($valArray[5] != "*") {
		Switch ($valArray[1]) {
			case "接頭辞":
				$list[] = $valArray[0].",,,,".$valArray[1].",".$valArray[2].",".$valArray[3].",*,*,*,,".$valArray[0].",".$valArray[0].",,".$valArray[0].",,和,*,*,*,*,OGI_TERMS"."\n";
				break;
			case "接尾辞":
				$list[] = $valArray[0].",,,,".$valArray[1].",".$valArray[2].",".$valArray[3].",*,*,*,,".$valArray[0].",".$valArray[0].",,".$valArray[0].",,和,*,*,*,*,OGI_TERMS"."\n";
				break;
			case "助詞":
				$list[] = $valArray[0].",,,,".$valArray[1].",".$valArray[2].",".$valArray[3].",*,*,*,,".$valArray[0].",".$valArray[0].",,".$valArray[0].",,和,*,*,*,*,OGI_TERMS"."\n";
				break;
			case "名詞":
			default:
				$list[] = $valArray[0].",,,,".$valArray[1].",".$valArray[2].",".$valArray[3].",*,*,*,,".$valArray[0].",".$valArray[0].",,".$valArray[0].",,和,*,*,*,*,OGI_TERMS"."\n";
				break;
		}
	}
}

sort($list, SORT_NATURAL);
$list2 = array_unique($list);

	
$datafile = $workDir."userDic/ogiTermsNoC.csv";
if (is_writable($datafile)) {
	file_put_contents($datafile, $list2, LOCK_EX);
} else {
	trigger_error("Failed to open the file.");
}
?>
