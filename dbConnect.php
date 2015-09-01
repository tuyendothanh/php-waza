<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php
$connect = new PDO('mysql:host=localhost;dbname=mngledger', 'root', 'T412tkmt');
if ( !$connect ) {
	die('データベースに接続できませんでした。');
}
/*
else {
	echo "データベースに接続できました。";
}
*/
$rs = $connect->query("SELECT * from tb_ledger2015");
while ($row = $rs->fetch()) {
	echo implode("-", $row) . "<br>\n";
}
?>

</body>
</html>
