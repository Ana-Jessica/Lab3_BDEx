// JavaScript para navegar entre as páginas (simples)
document.getElementById('inicio').addEventListener('click', function() {
    window.location.href = 'index.html'; // Redireciona para a página inicial
});

document.getElementById('manual').addEventListener('click', function() {
    alert("Página do Manual em construção!");
    // Você pode adicionar o link para a página do Manual assim que estiver pronta
    // window.location.href = 'manual.html'; // Redireciona para a página do Manual
});

document.getElementById('logon_dev').addEventListener('click', function() {
    window.location.href = 'pglogin.html';
});