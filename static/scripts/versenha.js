function mudarsenha() {
  let inppass = document.getElementById("inppass");

  let iconeye = document.getElementById("iconeye");
  if (inppass.type === "password") {
    inppass.setAttribute("type", "text");
    iconeye.classList.replace("bi-eye-slash", "bi-eye");
  } else {
    inppass.setAttribute("type", "password");
    iconeye.classList.replace("bi-eye", "bi-eye-slash");
  }
}

function mudarsenha1() {
  let senha1 = document.getElementById("senha1");
  let iconeye1 = document.getElementById("iconeye1");
  if (senha1.type === "password") {
    senha1.setAttribute("type", "text");
    iconeye1.classList.replace("bi-eye-slash", "bi-eye");
  } else {
    senha1.setAttribute("type", "password");
    iconeye1.classList.replace("bi-eye", "bi-eye-slash");
  }
}
