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
  
  //partes visualizaveis:
  var artcadastro = document.querySelector(".artcadastro");
  

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
})