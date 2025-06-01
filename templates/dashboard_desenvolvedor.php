<?php
include_once '../server/conexao.php'; // ou o nome do seu arquivo de conexão
include_once '../server/auth.php';

$id = $_SESSION['id']; // ID do desenvolvedor logado

// Buscar dados no banco
$sql = "SELECT * FROM desenvolvedor WHERE id_desenvolvedor = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$dev = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Página do desenvolvedor</title>
  <link rel="stylesheet" href="../static/styles/dash_desenvolvedor.css">
</head>

  <body>
    <header>
      <div class="menubar">
        <div class="logo">
          <img src="../static/imgs/bdexsemfundo.png" alt="" width="150px" height="68px" style="object-fit: contain;" />
        </div>

        <div class="usuarionotificacoes">

        </div>
          <a href="../server/logout.php">
                <img width="60px" height="60px" class="btndesconectar" src="../static/imgs/iconelogout.png" alt="">
            </a>
      </div>
    </header>
    <nav class="menulateral">
      <h2>Área do Desenvolvedor</h2>


      <br />
      <ul>

        <li class="item liddscadastro">Dados de cadastro</li>
        <br />
        <li class="item liconectar">Conectar a uma empresa</li>
        <br />
        <li class="item liconexoes">Minhas conexões</li>
        <br />
        
          <a  href="desativar_conta.php" onclick="return confirmarEncerramento()">Desativar Conta</a>

      </ul>
    </nav>
    <main>
      <article class="artcadastro">

        <form action="saveedit.php" method="POST">
          <box-inputset>
            <legend>
              <h1><b>Editar dados pessoais</b></h1>
            </legend>
            <br />
           
            <div class="box-input">
              <label for="nome">Nome:</label>
              <input type="text" placeholder="Digite seu nome" id="nome_desenvolvedor" name="nome_desenvolvedor"
                required>
            </div>
            <br>
            <div class="box-input">
              <label for="telefone_desenvolvedor">Telefone:</label>
              <input type="text" placeholder="Digite seu telefone" id="telefone_desenvolvedor"
                name="telefone_desenvolvedor" required>
            </div>
            <br>
            <div class="box-input">
              <label for="email_desenvolvedor">Email:</label>
              <input type="text" placeholder="Digite seu e-mail" id="email_desenvolvedor" name="email_desenvolvedor"
                required>
            </div>
            <br>
            <div class="box-input">
              <label for="cpf">CPF:</label>
              <input type="text" placeholder="Digite seu CPF:" id="cpf" name="cpf" required>
            </div>
            <br>
            <div class="box-input">
              <label for="linguagens_de_programacao">Skills:</label>
              <textarea type="text" placeholder="Digite seus conhecimentos:" id="linguagens_de_programacao"
                name="linguagens_de_programacao" required>
              </textarea>
            </div>
            <br>
            <div class="box-input">
              <label for="tecnologias">Tecnologias:</label>

              <textarea type="text" placeholder="Quais frameworks você programa:" id="tecnologias" name="tecnologias"
                required>
                  </textarea>
            </div>
            <br>
            <div class="box-input">
              <label for="senha">Senha:</label>
              <input type="password" placeholder="Digite sua senha" id="senha" name="senha" required>
            </div>



            <br />
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

    <script src="../static/scripts/dash_desenvolvedor.js"></script>





  </body>


</html>