
const modal = document.querySelector('.modal_default');
const form = document.getElementById('formEditar');
const btnConfirmar = document.getElementById('btnconfirmar');
const btnCancelar = document.getElementById('btncancelar');

  const btnEditar = document.getElementById('update');
    // Quando clica no botão "Editar"
    btnEditar.addEventListener('click', () => {
      modal.style.display = 'flex'; 
    });


// Ao confirmar no modal
btnConfirmar.addEventListener('click', () => {
  form.submit();
    modal.style.display = 'none';
  });
  
if (btnEditar) {
  btnEditar.addEventListener('click', () => {
    modal.style.display = 'flex'; 
  });
}

  // Ao cancelar no modal
  btnCancelar.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  // Botões que podem abrir o modal
const botoesAbrirModal = document.querySelectorAll('[data-message]');
const mensagemModal = document.getElementById('modalMensagem');

botoesAbrirModal.forEach(botao => {
  botao.addEventListener('click', () => {
    const mensagem = botao.getAttribute('data-message');
    mensagemModal.innerHTML = mensagem;
    modal.style.display = 'flex';
  });
});




