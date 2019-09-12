<?php
	// makeOgiDic.php: MeCab user dictionary creation
	// Creating and updating entity lists.
	
	$workDir = "/your-directory/ogiNikki/";
	$mecabDir = "/your-mecab-directory/";
	
        // Removing dubbed words.
	system("/usr/bin/php ".$workDir."uniqFile.php;");
	
	//Creating MeCab cost file without cost values.
	system("/usr/bin/php ".$workDir."userDic/ogiDateList.php;");
	system("/usr/bin/php ".$workDir."userDic/ogiJinmeiList.php;");
	system("/usr/bin/php ".$workDir."userDic/ogiEventList.php;");
	system("/usr/bin/php ".$workDir."userDic/ogiTermsList.php;");
	system("/usr/bin/php ".$workDir."userDic/ogiPlaceList.php;");
	system("/usr/bin/php ".$workDir."userDic/ogiRoleList.php;");
	system("/usr/bin/php ".$workDir."userDic/ogiQuantityList.php;");

	system("/bin/cat ".$workDir."userDic/*NoC.csv > ".$workDir."userDic/ogiNoCAll.csv;");
	
	// Adding cost values into the cost file.
	system("/usr/local/libexec/mecab/mecab-dict-index -m ".$mecabDir."model/model.def -d ".$mecabDir." -u ".$workDir."userDic/ogiCost.csv -f utf-8 -t utf-8 -a ".$workDir."userDic/ogiNoCAll.csv;");
	
	// In addition of the above operation, modifying the cost values of named entities.
	$costbase = file($workDir."userDic/ogiCost.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach($costbase as $cbVal) {
		$dbattr = $cblist = array();
		$cbattr = explode(",", $cbVal);

		// Longer entities, adding bigger values.
		$weight = mb_strlen($cbattr[0]);
		
		if ($cbattr[21] == "OGI_JINMEI") {
			$cblist = array_replace($cbattr, array(3 => ($cbattr[3] - 5000 * $weight)));
		} elseif ($cbattr[21] == "OGI_PLACE") {
			$cblist = array_replace($cbattr, array(3 => ($cbattr[3] - 4000 * $weight)));
		} elseif ($cbattr[21] == "OGI_ROLE") {
			$cblist = array_replace($cbattr, array(3 => ($cbattr[3] - 3000 * $weight)));
		} elseif ($cbattr[21] == "OGI_EVENT") {
			$cblist = array_replace($cbattr, array(3 => ($cbattr[3] - 3000 * $weight)));
		} else {
			$cblist = array_replace($cbattr, array(3 => ($cbattr[3] - 2000 * $weight)));
		}

		$changedCost[] = implode(",", $cblist)."\n";
	}
	#print_r($changedCost); exit;
	
	$datafile = $workDir."userDic/ogiCostNew.csv";
	
	if (is_writable($datafile)) {
		file_put_contents($datafile, $changedCost, LOCK_EX);
	} else {
		trigger_error("Failed to open the file. ");
		exit;
	}	
	
	// Creating a user dictionary.
	system("/usr/local/libexec/mecab/mecab-dict-index -d ".$mecabDir." -u ".$workDir."userDic/ogi.dic -f utf8 -t utf8 ".$workDir."userDic/ogiCostNew.csv;");
	exit;
	
?>
