// Função para preencher o formulário com dados do usuário
function preencherFormulario(id) {
  const nome = document.querySelector("#nome_empresa");
  const telefone = document.querySelector("#telefone_empresa");
  const email = document.querySelector("#email_empresa");
  const cnpj = document.querySelector("#cnpj");
  const linguagens = document.querySelector("#linguagens_de_programacao");
  const tecnologias = document.querySelector("#tecnologias");
  const senha = document.querySelector("#senha");

  const xhr = new XMLHttpRequest();
  xhr.open("GET", `getuser.php?idusuarios=${id}`, true);
  xhr.onload = function () {
    if (this.status === 200) {
      const user = JSON.parse(this.responseText);
      nome.value = user.nome_empresa || "";
      telefone.value = user.telefone_empresa || "";
      email.value = user.email_empresa || "";
      cnpj.value = user.cnpj || "";
      linguagens.value = user.linguagens_de_programacao || "";
      tecnologias.value = user.tecnologias || "";
      senha.value = ""; // por segurança
    }
  };
  xhr.send();
}

// Seletores dos itens de menu
const liddscadastro = document.querySelector(".liddscadastro");
const lisolicitacoes = document.querySelector(".lisolicitacoes");
const liconexoes = document.querySelector(".liconexoes");
const livagas = document.querySelector(".livagas");

// Seletores dos artigos
const artcadastro = document.querySelector(".artcadastro");
const artsolicitacoes = document.querySelector(".artsolicitacoes");
const artconexoes = document.querySelector(".artconexoes");
const artvagas = document.querySelector(".artvagas");

// Função para resetar os backgrounds dos menus
function resetarBackground() {
  liddscadastro.style.background = "";
  lisolicitacoes.style.background = "";
  liconexoes.style.background = "";
  liddscadastro.style.color = "";
  lisolicitacoes.style.color = "";
  liconexoes.style.color = "";
  
  livagas.style.background = "";
  livagas.style.color = "";
}

// Mostrar seção Cadastro
liddscadastro.addEventListener("click", function () {
  artcadastro.style.display = "flex";
  artsolicitacoes.style.display = "none";
  artconexoes.style.display = "none";
  artvagas.style.display = "none";

  resetarBackground();
  liddscadastro.style.background = "#00DE8A";
  liddscadastro.style.color = "black"
});

// Mostrar seção Conectar
lisolicitacoes.addEventListener("click", function () {
  artcadastro.style.display = "none";
  artsolicitacoes.style.display = "flex";
  artconexoes.style.display = "none";
  artvagas.style.display = "none";

  resetarBackground();
  lisolicitacoes.style.background = "#00DE8A";
lisolicitacoes.style.color = "black"
});

// Mostrar seção Conexões
liconexoes.addEventListener("click", function () {
  artcadastro.style.display = "none";
  artsolicitacoes.style.display = "none";
  artvagas.style.display = "none";
  artconexoes.style.display = "flex";

  resetarBackground();
  liconexoes.style.background = "#00DE8A";
  liconexoes.style.color = "black"
});

livagas.addEventListener("click", function () {
  artvagas.style.display = "flex";
  artcadastro.style.display = "none";
  artsolicitacoes.style.display = "none";
  artconexoes.style.display = "none";
  
  resetarBackground();
  livagas.style.background = "#00DE8A";
  livagas.style.color = "black"
  
  btnvaga = document.querySelector(".criarvaga");
  modalvaga = document.querySelector(".modalvaga");
  btnfecharmodal = document.querySelector(".btnfecharmodal");
  
  btnvaga.addEventListener("click", function () {
  modalvaga.style.display = "flex";
  });
  btnfecharmodal.addEventListener("click", function () {
  modalvaga.style.display = "none";
  });

});


 function confirmarEncerramento() {
    return confirm("Deseja realmente desativar sua conta?");
  }