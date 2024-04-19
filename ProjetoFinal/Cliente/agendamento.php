<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Costelinha Barber Shop</title>
</head>

<body>
    <img src="../costelinhapng.png">

    <div class="container" id="main">
        <div class="cadastro">
            <form action="includes/processa_agendamento.inc.php" method="POST">
                <?php
                require_once("../config.php");

                session_start();
                    
                if (!isset($_SESSION["usuario_id"])) {
                    header("Location: formulario_cliente.php");
                    exit;
                }
                    
                $usuario_id = $_SESSION["usuario_id"];
                    
                $sql = "SELECT agendamentos.*, profissionais.nome AS nome_profissional 
                        FROM agendamentos 
                        INNER JOIN profissionais ON agendamentos.profissional_id = profissionais.id
                        WHERE usuario_id = :usuario_id";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(":usuario_id", $usuario_id);
                $stmt->execute();
                $agendamentos = $stmt->fetchAll();
                    
                if (count($agendamentos) > 0) {
                    echo "<h2>Seus Agendamentos:</h2>";
                    echo "<table class='styled-table'>";
                    echo "<thead>
                            <tr>
                                <th>Tipo de Serviço</th>
                                <th>Horário</th>
                                <th>Profissional</th>
                                <th>Ação</th>
                            </tr>
                          </thead>";
                
                    foreach ($agendamentos as $agendamento) {
                        $horarioFormatado = date("d/m/Y H:i:s", strtotime($agendamento["horario"]));
                
                        echo "<tr>
                                <td>" . $agendamento["tipo_servico"] . "</td>
                                <td>" . $horarioFormatado . "</td>
                                <td>" . $agendamento["nome_profissional"] . "</td>
                                <td><a href='includes/cancelar_horario_agendado.inc.php?id=" . $agendamento["id"] . "' onclick='return confirmDesmarcarHorario()'>Desmarcar</a></td>;
                              </tr>";
                    }
                
                    echo "</table>";
                } else {
                    echo "Você não tem agendamentos.";
                }
                
                ?>
            </form>
        </div>

        <div class="login">
            <form action="includes/processa_agendamento.inc.php" method="POST">
                <h1>Agendamento</h1>
                <p>Agende seu horário</p>
                <div class="form-group">
                    <label for="profissional">Barbeiro:</label>
                    <select name="profissional" id="profissional" class="select">
                        <option value="" disabled selected>Selecione um Barbeiro</option>
                        <?php
                        require_once("../config.php");

                        try {
                            $sqlProfissionais = "SELECT id, nome FROM profissionais";
                            $stmtProfissionais = $db->prepare($sqlProfissionais);
                            $stmtProfissionais->execute();
                            $profissionais = $stmtProfissionais->fetchAll();

                            if ($profissionais) {
                                foreach ($profissionais as $profissional) {
                                    echo "<option value='" . $profissional['id'] . "'>" . $profissional['nome'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>Nenhum profissional encontrado</option>";
                            }
                        } catch (PDOException $e) {
                            echo "<option value=''>Erro ao carregar profissionais: " . $e->getMessage() . "</option>";
                        }
                        ?>
                    </select>
                </div> <br>

                <div class="form-group">
                    <label for="tipo_servico">Serviço:</label>
                    <select name="tipo_servico" id="tipo_servico" class="select">
                    <option value="" disabled selected>Selecione um Serviço</option>
                        <option name="tipo_servico">Sobrancelhas -- R$5,00</option> 
                        <option value="Barba -- R$20,00">Barba -- R$20,00</option> 
                        <option value="Corte -- R$25,00">Corte -- R$25,00</option> 
                        <option value="Corte + Sobrancelhas -- R$30,00">Corte + Sobrancelhas -- R$30,00</option> 
                        <option value="Corte + Sobrancelhas + Barba -- R$50,00">Corte + Sobrancelhas + Barba -- R$50,00</option> 
                        <option value="Reflexo alinhado (Corte incluso) -- R$60,00">Reflexo alinhado (Corte incluso) -- R$60,00</option>  
                        <option value="Platinado (Corte incluso) -- R$75,00">Platinado (Corte incluso) -- R$75,00</option> 
                        <!-- Adicione mais tipos de serviço conforme necessário -->
                    </select>
                </div> <br>

                <div class="form-group">
                    <label for="data">Data:</label>
                    <input type="date" name="data" id="data">
                </div> <br>
                
                <div class="form-group">
                    <label for="hora">Hora:</label>
                    <select name="hora" id="hora" disabled class="select">
                        <option value="">Selecione uma hora</option>
                        <!-- Horas disponíveis e indisponíveis serão adicionadas via JavaScript -->
                    </select>
                </div> <br>
                
                <button name="Entrar">Agendar</button>
                <a href="includes/logout.php" class="logout-link">Sair</a>
            </form>
        </div>


        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-esquerda">
                    <h1>Agendar</h1>
                    <p>Agende seu Horário</p>
                    <button id="agendar"> Agendar </button>
                </div>
                <div class="overlay-direita">
                    <h1>Olá Amigo!</h1>
                    <p>Veja os seus Horários</p>
                    <button id="horarios"> Horarios </button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById("data").addEventListener("change", function() {
        const dataSelecionada = new Date(this.value);
        const diaDaSemana = dataSelecionada.getDay();
        const horaSelect = document.getElementById("hora");
        horaSelect.innerHTML = "<option value=''>Selecione uma hora</option>";

        if (diaDaSemana !== 0 && diaDaSemana !== 6) {
            horaSelect.removeAttribute("disabled");
            for (let hora = 8; hora <= 17; hora++) {
                for (let minuto = 0; minuto < 60; minuto += 60) {
                    const horaFormatada = `${hora.toString().padStart(2, "0")}:${minuto.toString().padStart(2, "0")}`;
                    const option = document.createElement("option");
                    option.value = horaFormatada;
                    option.textContent = horaFormatada;
                    horaSelect.appendChild(option);
                    }
                }
        } else {
            horaSelect.setAttribute("disabled", "disabled");
        }
        });

        // Código para fazer a troca de telas no formulário
        const botaoCadastro = document.getElementById('horarios');
        const botaoLogin = document.getElementById('agendar');
        const main = document.getElementById('main')

        function confirmDesmarcarHorario() {
        return confirm("Você está prestes a desmarcar um agendamento, Tem certeza que quer continuar?");
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