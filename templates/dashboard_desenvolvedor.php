

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página do desenvolvedor</title>
    <link rel="stylesheet" href="../static/styles/dash_desenvolvedor.css">
</head>
<body>
    <body>
  <header>
    <div class="menubar">
      <div class="logo">
        <img src="../static/imgs/bdexsemfundo.png" alt="" width="150px" height="68px" style="object-fit: contain;" />
      </div>

      <div class="usuarionotificacoes">
      
      </div>
      <a href="sairsessao.php">
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
      <li class="item ligeconteudo">Conectar a uma empresa</li>
      <br />
      <li class="item liusuarios">Minhas conexões</li>
      <br />
    

    </ul>
  </nav>
  <main>
    <article class="artcadastro">

      <form action="saveedit.php" method="POST">
        <fieldset>
          <legend><b>Editar dados pessoais</b></legend>
          <br />
          <input type="hidden" name="iduser" id="" value="<?php echo "$idlogado" ?>">
          <div class="inputbox">
            <input type="text" name="nome" id="nome" class="inputuser" required maxlength="45" autofocus
              value="<?php echo "$nomelogado" ?>" />
            <label for="" class="labelinput">Nome Completo</label>
          </div>
          <div class="inputbox">
            <input type="text" name="email" id="email" class="inputuser" required maxlength="30"
              value="<?php echo "$emaillogado" ?>" />
            <label for="" class="labelinput">Email</label>
          </div>
          <div class="inputbox">
            <input type="tel" mask="(99)99999-9999" name="celular" id="celular" class="inputuser" required
              maxlength="25" value="<?php echo "$celularlogado" ?>" />
            <label for="celular" class="labelinput">Celular</label>
         
            <label for="dt_nasc"><b> Data de Nascimento</b></label>
            <input type="date" name="dt_nasc" id="dt_nasc" value="<?php echo "$dt_nasclogado" ?>" required />
          </div>
          <div class="box-input">
            <label for="" class="lblsenha " class="labelinput">Senha:</label>
            <input type="password" name="senha" id="senha1" class="inpsenha1" maxlength="25" value="" required />
            <i id="iconeye1" class="bi bi-eye-slash" onclick="mudarsenha1()"></i>
          </div>



          <br />
          <button type="submit" id="update" name="update"
            onclick="return confirm('Tem certeza de que deseja editar os dados? Verifique se a senha e os dados estão preenchidos corretamente');"
            class="btneditar">Editar</button>
        </fieldset>
      </form>

   
  </main>
  
  <script src="../static/scripts/dash_desenvolvedor.js"></script>
  
  
  

 
</body>
</body>
</html>