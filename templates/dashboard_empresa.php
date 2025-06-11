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
FROM solicitacao s
INNER JOIN vaga v ON s.id_vaga = v.id_vaga
INNER JOIN desenvolvedor d ON s.id_desenvolvedor = d.id_desenvolvedor
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
FROM conexao c
INNER JOIN desenvolvedor d ON c.id_desenvolvedor = d.id_desenvolvedor
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
    <link rel="stylesheet" href="../static/styles/modal_default.css">
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

                <form action="edit_dds_empresa.php" method="POST">
                    <box-inputset>
                        <legend>
                            <h1><b>Editar dados da Empresa</b></h1>
                        </legend>
                        <br />
                        <div class="box-input">
                            <label for="nome_empresa">Nome:</label>
                            <input type="text" placeholder="Digite seu nome" id="nome_empresa" name="nome_empresa"
                                required value="<?= htmlspecialchars($nome) ?>">
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


                        <br>


                        <a href="Desativar_conta.php" onclick="return confirmarEncerramento()">Desativar Conta</a>
                        <br />
                        <button type="submit" id="update" name="update"
                            onclick="return confirm('Tem certeza de que deseja editar os dados? Verifique se a senha e os dados estão preenchidos corretamente');"
                            class="btneditar">Editar</button>
                    </box-inputset>
                </form>
            </article>
            <article class="artvagas" style="display: flex;">
                <h2>gerenciar vagas</h2>
                <button class="criarvaga">criar vaga +</button>
                <br>
                <div class="modalvaga">
                    <form class="modaleditarvaga" action="../server/criar_vaga.php" method="POST">
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
                <div class="vagas-lista">
                    <?php
                    if ($_SESSION['tipo'] === 'empresa' && isset($_SESSION['id'])) {
                        $id_empresa = $_SESSION['id'];
                        $sql = "SELECT id_vaga, titulo_vaga, data_publicacao, descricao_vaga, valor_oferta 
                    FROM vaga 
                    WHERE id_empresa = ? 
                    ORDER BY data_publicacao DESC";
                        $stmt = mysqli_prepare($conn, $sql);
                        mysqli_stmt_bind_param($stmt, "i", $id_empresa);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if (mysqli_num_rows($result) > 0) {
                            while ($vaga = mysqli_fetch_assoc($result)) {
                                echo "<div class='card border-primary mb-3' style='width: 300px;margin-left:10px; '>
                            <div class='card-body'>
                                <h5 class='card-title text-primary'>" . htmlspecialchars($vaga['titulo_vaga']) . "</h5>
                                <h6 class='card-subtitle mb-2 text-muted'>Publicada em: " . $vaga['data_publicacao'] . "</h6>
                                <p class='card-text'>" . htmlspecialchars($vaga['descricao_vaga']) . "</p>
                                <p class='card-text'>
                                    <strong>Oferta Salarial:</strong> " .
                                    ($vaga['valor_oferta']
                                        ? 'R$ ' . number_format($vaga['valor_oferta'], 2, ',', '.')
                                        : '—') . "
                                </p>
                                <div style='display: flex; justify-content: center; gap: 10px;'>
                                    <a href='#' class='btn btn-info bi bi-pencil editarVaga'
   data-id='{$vaga['id_vaga']}'
   data-titulo='" . htmlspecialchars($vaga['titulo_vaga'], ENT_QUOTES) . "'
   data-descricao='" . htmlspecialchars($vaga['descricao_vaga'], ENT_QUOTES) . "'
   data-valor='{$vaga['valor_oferta']}'></a>

<a href='../server/gerenciar_vagas.php?acao=excluir&id={$vaga['id_vaga']}'
   onclick=\"return confirm('Deseja excluir esta vaga?')\"
   class='btn btn-danger'>
   <i class='bi bi-trash3'></i>
</a>

                                </div>
                            </div>
                        </div>";
                            }
                        } else {
                            echo "<p style='text-align: center; width: 100%;'>Você ainda não criou nenhuma vaga.</p>";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "<p style='text-align: center; width: 100%;'>Empresa não autenticada.</p>";
                    }
                    ?>
                </div>
            </article>

            <article class="artsolicitacoes" style="display: none;">
                <h2>Minhas Solicitações</h2>
                <table class="tabela-solicitacoes">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descrição</th>
                            <th>Data</th>
                            <th>Candidatos</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Exemplo de linha -->
                        <tr style="text-align: center;">
                            <td>001</td>
                            <td>Dev PHP</td>
                            <td>Preciso de um sistema de agendamento simples em PHP e MySQL.</td>
                            <td>31/05/2025</td>
                            <td>2
                                <button class="btn-ver">Ver Candidatos</button>
                            </td>
                            <td><span class="status pendente">Aberta</span></td>
                            <td>
                                <button class="btn-editar">Editar</button>
                                <button class="btn-cancelar">Cancelar</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </article>

            <article class="artconexoes" style="display: none;">
                <!-- Em breve: Minhas conexões -->
            </article>
        </main>

    </div>
    <script src="../static/scripts/dash_empresa.js"></script>





</body>

</html>