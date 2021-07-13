<?
$document_root = $_SERVER["DOCUMENT_ROOT"];
$config = $document_root."/open-records-generator/config/config.php";
require_once($config);
$db = db_connect("main");
$oo = new Objects();
$ww = new Wires();
require_once($document_root.'/static/php/function.php');

$data = file_get_contents( "php://input" );
$data = json_decode($data, true);
$s_urls = $data['s_urls'];

$new = array();
foreach($data as $key => $d){
	if($key != 'id' && $key != 'action' && $key != 's_urls' && $key != 'parent_id'){
		if($data['action'] == 'insert' && $key == 'name1')
			$new[$key] = !empty($d) ? "'.".htmlentities(addslashes($d))."'" : "null";
		else
			$new[$key] = !empty($d) ? "'".htmlentities(addslashes($d))."'" : "null";
	}
}
if($data['action'] == 'update'){
	if(isset($new['name1']))
		$new['url'] = slug($new['name1']);
	$urlIsValid = validate_url($new['url'], $s_urls);
	if( !$urlIsValid )
		$new['url'] = valid_url($new['url'], strval($data['id']), $s_urls);
	$new['url'] = "'".$new['url']."'";
	$id = $oo->update($data['id'], $new);
}
else if($data['action'] == 'insert'){

	$new_url = slug($data['name1']);
	$new['url'] = "'".$new_url."'";
	$id = $oo->insert($new);

	$urlIsValid = validate_url($new_url, $s_urls);
	if( !$urlIsValid )
	{
		$url = valid_url($new_url, strval($id), $s_urls);
		$new['url'] = "'".$url."'";
		$oo->update($id, $new);
	}
	if($id)
		$ww->create_wire($data['parent_id'], $id);
}

header('Content-Type: application/json');
echo json_encode($id);
?>