<?
include "gen_config.php";

$host=$_dbconf["server"];
$dbname=$_dbconf["dbname"];
$dbuser=$_dbconf["user"];
$dbpass=$_dbconf["password"];

$conn=new PDO("mysql:host=$host;dbname=$dbname", "$dbuser", "$dbpass");

function getTableList(){
	global $conn, $_tables;
	$sql="SHOW TABLES";
	$stm=$conn->query($sql);
	
	while ($str=$stm->fetchColumn()){
		$_tables[]=$str;
	}
}

function generate($outdir, $table_name, $class_name){
	global $conn, $namespace;
	echo "Generate entity implement for table " . $table_name . "\n";
	
	$sql="SHOW CREATE TABLE " . $table_name;
	$stm=$conn->query($sql);
	$data=$stm->fetch(PDO::FETCH_ASSOC);
	$sql_create=$data["Create Table"];
	
	$sql="DESC " . $table_name;
	$stm=$conn->query($sql);
	$fields=array();
	while ($obj=$stm->fetchObject()){
		$type=$obj->Type;
		$k=strpos($type, "(");
		if ($k!==false){
			$type=substr($type, 0, $k);
		}
		if ($type=="char" || $type=="varchar" || $type=="text" ||  $type=="tinytext" ||  $type=="date"){
			$obj->Type="string";
		}
		else if ($type=="float" || $type=="double"){
			$obj->Type="float";
		}
		else{
			$obj->Type="int";
		}
		$fields[]=$obj;
	}
	
	$outfile=$outdir . "/" . $class_name . ".php";
	$vars=array();
	$vars["namespace"]=$namespace;
	$vars["sql_create"]=$sql_create;
	$vars["class_name"]=$class_name;
	$vars["table_name"]=$table_name;
	$vars["fields"]=$fields;
	ob_start();
	extract($vars);
	include "_entity.tmp.php";
	$data=ob_get_clean();
	file_put_contents($outfile, $data);
}

if (count($_tables)==0){
	getTableList();
}

$output_dir=$_output_base . "/" . $dbname;
if (!file_exists($output_dir)){
	echo "Create output directory at " . $output_dir . "\n";
	mkdir($output_dir, 0700, true);
}

foreach ($_tables as $table_name => $class_name){
	generate($output_dir, $table_name, $class_name);
}