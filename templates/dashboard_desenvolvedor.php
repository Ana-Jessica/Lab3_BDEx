<?php
session_start();
include_once ("../server/conexao.php"); 
include_once ("../server/auth.php");

// Para aparecer o toast de sucesso
$exibir_toast = false; // garante que sempre existe
if (isset($_SESSION['editado_sucesso'])) {
    $exibir_toast = true;
    unset($_SESSION['editado_sucesso']); // evita repetição
}

// Buscar dados do desenvolvedor
$id_desenvolvedor = $_SESSION['id'];
$nome = $cpf = $endereco = $email = $telefone = $skills = ''; // Inicializa as variáveis

$stmt = $conn->prepare("SELECT nome_desenvolvedor, cpf, endereco_desenvolvedor, email_desenvolvedor, telefone_desenvolvedor, skills FROM desenvolvedor WHERE id_desenvolvedor = ?");
if ($stmt) {
  $stmt->bind_param("i", $id_desenvolvedor);
  $stmt->execute();
  $stmt->bind_result($nome, $cpf, $endereco, $email, $telefone, $skills);

  if (!$stmt->fetch()) {
    // Se não encontrar o desenvolver, define valores padrão
    $nome = "desenvolvedor(a) não encontrado(a)";
  }
  $stmt->close();
} else {
  // Em caso de erro na preparação da query
  $nome = "Erro ao carregar dados";
  error_log("Erro na preparação da query: " . $conn->error);
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
  <link rel="stylesheet" href="../static/styles/toast.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body>
  <header>
    <div class="menubar">
      <div class="logo">
        <img src="../static/imgs/bdexsemfundo.png" alt="" width="150px" height="68px" style="object-fit: contain;" />
      </div>
      <div class="usuarionotificacoes">
                <h3>Bem vindo <?= htmlspecialchars($nome) ?></h3>
      </div> 
      <a href="../server/logout.php">
        <i class="bi bi-box-arrow-right"></i>
      </a>
    </div>
  </header>
  <nav class="menulateral">
    <h2>Área do Desenvolvedor</h2>


    <br/>
    <ul>

      <li class="item liddscadastro">Dados de cadastro</li>
      <br/>
      <li class="item liconectar">Conectar a uma empresa</li>
      <br/>
      <li class="item liconexoes">Minhas conexões</li>
      <br/>


    </ul>
  </nav>
  <main>
    <article class="artcadastro">

      <form action="../server/editUserDev.php" method="POST">
        <box-inputset>
          <legend>
            <h1><b>Editar dados pessoais</b></h1>
          </legend>
          <br/>

          <div class="box-input">
            <label for="nome">Nome:</label>
            <input value="<?= htmlspecialchars($nome) ?>" type="text" placeholder="Digite seu nome" id="nome_desenvolvedor" name="nome_desenvolvedor" required>
          </div>
          <br>
          <div class="box-input">
            <label for="telefone_desenvolvedor">Telefone:</label>
            <input value="<?= htmlspecialchars($telefone) ?>" type="text" placeholder="Digite seu telefone" id="telefone_desenvolvedor"
              name="telefone_desenvolvedor" required>
          </div>
          <br>
          <div class="box-input">
            <label for="email_desenvolvedor">Email:</label>
            <input value="<?= htmlspecialchars($email) ?>" type="text" placeholder="Digite seu e-mail" id="email_desenvolvedor" name="email_desenvolvedor"
              required>
          </div>
          <br>
          <div class="box-input">
            <label for="cpf">CPF:</label>
            <input value="<?= htmlspecialchars($cpf) ?>" type="text" placeholder="Digite seu CPF:" id="cpf" name="cpf" required>
          </div>
          <br>

          <div class="box-input">
            <label for="skills">Skills:</label>
            <textarea placeholder="Digite seus conhecimentos:" id="skills"
              name="skills" required>
              <?= htmlspecialchars($skills) ?>
              </textarea>
          </div>

          <br>
          <div class="box-input">
            <label for="senha">Senha:</label>
            <input type="password" placeholder="Digite sua senha" id="senha" name="senha" required>
          </div>

          <br/>
          <!-- CRIAR DESATIVAR CONTA -->
          <a href="desativar_conta.php" onclick="return confirmarEncerramento()">Desativar Conta</a>
          
          <button type="submit" id="update" name="update"
            onclick="return confirm('Tem certeza de que deseja editar os dados? Verifique se a senha e os dados estão preenchidos corretamente');"
            class="btneditar">Editar</button>
        </box-inputset>
      </form>
    </article>
    
    <article class="artconectar" style="display: none;">
      <label for="">Disponibilidade para ser contratado:</label>
      <label class="switch">
        <input type="checkbox" class="toggle-logo" />
        <span class="slider round"></span>
      </label>
    </article>

    <article class="artconexoes" style="display: none;">
      <!-- Em breve: Minhas conexões -->
    </article>
  </main>

  <!-- Tost com notificação que os dados foram editados -->
  <?php if ($exibir_toast): ?>
    <div id="toast">Editado com sucesso</div>
  <?php endif; ?>

  <script src="../static/scripts/toast.js"></script>
  <!--  Fim toast -->
  <script src="../static/scripts/dash_desenvolvedor.js"></script>
</body>
</html>