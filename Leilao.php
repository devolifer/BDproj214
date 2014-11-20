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

$sql = "SELECT count(*) as a FROM concorrente WHERE pessoa=" . $nif . " AND leilao=" . $lid;
$veriSign = $connection->query($sql);

if ($veriSign[0] == 0) {
	$veriDate = "SELECT lid, dia, nrdias, dia+nrdias AS ultdia, curdate() AS today FROM leilaor WHERE lid=" . $lid;
	$result = $connection->query($veriDate);
	if ($result["today"] <=  $result["ultdia"]) {
		$sql = "INSERT INTO concorrente (pessoa,leilao) VALUES (" . $nif . "," . $lid . ")";
		$result = $connection->query($sql);
		if (!$result) {
			echo("<p> Pessoa nao registada: Erro na Query:($sql) <p>");
			exit();
		}
		echo("<p> Pessoa ($username), nif ($nif) Registada no leilao ($lid)</p>\n");
		session_destroy();
		exit();
	}
	echo("<p> Leilao terminado, inscrição cancelada</p>\n");
	session_destroy();
	exit();
}
echo("<p> Pessoa ja inscrita: ou Erro na Query:($sql) <p>");
//termina a sessão
session_destroy();
?>
</body>
</html>
