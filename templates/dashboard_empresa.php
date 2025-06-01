<?php
include_once("../server/auth.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagina da empresa</title>
    <link rel="stylesheet" href="../static/styles/dash_empresa.css">
</head>

<body>
    <header>
        <div class="menubar">
            <div class="logo">
                <img src="../static/imgs/bdexsemfundo.png" alt="" width="150px" height="68px"
                    style="object-fit: contain;" />
            </div>

            <div class="usuarionotificacoes">

            </div>
            <a href="sairsessao.php">
                <img width="60px" height="60px" class="btndesconectar" src="../static/imgs/iconelogout.png" alt="">
            </a>
        </div>
    </header>
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

            <a href="Desativar_conta.php" onclick="return confirmarEncerramento()">Desativar Conta</a>

            <br />


        </ul>
    </nav>
    <main>
        <article class="artcadastro">

            <form action="saveedit.php" method="POST">
                <box-inputset>
                    <legend>
                        <h1><b>Editar dados da Empresa</b></h1>
                    </legend>
                    <br />

                    <div class="box-input">
                        <label for="nome_empresa">Nome:</label>
                        <input type="text" placeholder="Digite seu nome" id="nome_empresa" name="nome_empresa" required>
                    </div>
                    <br>
                    <div class="box-input">
                        <label for="cnpj">CNPJ</label>
                        <input type="text" placeholder="Digite..." id="cnpj" name="cnpj" required>
                    </div>
                    <br>
                    <div class="box-input">
                        <label for="endereco">Endereço:</label>
                        <input type="text" placeholder="Digite seu endereço" id="endereco" name="endereco" required>
                    </div>
                    <br>
                    <div class="box-input">
                        <label for="email">Email:</label>
                        <input type="text" placeholder="Digite seu e-mail" id="email" name="email" required>
                    </div>
                    <br>
                    <div class="box-input">
                        <label for="telefone_empresa">Telefone:</label>
                        <input type="text" placeholder="Digite seu telefone" id="telefone_empresa"
                            name="telefone_empresa" required>
                    </div>
                    <br>
                    <div class="box-input">
                        <label for="senha">Senha:</label>
                        <input type="password" placeholder="Digite sua senha" id="senha" name="senha" required>
                    </div>
                    <br>



                    <br />
                    <button type="submit" id="update" name="update"
                        onclick="return confirm('Tem certeza de que deseja editar os dados? Verifique se a senha e os dados estão preenchidos corretamente');"
                        class="btneditar">Editar</button>
                </box-inputset>
            </form>
        </article>
         <article class="artvagas" style="display: none;">
            <h2>gerenciar vagas</h2>
            <button>criar vaga</button>
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

    <script src="../static/scripts/dash_empresa.js"></script>





</body>



</html>