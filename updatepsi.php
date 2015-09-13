<?php
	date_default_timezone_set('Asia/Singapore');
    $page = file_get_contents('http://www.haze.gov.sg/haze-updates/psi');
    $psi_str = substr($page, strpos($page, '<div id="ContentPlaceHolderInnerMain_C011_PnlTimestamp">')+ 56);
	$psi_str = preg_replace('~>\s+<~', '><', $psi_str);
	$e = strpos($psi_str, "</div>");
	$psi_str = substr($psi_str, 0, $e);
	$search = array("\r\n", "\n", "\r", "<", "\t", "  ");
	$psi_str = str_replace($search,"",$psi_str);
	$psi_value = substr($psi_str, strpos($psi_str, '3-hr PSI:')+9);
	$end_v = strpos($psi_value, '/p>p class="time">');
	$psi = substr($psi_value, 0, $end_v);	
	$returnedTime = substr($psi_str, strpos($psi_str, 'class="time">') + 13);
	$ee = strpos($returnedTime, '/p>');
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

	/*
	
	// tried to use the NEA API but it is not very stable in a way that it is not updating regularly.
	$map_url = "http://www.nea.gov.sg/api/WebAPI/?dataset=psi_update&keyref=KEY";
	if (($response_xml_data = file_get_contents($map_url))===false){
		echo "Error fetching XML\n";
	} else {
	   libxml_use_internal_errors(true);
	   $data = simplexml_load_string($response_xml_data);
	   if (!$data) {
		   echo "Error loading XML\n";
		   foreach(libxml_get_errors() as $error) {
			   echo "\t", $error->message;
		   }
	   } else {
			$updated_data = $data->item->region[1]->record;
			echo $updated_data['timestamp'];
			echo $updated_data->reading[1]['value'];
	   }
	}
*/
?>