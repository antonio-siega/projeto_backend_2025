document.getElementById('evento').addEventListener('change', function() {
    var eventoId = this.value;
    var setorSelect = document.getElementById('setor');
    
    // Limpa e desabilita o segundo select enquanto carrega
    setorSelect.innerHTML = '<option value="">Carregando...</option>';
    setorSelect.disabled = true;

    if (categoriaId) {
        // Faz a requisição AJAX (fetch) para o script PHP
        fetch('get_eventos.php?evento_id=' + eventoId)
            .then(response => response.json())
            .then(data => {
                // Preenche o segundo select com as opções retornadas
                setorSelect.innerHTML = '<option value="">Selecione o produto</option>';
                data.forEach(evento => {
                    var option = document.createElement('option');
                    option.value = evento.id;
                    option.textContent = evento.nome;
                    eventoSelect.appendChild(option);
                });
                eventoSelect.disabled = false;
            })
            .catch(error => {
                console.error('Erro ao buscar eventos:', error);
                eventoSelect.innerHTML = '<option value="">Erro ao carregar</option>';
            });
    } else {
        // Se nenhuma categoria válida for selecionada, reseta o segundo select
        eventoSelect.innerHTML = '<option value="">Selecione a categoria primeiro</option>';
    }
});
