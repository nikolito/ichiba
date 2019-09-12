<?php 
// mainAnalysis.php: Deploying morphological analysis, named entity extraction and storing data.
// PHP newer than v7, A morphological analysis tool, MeCab and a database tool, such as PostgreSQL is required for this script.
// Please install them on your system.

$workDir = "/your_directory/ogiNikki/";

include_once $workDir.'function.php';
include_once $workDir.'dbsetting.php';

$url = 'https://your_domain/ogiNikki/';

//field data with MeCab flags
//You can choose which fields you want to mecab
$field = file($workDir."fieldDataOgiNikki.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//ogiMain CSV data (https://www.dl.saga-u.ac.jp/ogiNikki) 
$recordBase = file($workDir."ogiMain_sample.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);


///////////////////////////////////////////
// Settling tags for NEE
// finding MeCab flags. 

// "title" field is 5th column in ogiMain data.
foreach($recordBase as $recBVal) {
	$rbval = explode(',', $recBVal);
	$record[] = $rbval[4]; // Change number for actual column number on you data.
}
#print_r($record); exit;

// If you start an analysis from record number 1, set 0 in $rnBase.
$rnBase = 0;

$sentence = array();
$mtextarray = array();

for($rn=$rnBase; $rn<count($record); $rn++) {
	$value = explode(',', $record[$rn]);

	foreach($value as $key => $val) {
		$sentence_pre = preg_replace('/　/', '@@', $val);
		$sentence_pre = mb_ereg_replace('[@]{2}$|\n', '', $sentence_pre);
		$sentence[$rn][] = explode('@@', $sentence_pre);
	}

	// MeCab 
	for ($snum = 0; $snum < count($sentence[$rn]); $snum++) {
		for ($osnum = 0; $osnum < count($sentence[$rn][$snum]); $osnum++) {
			$mecab = new \MeCab\Tagger();
			$mtext = $mecab->parse(trim($sentence[$rn][$snum][$osnum])); 
			$mtext = str_replace("EOS", '', $mtext);
			$mdatavals[] = $mtext;
			$mtextarray[$rn][$snum][$osnum] = explode("\n", trim($mtext));
		}
	}
}

#print_r($sentence); exit;
#print_r($mtextarray); exit;
#print count($mtextarray); exit;
#print_r($mdatavals); exit;

/////////////////////////////////
// Saving All MeCab analized data 

$type = "ALL";

$rn = $snum = $osnum = 0;
for ($i = 0; $i <= count($mtextarray); $i++) {
	$rn = $rnBase + $i;
	for ($e = 0; $e < @count($mtextarray[$rn]); $e++) {
		$snum = $e;
		for ($f = 0; $f < @count($mtextarray[$rn][$e]); $f++) {
			$osnum = $f;
	    		for ($j = 0; $j < count($mtextarray[$rn][$e][$f]); $j++) {
				$tangoarray = explode("\t", $mtextarray[$rn][$e][$f][$j]);
				$element = '';
				$element = @str_replace(",", "::", $tangoarray[1]);
				$data_all[] = $type.",".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$element;
			}
		}
	}
}
#print_r($data_all); exit;

/////////////////////////////////////////
// Storing data in a MeCab analyzed file

$mdatafile = $workDir."mtextdata".date('YmdHis').'.txt';
file_put_contents($mdatafile, implode("\n", $mdatavals));
#readfile($mdatafile); exit;

//全データをファイルに格納
$datafile = $workDir."ogiDataAll".date('YmdHis').'.csv';
file_put_contents($datafile, implode("\n", $data_all));
#readfile($datafile); exit;


$jinmeiF = file($workDir."userDic/jinmei.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$roleF = file($workDir."userDic/role.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$placeF = file($workDir."userDic/place.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$dateF = file($workDir."userDic/date.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$eventF = file($workDir."userDic/event.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$termsF = file($workDir."userDic/terms.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$quF = file($workDir."userDic/quantity.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$toki_tango = array();
$qu_tango = array();
$terms_tango = array();
$event_tango = array();
$place_tango = array();
$jinmei_tango = array();
$role_tango = array();

$rn = $snum = $osnum = 0;
for ($i = 0; $i < count($mtextarray); $i++) {
	$rn = $rnBase + $i;
	for ($e = 0; $e < count($fldflag); $e++) {
		$snum = 0;
		for ($f = 0; $f < @count($mtextarray[$rn][$snum]); $f++) {
			$osnum = $f;
			for ($j = 0; $j < count($mtextarray[$rn][$snum][$f]); $j++) {
				$tangoarray = explode("\t", $mtextarray[$rn][$snum][$f][$j]);
				$tangoarrayNext = @explode("\t", $mtextarray[$rn][$snum][$f][($j+1)]);
				$element = @explode(",", $tangoarray[1]);
				$uri = $url.($rn + 1)."-".$snum."-".$osnum."-";
		
				// Person
				if (@mb_strpos($tangoarray[1], "_JINMEI") !== false && 
				(array_search($tangoarray[0], $roleF) === false || 
				array_search($tangoarray[0], $eventF) === false)
				) {
					$jinmei_tango[] = "Person,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
				}
										
				// Role
				if (@mb_strpos($tangoarray[1], "_ROLE") !== false || 
				array_search($tangoarray[0], $roleF) !== false) {
					$role_tango[] = "Role,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
				}
					
				// Place
				if (@mb_strpos($tangoarray[1], "_PLACE") !== false || 
				array_search($tangoarray[0], $placeF) !== false) {
					$place_tango[] = "Place,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
				}
					
				// Date
				if (@mb_strpos($tangoarray[1], "_DATE") !== false || 
				array_search($tangoarray[0], $dateF) !== false) {
					$toki_tango[] = "Date,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
				}
					
				// Event
				if (@mb_strpos($tangoarray[1], "_EVENT") !== false || 
				array_search($tangoarray[0], $eventF) !== false) {
					$event_tango[] = "Event,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
				}
					
				// Terms
				if (@mb_strpos($tangoarray[1], "_TERMS") !== false) {
					$terms_tango[] = "Terms,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
				}
					
				// Quantity
				if (@mb_strpos($tangoarray[1], "_QUANTITY") !== false || 
				array_search($tangoarray[0], $quF) !== false) {
					$qu_tango[] = "Quantity,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
				}
			}
		}
	}
}

// Saving NEE data in a file.
$data = array_merge($event_tango, $toki_tango, $place_tango, $jinmei_tango, $terms_tango, $role_tango, $qu_tango);
sort($data, SORT_NATURAL);
$data2 = array_unique($data, SORT_STRING);
#print_r($data2); exit;

$indexfile = $workDir."oginikki".date('YmdHis').'.index';
file_put_contents($indexfile, implode("\n", $data2));
readfile($indexfile);

?>
