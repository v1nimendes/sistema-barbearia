<?php
$host = "localhost"; // Endereço do banco de dados
$dbname = "barbearia"; // Nome do banco de dados
$username = "root"; // Nome de usuário do banco de dados
$password = ""; // Senha do banco de dados

try {
    $db = new PDO("mysql:host=$host", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Criar o banco de dados
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";
    $db->exec($sql);

    // Selecionar o banco de dados recém-criado
    $db->exec("USE $dbname");

    // Comandos SQL para criar tabelas
    $sql = "
    CREATE TABLE IF NOT EXISTS usuarios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        senha VARCHAR(255) NOT NULL
    );

    CREATE TABLE IF NOT EXISTS profissionais (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL
    );

    CREATE TABLE IF NOT EXISTS agendamentos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        usuario_id INT,
        profissional_id INT,
        tipo_servico VARCHAR(255) NOT NULL,
        horario DATETIME,
        FOREIGN KEY (usuario_id) REFERENCES usuarios(id),
        FOREIGN KEY (profissional_id) REFERENCES profissionais(id) ON DELETE CASCADE
    );

    CREATE TABLE IF NOT EXISTS horarios_disponiveis (
        id INT AUTO_INCREMENT PRIMARY KEY,
        profissional_id INT,
        horario DATETIME,
        disponivel TINYINT(1) DEFAULT 1,
        FOREIGN KEY (profissional_id) REFERENCES profissionais(id)
    );
    ";

    // Executar comandos SQL
    $db->exec($sql);

    // Verificar se a tabela profissionais está vazia antes de inserir Cleber e Jorge
    $result = $db->query("SELECT COUNT(*) FROM profissionais")->fetchColumn();
    if ($result == 0) {
        // Inserir Cleber e Jorge somente se a tabela estiver vazia
        $sqlInsert = "INSERT INTO profissionais (nome) VALUES ('Cleber'), ('Jorge')";
        $db->exec($sqlInsert);
    }
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>
