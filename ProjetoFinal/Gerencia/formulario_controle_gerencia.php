<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION["usuario_id"])) {
    header("Location: index.php");
    exit;
}

require_once("../config.php");
$usuario_id = $_SESSION["usuario_id"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Gerenciamento de Profissionais</title>
</head>

<body>
    <img src="../costelinhapng.png"> 
    <div class="container" id="main">
        <div class="cadastro">
            <form>
                <h2>Horários Agendados:</h2>
                <div class="table-container">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Tipo de Serviço</th>
                                <th>Cliente</th>
                                <th>Profissional</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Consulta para selecionar todos os agendamentos com informações de usuário e profissional
                            $sqlAgendamentos = "SELECT agendamentos.*, usuarios.nome AS nome_usuario, profissionais.nome AS nome_profissional
                                                FROM agendamentos
                                                INNER JOIN usuarios ON agendamentos.usuario_id = usuarios.id
                                                INNER JOIN profissionais ON agendamentos.profissional_id = profissionais.id";
                            $stmtAgendamentos = $db->prepare($sqlAgendamentos);
                            $stmtAgendamentos->execute();
                            $agendamentos = $stmtAgendamentos->fetchAll();

                            foreach ($agendamentos as $agendamento) {
                                echo "<tr>";
                                // Formatando o horário para o formato desejado (por exemplo, "d/m/Y H:i")
                                $horarioFormatado = date("d/m/Y H:i", strtotime($agendamento["horario"]));
                                echo "<td>" . $horarioFormatado . "</td>";
                                echo "<td>" . $agendamento["tipo_servico"] . "</td>";
                                echo "<td>" . $agendamento["nome_usuario"] . "</td>";
                                echo "<td>" . $agendamento["nome_profissional"] . "</td>";
                                echo "<td><a href='includes/desmarca_horario.php?id=" . $agendamento["id"] . "' onclick='return confirmDesmarcarHorario()'>Desmarcar</a></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>
        </div>

        <div class="login">
            <form action="includes/adicionar_profissional.php" method="POST">
                <h1>Gerenciamento</h1>

                <div class="form-group">
                    <label for="novo_profissional">Novo Profissional:</label>
                    <input type="text" id="novo_profissional" name="novo_profissional">
                    <button type="submit"> Adicionar</button>
                </div>

                <div class="form-group">
                    <h3>Profissionais Cadastrados:</h3>
                    <div class="table-container">
                        <table class="styled-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Consulta para selecionar todos os profissionais do banco de dados
                                $sqlProfissionais = "SELECT id, nome FROM profissionais";
                                $stmtProfissionais = $db->prepare($sqlProfissionais);
                                $stmtProfissionais->execute();
                                $profissionais = $stmtProfissionais->fetchAll();

                                // Exibição dinâmica dos profissionais em uma tabela
                                foreach ($profissionais as $profissional) {
                                    echo "<tr>";
                                    echo "<td>" . $profissional['id'] . "</td>";
                                    echo "<td>" . $profissional['nome'] . "</td>";
                                    echo "<td><a href='includes/remover_profissional.php?id=" . $profissional['id'] . "' onclick='return confirmDesligarFuncionario()'>Remover</a></td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <a href="includes/logout.php" class="logout-link">Sair</a>
            </form>

            <br>
        </div>

        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-esquerda">
                    <h1>Funcionários</h1>
                    <p>Gerenciamento de Funcionários</p>
                    <button id="agendar"> Gerenciar </button>
                </div>
                <div class="overlay-direita">
                    <h1>Horários</h1>
                    <p>Veja os Horários agendados</p>
                    <button id="horarios"> Horários </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Código para fazer a troca de telas no formulário
        const botaoCadastro = document.getElementById('horarios');
        const botaoLogin = document.getElementById('agendar');
        const main = document.getElementById('main')

        function confirmDesmarcarHorario() {
        return confirm("Você está prestes a desmarcar um horário de um cliente, Tem certeza que quer continuar?");
        }
        function confirmDesligarFuncionario() {
        return confirm("Você está prestes a remover um Funcionário, ao fazer isso todos os agendamentos ligados a ele serão removidos do sistema, Tem Certeza que quer continuar?");
        }

        botaoCadastro.addEventListener('click', () => {
            main.classList.add("right-panel-active");
        });

        botaoLogin.addEventListener('click', () => {
            main.classList.remove("right-panel-active");
        });
    </script>
</body>

</html>
