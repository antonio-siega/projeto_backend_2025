const loginForm = document.getElementById("loginForm");
const registerForm = document.getElementById("registerForm");
const showRegister = document.getElementById("showRegister");
const showLogin = document.getElementById("showLogin");
const openTerms = document.getElementById("openTerms");
const termsModal = document.getElementById("termsModal");
const closeModal = document.getElementById("closeModal");
const confirmTerms = document.getElementById("confirmTerms");
const termsCheckbox = document.getElementById("termsCheckbox");
const submitButton = document.getElementById("submitButton");
const registerPassword = document.getElementById("registerPassword");
const registerConfirmPassword = document.getElementById(
  "registerConfirmPassword"
);

showRegister.addEventListener("click", (e) => {
  e.preventDefault();
  loginForm.style.display = "none";
  registerForm.style.display = "flex";
});

showLogin.addEventListener("click", (e) => {
  e.preventDefault();
  registerForm.style.display = "none";
  loginForm.style.display = "flex";
});

openTerms.addEventListener("click", (e) => {
  e.preventDefault();
  termsModal.classList.add("active");
});

closeModal.addEventListener("click", () => {
  termsModal.classList.remove("active");
});

confirmTerms.addEventListener("click", () => {
  termsCheckbox.checked = true;
  termsModal.classList.remove("active");
  validateRegisterForm();
});

function validateRegisterForm() {
  const passwordsFilled =
    registerPassword.value && registerConfirmPassword.value;

  const passwordsMatch =
    registerPassword.value === registerConfirmPassword.value;

  submitButton.disabled = !(
    termsCheckbox.checked &&
    passwordsFilled &&
    passwordsMatch
  );
}

registerPassword.addEventListener("input", validateRegisterForm);
registerConfirmPassword.addEventListener("input", validateRegisterForm);
termsCheckbox.addEventListener("change", validateRegisterForm);
