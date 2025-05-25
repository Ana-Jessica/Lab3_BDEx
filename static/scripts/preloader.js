window.addEventListener("load", function () {
  const preloader = document.querySelector(".conteiner_preloader");
  if (preloader) {
    setTimeout(() => {
      preloader.style.display = "none";
    }, 5000); 
  }
});