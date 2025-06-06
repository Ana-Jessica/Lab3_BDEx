window.addEventListener("DOMContentLoaded", () => {
  const toast = document.getElementById("toast");
  if (toast) {
    // Remove o toast após a animação (3s)
    setTimeout(() => {
      toast.remove();
    }, 3000);
  }
});
