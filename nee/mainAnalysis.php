<?php //Morphological analysis for ogiNikki

//Please change locations of directories and files as you want.
// function.php
// dbsetting.php
// $url
// $workDir

include_once '/nee/function.php';
include_once '/nee/dbsetting.php';

$url = 'https://www.dl.saga-u.ac.jp/ogiNikki/';
$workDir = "/work/";

//macabフラグつきフィールドデータ
$field = file($workDir."fieldDataOgiNikki.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

//ogiMain data
$recordBase = file($workDir."backupOgiDb/ogiMain20190627.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$sentence = array();
$mtextarray = array();

///////////////////////////////////////////
//固有表現タグ付け
//mecabフラグ*が立っている行を見つける
$fldflag = array();
$i = 0;

foreach($field as $fldKey => $fldstr) {
	if (mb_strpos($fldstr, "mainField") !== false) {
		#print $fldKey."\n";
		if (mb_strpos($fldstr, "mecab") !== false) {
			$fldflag[] = $fldKey - $i;
		}
	} else {
		$i++;
	}
}

//mecabを通すフィールド番号を指定する．
#print_r($fldflag); exit;

//ogiMainデータから記事項目のみを抜き出し
foreach($recordBase as $recBVal) {
	$rbval = explode(',', $recBVal);
	$record[] = $rbval[4];
}
#print_r($record); exit;

//解析済みのレコード番号
//開始uid-1を入力する
$rnBase = 0;
#$rnBase = 5127;

for($rn=$rnBase; $rn<count($record); $rn++) {
	$value = explode(',', $record[$rn]);

	foreach($value as $key => $val) {
			$sentence_pre = preg_replace('/　/', '@@', $val);
			$sentence_pre = mb_ereg_replace('[@]{2}$|\n', '', $sentence_pre);
			$sentence[$rn][] = explode('@@', $sentence_pre);
	}
	#print_r($sentence); exit;

	for ($snum = 0; $snum < count($sentence[$rn]); $snum++) {
		for ($osnum = 0; $osnum < count($sentence[$rn][$snum]); $osnum++) {
			#if (array_search($snum, $fldflag) !== false) { //国文研注記は21 市場は18
				//Mecabオブジェクトを生成
				#$mecab = new MeCab_Tagger();
				$mecab = new \MeCab\Tagger();
				$mtext = $mecab->parse(trim($sentence[$rn][$snum][$osnum])); //解析実行
				$mtext = str_replace("EOS", '', $mtext);
			#} else {
				#$mtext = trim($sentence[$rn][$snum][$osnum]);
			#}
			#print $mtext."\n";
			$mdatavals[] = $mtext;
			$mtextarray[$rn][$snum][$osnum] = explode("\n", trim($mtext));
		}
	}
}

#print_r($sentence); exit;
#print_r($mtextarray); exit;
#print count($mtextarray); exit;
#print_r($mdatavals); exit;

////////////////////////////
//全ての単語の情報を抽出
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

////////////////////////////

//Mecab解析結果ファイル
$mdatafile = $workDir."mtextdata".date('YmdHis').'.txt';
file_put_contents($mdatafile, implode("\n", $mdatavals));
#readfile($mdatafile); exit;

//全データをファイルに格納
$datafile = $workDir."ogiDataAll".date('YmdHis').'.csv';
file_put_contents($datafile, implode("\n", $data_all));
#readfile($datafile); exit;

//データベースに格納
if (!($iddel = sql("DELETE from ogiDataAll;"))) {
	trigger_error("中間データを消去できませんでした。");
} else {
	$copyingdata = file($datafile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if (!($idmdcopy = sqlCopy('ogiDataAll', $copyingdata, ','))) {
		trigger_error("中間データを登録できませんでした。");
	}
}
#exit;

$jinmeiF = file($workDir."userDic/jinmei.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$roleF = file($workDir."userDic/role.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$placeF = file($workDir."userDic/place.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$dateF = file($workDir."userDic/date.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$eventF = file($workDir."userDic/event.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$termsF = file($workDir."userDic/terms.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$quF = file($workDir."userDic/quantity.csv", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$pattern = "/[氏様殿公翁院]/u";

$toki_tango = array();
$qu_tango = array();
$terms_tango = array();
$event_tango = array();
$place_tango = array();
$jinmei_tango = array();
$role_tango = array();

$flagJinmei = 0;
$rn = $snum = $osnum = 0;
for ($i = 0; $i < count($mtextarray); $i++) {
	$rn = $rnBase + $i;
	for ($e = 0; $e < count($fldflag); $e++) {
		$snum = 0;
		for ($f = 0; $f < @count($mtextarray[$rn][$snum]); $f++) {
			$osnum = $f;
			$flagGenre = 0;
			
			for ($j = 0; $j < count($mtextarray[$rn][$snum][$f]); $j++) {
				//$flagJinmeiが1の時ループを抜ける
				if ($flagJinmei == 1) {
					$flagJinmei = 0;
				} else {
					$tangoarray = explode("\t", $mtextarray[$rn][$snum][$f][$j]);
					$tangoarrayNext = @explode("\t", $mtextarray[$rn][$snum][$f][($j+1)]);
					$element = @explode(",", $tangoarray[1]);
					$uri = $url.($rn + 1)."-".$snum."-".$osnum."-";
		
					//人名
					if (@mb_strpos($tangoarray[1], "_JINMEI") !== false && 
					(array_search($tangoarray[0], $roleF) === false || 
					array_search($tangoarray[0], $eventF) === false)
					) {
						$jinmei_tango[] = "Person,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
					}
					
					//MeCabで抽出困難なパターン：姓の次に名が来るパターン
/*
					if (@mb_strpos($tangoarray[1], "名詞,固有名詞,人名,姓,") !== false) {
						
						if (@mb_strpos($tangoarrayNext[1], "名詞,固有名詞,人名,名,") !== false || 
						(@mb_strpos($tangoarrayNext[1], "名詞,固有名詞,人名,一般,") !== false && 
						@mb_strpos($tangoarrayNext[1], "OGI_JINMEI") !== false)
						) {
							$flagJinmei = 1;
							$jinmei_tango[] = "Person,".($rn + 1).",".$snum.",".$osnum.",".$j.",".($j+1).",".$tangoarray[0].$tangoarrayNext[0].",".$uri.$j."-".($j+1);
						}
					}
*/
					
					//ロール
					if (@mb_strpos($tangoarray[1], "_ROLE") !== false || 
					array_search($tangoarray[0], $roleF) !== false) {
						#if (preg_match($pattern, $tangoarrayNext[0])) {
							#$jinmei_tango[] = "Person,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
						#} else {
							$role_tango[] = "Role,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
						#}
					}
					
					//場所
					if (@mb_strpos($tangoarray[1], "_PLACE") !== false || 
					array_search($tangoarray[0], $placeF) !== false) {
						$place_tango[] = "Place,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
					}
					
					//時
					if (@mb_strpos($tangoarray[1], "_DATE") !== false || 
					array_search($tangoarray[0], $dateF) !== false) {
						$toki_tango[] = "Date,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
					}
					
					//出来事
					if (@mb_strpos($tangoarray[1], "_EVENT") !== false || 
					array_search($tangoarray[0], $eventF) !== false) {
						$event_tango[] = "Event,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
					}
					
					//用語
					if (@mb_strpos($tangoarray[1], "_TERMS") !== false) {
						$terms_tango[] = "Terms,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
					}
					
					//その他
					if (@mb_strpos($tangoarray[1], "_QUANTITY") !== false || 
					array_search($tangoarray[0], $quF) !== false) {
						$qu_tango[] = "Quantity,".($rn + 1).",".$snum.",".$osnum.",".$j.",".$j.",".$tangoarray[0].",".$uri.$j."-".$j;
					}
				}
			}
		}
	}
}

//中間データをファイルに格納（中間データ）
$data = array_merge($event_tango, $toki_tango, $place_tango, $jinmei_tango, $terms_tango, $role_tango, $qu_tango);
sort($data, SORT_NATURAL);
$data2 = array_unique($data, SORT_STRING);
#print_r($data2); exit;

$indexfile = $workDir."oginikki".date('YmdHis').'.index';
file_put_contents($indexfile, implode("\n", $data2));
readfile($indexfile);


//中間データをデータベースsitesにアップロード
if (!($iddel = sql("DELETE from ogiNikkiIndex;"))) {
	trigger_error("中間データを消去できませんでした。");
} else {
	$copyingdata = file($indexfile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	if (!($idmdcopy = sqlCopy('ogiNikkiIndex', $copyingdata, ','))) {
		trigger_error("中間データを登録できませんでした。");
	}
}

#include_once $workDir."dataCleaning.php";
for ($i = 0; $i<10; $i++) { echo "\007";}

?>
