 <?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'relatorios_voos';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
