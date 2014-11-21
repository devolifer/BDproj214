<html>
<body>
<?php
// inicia sessão para passar variaveis entre ficheiros php
session_start();
$username = $_SESSION['username'];
$nif = $_SESSION['nif'];
// Função para limpar os dados de entrada
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// Carregamento das variáveis username e pin do form HTML através do metodo POST;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$lid = test_input($_POST["lid"]);
	$lance = test_input($_POST["valor"]);
}

// Conexão à BD
$host="db.ist.utl.pt";
 // o MySQL esta disponivel nesta maquina
$user ="ist166983";
 // -> substituir pelo nome de utilizador
$password = "rrdu5980";
 // -> substituir pela password dada pelo mysql_reset
$dbname = $user; // a BD tem nome identico ao utilizador
$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password,
array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");

$sql = "SELECT * FROM concorrente WHERE pessoa=" . $nif . " AND leilao=" . $lid;
$result = $connection->query($sql);
if (!$result) {
	echo("<p> Pessoa nao inscrita no leilao: Erro na Query:($sql) <p>");
	session_destroy();
	exit();
}
$veriDate = "SELECT lid, dia, nrdias, dia+nrdias AS ultdia, curdate()+0 AS today FROM leilaor WHERE lid=" . $lid;
$sth = $connection->prepare($veriDate);
$sth->execute();
$result = $sth->fetch(PDO::FETCH_ASSOC);
if ($result["today"] <=  $result["ultdia"]) {
	$sql = "INSERT INTO lance VALUES (" . $nif . "," . $lid . "," . $lance . ")";
	$result = $connection->query($sql);
	if (!$result) {
		echo("<p> Lance duplicado, ou outro erro: Erro na Query:($sql) <p>");
		session_destroy();
		exit();
	}
	echo("<p> Pessoa $nif participou com um lance no leilao $lid com o valor $lance € <p>");
	//termina a sessão
	session_destroy();
	exit();
}
echo("<p> Leilao terminado, lance cancelado <p>");
session_destroy();
exit();
?>
</body>
</html>
