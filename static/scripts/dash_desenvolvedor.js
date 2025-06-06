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
      skills.value = user.skills || "";
      senha.value = ""; // por segurança
    }
  };
  xhr.send();
}

// Seletores dos itens de menu
const liddscadastro = document.querySelector(".liddscadastro");
const liconectar = document.querySelector(".liconectar");
const liconexoes = document.querySelector(".liconexoes");

// Seletores dos artigos
const artcadastro = document.querySelector(".artcadastro");
const artconectar = document.querySelector(".artconectar");
const artconexoes = document.querySelector(".artconexoes");

// Função para resetar os backgrounds dos menus
function resetarBackground() {
  liddscadastro.style.background = "";
  liconectar.style.background = "";
  liconexoes.style.background = "";
  liddscadastro.style.color = "";
  liconectar.style.color = "";
  liconexoes.style.color = "";
}

// Mostrar seção Cadastro
liddscadastro.addEventListener("click", function () {
  artcadastro.style.display = "flex";
  artconectar.style.display = "none";
  artconexoes.style.display = "none";

  resetarBackground();
  liddscadastro.style.background = "#00DE8A";
  liddscadastro.style.color = "black"
});

// Mostrar seção Conectar
liconectar.addEventListener("click", function () {
  artcadastro.style.display = "none";
  artconectar.style.display = "flex";
  artconexoes.style.display = "none";

  resetarBackground();
  liconectar.style.background = "#00DE8A";
liconectar.style.color = "black"
});

// Mostrar seção Conexões
liconexoes.addEventListener("click", function () {
  artcadastro.style.display = "none";
  artconectar.style.display = "none";
  artconexoes.style.display = "flex";

  resetarBackground();
  liconexoes.style.background = "#00DE8A";
  liconexoes.style.color = "black"
});
 function confirmarEncerramento() {
    return confirm("Deseja realmente desativar sua conta?");
  }