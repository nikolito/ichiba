<?php
	include_once "settings.php";
	include_once $workDir."function.php";
	sortUniqueFile($workDir."userDic/date.csv");
	sortUniqueFile($workDir."userDic/place.csv");
	sortUniqueFile($workDir."userDic/jinmei.csv");
	sortUniqueFile($workDir."userDic/role.csv");
	sortUniqueFile($workDir."userDic/event.csv");
	sortUniqueFile($workDir."userDic/terms.csv");
	sortUniqueFile($workDir."userDic/quantity.csv");
	sortUniqueFile($workDir."userDic/placeJisha.csv");
?>
