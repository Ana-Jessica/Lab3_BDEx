<?php
session_start();
include_once("../server/conexao.php");
include_once("../server/autenticacao/auth.php");

// Para aparecer o toast de sucesso
$exibir_toast_vagacriada = false;
$exibir_toast_editar = false;
$exibir_toast_bem_vindo = false;
$exibir_toast_conexao_criada = false;
$exibir_toast_conexao_jaexiste = false;


if (isset($_SESSION['vagacriada'])) {
    $exibir_toast_vagacriada = true;
    unset($_SESSION['vagacriada']); // evita repetição
}
if (isset($_SESSION['editado_sucesso'])) {
    $exibir_toast_editar = true;
    unset($_SESSION['editado_sucesso']); // evita repetição
}


if (isset($_SESSION['bem_vindoo'])) {
    $exibir_toast_bem_vindo = true;
    unset($_SESSION['bem_vindoo']); // evita repetição
}

if (isset($_SESSION['conexao_criada'])) {
    $exibir_toast_conexao_criada = true;
    unset($_SESSION['conexao_criada']); // evita repetição
}

if (isset($_SESSION['conexao_jaexiste'])) {
    $exibir_toast_conexao_jaexiste = true;
    unset($_SESSION['conexao_jaexiste']); // evita repetição
}


// Verifica se está logado e se é uma empresa
if (!isset($_SESSION['id']) || $_SESSION['tipo'] !== 'empresa') {
    header("Location: templates/pglogin.html");
    exit();
}

$id_empresa = $_SESSION['id'];

// Inicializa variáveis
$nome_empresa = $cnpj_empresa = $endereco_empresa = $email_empresa = $telefone_empresa = '';

// Preparar a consulta para buscar os dados da empresa
$stmt = $conn->prepare("SELECT nome_empresa, cnpj_empresa, endereco_empresa, email_empresa, telefone_empresa FROM empresa WHERE id_empresa = ?");
if ($stmt) {
    $stmt->bind_param("i", $id_empresa);
    $stmt->execute();
    $stmt->bind_result($nome_empresa, $cnpj_empresa, $endereco_empresa, $email_empresa, $telefone_empresa);
    if (!$stmt->fetch()) {
        $nome_empresa = "Empresa não encontrada";
    }
    $stmt->close();
} else {
    $nome_empresa = "Erro ao carregar dados";
    error_log("Erro na preparação da query: " . $conn->error);
}

// Buscar solicitações
$solicitacoes = [];

$sql = "
SELECT 
    s.id_solicitacao,
    s.data_solicitacao,
    s.status_solicitacao,
    v.id_vaga,
    v.titulo_vaga,
    d.id_desenvolvedor,
    d.nome_desenvolvedor,
    d.email_desenvolvedor,
    d.skills_desenvolvedor
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

// Buscar conexões
$conexoes = [];

$sql_conexoes = "
SELECT 
    c.id_conexao,
    c.data_conexao,
    c.status_conexao,
    d.nome_desenvolvedor,
    d.email_desenvolvedor,
    d.telefone_desenvolvedor,
    d.skills_desenvolvedor,
    v.titulo_vaga
FROM conexao c
INNER JOIN desenvolvedor d ON c.id_desenvolvedor = d.id_desenvolvedor
INNER JOIN vaga v ON c.id_vaga = v.id_vaga
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
    $qtd_conexoes = count($conexoes);

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
    <link rel="stylesheet" href="../static/styles/toast.css">
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
                <h3><?= htmlspecialchars($nome_empresa) ?></h3>
            </div>

            <a class="logout-link" href="../server/autenticacao/logout.php">
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
                    <i class="brief bi bi-people-fill"><?php if ($qtd_conexoes > 0): ?>
                            <div class="conexao-qtd">
                                <?= $qtd_conexoes ?>
                            </div>
                        <?php endif; ?>
                    </i>


                </li>
                <br />
            </ul>

        </nav>
        <main>
            <article class="artcadastro" style="display: none;">
                <!-- Editar dados da empresa -->
                <form id="formEditar" action="../server/empresa/EditUserEmpresa.php" method="POST">
                    <box-inputset>
                        <legend>
                            <h1><b>Editar dados da Empresa</b></h1>
                        </legend>
                        <br />

                        <div class="box-input">
                            <label for="nome_empresa">Nome:</label>
                            <input type="text" placeholder="Digite o nome da Empresa" id="nome_empresa"
                                name="nome_empresa" required value="<?= htmlspecialchars($nome_empresa) ?>">
                        </div>
                        <br>

                        <div class="box-input">
                            <label for="endereco_empresa">Endereço:</label>
                            <input type="text" placeholder="Digite seu endereço" id="endereco_empresa"
                                name="endereco_empresa" required value="<?= htmlspecialchars($endereco_empresa) ?>">
                        </div>
                        <br>

                        <div class="box-input">
                            <label for="email_empresa">Email:</label>
                            <input type="email" placeholder="Digite seu e-mail" id="email_empresa" name="email_empresa"
                                required value="<?= htmlspecialchars($email_empresa) ?>">
                        </div>
                        <br>

                        <div class="box-input">
                            <label for="telefone_empresa">Telefone:</label>
                            <input type="text" placeholder="Digite seu telefone" id="telefone_empresa"
                                name="telefone_empresa" required value="<?= htmlspecialchars($telefone_empresa) ?>">
                        </div>
                        <br>


                        <a href="Desativar_conta.php" onclick="return confirmarEncerramento()">Desativar Conta</a>
                        <br />

                        <!-- CRIAR MODAL SOBRE -->
                        <button type="button" id="update" name="update" class="btneditar">Editar</button>
                    </box-inputset>
                </form>
            </article>

            <article class="artvagas" style="display: flex;">
                <h2>gerenciar vagas</h2>
                <button class="criarvaga">criar vaga +</button>
                <br>
                <div class="modalvaga">
                    <form class="modaleditarvaga" action="../server/conexao/criar_vaga.php" method="POST">
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
                            <label for="">Empresa: <?php echo htmlspecialchars($nome_empresa); ?></label>

                        </div>
                        <button class="btnsubmitvaga" type="submit"> CRIAR VAGA</button>
                    </form>
                </div>
                <div class="vagas-lista">
                    <?php
                    if ($_SESSION['tipo'] === 'empresa' && isset($_SESSION['id'])) {
                        $id_empresa = $_SESSION['id'];
                        $sql = "SELECT id_vaga, titulo_vaga, data_publicacao, descricao_vaga, valor_oferta, status_vaga 
            FROM vaga 
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
                                $status = htmlspecialchars($vaga['status_vaga']);
                                $data = htmlspecialchars($vaga['data_publicacao']);

                                // Card da vaga
                                echo "
            <div class='card border-primary mb-3' style='width: 300px; margin-left: 10px;'>
                <div class='card-body'>
                    <h5 class='card-title text-primary'>{$titulo}</h5>
                    <h6 class='card-subtitle mb-2 text-muted'>Publicada em: {$data}</h6>
                    <p class='card-text'>{$descricao}</p>
                    <p class='card-text'><strong>Oferta Salarial:</strong> R$ {$valor}</p>
                    <p class='card-text'><strong>Status:</strong> {$status}</p>
                    <div style='display: flex; justify-content: center; gap: 10px;'>
                        <a href='#' class='btn btn-info criarvaga' data-modal='modal-editar-{$id}'><i class='bi bi-pencil'></i></a>
                        <a href='../server/conexao/gerenciar_vagas.php?acao=excluir&id={$id}' 
                           onclick=\"return confirm('Deseja excluir esta vaga?')\" 
                           class='btn btn-danger'>
                           <i class='bi bi-trash3'></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal de edição -->
            <div class='modalvaga' id='modal-editar-{$id}' style='display: none;'>
                <form class='modaleditarvaga' method='POST' action='../server/conexao/gerenciar_vagas.php?acao=editar&id={$id}'>
                    <div class='btnfecharmodal'>X</div>
                    <h2>Editar Vaga</h2>
                    <input type='hidden' name='id_empresa' value='" . intval($_SESSION['id']) . "'>
                    
                    <div class='box-input'>
                        <label for='titulo{$id}'>Título</label>
                        <input type='text' name='titulo' id='titulo{$id}' value='{$titulo}' required>
                    </div>
                    
                    <div class='box-input'>
                        <label for='descricao{$id}'>Descrição</label>
                        <input type='text' name='descricao' id='descricao{$id}' value='{$descricao}' required>
                    </div>
                    
                    <div class='box-input'>
                        <label for='valor{$id}'>Valor de Oferta</label>
                        <input type='text' name='valor' id='valor{$id}' value='{$valor}'>
                    </div>

                    <div class='box-input'>
                        <label for='status{$id}'>Status da Vaga</label>
                        <select name='status_vaga' id='status{$id}'>
                            <option value='ativa'" . ($status === 'ativa' ? ' selected' : '') . ">Ativa</option>
                            <option value='fechada'" . ($status === 'fechada' ? ' selected' : '') . ">Fechada</option>
                            <option value='conectada'" . ($status === 'conectada' ? ' selected' : '') . ">Conectada</option>
                        </select>
                    </div>

                    <div class='box-input'>
                        <label>Empresa: " . htmlspecialchars($nome_empresa) . "</label>
                    </div>

                    <button class='btnsubmitvaga' type='submit'>Salvar Alterações</button>
                </form>
            </div>
            ";
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
                            <th>Título da vaga</th>
                            <th>Nome do Candidato</th>
                            <th>Skills do Candidato</th>
                            <th>Data da solicitação</th>

                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($solicitacoes as $sol): ?>
                            <tr style="text-align: center;">
                                <td><?= htmlspecialchars($sol['id_solicitacao']) ?></td>
                                <td><b><?= htmlspecialchars($sol['titulo_vaga']) ?></b></td>
                                <td> <?= htmlspecialchars($sol['nome_desenvolvedor']) ?></td>
                                <td> <?= htmlspecialchars($sol['skills_desenvolvedor']) ?></td>
                                <td><?= htmlspecialchars($sol['data_solicitacao'] ?? 'Data indefinida') ?></td>

                                </td>
                                <td class="ldld">
                                    <button class="btn-conectar" data-id-vaga="<?= $sol['id_vaga'] ?>"
                                        data-id-desenvolvedor="<?= $sol['id_desenvolvedor'] ?>">
                                        Conectar <i class="bi bi-person-fill-add"></i>
                                    </button>
                                    <button class="btn-cancelar" data-id-vaga="<?= $sol['id_vaga'] ?>"
                                        data-id-desenvolvedor="<?= $sol['id_desenvolvedor'] ?>">
                                        Rejeitar <i class="bi bi-person-fill-dash"></i>
                                    </button>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="modal-candidatos" style="display: none;">
                    <div class="modal-content">
                        <span class="close-modal">&times;</span>
                        <h3>Candidatos</h3>
                        <ul class="lista-candidatos">
                            <?php foreach ($solicitacoes as $sol): ?>
                                <li>
                                    <strong><?= htmlspecialchars($sol['nome_desenvolvedor']) ?></strong> -
                                    Email: <?= htmlspecialchars($sol['email_desenvolvedor']) ?> -
                                    Telefone: <?= htmlspecialchars($sol['telefone_desenvolvedor']) ?> -
                                    Conhecimentos: <?= htmlspecialchars($sol['skills_desenvolvedor']) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
            </article>

            <article class="artconexoes" style="display: none;">
                <?php if (count($conexoes) > 0): ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID Conexão</th>
                                <th>Nome da empresa</th>
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

        <!-- Tost com notificação que os dados foram editados -->
        <?php if ($exibir_toast_vagacriada): ?>
            <div id="toast_bemvindo">uma vaga foi criada</div>
        <?php endif; ?>

        <?php if ($exibir_toast_editar): ?>
            <div id="toast">os dados de <?= htmlspecialchars($nome_empresa) ?> foram alterados</div>
        <?php endif; ?>


        <?php if ($exibir_toast_conexao_criada): ?>
            <div id="toast"> foi criada uma conexão </div>
        <?php endif; ?>

        <?php if ($exibir_toast_conexao_jaexiste): ?>
            <div id="toast_aviso">essa conexao já foi aceita </div>
        <?php endif; ?>

        <!-- modal editar -->
        <div class="modal_default">
            <div class="modal-content">
                <p id="modalMensagem">Tem certeza de que deseja editar os dados?<br>Verifique se estão corretos.</p>
                <div class="modal-buttons">
                    <button id="btnconfirmar" onclick="confirmarModal(true)">Confirmar</button>
                    <button id="btncancelar" onclick="confirmarModal(false)">Cancelar</button>
                </div>
            </div>
        </div>

    </div>
    <script src="../static/scripts/dash_empresa.js"></script>
    <script src="../static/scripts/toast.js"></script>
    <script src="../static/scripts/modal_default.js"></script>

</body>

</html>