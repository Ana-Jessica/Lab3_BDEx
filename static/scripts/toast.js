// Esse toast é usado para mostrar mensagens de sucesso ou erro após ações do usuário, como salvar ou excluir dados.
// Ele é adicionado dinamicamente ao DOM e removido após 3 segundos.


window.addEventListener("DOMContentLoaded", () => {
  const toast = document.getElementById("toast");
  if (toast) {
    // Remove o toast após a animação (3s)
    setTimeout(() => {
      toast.remove();
    }, 3000);
  }
});
