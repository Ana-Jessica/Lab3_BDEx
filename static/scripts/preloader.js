window.addEventListener("load", function () {
  const preloader = document.querySelector(".conteiner_preloader");
  if (preloader) {
    setTimeout(() => {
      preloader.style.display = "none";
    }, 2000); 
  }
});
window.addEventListener("DOMContentLoaded", function () {
  const params = new URLSearchParams(window.location.search);
  const sucesso = params.get("sucesso");

  if (sucesso) {
    const div = document.getElementById("mensagem-sucesso");
    if (div) {
      div.textContent = decodeURIComponent(sucesso);
      div.style.display = "block";
    }
  }
});
