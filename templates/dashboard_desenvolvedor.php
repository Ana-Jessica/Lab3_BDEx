<?php
session_start();
include_once("../server/conexao.php");
include_once("../server/autenticacao/auth.php");

// Para aparecer o toast de sucesso
$exibir_toast_editar = false; // 
$exibir_toast_solicitar = false;
$exibir_toast_solicitar_aviso = false;
$exibir_toast_bem_vindo = false;

if (isset($_SESSION['editado_sucesso'])) {
  $exibir_toast_editar = true;
  unset($_SESSION['editado_sucesso']); // evita repetição
}

if (isset($_SESSION['solicitado_sucesso'])) {
  $exibir_toast_solicitar = true;
  unset($_SESSION['solicitado_sucesso']); // evita repetição
}

if (isset($_SESSION['solicitado_aviso'])) {
  $exibir_toast_solicitar_aviso = true;
  unset($_SESSION['solicitado_aviso']); // evita repetição
}
if (isset($_SESSION['bem_vindo'])) {
  $exibir_toast_bem_vindo = true;
  unset($_SESSION['bem_vindo']); // evita repetição
}

// Buscar dados do desenvolvedor
$id_desenvolvedor = $_SESSION['id'];
$nome = $cpf = $endereco = $email = $telefone = $skills = ''; // Inicializa as variáveis

// Prepara a consulta para buscar os dados do desenvolvedor
$stmt = $conn->prepare("SELECT nome_desenvolvedor, cpf_desenvolvedor, endereco_desenvolvedor, email_desenvolvedor, telefone_desenvolvedor, skills_desenvolvedor FROM desenvolvedor WHERE id_desenvolvedor = ?");
if ($stmt) {
  $stmt->bind_param("i", $id_desenvolvedor);
  $stmt->execute();
  $stmt->bind_result($nome, $cpf, $endereco, $email, $telefone, $skills);

  if (!$stmt->fetch()) {
    // Se não encontrar o desenvolvedor, define valores padrão
    $nome = "desenvolvedor(a) não encontrado(a)";
  }
  $stmt->close();
} else {
  // Em caso de erro na preparação da query
  $nome = "Erro ao carregar dados";
  error_log("Erro na preparação da query: " . $conn->error);
}

// Buscar conexões do desenvolvedor
$conexoes = [];

$sql_conexoes = "
SELECT 
    c.id_conexao,
    c.status_conexao,
    e.nome_empresa,
    e.email_empresa,
    e.telefone_empresa,
    c.data_conexao
FROM Conexao c
INNER JOIN empresa e ON c.id_empresa = e.id_empresa
WHERE c.id_desenvolvedor = ?
ORDER BY c.data_conexao DESC
";

$stmt_conexoes = $conn->prepare($sql_conexoes);
if ($stmt_conexoes) {
  $stmt_conexoes->bind_param("i", $id_desenvolvedor);
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
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página do desenvolvedor</title>
  <link rel="icon" type="image/png" sizes="32x32" href="../static/imgs/logo/simbolo.svg">
  <link rel="icon" type="image/png" sizes="16x16" href="../static/imgs/logo/simbolo.svg">
  <link rel="stylesheet" href="../static/styles/dash_desenvolvedor.css">
  <link rel="stylesheet" href="../static/styles/modal_default.css">
  <link rel="stylesheet" href="../static/styles/toast.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <!-- Cabeçalho -->
  <header>
    <div class="menubar">
      <div class="logo">
        <img src="../static/imgs/bdexsemfundo.png" alt="" width="150px" height="68px" style="object-fit: contain;" />
      </div>
      <div class="usuarionotificacoes">
        <h3> <?= htmlspecialchars($nome) ?></h3>
      </div>
      <a href="../server/autenticacao/logout.php">
        <i class="bi bi-box-arrow-right"></i>
      </a>
    </div>
  </header>
  <div class="ldld">


    <nav class="menulateral">
      <div class="tammenu"><i class="bi bi-list"></i></div>
      <h1>Área do Desenvolvedor</h1>


      <br />
      <ul>
        <li class="item liconectar">Candidatar a uma vaga
          <i class="brief bi bi-briefcase-fill"></i>
        </li>

        <li class="item liddscadastro">Dados de cadastro
          <i class="person bi bi-person-badge"></i>
        </li>

        <li class="item lisenha">alterar senha
          <i class="bikey bi bi-key"></i>
        </li>


        <li class="item liconexoes">Minhas conexões
          <i class="people bi bi-people-fill">
            <?php if ($qtd_conexoes > 0): ?>
              <div class="conexao-qtd">
                <?= $qtd_conexoes ?>
              </div>
            <?php endif; ?>
          </i>
        </li>

        <a class="item liconexoes">desativar conta
          <i class="bi bi-person-x"></i>
        </a>



      </ul>
    </nav>
    <main>
      <article class="artcadastro">

        <form id="formEditar" action="../server/desenvolvedor/editUserDev.php" method="POST">
          <box-inputset>
            <legend>
              <h1><b>Editar dados pessoais</b></h1>
            </legend>
            <br />

            <div class="box-input">
              <label for="nome">Nome:</label>
              <input value="<?= htmlspecialchars($nome) ?>" type="text" placeholder="Digite seu nome"
                id="nome_desenvolvedor" name="nome_desenvolvedor" required>
            </div>
            <br>
            <div class="box-input">
              <label for="telefone_desenvolvedor">Telefone:</label>
              <input value="<?= htmlspecialchars($telefone) ?>" type="text" placeholder="Digite seu telefone"
                id="telefone_desenvolvedor" name="telefone_desenvolvedor" required>
            </div>
            <br>
            <div class="box-input">
              <label for="email_desenvolvedor">Email:</label>
              <input value="<?= htmlspecialchars($email) ?>" type="text" placeholder="Digite seu e-mail"
                id="email_desenvolvedor" name="email_desenvolvedor" required>
            </div>
            <!-- <br> -->
            <!-- <div class="box-input">
              <label for="cpf_desenvolvedor">CPF:</label>
              <input value="<?= htmlspecialchars($cpf) ?>" type="text" placeholder="Digite seu CPF:"
                id="cpf_desenvolvedor" name="cpf_desenvolvedor" required>
            </div> -->
            <br>

            <div class="box-input">
              <label for="skills_desenvolvedor">Skills:</label>
              <textarea placeholder="Digite seus conhecimentos:" id="skills_desenvolvedor" name="skills_desenvolvedor"
                required>
              <?= htmlspecialchars($skills) ?>
              </textarea>
            </div>

            <br>


            <br />
            <!-- CRIAR DESATIVAR CONTA -->
            <a href="desativar_conta.php" onclick="return confirmarEncerramento()">Desativar Conta</a>

            <button type="button" id="update" name="update" class="btneditar">Editar</button>
          </box-inputset>
        </form>
      </article>

      <article class="artconectar" style="display: flex;">
        <h2>Vagas Diponiveis</h2>
        <div class="vagas-lista">
          <?php
          if (isset($_SESSION['id'])) {
            // Consulta todas as vagas com os nomes das empresas
            $sql = "SELECT vaga.*, empresa.nome_empresa 
            FROM vaga 
            INNER JOIN empresa ON vaga.id_empresa = empresa.id_empresa
            WHERE vaga.status_vaga = 'ativa'";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
              while ($vaga = mysqli_fetch_assoc($result)) {
                echo "<div class='card border-primary mb-3' style='width: 300px; margin: 10px; display: inline-block;'>
                    <div class='card-body'>
                        <h5 class='card-title text-primary'>" . htmlspecialchars($vaga['titulo_vaga']) . "</h5></br>
                        <h6 class='card-subtitle mb-2 text-muted'>Empresa:<b> " . htmlspecialchars($vaga['nome_empresa']) . "</b></h6>
                        <h6 class='card-subtitle mb-2 text-muted'>Publicada em: " . htmlspecialchars($vaga['data_publicacao']) . "</h6>
                        <p class='card-text'>" . htmlspecialchars($vaga['descricao_vaga']) . "</p>
                        <p class='card-text'>
                            <strong>Oferta Salarial:</strong> " .
                  ($vaga['valor_oferta']
                    ? 'R$ ' . number_format($vaga['valor_oferta'], 2, ',', '.')
                    : '—') . "
                        </p>
                        <div style='display: flex; justify-content: center; gap: 10px;'>
                            <a href='../server/conexao/candidatar.php?id=" . $vaga['id_vaga'] . "' class='btn btn-success'>
                                <i class='bi bi-person-check'></i> Candidatar-se
                            </a>
                            
                        </div>
                    </div>
                </div>";
              }
            } else {
              echo "<p style='text-align: center; width: 100%;'>Nenhuma vaga disponível no momento.</p>";
            }

            mysqli_stmt_close($stmt);
          } else {
            echo "<p style='text-align: center; width: 100%;'>Usuário não autenticado.</p>";
          }
          ?>

        </div>


      </article>

      <article class="artconexoes" style="display: none;">
        <div class="card shadow mb-4">
          <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Conexões Realizadas</h5>
          </div>
          <div class="card-body">
            <?php if (count($conexoes) > 0): ?>
              <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>ID Conexão</th>
                      <th>Status da Conexão</th>
                      <th>Nome da Empresa</th>
                      <th>Email</th>
                      <th>Telefone</th>
                      <th>Data</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($conexoes as $conexao): ?>
                      <tr>
                        <td><?= $conexao['id_conexao'] ?></td>
                        <td><?= htmlspecialchars($conexao['status_conexao']) ?></td>
                        <td><?= htmlspecialchars($conexao['nome_empresa']) ?></td>
                        <td><?= htmlspecialchars($conexao['email_empresa']) ?></td>
                        <td><?= htmlspecialchars($conexao['telefone_empresa']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($conexao['data_conexao']))) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            <?php else: ?>
              <div class="alert alert-info mt-3">Nenhuma conexão realizada ainda.</div>
            <?php endif; ?>
          </div>
        </div>
      </article>


      <div class="modalsenha">
        <form class="modaleditarsenha" action="../server/conexao/criar_vaga.php" method="POST">
          <div class="btnfecharmodalsenha">X</div>
          <h2>ALTERAR SENHA</h2>
          <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id']; ?>">
          <div class="box-input">
            <label for="">Senha Atual</label>
            <input type="password" name="titulo_vaga" placeholder="senha em uso">
          </div>
          <div class="box-input">
            <label for="">Nova senha:</label>
            <input type="password" name="descricao_vaga" placeholder="Digite a nova senha">
          </div>
          <div class="box-input">
            <label for="">Repetir nova senha</label>
            <input type="password" name="valor_oferta" placeholder="Repita a nova senha">
          </div>
          <br><br>
          <button class="btnsubmitvaga" type="submit"> Alterar senha</button>
        </form>
      </div>


      </article>

      <article class="artconexoes" style="display: none;">
        <!-- Em breve: Minhas conexões -->
      </article>

      <div class="modalsenha">
        <form class="modaleditarsenha" action="../server/conexao/criar_vaga.php" method="POST">
          <div class="btnfecharmodalsenha">X</div>
          <h2>ALTERAR SENHA</h2>
          <input type="hidden" name="id_empresa" value="<?php echo $_SESSION['id']; ?>">
          <div class="box-input">
            <label for="">Senha Atual</label>
            <input type="text" name="titulo_vaga" placeholder="senha em uso">
          </div>
          <div class="box-input">
            <label for="">Nova senha:</label>
            <input type="text" name="descricao_vaga"
              placeholder="precisamos de um desenvolvedor php com conhecimentos em laravel...">
          </div>
          <div class="box-input">
            <label for="">Repetir nova senha</label>
            <input type="text" name="valor_oferta" placeholder="R$ 99,99">
          </div>
          <br><br>
          <button class="btnsubmitvaga" type="submit"> Alterar senha</button>
        </form>
      </div>

    </main>
  </div>
  <!-- Tost com notificação que os dados foram editados -->
  <?php if ($exibir_toast_editar): ?>
    <div id="toast">os dados de <?= htmlspecialchars($nome) ?> foram alterados</div>
  <?php endif; ?>

  <?php if ($exibir_toast_solicitar): ?>
    <div id="toast">foi realizada uma solicitacao de vaga</div>
  <?php endif; ?>

  <?php if ($exibir_toast_solicitar_aviso): ?>
    <div id="toast_aviso">essa vaga ja recebeu sua solicitacao</div>
  <?php endif; ?>

  <?php if ($exibir_toast_bem_vindo): ?>
    <div id="toast_bemvindo"> Seja bem vindo <?= htmlspecialchars($nome) ?> </div>
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

  <script src="../static/scripts/toast.js"></script>
  <!--  Fim toast -->
  <script src="../static/scripts/dash_desenvolvedor.js"></script>
  <script src="../static/scripts/modal_default.js"></script>
</body>

</html>