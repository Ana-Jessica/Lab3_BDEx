// Função para preencher o formulário com dados do usuário
function preencherFormulario(id) {
  const nome = document.querySelector("#nome_empresa");
  const telefone = document.querySelector("#telefone_empresa");
  const email = document.querySelector("#email_empresa");
  const cnpj = document.querySelector("#cnpj_empresa");
  const skills = document.querySelector("#skills");
  const senha = document.querySelector("#senha");

  const xhr = new XMLHttpRequest();
  xhr.open("GET", `getuser.php?idusuarios=${id}`, true);
  xhr.onload = function () {
    if (this.status === 200) {
      const user = JSON.parse(this.responseText);
      nome.value = user.nome_empresa || "";
      telefone.value = user.telefone_empresa || "";
      email.value = user.email_empresa || "";
      cnpj.value_empresa = user.cnpj_empresa || "";
      skills.value = user.skills_desenvolvedor || "";
      senha.value = ""; // por segurança
    }
  };
  xhr.send();
}

document.querySelectorAll(".btn-ver").forEach(btn => {
  btn.addEventListener("click", function () {
    const idVaga = this.closest("tr").querySelector("td").innerText;

    fetch(`../server/ver_candidatos.php?id_vaga=${idVaga}`)
      .then(response => response.json())
      .then(data => {
        const container = document.getElementById("conteudoCandidatos");
        container.innerHTML = "";

        if (data.length === 0) {
          container.innerHTML = "<p>Nenhum candidato encontrado para esta vaga.</p>";
        } else {
          data.forEach(candidato => {
            const bloco = document.createElement("div");
            bloco.innerHTML = `
                            <strong>Nome:</strong> ${candidato.nome_desenvolvedor}<br>
                            <strong>Email:</strong> ${candidato.email_desenvolvedor}<br>
                            <strong>Skills:</strong> ${candidato.skills || "Não informado"}<hr>
                        `;
            container.appendChild(bloco);
          });
        }

        document.getElementById("modalCandidatos").style.display = "block";
      })
      .catch(err => {
        alert("Erro ao carregar candidatos");
        console.error(err);
      });
  });
});

document.querySelectorAll(".btn-conectar").forEach(button => {
  button.addEventListener("click", () => {
    const idVaga = button.getAttribute("data-id-vaga");
    const idDev = button.getAttribute("data-id-desenvolvedor");

    fetch("../server/conexao/criar_conexao.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      body: `id_vaga=${idVaga}&id_desenvolvedor=${idDev}`
    })
      .then(response => response.text())
      .then(data => {

        location.reload(); // recarrega para atualizar status, ou modifique dinamicamente
      })
      .catch(error => {
        alert("Erro ao conectar: " + error);
      });
  });
});

// Configuração dos botões de encerramento
// Inicializa o modal de encerramento
document.querySelectorAll('.btn-encerrar').forEach(btn => {
    btn.addEventListener('click', function() {
        const idConexao = this.getAttribute('data-id');
        document.getElementById('idConexaoEncerrar').value = idConexao;
        
        // Limpa a justificativa anterior
        document.getElementById('justificativa').value = '';
        
        // Mostra o modal
        const modal = new bootstrap.Modal(document.getElementById('modalEncerrar'));
        modal.show();
    });
});

// Configura o botão de concluir
document.querySelectorAll('.btn-concluir').forEach(btn => {
    btn.addEventListener('click', function() {
        const idConexao = this.getAttribute('data-id');
        if(confirm('Deseja marcar esta conexão como concluída?')) {
            // Cria um formulário dinâmico para envio
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../server/conexao/gerenciar_conexao.php';
            
            const acaoInput = document.createElement('input');
            acaoInput.type = 'hidden';
            acaoInput.name = 'acao';
            acaoInput.value = 'concluir';
            
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'id_conexao';
            idInput.value = idConexao;
            
            form.appendChild(acaoInput);
            form.appendChild(idInput);
            document.body.appendChild(form);
            form.submit();
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {
  const botoesCancelar = document.querySelectorAll(".btn-cancelar");

  botoesCancelar.forEach(btn => {
    btn.addEventListener("click", function () {
      if (!confirm("Deseja realmente rejeitar esta solicitação?")) return;

      // Correção: usar a sintaxe correta para dataset
      const idSolicitacao = btn.dataset.idSolicitacao || btn.getAttribute('data-id-solicitacao');

      if (!idSolicitacao) {
        alert("ID da solicitação não encontrado.");
        console.error("Botão:", btn);
        return;
      }

      fetch(`../server/conexao/gerenciar_solicitacao.php?acao=recusar&id=${idSolicitacao}`)
        .then(res => {
          if (!res.ok) {
            throw new Error('Erro na resposta do servidor');
          }
          return res.text();
        })
        .then(response => {
          alert("Solicitação rejeitada com sucesso.");
          location.reload();
        })
        .catch(err => {
          console.error("Erro detalhado:", err);
          alert("Erro ao processar a solicitação: " + err.message);
        });
    });
  });
});




document.querySelectorAll('.criarvaga').forEach(botao => {
  botao.addEventListener('click', () => {
    const targetId = botao.getAttribute('data-modal');
    const modal = document.getElementById(targetId);
    if (modal) {
      modal.style.display = 'flex';
    }
  });
});

document.querySelectorAll('.btnfecharmodal').forEach(botao => {
  botao.addEventListener('click', () => {
    const modal = botao.closest('.modalvaga');
    if (modal) {
      modal.style.display = 'none';
    }
  });
});

function fecharModal() {
  document.getElementById("modalCandidatos").style.display = "none";
}


// Fecha o modal
document.querySelector('.close-modal').addEventListener('click', function () {
  document.getElementById('modalCandidatos').style.display = 'none';
});

// Seletores dos itens de menu
const liddscadastro = document.querySelector(".liddscadastro");
const lisolicitacoes = document.querySelector(".lisolicitacoes");
const liconexoes = document.querySelector(".liconexoes");
const livagas = document.querySelector(".livagas");

// Seletores dos artigos
const artcadastro = document.querySelector(".artcadastro");
const artsolicitacoes = document.querySelector(".artsolicitacoes");
const artconexoes = document.querySelector(".artconexoes");
const artvagas = document.querySelector(".artvagas");

// Função para resetar os backgrounds dos menus
function resetarBackground() {
  liddscadastro.style.background = "";
  lisolicitacoes.style.background = "";
  liconexoes.style.background = "";

  liddscadastro.style.color = "";
  lisolicitacoes.style.color = "";
  liconexoes.style.color = "";

  livagas.style.background = "";
  livagas.style.color = "";


  liddscadastro.classList.remove("ativo");
  liconexoes.classList.remove("ativo");
  lisolicitacoes.classList.remove("ativo");
  livagas.classList.remove("ativo");
}
const botaoToggle = document.querySelector(".tammenu");
const menuLateral = document.querySelector(".menulateral");
const mainContent = document.querySelector("main");

botaoToggle.addEventListener("click", function () {
  menuLateral.classList.toggle("escondido");
  mainContent.classList.toggle("expandido");
  menuLateral.classList.toggle("fechar");
});
// Mostrar seção Cadastro
liddscadastro.addEventListener("click", function () {
  artcadastro.style.display = "flex";
  artsolicitacoes.style.display = "none";
  artconexoes.style.display = "none";
  artvagas.style.display = "none";

  resetarBackground();
  liddscadastro.style.background = "#00DE8A";
  liddscadastro.style.color = "black"
  liddscadastro.classList.add("ativo");
});

// Mostrar seção Conectar
lisolicitacoes.addEventListener("click", function () {
  artcadastro.style.display = "none";
  artsolicitacoes.style.display = "flex";
  artconexoes.style.display = "none";
  artvagas.style.display = "none";

  resetarBackground();
  lisolicitacoes.style.background = "#00DE8A";
  lisolicitacoes.style.color = "black"
  lisolicitacoes.classList.add("ativo");
});

// Mostrar seção Conexões
liconexoes.addEventListener("click", function () {
  artcadastro.style.display = "none";
  artsolicitacoes.style.display = "none";
  artvagas.style.display = "none";
  artconexoes.style.display = "flex";

  resetarBackground();
  liconexoes.style.background = "#00DE8A";
  liconexoes.style.color = "black"
  liconexoes.classList.add("ativo");
});

livagas.addEventListener("click", function () {
  artvagas.style.display = "flex";
  artcadastro.style.display = "none";
  artsolicitacoes.style.display = "none";
  artconexoes.style.display = "none";

  resetarBackground();
  livagas.style.background = "#00DE8A";
  livagas.style.color = "black";
  livagas.classList.add("ativo");
});

btnvaga = document.querySelector(".criarvaga");
modalvaga = document.querySelector(".modalvaga");
btnfecharmodal = document.querySelector(".btnfecharmodal");

btnvaga.addEventListener("click", function () {
  modalvaga.style.display = "flex";
});
btnfecharmodal.addEventListener("click", function () {
  modalvaga.style.display = "none";
});



const btneditvaga = document.querySelector(".editvaga");
const modaleditvaga = document.querySelector(".modaleditvaga");
const btnfechareditmodal = document.querySelector(".btnfechareditmodal");

// Abrir modal editar vaga
if (btneditvaga && modaleditvaga) {
  btneditvaga.onclick = () => modaleditvaga.style.display = "flex";
}

// Fechar modal editar vaga
if (btnfechareditmodal && modaleditvaga) {
  btnfechareditmodal.onclick = () => modaleditvaga.style.display = "none";
}




const logoutLink = document.querySelector('.logout-link');
const ulogado = document.querySelector('.ulogado');

logoutLink.addEventListener("mouseenter", function () {
  ulogado.classList.add(".mover");
});

logoutLink.addEventListener("mouseleave", function () {
  ulogado.classList.remove(".mover");
});





function confirmarEncerramento() {
  return confirm("Deseja realmente desativar sua conta?");
}