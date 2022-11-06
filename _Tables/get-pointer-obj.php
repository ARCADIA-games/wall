<?php include '../_config.php';
$objID = $_POST['objID'];
$tableName = $_POST['tableName'];

// HTML sanitization
if (strpos($tableName, '<') !== false || strpos($tableName, '>') !== false
){ $tableName = preg_replace("/[^a-zA-Z]/", "", $tableName); }

// Get JSON Table's data
$data = file_get_contents($tableName. '.json');
$data_array = json_decode($data, true);

for ($i=0; $i<count($data_array); $i++) {
	if ($data_array[$i]['ID_id'] == $objID) {
		echo json_encode($data_array[$i], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
}
?>