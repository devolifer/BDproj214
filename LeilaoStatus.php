<html>
<body>
<?php
// inicia sessão para passar variaveis entre ficheiros php
session_start();
// Função para limpar os dados de entrada
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}


// Carregamento das variáveis username e pin do form HTML através do metodo POST;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = test_input($_POST["username"]);
	$pin = test_input($_POST["pin"]);
}
echo("<p>Valida Pin da Pessoa $username</p>\n");

// Variáveis de conexão à BD
$host="db.ist.utl.pt";
 // o MySQL esta disponivel nesta maquina
$user ="ist166983";
 // -> substituir pelo nome de utilizador
$password = "rrdu5980";
 // -> substituir pela password dada pelo mysql_reset
$dbname = $user;
 // a BD tem nome identico ao utilizador
echo("<p>Projeto Base de Dados Parte II</p>\n");
$connection = new PDO("mysql:host=" . $host. ";dbname=" . $dbname, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
echo("<p>Connected to MySQL database $dbname on $host as user $user</p>\n");




// passa variaveis para a sessao;
$_SESSION['username'] = $username;
$_SESSION['nif'] = $nif;

// Apresenta os leilões
// $sql = "SELECT b.dia, b.nrleilaonodia, b.nif, b.nrdias, b.lid, a.nome, a.valorbase FROM leilao AS a, leilaor AS b";

// o estado dos leiloes em curso, o maior lance para cada leilao em q esta inscrito, tempo em falta para esse leilao fechar
//  select dia, nrdias, date(dia+nrdias), date(dia+nrdias)-curdate() as diasParaAcabar from leilaor;
// select lid, dia, date(dia+nrdias)-curdate() as diasParaAcabar, max(valor) as maiorLance from leilaor as a, lance as b where nif=pessoa AND a.nif=111 AND leilao=lid;

$sql = "SELECT lid, dia, date(dia+nrdias)-curdate() as diasParaAcabar, max(valor) as maiorLance from leilaor as a, lance as b where nif=pessoa AND a.nif=" . 111 . " AND leilao=lid";
$result = $connection->query($sql);
echo("<table border=\"1\">\n");
echo("<tr><td>ID</td><td>dia do ínicio do leilão</td><td>número de dias para terminar</td><td>valor do lance maior</td></tr>\n");

foreach($result as $row){
echo("<tr><td>");
echo($row["lid"]); echo("</td><td>");
echo($row["dia"]); echo("</td><td>");
echo($row["diasParaAcabar"]); echo("</td><td>");
echo($row["maiorLance"]); echo("</td>");
}
echo("</table>\n");
?>

</body>
</html>
