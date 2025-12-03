// Obtém os formulários de login e cadastro do HTML
const loginForm = document.getElementById("login");
const registerForm = document.getElementById("cadastrar");
// Recupera do localStorage qual formulário deve ser exibido
const choice = localStorage.getItem("form");
// Exibe o formulário correto com base na escolha armazenada
if (choice === "cadastrar") {
  loginForm.style.display = "none";
  registerForm.style.display = "flex";
} else {
  registerForm.style.display = "none";
  loginForm.style.display = "flex";
}
// Remove a escolha salva, para não afetar a próxima visita
localStorage.removeItem("form");
// Cria atalho para document.getElementById
const $ = (id) => document.getElementById(id);
// Referências dos campos do formulário (checkbox, mensagem de erro, senhas, telefone)
const termsCheckbox = $("check");
const errorMsg = $("msg");
const passwordInput = $("password");
const passwordConfirmInput = $("passwordConfirm");
const contactInput = $("contact");
// Função para mostrar mensagem de erro
function showError(message) {
  errorMsg.textContent = message;
  errorMsg.style.display = "flex";
}
// Função para esconder a mensagem de erro
function hideError() {
  errorMsg.textContent = "";
  errorMsg.style.display = "none";
}
// Função principal para validar o formulário
function validateForm() {
  hideError();
  // Verifica se os termos foram aceitos
  if (termsCheckbox && !termsCheckbox.checked) {
    showError("Concorde com os termos.");
    return false;
  }
  // Verifica se as senhas são iguais
  const password = passwordInput.value;
  const passwordConfirm = passwordConfirmInput.value;

  if (password !== passwordConfirm) {
    showError("As senhas precisam ser iguais.");
    return false;
  }
  // Remove caracteres não numéricos e verifica se o telefone tem 13 dígitos
  const digits = contactInput.value.replace(/\D/g, "");

  if (digits.length !== 13) {
    showError("Telefone incompleto.");
    return false;
  }

  return true;
}
// Esconde o erro ao marcar/desmarcar os termos
if (termsCheckbox) {
  termsCheckbox.addEventListener("change", hideError);
}
// Remove o erro quando o usuário começa a digitar as senhas
passwordInput.addEventListener("input", hideError);
passwordConfirmInput.addEventListener("input", hideError);
// Verificação em tempo real se as senhas coincidem
if (passwordInput && passwordConfirmInput) {
  const checkPasswordsLive = () => {
    if (passwordConfirmInput.value.length === 0) {
      hideError();
      return;
    }
    // Função que compara senhas enquanto o usuário digita
    if (passwordInput.value !== passwordConfirmInput.value) {
      showError("As senhas precisam ser iguais.");
    } else {
      hideError();
    }
  };

  passwordInput.addEventListener("input", checkPasswordsLive);
  passwordConfirmInput.addEventListener("input", checkPasswordsLive);
}
// Formatação automática do telefone durante a digitação
if (contactInput) {
  contactInput.addEventListener("input", () => {
    hideError();

    let digits = contactInput.value.replace(/\D/g, "");

    if (!digits.startsWith("55")) {
      digits = "55" + digits;
    }

    digits = digits.slice(0, 13);

    let formatted = digits
      .replace(/^(\d{2})(\d)/, "$1($2")
      .replace(/^(\d{2})\((\d{2})(\d)/, "$1($2)$3")
      .replace(/^(\d{2})\((\d{2})\)(\d)(\d{4})(\d)/, "$1($2)$3$4-$5");

    contactInput.value = formatted;
  });

  contactInput.addEventListener("paste", (ev) => {
    ev.preventDefault();
    const pasted = (ev.clipboardData || window.clipboardData).getData("text");
    const onlyNums = pasted.replace(/\D/g, "").slice(0, 13);
    contactInput.value = onlyNums;
    contactInput.dispatchEvent(new Event("input"));
  });
}
// Abre o modal com os termos
function openModal() {
  document.getElementById("modalTerms").style.display = "flex";
}

window.onclick = (e) => {
  const modal = document.getElementById("modalTerms");
  if (e.target === modal) modal.style.display = "none";
};
