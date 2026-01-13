const openModalButton = document.getElementById("openModal");
const modalWrapper = document.getElementById("termsModal");
const mainModal = modalWrapper.querySelector(".main-modal");
const secondaryModal = document.getElementById("secondaryModal");
const secondaryTitle = document.getElementById("secondaryTitle");
const secondaryContent = document.getElementById("secondaryContent");

openModalButton.addEventListener("click", () => {
  modalWrapper.classList.add("active");
  secondaryModal.classList.remove("active");

  mainModal.style.display = "flex";
});

modalWrapper.addEventListener("click", (event) => {
  const clickedOutsideMain = !mainModal.contains(event.target);
  const clickedOutsideSecondary =
    !secondaryModal.contains(event.target) ||
    !secondaryModal.classList.contains("active");

  if (clickedOutsideMain && clickedOutsideSecondary) {
    modalWrapper.classList.remove("active");
    secondaryModal.classList.remove("active");
  }
});

const modalConfig = {
  edit: {
    title: "Editar conta",
    render: () => `
      <div class="input-wrapper">
        <span class="input-label">Novo nome</span>
        <input type="text" placeholder="Insira seu novo nome" />
      </div>
      <div class="input-wrapper">
        <span class="input-label">Novo email</span>
        <input type="text" placeholder="Insira seu novo email" />
      </div>
      <div class="input-wrapper">
        <span class="input-label">Nova senha</span>
        <input type="text" placeholder="Insira sua nova senha" />
      </div>
      <button class="button">Confirmar</button>
    `,
  },

  create: {
    title: "Criar evento",
    render: () => `
      <div class="input-wrapper">
        <span class="input-label">Nome</span>
        <input type="text" placeholder="Insira o nome do evento" required />
      </div>
      <div class="input-wrapper">
        <span class="input-label">Palestrante</span>
        <input type="text" placeholder="Insira o nome do palestrante" required />
      </div>
      <div class="input-wrapper">
        <span class="input-label">Local</span>
        <input type="text" placeholder="Insira o local" required />
      </div>
      <div class="input-wrapper">
        <span class="input-label">Data</span>
        <input type="date" class="date-input default-date" required />
      </div>
      <button class="button">Criar</button>
    `,
  },

  join: {
    title: "Entrar em um evento",
    render: () => `
      <div class="input-wrapper">
        <span class="input-label">Código</span>
        <input type="text" placeholder="Insira o código" required />
      </div>
      <button class="button">Entrar</button>
    `,
  },
};

function getTodayISO() {
  const today = new Date();
  today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
  return today.toISOString().split("T")[0];
}

document.querySelectorAll("[data-action]").forEach((button) => {
  button.addEventListener("click", (event) => {
    event.stopPropagation();

    const action = button.dataset.action;
    const config = modalConfig[action];

    secondaryTitle.textContent = config.title;
    secondaryContent.innerHTML = config.render();
    secondaryModal.classList.add("active");

    const today = getTodayISO();

    secondaryContent.querySelectorAll(".date-input").forEach((input) => {
      input.value = today;
      input.min = today;
      input.classList.add("default-date");

      input.addEventListener("input", () => {
        input.classList.remove("default-date");
      });
    });
  });
});

function initCardModal() {
  const cards = document.querySelectorAll(".card");

  cards.forEach((card) => {
    card.addEventListener("click", (event) => {
      event.stopPropagation();

      modalWrapper.classList.add("active");

      mainModal.style.display = "none";

      secondaryModal.classList.add("active");

      secondaryTitle.textContent = "Preview do card";
      secondaryContent.innerHTML = `
        <p>Você clicou no card:</p>
        <strong>${card.textContent}</strong>
      `;
    });
  });
}

document.addEventListener("DOMContentLoaded", () => {
  initCardModal();
});
