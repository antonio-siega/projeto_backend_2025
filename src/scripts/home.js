const button = document.getElementById("settings-button");
const dropdown = document.getElementById("dropdown");

// Abrir e fechar quando clicar no botão
button.addEventListener("click", (event) => {
  event.stopPropagation();
  dropdown.classList.toggle("active");
});

// Fechar quando clicar fora
document.addEventListener("click", (event) => {
  if (!dropdown.contains(event.target) && !button.contains(event.target)) {
    dropdown.classList.remove("active");
  }
});
