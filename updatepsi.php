<?php
	date_default_timezone_set('Asia/Singapore');
    $page = file_get_contents('http://app2.nea.gov.sg/');
    $psi = substr($page, strpos($page, '<span id="ContentPlaceHolderSidebar_C015_Lbl3HrReading" class="psi-reading"') + 108);
	$e = strpos($psi, "</span>");
	$psi = substr($psi, 0, $e);
	$returnedTime = substr($page, strpos($page, '<span id="ContentPlaceHolderSidebar_C015_LblDateTime" class="psi-status">') + 73);
	$ee = strpos($returnedTime, '</span>');
	$returnedTime = substr($returnedTime, 0, $ee);
	$returnedTime = str_replace("At", "at", $returnedTime);

	$file = 'psi.json';
	$fileContent = json_decode(@file_get_contents($file), true);
	if ($fileContent['data']['latest']['updated_at'] !== $returnedTime) {
		$currentTime = date("Y-m-d H", time()); 
		$fileContent['data'][$currentTime] = array("psi" => (int)$psi."", "updated_at" => $returnedTime);
		$fileContent['data']['latest'] = array("psi" => (int)$psi."", "updated_at" => $returnedTime);
		file_put_contents($file, json_encode($fileContent));
	}
?>