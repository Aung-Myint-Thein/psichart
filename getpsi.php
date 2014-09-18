<?php
    $file = 'psi.json';
	$fileContent = @file_get_contents($file);
	if (!$fileContent) { // If there is no data file(psi.json), run updatepsi.php
		include 'updatepsi.php';
		$fileContent = file_get_contents($file);
	}
    echo "getPSI(" . $fileContent . ");";
?>