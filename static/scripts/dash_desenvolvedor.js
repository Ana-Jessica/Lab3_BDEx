document.addEventListener("DOMContentLoaded", function () {
  // Função para preencher o formulário com dados do usuário
  function preencherFormulario(id) {
    const nome = document.querySelector("#nome_desenvolvedor");
    const telefone = document.querySelector("#telefone_desenvolvedor");
    const email = document.querySelector("#email_desenvolvedor");
    const cpf = document.querySelector("#cpf");
    const linguagens = document.querySelector("#skills");
    const senha = document.querySelector("#senha");

    const xhr = new XMLHttpRequest();
    xhr.open("GET", `getuser.php?idusuarios=${id}`, true);
    xhr.onload = function () {
      if (this.status === 200) {
        const user = JSON.parse(this.responseText);
        nome.value = user.nome_desenvolvedor || "";
        telefone.value = user.telefone_desenvolvedor || "";
        email.value = user.email_desenvolvedor || "";
        cpf.value = user.cpf || "";
        linguagens.value = user.skills || "";
        senha.value = ""; // por segurança
      }
    };
    xhr.send();
  }

  // Seletores dos itens de menu
  const liddscadastro = document.querySelector(".liddscadastro");
  const liconectar = document.querySelector(".liconectar");
  const liconexoes = document.querySelector(".liconexoes");
  const lialterarsenha = document.querySelector(".lisenha");

  // Seletores dos artigos
  const artcadastro = document.querySelector(".artcadastro");
  const artconectar = document.querySelector(".artconectar");
  const artconexoes = document.querySelector(".artconexoes");
  const modalsenha = document.querySelector(".modalsenha");

  const btnfecharmodalsenha = document.querySelector(".btnfecharmodalsenha");

  // Função para resetar os backgrounds dos menus
  function resetarBackground() {
    liddscadastro.style.background = "";
    liconectar.style.background = "";
    liconexoes.style.background = "";
    lialterarsenha.style.background = "";

    liddscadastro.style.color = "";
    liconectar.style.color = "";
    liconexoes.style.color = "";
    lialterarsenha.style.color = "";

    liddscadastro.classList.remove("ativo");
    liconectar.classList.remove("ativo");
    liconexoes.classList.remove("ativo");
    lialterarsenha.classList.remove("ativo");
  }

  // Mostrar seção Cadastro
  liddscadastro.addEventListener("click", function () {
    artcadastro.style.display = "flex";
    artconectar.style.display = "none";
    artconexoes.style.display = "none";
    modalsenha.style.display = "none";

    resetarBackground();
    liddscadastro.style.background = "#00DE8A";
    liddscadastro.style.color = "black";
    liddscadastro.classList.add("ativo");
  });

  // Mostrar seção Conectar
  liconectar.addEventListener("click", function () {
    artcadastro.style.display = "none";
    artconectar.style.display = "flex";
    artconexoes.style.display = "none";
    modalsenha.style.display = "none";

    resetarBackground();
    liconectar.style.background = "#00DE8A";
    liconectar.style.color = "black";
    liconectar.classList.add("ativo");
  });

  // Mostrar seção Conexões
  liconexoes.addEventListener("click", function () {
    artcadastro.style.display = "none";
    artconectar.style.display = "none";
    artconexoes.style.display = "flex";
    modalsenha.style.display = "none";

    resetarBackground();
    liconexoes.style.background = "#00DE8A";
    liconexoes.style.color = "black";
    liconexoes.classList.add("ativo");
  });

  // Mostrar modal de senha
  lialterarsenha.addEventListener("click", function () {
    artcadastro.style.display = "none";
    artconectar.style.display = "none";
    artconexoes.style.display = "none";
    modalsenha.style.display = "flex";

    resetarBackground();
    lialterarsenha.style.background = "#00DE8A";
    lialterarsenha.style.color = "black";
    lialterarsenha.classList.add("ativo");
  });

  // Fechar modal senha
  btnfecharmodalsenha.addEventListener("click", function () {
    modalsenha.style.display = "none";
    artconectar.style.display = "flex";
  });

  // Menu lateral se mexer
  const botaoToggle = document.querySelector(".tammenu");
  const menuLateral = document.querySelector(".menulateral");
  const mainContent = document.querySelector("main");

  botaoToggle.addEventListener("click", function () {
    menuLateral.classList.toggle("escondido");
    mainContent.classList.toggle("expandido");
    menuLateral.classList.toggle("fechar");
  });

  // Confirmação de encerramento de conta
  window.confirmarEncerramento = function () {
    return confirm("Deseja realmente desativar sua conta?");
  };

  // Exportar a função se necessário
  window.preencherFormulario = preencherFormulario;
});
