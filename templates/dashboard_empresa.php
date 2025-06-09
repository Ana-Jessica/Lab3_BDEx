<?php
session_start();
include_once("../server/conexao.php");
include_once("../server/auth.php");

// Verifica se está logado e se é uma empresa
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: ../templates/pglogin.html");
    exit();
}

// Buscar dados da empresa
$id_empresa = $_SESSION['id'];
$nome = $cnpj = $endereco = $email = $telefone = ''; // Inicializa as variáveis
$stmt = $conn->prepare("SELECT nome_empresa, cnpj, endereco, email_empresa, telefone_empresa FROM empresa WHERE id_empresa = ?");
if ($stmt) {
    $stmt->bind_param("i", $id_empresa);
    $stmt->execute();
    $stmt->bind_result($nome, $cnpj, $endereco, $email, $telefone);
    if (!$stmt->fetch()) {
        $nome = "Empresa não encontrada";
    }
    $stmt->close();
} else {
    $nome = "Erro ao carregar dados";
    error_log("Erro na preparação da query: " . $conn->error);
}

// Buscar solicitações de candidatos para as vagas da empresa
$solicitacoes = [];

$sql = "
SELECT 
    s.id_solicitacao,
    v.titulo_vaga,
    d.nome_desenvolvedor,
    d.email_desenvolvedor,
    d.telefone_desenvolvedor
FROM Solicitacao s
INNER JOIN Vaga v ON s.id_vaga = v.id_vaga
INNER JOIN Desenvolvedor d ON s.id_desenvolvedor = d.id_desenvolvedor
WHERE v.id_empresa = ?
ORDER BY s.id_solicitacao DESC
";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $id_empresa);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $solicitacoes[] = $row;
    }

    $stmt->close();
} else {
    error_log("Erro ao buscar solicitações: " . $conn->error);
}

// Buscar conexões da empresa
$conexoes = [];

$sql_conexoes = "
SELECT 
    c.id_conexao,
    d.nome_desenvolvedor,
    d.email_desenvolvedor,
    d.telefone_desenvolvedor,
    c.data_conexao
FROM Conexao c
INNER JOIN Desenvolvedor d ON c.id_desenvolvedor = d.id_desenvolvedor
WHERE c.id_empresa = ?
ORDER BY c.data_conexao DESC
";

$stmt_conexoes = $conn->prepare($sql_conexoes);
if ($stmt_conexoes) {
    $stmt_conexoes->bind_param("i", $id_empresa);
    $stmt_conexoes->execute();
    $result_conexoes = $stmt_conexoes->get_result();

    while ($row = $result_conexoes->fetch_assoc()) {
        $conexoes[] = $row;
    }

    $stmt_conexoes->close();
} else {
    error_log("Erro ao buscar conexões: " . $conn->error);
}


?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página da Empresa</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../static/imgs/logo/simbolo.svg">
    <link rel="stylesheet" href="../static/styles/dash_empresa.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>

    <!-- Cabeçalho -->
    <header>
        <div class="menubar">
            <div class="logo">
                <img src="../static/imgs/bdexsemfundo.png" alt="" width="150px" height="68px" />
            </div>
            <div class="ulogado">
                <h3><?= htmlspecialchars($nome) ?></h3>
            </div>
<<<<<<< HEAD
          
            <a class="logout-link" href="../server/logout.php">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </header>
    <div class="ldld">

        <nav class="menulateral">
              <div class="tammenu"><i class="bi bi-list"></i></div>
            <h2>Área da Empresa</h2>
        <br />
       <ul>
           <li class="item livagas">
               Gerenciar vagas
               <i class="brief bi bi-clipboard-data"></i> 
           </li>
           <br>
    <li class="item liddscadastro">
        Dados de cadastro
        <i class="brief bi bi-person-lines-fill"></i> 
    </li>
    <br />
    <li class="item lisolicitacoes">
        Solicitações
        <i class="brief bi bi-envelope-open-fill"></i> 
    </li>
    <br />
    <li class="item liconexoes">
        Conexões
        <i class="brief bi bi-people-fill"></i> 
    </li>
    <br />
</ul>

    </nav>
    <main>
        <article class="artcadastro" style="display: none;">
=======
            <div class="usuarionotificacoes"></div>
            <a href="../server/logout.php"><i class="bi bi-box-arrow-right"></i></a>
        </div>
    </header>

    <!-- Menu lateral -->
    <nav class="menulateral">
        <h2>Área da Empresa</h2>
        <br />
        <ul>
            <li class="item liddscadastro">Dados de cadastro</li>
            <br />
            <li class="item livagas">Gerenciar vagas</li>
            <br />
            <li class="item lisolicitacoes">Solicitações</li>
            <br />
            <li class="item liconexoes">Conexões</li>
            <br />
        </ul>
    </nav>
>>>>>>> 088065622bceed42533b02ac5441898352c92eef

    <!-- Conteúdo principal -->
    <main>
        <!-- Artigo: Cadastro da empresa -->
        <article class="artcadastro">
            <form action="editUserEmpresa.php" method="POST">
                <box-inputset>
                    <legend>
                        <h1><b>Editar dados da Empresa</b></h1>
                    </legend>
                    <br />
                    <div class="box-input">
                        <label for="nome_empresa">Nome:</label>
                        <input type="text" placeholder="Digite seu nome" id="nome_empresa" name="nome_empresa" required
                            value="<?= htmlspecialchars($nome) ?>">
                    </div>
                    <br>
                    <div class="box-input">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" placeholder="Digite..." id="cnpj" name="cnpj" required
                            value="<?= htmlspecialchars($cnpj) ?>">
                    </div>
                    <br>
                    <div class="box-input">
                        <label for="endereco">Endereço:</label>
                        <input type="text" placeholder="Digite seu endereço" id="endereco" name="endereco" required
                            value="<?= htmlspecialchars($endereco) ?>">
                    </div>
                    <br>
                    <div class="box-input">
                        <label for="email">Email:</label>
                        <input type="text" placeholder="Digite seu e-mail" id="email" name="email" required
                            value="<?= htmlspecialchars($email) ?>">
                    </div>
                    <br>
                    <div class="box-input">
                        <label for="telefone_empresa">Telefone:</label>
                        <input type="text" placeholder="Digite seu telefone" id="telefone_empresa"
                            name="telefone_empresa" required value="<?= htmlspecialchars($telefone) ?>">
                    </div>
                    <br>
<<<<<<< HEAD


                    <br>


=======
>>>>>>> 088065622bceed42533b02ac5441898352c92eef
                    <a href="Desativar_conta.php" onclick="return confirmarEncerramento()">Desativar Conta</a>
                    <br />
                    <button type="submit" id="update" name="update"
                        onclick="return confirm('Tem certeza de que deseja editar os dados? Verifique se a senha e os dados estão preenchidos corretamente');"
                        class="btneditar">Editar</button>
                </box-inputset>
            </form>
        </article>
<<<<<<< HEAD
        <article class="artvagas" style="display: flex;">
            <h2>gerenciar vagas</h2>
            <button class="criarvaga">criar vaga +</button>
=======

        <!-- Artigo: Gerenciar Vagas -->
        <article class="artvagas" style="display: none;">
            <h2>Gerenciar vagas</h2>
            <button class="criarvaga">Criar vaga +</button>
>>>>>>> 088065622bceed42533b02ac5441898352c92eef
            <br>

            <!-- Modal Único (reutilizável para criar e editar) -->
            <div class="modalvaga" style="display: none;">
                <form id="formVaga" method="POST" action="../server/criar_vaga.php">
                    <div class="btnfecharmodal">X</div>
                    <h2>NOVA VAGA</h2>
                    <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id']; ?>">
                    <div class="box-input">
                        <label for="">Titulo</label>
                        <input type="text" name="titulo_vaga" placeholder="ex: dev frontEnd php">
                    </div>
                    <div class="box-input">
                        <label for="">descrição:</label>
                        <input type="text" name="descricao_vaga"
                            placeholder="precisamos de um desenvolvedor php com conhecimentos em laravel...">
                    </div>
                    <div class="box-input">
                        <label for="">valor de oferta:</label>
                        <input type="text" name="valor_oferta" placeholder="R$ 99,99">
                    </div>
                    <div class="box-input">
                        <label for="">Empresa: <?php echo htmlspecialchars($nome); ?></label>

                    </div>
                    <button class="btnsubmitvaga" type="submit"> CRIAR VAGA</button>
                </form>
            </div>

            <!-- Lista de Vagas -->
            <div class="vagas-lista">
                <?php
                if ($_SESSION['tipo'] === 'empresa' && isset($_SESSION['id'])) {
                    $id_empresa = $_SESSION['id'];
                    $sql = "SELECT id_vaga, titulo_vaga, data_publicacao, descricao_vaga, valor_oferta 
                        FROM Vaga 
                        WHERE id_empresa = ? 
                        ORDER BY data_publicacao DESC";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $id_empresa);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($vaga = mysqli_fetch_assoc($result)) {
                            $id = $vaga['id_vaga'];
                            $titulo = htmlspecialchars($vaga['titulo_vaga']);
                            $descricao = htmlspecialchars($vaga['descricao_vaga']);
                            $valor = $vaga['valor_oferta'] ? number_format($vaga['valor_oferta'], 2, ',', '.') : '';
                            $data = $vaga['data_publicacao'];

                            echo "
                        <div class='card border-primary mb-3' style='width: 300px; margin-left: 10px;'>
                            <div class='card-body'>
                                <h5 class='card-title text-primary'>{$titulo}</h5>
                                <h6 class='card-subtitle mb-2 text-muted'>Publicada em: {$data}</h6>
                                <p class='card-text'>{$descricao}</p>
                                <p class='card-text'><strong>Oferta Salarial:</strong> R$ {$valor}</p>
                                <div style='display: flex; justify-content: center; gap: 10px;'>
                                    <a href='#' class='btn btn-info bi bi-pencil editarVaga'
                                       data-id='{$id}'
                                       data-titulo='{$titulo}'
                                       data-descricao='{$descricao}'
                                       data-valor='{$valor}'></a>
                                    <a href='../server/gerenciar_vagas.php?acao=excluir&id={$id}'
                                       onclick=\"return confirm('Deseja excluir esta vaga?')\"
                                       class='btn btn-danger'>
                                        <i class='bi bi-trash3'></i>
                                    </a>
                                </div>
                            </div>
                        </div>";
                        }
                    } else {
                        echo "<p style='text-align: center;'>Você ainda não criou nenhuma vaga.</p>";
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<p style='text-align: center;'>Empresa não autenticada.</p>";
                }
                ?>
            </div>
        </article>

        <!-- Outros artigos -->
        <article class="artsolicitacoes" style="display: none;">
            <h2>Solicitacoes para vagas</h2>
            <?php if (count($solicitacoes) > 0): ?>
                <!-- tabela como já estava -->
            <?php else: ?>
                <div class="alert alert-info mt-4">Nenhuma solicitação recebida até o momento.</div>
            <?php endif; ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título da Vaga</th>
                        <th>Nome do Candidato</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($solicitacoes as $solicitacao): ?>
                        <tr>
                            <td><?= $solicitacao['id_solicitacao'] ?></td>
                            <td><?= htmlspecialchars($solicitacao['titulo_vaga']) ?></td>
                            <td><?= htmlspecialchars($solicitacao['nome_desenvolvedor']) ?></td>
                            <td><?= htmlspecialchars($solicitacao['email_desenvolvedor']) ?></td>
                            <td><?= htmlspecialchars($solicitacao['telefone_desenvolvedor']) ?></td>
                            <td>
                                <a href="../server/gerenciar_solicitacao.php?acao=aceitar&id=<?= $solicitacao['id_solicitacao'] ?>"
                                    onclick="return confirm('Deseja aceitar esta solicitação?')"
                                    class="btn btn-success">Aceitar</a>
                                <a href="../server/gerenciar_solicitacao.php?acao=recusar&id=<?= $solicitacao['id_solicitacao'] ?>"
                                    class="btn btn-danger">Recusar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>

        </article>

        <article class="artconexoes" style="display: none;">
            <h2>Conexões realizadas</h2>
            <?php if (count($conexoes) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Conexão</th>
                            <th>Nome do Desenvolvedor</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($conexoes as $conexao): ?>
                            <tr>
                                <td><?= $conexao['id_conexao'] ?></td>
                                <td><?= htmlspecialchars($conexao['nome_desenvolvedor']) ?></td>
                                <td><?= htmlspecialchars($conexao['email_desenvolvedor']) ?></td>
                                <td><?= htmlspecialchars($conexao['telefone_desenvolvedor']) ?></td>
                                <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($conexao['data_conexao']))) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info mt-4">Nenhuma conexão realizada ainda.</div>
            <?php endif; ?>
        </article>
    </main>
<<<<<<< HEAD
    
</div>
=======

    <!-- Scripts -->
>>>>>>> 088065622bceed42533b02ac5441898352c92eef
    <script src="../static/scripts/dash_empresa.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const modalvaga = document.querySelector(".modalvaga");
            const form = document.getElementById("formVaga");
            const tituloModal = document.getElementById("tituloModal");

            // Botão "Criar Vaga"
            document.querySelector(".criarvaga")?.addEventListener("click", function () {
                form.reset();
                form.id_vaga.value = "";
                form.action = "../server/criar_vaga.php";
                tituloModal.textContent = "NOVA VAGA";
                modalvaga.style.display = "flex";
            });

            // Botões "Editar Vaga"
            document.querySelectorAll(".editarVaga").forEach(botao => {
                botao.addEventListener("click", function () {
                    const card = this.closest('.card');
                    const idVaga = this.getAttribute("data-id");
                    const titulo = this.getAttribute("data-titulo");
                    const descricao = this.getAttribute("data-descricao");
                    const valor = this.getAttribute("data-valor");

                    form.titulo.value = titulo;
                    form.descricao.value = descricao;
                    form.valor_oferta.value = "R$ " + valor.replace('.', ',');
                    form.id_vaga.value = idVaga;
                    form.action = `../server/gerenciar_vagas.php?acao=editar&id=${idVaga}`;
                    tituloModal.textContent = "EDITAR VAGA";
                    modalvaga.style.display = "flex";
                });
            });

            // Fechar modal
            document.querySelector(".btnfecharmodal")?.addEventListener("click", function () {
                modalvaga.style.display = "none";
            });

            // Fechar ao clicar fora
            window.addEventListener("click", function (e) {
                if (e.target === modalvaga) {
                    modalvaga.style.display = "none";
                }
            });
        });
    </script>

    <script>
        function confirmarEncerramento() {
            return confirm("Deseja realmente desativar sua conta?");
        }
    </script>

</body>

</html>