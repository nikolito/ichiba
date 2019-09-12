<?php 
$workDir = "/your-directory/ogiNikki/";
$hanseki1 = file_get_contents($workDir."userDic/ogiNabeshima.xml", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$hanseki2 = file_get_contents($workDir."userDic/honNabeshima.xml", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$hanseki3 = file_get_contents($workDir."userDic/hasuNabeshima.xml", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$hansekiArray = array('OGI_JINMEI' => $hanseki1, 'OGI_JINMEI' => $hanseki2, 'OGI_JINMEI' => $hanseki3);

$familynames = file($workDir."userDic/familyNames.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$fmPattern = "/(".implode(")|(", $familynames).")/u";
#print $fmPattern;
#exit;

$jinmei1 = file($workDir."userDic/jinmei.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$jinmei2 = file($workDir."userDic/jinmeiSaga.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$jinmei3 = file($workDir."userDic/jinmeiOgi.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$jinmeiAll = array_merge($jinmei1, $jinmei2, $jinmei3);
sort($jinmeiAll);
$jinmei = array_unique($jinmeiAll);

$yomi = '*';
$hanmei = 'OGI_JINMEI';

foreach($hansekiArray as $hanmei => $hansekiName) {
	$hanseki = new SimpleXMLElement($hansekiName);
	$underName = '';
	
	//name
	foreach($hanseki->xpath('//name') as $name) {
		if ($name == "") {
			continue;
		} else {
			preg_match($fmPattern, $name, $fname);
			if (@$fname[0] != "") {
				$data[] = $fname[0].",,,,名詞,固有名詞,人名,姓,*,*,".$yomi.",".$yomi.",".$fname[0].",".$yomi.",".$fname[0].",".$yomi.",固,*,*,*,*,".$hanmei."\n";
				
				$underName = str_replace($familynames, '', $name);
				if ($underName != '氏' || $underName != '子' || $underName != '娘') {
					$data[] = $underName.",,,,名詞,固有名詞,人名,名,*,*,".$yomi.",".$yomi.",".$underName.",".$yomi.",".$underName.",".$yomi.",固,*,*,*,*,".$hanmei."\n";
				}
			}
			$data[] = $name.",,,,名詞,固有名詞,人名,一般,*,*,".$yomi.",".$yomi.",".$name.",".$yomi.",".$name.",".$yomi.",固,*,*,*,*,".$hanmei."\n";
		}
	}
	
	//altName (main*, first*, other*)
	foreach($hanseki->xpath('//altName/main') as $altName) {
		if ($altName == "") {
			continue;
		} else {
			preg_match($fmPattern, $altName, $fname);
			if (@$fname[0] != "") {
				$data[] = $fname[0].",,,,名詞,固有名詞,人名,姓,*,*,".$yomi.",".$yomi.",".$fname[0].",".$yomi.",".$fname[0].",".$yomi.",固,*,*,*,*,".$hanmei."\n";
				
				$underName = str_replace($familynames, '', $altName);
				if ($underName != '氏' || $underName != '子' || $underName != '娘') {
					$data[] = $underName.",,,,名詞,固有名詞,人名,名,*,*,".$yomi.",".$yomi.",".$underName.",".$yomi.",".$underName.",".$yomi.",固,*,*,*,*,".$hanmei."\n";
				}
			}
			$data[] = $altName.",,,,名詞,固有名詞,人名,一般,*,*,".$yomi.",".$yomi.",".$altName.",".$yomi.",".$altName.",".$yomi.",固,*,*,*,*,".$hanmei."\n";
		}
	}
	
	foreach($hanseki->xpath('//altName/first') as $altName1) {
		if ($altName1 != "") {
			$data[] = $altName1.",,,,名詞,固有名詞,人名,一般,*,*,".$yomi.",".$yomi.",".$altName1.",".$yomi.",".$altName1.",".$yomi.",固,*,*,*,*,".$hanmei."\n";
		}
	}
	
	foreach($hanseki->xpath('//altName/other') as $altName2) {
		if ($altName2 != "") {
			$data[] = $altName2.",,,,名詞,固有名詞,人名,一般,*,*,".$yomi.",".$yomi.",".$altName2.",".$yomi.",".$altName2.",".$yomi.",固,*,*,*,*,".$hanmei."\n";
		}
	}
}

foreach($jinmei as $jinmeiVal) {
	if (trim($jinmeiVal) != "") {
		$data[] = $jinmeiVal.",,,,名詞,固有名詞,人名,一般,*,*,".$yomi.",".$yomi.",".$jinmeiVal.",".$yomi.",".$jinmeiVal.",".$yomi.",固,*,*,*,*,OGI_JINMEI"."\n";
	}
}

sort($data);
$dataJinmei = array_unique($data);

//コスト無しファイル
$datafile = $workDir."userDic/ogiJinmeiNoC.csv"; 
if (is_writable($datafile)) {
	file_put_contents($datafile, $dataJinmei, LOCK_EX);
} else {
	trigger_error("Failed to open the file.");
}

?>
