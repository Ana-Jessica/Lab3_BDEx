const modal = document.querySelector(".janelaedicao");
const nomeInput = document.querySelector("#nome");
const emailInput = document.querySelector("#email");
const celularInput = document.querySelector("#celular");
const generoInputs = document.querySelectorAll("input[name='genero']");
const dataNascInput = document.querySelector("#dt_nasc");

function abrircaixaedicao(id) {
  modal.classList.add("abrir");

  modal.addEventListener("click", (e) => {
    if (e.target.id == "janela-edicao") {
      modal.classList.remove("abrir");
    }
  });

  // Realiza uma requisição AJAX para buscar os dados do usuário
  const xhr = new XMLHttpRequest();
  xhr.open("GET", `getuser.php?idusuarios=${id}`, true);
  xhr.onload = function () {
    if (this.status === 200) {
      const user = JSON.parse(this.responseText);
      nomeInput.value = user.nome;
      emailInput.value = user.email;
      celularInput.value = user.celular;
      generoInputs.forEach((input) => {
        if (input.value === user.genero) {
          input.checked = true;
        }
      });
      dataNascInput.value = user.dt_nasc;
    }
  };
  xhr.send();
}

//separador
document.addEventListener("DOMContentLoaded", function () {
  //movimentacao do nav lateral
  let botaomenulateral = document.querySelector(".botaomenulateral");
  let menulateral = document.querySelector(".menulateral");
  /*
  html: <button class="botaomenulateral">⬌</button>

  botaomenulateral.addEventListener("click", function () {
    menulateral.style.left = "-300px";
    botaomenulateral.style.background = "pink";
  });
*/
  //partes clicaveis li
  var liddscadastro = document.querySelector(".liddscadastro");
  var lipainel = document.querySelector(".lipainel");
  var ligeconteudo = document.querySelector(".ligeconteudo");
  var liusuarios = document.querySelector(".liusuarios");
  var liconfigsite = document.querySelector(".liconfigsite");
  var liinscritos = document.querySelector(".liinscritos");
  //partes visualizaveis:
  var artcadastro = document.querySelector(".artcadastro");
  var artusuarios = document.querySelector(".artusuarios");
  var artconfigsite = document.querySelector(".artconfigsite");
  var artconteudo = document.querySelector(".artconteudo");
  var artinscritos = document.querySelector(".artinscritos");

  //funcoes
  liconfigsite.addEventListener("click", function () {
    artconfigsite.style.display = "flex";

    artcadastro.style.display = "none";
    artusuarios.style.display = "none";
    artconteudo.style.display = "none";
    artinscritos.style.display = "none";

    liconfigsite.style.background = "#FAB243";

    liusuarios.style.background = "#429867";
    liddscadastro.style.background = "#429867";
    ligeconteudo.style.background = "#429867";
  });
  ligeconteudo.addEventListener("click", function () {
    artconteudo.style.display = "flex";
    artcadastro.style.display = "none";
    artconfigsite.style.display = "none";
    artusuarios.style.display = "none";
    artinscritos.style.display = "none";

    ligeconteudo.style.background = "#FAB243";

    liddscadastro.style.background = "#429867";
    liusuarios.style.background = "#429867";
    liconfigsite.style.background = "#429867";
  });

  liddscadastro.addEventListener("click", function () {
    artcadastro.style.display = "flex";

    artconfigsite.style.display = "none";
    artusuarios.style.display = "none";
    artconteudo.style.display = "none";
    artinscritos.style.display = "none";

    liddscadastro.style.background = "#FAB243";

    ligeconteudo.style.background = "#429867";
    liusuarios.style.background = "#429867";
    liconfigsite.style.background = "#429867";
  });

  liusuarios.addEventListener("click", function () {
    artusuarios.style.display = "flex";

    artconteudo.style.display = "none";
    artcadastro.style.display = "none";
    artconfigsite.style.display = "none";
    artinscritos.style.display = "none";

    liusuarios.style.background = "#FAB243";

    liddscadastro.style.background = "#429867";
    ligeconteudo.style.background = "#429867";
    liconfigsite.style.background = "#429867";
  });
  liinscritos.addEventListener("click", function () {
    artinscritos.style.display = "flex";

    artusuarios.style.display = "none";
    artconteudo.style.display = "none";
    artcadastro.style.display = "none";
    artconfigsite.style.display = "none";

    liinscritos.style.background = "#FAB243";

    liusuarios.style.background = "#429867";
    liddscadastro.style.background = "#429867";
    ligeconteudo.style.background = "#429867";
    liconfigsite.style.background = "#429867";
  });
});
