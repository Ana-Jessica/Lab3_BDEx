
  const btnEditar = document.getElementById('update');
  const modal = document.querySelector('.modal_default');
  const form = document.getElementById('formEditar');
  const btnConfirmar = document.getElementById('btnconfirmar');
  const btnCancelar = document.getElementById('btncancelar');

  // Quando clica no botão "Editar"
  btnEditar.addEventListener('click', () => {
    modal.style.display = 'flex'; // Mostra o modal de confirmação
  });

  // Ao confirmar no modal
  btnConfirmar.addEventListener('click', () => {
    form.submit(); // Submete o formulário manualmente
    modal.style.display = 'none';
  });

  // Ao cancelar no modal
  btnCancelar.addEventListener('click', () => {
    modal.style.display = 'none';
  });

