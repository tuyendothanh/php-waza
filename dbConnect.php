<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

<?php
$connect = new PDO('mysql:host=localhost;dbname=mngledger', 'root', 'T412tkmt');
if ( !$connect ) {
	die('�f�[�^�x�[�X�ɐڑ��ł��܂���ł����B');
}
/*
else {
	echo "�f�[�^�x�[�X�ɐڑ��ł��܂����B";
}
*/
$rs = $connect->query("SELECT * from tb_ledger2015");
while ($row = $rs->fetch()) {
	echo implode("-", $row) . "<br>\n";
}
?>

</body>
</html>
