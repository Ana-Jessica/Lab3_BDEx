document.addEventListener('DOMContentLoaded', function () {
         const toggle = document.getElementById('toggleCadastro');
         const empresaForm = document.getElementById('empresaForm');
         const desenvolvedorForm = document.getElementById('desenvolvedorForm');
         const tipoUsuario = document.getElementById('tipoUsuario');




         toggle.addEventListener('change', function () {
            if (this.checked) {
               empresaForm.classList.remove('active');
               desenvolvedorForm.classList.add('active');
               tipoUsuario.textContent = 'Desenvolvedor';
               tipoUsuario.style.left = '10px';
               tipoUsuario.style.color = 'black';
               
            } else {
                empresaForm.classList.add('active');
                desenvolvedorForm.classList.remove('active');
                tipoUsuario.textContent = 'Empresa';
                tipoUsuario.style.left = '60px';
                tipoUsuario.style.color = 'white';
                
                
          
            }
         });
      });

window.addEventListener("DOMContentLoaded", () => {
  const params = new URLSearchParams(window.location.search);
  const erro = params.get("erro");
  const sucesso = params.get("sucesso");

  if (erro) {
    const div = document.getElementById("mensagem-erro");
    div.textContent = decodeURIComponent(erro);
    div.style.display = "block";
  }

  if (sucesso) {
    const div = document.getElementById("mensagem-sucesso");
    div.textContent = decodeURIComponent(sucesso);
    div.style.display = "block";
  }
});
