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