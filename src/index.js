document.addEventListener("DOMContentLoaded", () => {
  // Log para confirmar que o script foi carregado corretamente
  console.log("Script index.js carregado e pronto.");

  // Captura dos elementos principais do DOM
  const openModalButton = document.getElementById("openModal");
  const modalWrapper = document.getElementById("termsModal");
  const mainModal = modalWrapper ? modalWrapper.querySelector(".main-modal") : null;
  const secondaryModal = document.getElementById("secondaryModal");
  const secondaryTitle = document.getElementById("secondaryTitle");
  const secondaryContent = document.getElementById("secondaryContent");

  // Lógica para abrir o modal principal (se existir na página)
  if (openModalButton && modalWrapper && mainModal) {
    openModalButton.addEventListener("click", () => {
      modalWrapper.classList.add("active");
      if (secondaryModal) secondaryModal.classList.remove("active");
      mainModal.style.display = "flex";
    });
  }

  // Lógica para fechar o modal ao clicar fora dele (no fundo escuro)
  if (modalWrapper) {
    modalWrapper.addEventListener("click", (event) => {
      if (!secondaryModal) return;
      
      const clickedOutsideMain = mainModal ? !mainModal.contains(event.target) : true;
      const clickedOutsideSecondary =
        !secondaryModal.contains(event.target) ||
        !secondaryModal.classList.contains("active");

      if (clickedOutsideMain && clickedOutsideSecondary) {
        modalWrapper.classList.remove("active");
        secondaryModal.classList.remove("active");
      }
    });
  }

  // Configuração de cada funcionalidade do sistema (Modais Dinâmicos)
  const modalConfig = {
    // Tela de edição de perfil do usuário
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

    // Cadastro de Organizações (Instituições que promovem eventos)
    organization: {
      title: "Cadastrar Organização",
      render: () => `
        <form id="createOrgForm">
          <div class="input-wrapper">
            <span class="input-label">Nome da Instituição</span>
            <input type="text" name="nome" placeholder="Ex: IF Campus..." required />
          </div>
          <div class="input-wrapper">
            <span class="input-label">Contato</span>
            <input type="text" name="contato" placeholder="email@exemplo.com" required />
          </div>
          <button type="submit" class="button">Cadastrar</button>
        </form>
      `,
      init: (container) => {
        const form = container.querySelector("#createOrgForm");
        form.addEventListener("submit", async (e) => {
          e.preventDefault();
          const data = Object.fromEntries(new FormData(form).entries());
          const isSubPage = window.location.pathname.includes('/pages/');
          const apiPath = isSubPage ? "../../api/organizacoes.php" : "api/organizacoes.php";
          try {
              const response = await fetch(apiPath, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data),
              });
              if (response.ok) {
                alert("Organização cadastrada com sucesso!");
                modalWrapper.classList.remove("active");
                secondaryModal.classList.remove("active");
              }
          } catch(err) { console.error(err); }
        });
      }
    },

    // Gerenciamento de Locais Físicos (Auditórios, Arenas, etc.)
    local: {
      title: "Gerenciar Locais",
      render: () => `
        <div id="localManagementContent">
            <button class="button" id="btnAddNewLocal" style="margin-bottom: 2rem;">+ Novo Local</button>
            <div id="localsList" style="display: flex; flex-direction: column; gap: 1rem;">
                <p>Carregando locais...</p>
            </div>
        </div>
        <form id="localForm" style="display: none;">
            <input type="hidden" name="id" id="localId" />
            <div class="input-wrapper">
                <span class="input-label">Endereço/Nome do Local</span>
                <input type="text" name="endereco" id="localEndereco" placeholder="Ex: Auditório B" required />
            </div>
            <div class="input-wrapper">
                <span class="input-label">Capacidade Total</span>
                <input type="number" name="capacidade_total" id="localCapacidade" placeholder="Ex: 200" required />
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap;">
                <button type="submit" class="button" id="btnSaveLocal">Salvar</button>
                <button type="button" class="button" id="btnDeleteLocalForm" style="background: #ff4d4d; display: none;">Excluir Local</button>
                <button type="button" class="button button-secundary" id="btnCancelLocal">Cancelar</button>
            </div>
        </form>
      `,
      init: (container) => {
        const listContainer = container.querySelector("#localsList");
        const managementContent = container.querySelector("#localManagementContent");
        const form = container.querySelector("#localForm");
        const btnAddNew = container.querySelector("#btnAddNewLocal");
        const btnCancel = container.querySelector("#btnCancelLocal");
        const btnDeleteForm = container.querySelector("#btnDeleteLocalForm");
        
        const isSubPage = window.location.pathname.includes('/pages/');
        const apiPath = isSubPage ? "../../api/locais.php" : "api/locais.php";

        // Busca a lista de locais da API
        const loadLocals = async () => {
          try {
            const res = await fetch(apiPath);
            const data = await res.json();
            if (data.records && data.records.length > 0) {
              listContainer.innerHTML = data.records.map(l => `
                <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 0.8rem; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid var(--color-green);">
                  <div>
                    <strong style="font-size: 1.6rem;">${l.endereco}</strong>
                    <p style="font-size: 1.2rem; color: var(--color-gray-100);">${l.capacidade_total} pessoas</p>
                  </div>
                  <div style="display: flex; gap: 0.5rem;">
                      <button class="button button-small btn-edit-local" data-id="${l.id}" data-endereco="${l.endereco}" data-capacidade="${l.capacidade_total}">
                        <i class="ph ph-pencil-simple"></i>
                      </button>
                      <button class="button button-small btn-delete-local" data-id="${l.id}" style="background: #ff4d4d;">
                        <i class="ph ph-trash"></i>
                      </button>
                  </div>
                </div>
              `).join('');

              // Eventos para editar local
              container.querySelectorAll(".btn-edit-local").forEach(btn => {
                btn.addEventListener("click", () => {
                  container.querySelector("#localId").value = btn.dataset.id;
                  container.querySelector("#localEndereco").value = btn.dataset.endereco;
                  container.querySelector("#localCapacidade").value = btn.dataset.capacidade;
                  managementContent.style.display = "none";
                  form.style.display = "block";
                  btnDeleteForm.style.display = "block";
                  secondaryTitle.textContent = "Editar Local";
                });
              });

              // Eventos para excluir local da lista
              container.querySelectorAll(".btn-delete-local").forEach(btn => {
                btn.addEventListener("click", () => deleteLocal(btn.dataset.id));
              });
            } else {
              listContainer.innerHTML = "<p>Nenhum local cadastrado.</p>";
            }
          } catch (err) { console.error(err); }
        };

        // Função reutilizável para excluir local com verificação
        const deleteLocal = async (id) => {
            if (confirm("Tem certeza que deseja excluir este local?")) {
                try {
                    const response = await fetch(apiPath, {
                        method: "DELETE",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ id: id })
                    });
                    const result = await response.json();
                    if (response.ok) {
                        alert(result.message);
                        form.style.display = "none";
                        managementContent.style.display = "block";
                        secondaryTitle.textContent = "Gerenciar Locais";
                        loadLocals();
                    } else {
                        alert(result.message); // Exibe mensagem se houver eventos vinculados
                    }
                } catch (err) { console.error(err); }
            }
        };

        btnDeleteForm.addEventListener("click", () => {
            const id = container.querySelector("#localId").value;
            deleteLocal(id);
        });

        btnAddNew.addEventListener("click", () => {
          form.reset();
          container.querySelector("#localId").value = "";
          managementContent.style.display = "none";
          form.style.display = "block";
          btnDeleteForm.style.display = "none";
          secondaryTitle.textContent = "Novo Local";
        });

        btnCancel.addEventListener("click", () => {
          form.style.display = "none";
          managementContent.style.display = "block";
          secondaryTitle.textContent = "Gerenciar Locais";
        });

        // Grava ou Atualiza o Local no banco de dados
        form.addEventListener("submit", async (e) => {
          e.preventDefault();
          const formData = Object.fromEntries(new FormData(form).entries());
          const isUpdate = formData.id !== "";
          
          try {
              const response = await fetch(apiPath, {
                method: isUpdate ? "PUT" : "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData),
              });
              if (response.ok) {
                alert(isUpdate ? "Local atualizado!" : "Local cadastrado!");
                form.style.display = "none";
                managementContent.style.display = "block";
                secondaryTitle.textContent = "Gerenciar Locais";
                loadLocals();
              }
          } catch(err) { console.error(err); }
        });

        loadLocals();
      }
    },

    // Gerenciamento de Setores vinculados a Eventos (VIP, Pista, etc.)
    setores: {
      title: "Gerenciar Setores",
      render: () => `
        <div id="setorMainContent">
            <div class="input-wrapper">
                <span class="input-label">Selecione o Evento</span>
                <select id="setorEventSelect"><option value="">Carregando eventos...</option></select>
            </div>
            <div id="setorManagementArea" style="display: none; margin-top: 2rem;">
                <hr>
                <button class="button" id="btnAddNewSetorMain" style="margin: 2rem 0;">+ Novo Setor</button>
                <div id="setorsList" style="display: flex; flex-direction: column; gap: 1rem;"></div>
            </div>
        </div>

        <form id="setorMainForm" style="display: none; margin-top: 2rem;">
            <input type="hidden" name="id" id="setorMainId" />
            <input type="hidden" name="evento_id" id="setorMainEventoId" />
            <div class="input-wrapper">
                <span class="input-label">Nome do Setor</span>
                <input type="text" name="nome" id="setorMainNome" placeholder="Ex: Pista, Camarote..." required />
            </div>
            <div class="input-wrapper">
                <span class="input-label">Capacidade</span>
                <input type="number" name="capacidade" id="setorMainCapacidade" placeholder="Ex: 500" required />
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" class="button">Salvar Setor</button>
                <button type="button" class="button button-secundary" id="btnCancelSetorMain">Cancelar</button>
            </div>
        </form>
      `,
      init: async (container) => {
        const eventSelect = container.querySelector("#setorEventSelect");
        const managementArea = container.querySelector("#setorManagementArea");
        const listContainer = container.querySelector("#setorsList");
        const form = container.querySelector("#setorMainForm");
        const mainContent = container.querySelector("#setorMainContent");

        const isSubPage = window.location.pathname.includes('/pages/');
        const apiBase = isSubPage ? "../../api/" : "api/";

        // Carrega a lista de eventos para selecionar qual setor gerenciar
        const evRes = await fetch(apiBase + "eventos.php");
        const evData = await evRes.json();
        eventSelect.innerHTML = '<option value="">Escolha um evento</option>' + 
            evData.records.map(e => `<option value="${e.id}">${e.nome}</option>`).join('');

        const loadSetoresList = async () => {
            const res = await fetch(apiBase + "setores.php?evento_id=" + eventSelect.value);
            const data = await res.json();
            if (data.records) {
                listContainer.innerHTML = data.records.map(s => `
                    <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 0.8rem; border-left: 4px solid var(--color-green); display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="font-size: 1.6rem;">${s.nome}</strong>
                            <p style="font-size: 1.2rem;">Capacidade: ${s.capacidade} pessoas</p>
                        </div>
                        <div style="display: flex; gap: 0.5rem;">
                            <button class="button button-small btn-edit-setor" data-id="${s.id}" data-nome="${s.nome}" data-capacidade="${s.capacidade}">
                                <i class="ph ph-pencil-simple"></i>
                            </button>
                            <button class="button button-small btn-delete-setor" data-id="${s.id}" style="background: #ff4d4d;">
                                <i class="ph ph-trash"></i>
                            </button>
                        </div>
                    </div>
                `).join('');

                container.querySelectorAll(".btn-edit-setor").forEach(btn => {
                    btn.addEventListener("click", () => {
                        container.querySelector("#setorMainId").value = btn.dataset.id;
                        container.querySelector("#setorMainNome").value = btn.dataset.nome;
                        container.querySelector("#setorMainCapacidade").value = btn.dataset.capacidade;
                        mainContent.style.display = "none";
                        form.style.display = "block";
                        secondaryTitle.textContent = "Editar Setor";
                    });
                });

                container.querySelectorAll(".btn-delete-setor").forEach(btn => {
                    btn.addEventListener("click", async () => {
                        if (confirm("Deseja excluir este setor?")) {
                            const res = await fetch(apiBase + "setores.php", {
                                method: "DELETE",
                                body: JSON.stringify({ id: btn.dataset.id })
                            });
                            const result = await res.json();
                            alert(result.message);
                            if (res.ok) loadSetoresList();
                        }
                    });
                });
            } else {
                listContainer.innerHTML = "<p>Nenhum setor cadastrado para este evento.</p>";
            }
        };

        eventSelect.addEventListener("change", () => {
            if (!eventSelect.value) {
                managementArea.style.display = "none";
                return;
            }
            managementArea.style.display = "block";
            loadSetoresList();
        });

        container.querySelector("#btnAddNewSetorMain").addEventListener("click", () => {
            form.reset();
            container.querySelector("#setorMainId").value = "";
            container.querySelector("#setorMainEventoId").value = eventSelect.value;
            mainContent.style.display = "none";
            form.style.display = "block";
            secondaryTitle.textContent = "Novo Setor";
        });

        container.querySelector("#btnCancelSetorMain").addEventListener("click", () => {
            form.style.display = "none";
            mainContent.style.display = "block";
            secondaryTitle.textContent = "Gerenciar Setores";
        });

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const formData = Object.fromEntries(new FormData(form).entries());
            const isUpdate = formData.id !== "";
            const response = await fetch(apiBase + "setores.php", {
                method: isUpdate ? "PUT" : "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData)
            });
            if (response.ok) {
                alert(isUpdate ? "Setor atualizado!" : "Setor criado!");
                form.style.display = "none";
                mainContent.style.display = "block";
                secondaryTitle.textContent = "Gerenciar Setores";
                loadSetoresList();
            }
        });
      }
    },

    // Gerenciamento de Lotes (Preços e Períodos de Venda)
    lotes: {
      title: "Gerenciar Lotes de Ingressos",
      render: () => `
        <div id="loteSelectionStep">
            <div class="input-wrapper">
                <span class="input-label">Selecione o Evento</span>
                <select id="loteEventSelect"><option value="">Carregando...</option></select>
            </div>
            <div class="input-wrapper" id="loteSetorWrapper" style="display: none;">
                <span class="input-label">Selecione o Setor</span>
                <div style="display: flex; gap: 1rem;">
                    <select id="loteSetorSelect" style="flex: 1;"><option value="">Selecione um evento primeiro</option></select>
                    <button class="button" id="btnAddNewSetor" title="Novo Setor" style="padding: 0.5rem 1.5rem;">+</button>
                </div>
            </div>
        </div>

        <div id="setorForm" style="display: none; margin-top: 2rem; background: #f0f0f0; padding: 1.5rem; border-radius: 0.8rem;">
            <h4 style="font-size: 1.6rem; margin-bottom: 1.5rem;">Novo Setor</h4>
            <div class="input-wrapper">
                <span class="input-label">Nome do Setor</span>
                <input type="text" id="setorNome" placeholder="Ex: Pista, Camarote..." />
            </div>
            <div class="input-wrapper">
                <span class="input-label">Capacidade do Setor</span>
                <input type="number" id="setorCapacidade" placeholder="Ex: 500" />
            </div>
            <div style="display: flex; gap: 1rem;">
                <button type="button" class="button" id="btnSaveSetor">Criar Setor</button>
                <button type="button" class="button button-secundary" id="btnCancelSetor">Voltar</button>
            </div>
        </div>

        <div id="loteManagementContent" style="display: none; margin-top: 2rem;">
            <hr>
            <button class="button" id="btnAddNewLote" style="margin: 2rem 0;">+ Novo Lote</button>
            <div id="lotesList" style="display: flex; flex-direction: column; gap: 1rem;"></div>
        </div>

        <form id="loteForm" style="display: none; margin-top: 2rem;">
            <input type="hidden" name="id" id="loteId" />
            <input type="hidden" name="setor_id" id="loteSetorId" />
            <div class="input-wrapper">
                <span class="input-label">Preço (R$)</span>
                <input type="number" step="0.01" name="preco" id="lotePreco" placeholder="0.00" required />
            </div>
            <div class="input-wrapper">
                <span class="input-label">Quantidade de Ingressos</span>
                <input type="number" name="limite" id="loteLimite" placeholder="Ex: 100" required />
            </div>
            <div class="input-wrapper">
                <span class="input-label">Início da Venda</span>
                <input type="date" name="periodo_vigencia_ini" id="loteIni" required />
            </div>
            <div class="input-wrapper">
                <span class="input-label">Fim da Venda</span>
                <input type="date" name="periodo_vigencia_fim" id="loteFim" required />
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                <button type="submit" class="button">Salvar Lote</button>
                <button type="button" class="button button-secundary" id="btnCancelLote">Cancelar</button>
            </div>
        </form>
      `,
      init: async (container) => {
        const eventSelect = container.querySelector("#loteEventSelect");
        const setorSelect = container.querySelector("#loteSetorSelect");
        const setorWrapper = container.querySelector("#loteSetorWrapper");
        const managementContent = container.querySelector("#loteManagementContent");
        const lotesList = container.querySelector("#lotesList");
        const form = container.querySelector("#loteForm");
        const setorForm = container.querySelector("#setorForm");
        const loteSelectionStep = container.querySelector("#loteSelectionStep");

        const isSubPage = window.location.pathname.includes('/pages/');
        const apiBase = isSubPage ? "../../api/" : "api/";

        // Carrega Eventos no Select inicial
        const evRes = await fetch(apiBase + "eventos.php");
        const evData = await evRes.json();
        if (evData.records) {
            eventSelect.innerHTML = '<option value="">Selecione um evento</option>' + 
                evData.records.map(e => `<option value="${e.id}">${e.nome}</option>`).join('');
        }

        const loadSetores = async () => {
            const setRes = await fetch(apiBase + "setores.php?evento_id=" + eventSelect.value);
            const setData = await setRes.json();
            setorSelect.innerHTML = '<option value="">Selecione o setor</option>' + 
                (setData.records ? setData.records.map(s => `<option value="${s.id}">${s.nome}</option>`).join('') : '<option value="">Sem setores</option>');
        };

        eventSelect.addEventListener("change", async () => {
            if (!eventSelect.value) {
                setorWrapper.style.display = "none";
                managementContent.style.display = "none";
                return;
            }
            await loadSetores();
            setorWrapper.style.display = "block";
            managementContent.style.display = "none";
        });

        // Permite criar um novo setor direto da tela de lotes
        container.querySelector("#btnAddNewSetor").addEventListener("click", () => {
            loteSelectionStep.style.display = "none";
            setorForm.style.display = "block";
        });

        container.querySelector("#btnCancelSetor").addEventListener("click", () => {
            setorForm.style.display = "none";
            loteSelectionStep.style.display = "block";
        });

        container.querySelector("#btnSaveSetor").addEventListener("click", async () => {
            const nome = container.querySelector("#setorNome").value;
            const capacidade = container.querySelector("#setorCapacidade").value;
            
            if (!nome || !capacidade) {
                alert("Preencha todos os campos do setor.");
                return;
            }

            const response = await fetch(apiBase + "setores.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    evento_id: eventSelect.value,
                    nome: nome,
                    capacidade: capacidade
                })
            });

            if (response.ok) {
                alert("Setor criado com sucesso!");
                setorForm.style.display = "none";
                loteSelectionStep.style.display = "block";
                await loadSetores();
            }
        });

        setorSelect.addEventListener("change", () => {
            if (!setorSelect.value) {
                managementContent.style.display = "none";
                return;
            }
            managementContent.style.display = "block";
            form.style.display = "none";
            loadLotes();
        });

        const loadLotes = async () => {
            const res = await fetch(apiBase + "lotes.php?setor_id=" + setorSelect.value);
            const data = await res.json();
            if (data.records) {
                lotesList.innerHTML = data.records.map(l => `
                    <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 0.8rem; border-left: 4px solid var(--color-green); display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <strong style="font-size: 1.6rem; color: var(--color-green);">R$ ${l.preco}</strong>
                            <p style="font-size: 1.2rem;">Qtd: ${l.limite} | Até: ${new Date(l.periodo_vigencia_fim).toLocaleDateString()}</p>
                        </div>
                        <button class="button button-small btn-delete-lote" data-id="${l.id}" style="background: #ff4d4d; padding: 0.5rem 1rem;">
                            <i class="ph ph-trash"></i>
                        </button>
                    </div>
                `).join('');

                container.querySelectorAll(".btn-delete-lote").forEach(btn => {
                    btn.addEventListener("click", async () => {
                        if (confirm("Tem certeza que deseja excluir este lote?")) {
                            const response = await fetch(apiBase + "lotes.php", {
                                method: "DELETE",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({ id: btn.dataset.id })
                            });
                            if (response.ok) {
                                alert("Lote excluído!");
                                loadLotes();
                            }
                        }
                    });
                });
            } else {
                lotesList.innerHTML = "<p>Nenhum lote para este setor.</p>";
            }
        };

        container.querySelector("#btnAddNewLote").addEventListener("click", () => {
            form.reset();
            container.querySelector("#loteSetorId").value = setorSelect.value;
            form.style.display = "block";
            managementContent.style.display = "none";
        });

        container.querySelector("#btnCancelLote").addEventListener("click", () => {
            form.style.display = "none";
            managementContent.style.display = "block";
        });

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const data = Object.fromEntries(new FormData(form).entries());
            
            // Ajuste para o formato DATETIME do MySQL
            if (data.periodo_vigencia_ini) data.periodo_vigencia_ini = data.periodo_vigencia_ini + " 00:00:00";
            if (data.periodo_vigencia_fim) data.periodo_vigencia_fim = data.periodo_vigencia_fim + " 23:59:59";
            
            data.status = 'ativo';
            const response = await fetch(apiBase + "lotes.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data)
            });
            if (response.ok) {
                alert("Lote criado!");
                form.style.display = "none";
                managementContent.style.display = "block";
                loadLotes();
            }
        });
      }
    },

    // Gerenciamento Central de Eventos
    create: {
      title: "Gerenciar Eventos",
      render: () => `
        <div id="eventManagementContent">
            <button class="button" id="btnAddNewEvent" style="margin-bottom: 2rem;">+ Novo Evento</button>
            <div id="manageEventsList" style="display: flex; flex-direction: column; gap: 1rem;">
                <p>Carregando eventos...</p>
            </div>
        </div>
        <form id="createEventForm" style="display: none;">
          <div class="input-wrapper">
            <span class="input-label">Nome</span>
            <input type="text" name="nome" placeholder="Insira o nome do evento" required />
          </div>
          <div class="input-wrapper">
            <span class="input-label">Descrição</span>
            <input type="text" name="descricao" placeholder="Insira a descrição" required />
          </div>
          <div class="input-wrapper">
            <span class="input-label">URL da Imagem</span>
            <input type="text" name="imagem_url" placeholder="https://..." />
          </div>
          <div class="input-wrapper">
            <span class="input-label">Organização</span>
            <select name="organizacao_id" id="selectOrg" required>
                <option value="">Carregando...</option>
            </select>
          </div>
          <div class="input-wrapper">
            <span class="input-label">Local</span>
            <select name="local_id" id="selectLocal" required>
                <option value="">Carregando...</option>
            </select>
          </div>
          <div class="input-wrapper">
            <span class="input-label">Data de Início</span>
            <input type="date" name="data_inicio" class="date-input default-date" required />
          </div>
          <div class="input-wrapper">
            <span class="input-label">Data de Fim</span>
            <input type="date" name="data_fim" class="date-input default-date" required />
          </div>
          <div style="display: flex; gap: 1rem; margin-top: 1rem;">
            <button type="submit" class="button">Criar</button>
            <button type="button" class="button button-secundary" id="btnCancelEvent">Cancelar</button>
          </div>
        </form>
      `,
      init: async (container) => {
        const listContainer = container.querySelector("#manageEventsList");
        const managementContent = container.querySelector("#eventManagementContent");
        const form = container.querySelector("#createEventForm");
        const btnAddNew = container.querySelector("#btnAddNewEvent");
        const btnCancel = container.querySelector("#btnCancelEvent");

        const isSubPage = window.location.pathname.includes('/pages/');
        const apiPath = isSubPage ? "../../api/eventos.php" : "api/eventos.php";

        const loadManageEventsList = async () => {
          try {
            const res = await fetch(apiPath);
            const data = await res.json();
            if (data.records && data.records.length > 0) {
              listContainer.innerHTML = data.records.map(e => `
                <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 0.8rem; display: flex; justify-content: space-between; align-items: center; border-left: 4px solid var(--color-green);">
                  <div>
                    <strong style="font-size: 1.6rem;">${e.nome}</strong>
                    <p style="font-size: 1.2rem; color: var(--color-gray-100);">${new Date(e.data_inicio).toLocaleDateString()}</p>
                  </div>
                  <button class="button button-small btn-delete-event" data-id="${e.id}" style="background: #ff4d4d; padding: 0.5rem 1rem;">
                    <i class="ph ph-trash"></i> Excluir
                  </button>
                </div>
              `).join('');

              container.querySelectorAll(".btn-delete-event").forEach(btn => {
                btn.addEventListener("click", async () => {
                  if (confirm("Tem certeza que deseja excluir este evento?")) {
                    try {
                      const response = await fetch(apiPath, {
                        method: "DELETE",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ id: btn.dataset.id })
                      });
                      if (response.ok) {
                        alert("Evento excluído!");
                        loadManageEventsList();
                        if (window.loadEvents) window.loadEvents(); // Atualiza cards na home
                      }
                    } catch (err) { console.error(err); }
                  }
                });
              });
            } else {
              listContainer.innerHTML = "<p>Nenhum evento cadastrado.</p>";
            }
          } catch (err) { console.error(err); }
        };

        btnAddNew.addEventListener("click", () => {
          form.reset();
          managementContent.style.display = "none";
          form.style.display = "block";
          secondaryTitle.textContent = "Novo Evento";
        });

        btnCancel.addEventListener("click", () => {
          form.style.display = "none";
          managementContent.style.display = "block";
          secondaryTitle.textContent = "Gerenciar Eventos";
        });

        // Carrega Orgs e Locais para o formulário de evento
        try {
            const orgPath = isSubPage ? "../../api/organizacoes.php" : "api/organizacoes.php";
            const localPath = isSubPage ? "../../api/locais.php" : "api/locais.php";
            
            const orgSelect = container.querySelector("#selectOrg");
            const orgRes = await fetch(orgPath);
            const orgData = await orgRes.json();
            orgSelect.innerHTML = orgData.records.map(o => `<option value="${o.id}">${o.nome}</option>`).join('');

            const localSelect = container.querySelector("#selectLocal");
            const localRes = await fetch(localPath);
            const localData = await localRes.json();
            localSelect.innerHTML = localData.records.map(l => `<option value="${l.id}">${l.endereco}</option>`).join('');
        } catch(err) { console.error(err); }

        form.addEventListener("submit", async (e) => {
          e.preventDefault();
          const data = Object.fromEntries(new FormData(form).entries());
          if (data.data_inicio) data.data_inicio = data.data_inicio + " 00:00:00";
          if (data.data_fim) data.data_fim = data.data_fim + " 23:59:59";
          data.status = 'publicado';
          data.politica_cancelamento = 'Padrão';

          try {
              const response = await fetch(apiPath, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data),
              });
              if (response.ok) {
                alert("Evento criado com sucesso!");
                form.style.display = "none";
                managementContent.style.display = "block";
                secondaryTitle.textContent = "Gerenciar Eventos";
                loadManageEventsList();
                if (window.loadEvents) window.loadEvents();
              }
          } catch(err) { console.error(err); }
        });

        loadManageEventsList();
      }
    },

    // Busca de Eventos em tempo real
    join: {
      title: "Buscar Evento",
      render: () => `
        <div class="input-wrapper">
          <span class="input-label">Código ou Nome do Evento</span>
          <input type="text" id="eventSearchInput" placeholder="Digite o nome ou código..." />
        </div>
        <div id="searchSuggestions" style="margin-top: 2rem; max-height: 30rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem;">
            <p style="font-size: 1.4rem; color: var(--color-gray-200);">Carregando eventos...</p>
        </div>
      `,
      init: (container) => {
        const input = container.querySelector("#eventSearchInput");
        const suggestions = container.querySelector("#searchSuggestions");

        const fetchAndDisplay = async (filter = "") => {
          const isSubPage = window.location.pathname.includes('/pages/');
          const apiPath = isSubPage ? "../../api/eventos.php" : "api/eventos.php";
          
          try {
            const response = await fetch(apiPath);
            const data = await response.json();
            
            const filtered = data.records.filter(e => 
              e.nome.toLowerCase().includes(filter.toLowerCase()) || 
              e.id.toString() === filter
            );

            if (filtered.length > 0) {
              suggestions.innerHTML = filtered.map(e => `
                <div class="search-result-item" style="padding: 1.5rem; background: #f8f9fa; border-radius: 0.8rem; cursor: pointer; border-left: 4px solid var(--color-green);" onclick="window.location.href='comprar.php?id=${e.id}'">
                  <div style="display: flex; justify-content: space-between; align-items: center;">
                    <strong style="font-size: 1.6rem; color: var(--color-green);">#${e.id} - ${e.nome}</strong>
                    <i class="ph ph-caret-right" style="font-size: 1.8rem;"></i>
                  </div>
                  <p style="font-size: 1.2rem; color: var(--color-gray-100); margin-top: 0.5rem;">${e.descricao.substring(0, 60)}...</p>
                </div>
              `).join('');
            } else {
              suggestions.innerHTML = "<p style='font-size: 1.4rem; text-align: center;'>Nenhum evento encontrado.</p>";
            }
          } catch (err) {
            suggestions.innerHTML = "<p>Erro ao carregar eventos.</p>";
          }
        };

        input.addEventListener("input", (e) => fetchAndDisplay(e.target.value));
        fetchAndDisplay();
      }
    },
  };

  // Helper para pegar a data de hoje no formato ISO (YYYY-MM-DD)
  function getTodayISO() {
    const today = new Date();
    today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
    return today.toISOString().split("T")[0];
  }

  // Listener Global para botões que abrem modais (usando data-action)
  document.querySelectorAll("[data-action]").forEach((button) => {
    button.addEventListener("click", (event) => {
      console.log("Botão clicado:", button.dataset.action);
      event.stopPropagation();

      const action = button.dataset.action;
      const config = modalConfig[action];

      if (!config) return;

      if (secondaryTitle) secondaryTitle.textContent = config.title;
      if (secondaryContent) {
        secondaryContent.innerHTML = config.render();
        if (config.init) {
          config.init(secondaryContent);
        }
      }

      if (modalWrapper) modalWrapper.classList.add("active");
      if (secondaryModal) secondaryModal.classList.add("active");
      if (mainModal) mainModal.style.display = "none";

      const today = getTodayISO();
      if (secondaryContent) {
        secondaryContent.querySelectorAll(".date-input").forEach((input) => {
          input.value = today;
          input.min = today;
          input.classList.add("default-date");

          input.addEventListener("input", () => {
            input.classList.remove("default-date");
          });
        });
      }
    });
  });

  // Função para buscar e exibir os eventos na tela inicial (Home)
  function loadEvents() {
    const container = document.getElementById("eventos-list");
    if (!container) return;

    const isSubPage = window.location.pathname.includes("/pages/");
    const apiPath = isSubPage ? "../../api/eventos.php" : "api/eventos.php";

    console.log("Buscando eventos em:", apiPath);

    fetch(apiPath)
      .then((response) => response.json())
      .then((data) => {
        container.innerHTML = "";
        if (data.records && data.records.length > 0) {
          data.records.forEach((evento) => {
            const card = document.createElement("div");
            card.className = "card";
            
            const cardHeader = evento.imagem_url 
                ? `<div class="card-image-container"><img src="${evento.imagem_url}" class="card-photo"></div>`
                : "";

            card.innerHTML = `
              ${cardHeader}
              <div class="card-content">
                <h3 class="card-title">${evento.nome}</h3>
                <p class="card-description">${evento.descricao}</p>
                <div class="card-footer">
                  <span class="card-date">${new Date(evento.data_inicio).toLocaleDateString()}</span>
                  <button class="button button-small">Ver Detalhes</button>
                </div>
              </div>
            `;
            
            card.addEventListener("click", () => {
                showPreview(evento);
            });

            container.appendChild(card);
          });
        } else {
          container.innerHTML = "<p>Nenhum evento disponível no momento.</p>";
        }
      })
      .catch((error) => {
        console.error("Erro ao carregar eventos:", error);
        container.innerHTML = "<p>Erro ao carregar os eventos.</p>";
      });
  }

  // Exibe o modal detalhado de um evento ao clicar no card
  function showPreview(evento) {
    if (modalWrapper) modalWrapper.classList.add("active");
    if (mainModal) mainModal.style.display = "none";
    if (secondaryModal) {
      secondaryModal.classList.add("active");
      if (secondaryTitle) secondaryTitle.textContent = "Detalhes do Evento";
      
      const dataFormatada = new Date(evento.data_inicio).toLocaleDateString('pt-BR', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric',
        hour: '2-digit', minute: '2-digit'
      });

      if (secondaryContent) {
        const headerContent = evento.imagem_url 
            ? `<img src="${evento.imagem_url}" alt="${evento.nome}" class="event-header-photo">`
            : `<i class="ph-fill ph-ticket"></i>`;

        secondaryContent.innerHTML = `
          <div class="event-details-container">
            <div class="event-header-image">
                ${headerContent}
            </div>
            
            <div class="event-info-section">
                <h2 class="event-detail-title">${evento.nome}</h2>
                <div class="event-status-badge ${evento.status}">${evento.status.toUpperCase()}</div>
                
                <p class="event-detail-description">${evento.descricao}</p>
                
                <div class="event-metadata">
                    <div class="metadata-item">
                        <i class="ph ph-calendar-blank"></i>
                        <span>${dataFormatada}</span>
                    </div>
                    <div class="metadata-item">
                        <i class="ph ph-map-pin"></i>
                        <span>Auditório Principal (Campus IF)</span>
                    </div>
                </div>
            </div>

            <div class="event-action-bar">
                <button class="button" onclick="window.location.href='comprar.php?id=${evento.id}'">
                    <i class="ph-fill ph-shopping-cart"></i>
                    Comprar Ingresso
                </button>
            </div>
          </div>
        `;
      }
    }
  }

  // Torna a função global e inicializa a carga de eventos
  window.loadEvents = loadEvents;
  loadEvents();

  // Inicializa funcionalidade extra de cards se necessário
  function initCardModal() {
    const cards = document.querySelectorAll(".card");
    cards.forEach((card) => {
      card.addEventListener("click", (event) => {
        event.stopPropagation();
        if (modalWrapper) modalWrapper.classList.add("active");
        if (mainModal) mainModal.style.display = "none";
        if (secondaryModal) {
          secondaryModal.classList.add("active");
          if (secondaryTitle) secondaryTitle.textContent = "Preview do card";
          if (secondaryContent) {
            secondaryContent.innerHTML = `
              <p>Você clicou no card:</p>
              <strong>${card.textContent}</strong>
            `;
          }
        }
      });
    });
  }

  initCardModal();
});