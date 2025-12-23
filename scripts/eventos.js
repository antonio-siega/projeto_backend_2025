document.addEventListener('DOMContentLoaded', function () {
    const eventosList = document.getElementById('eventos-list');
    const createEventoForm = document.getElementById('create-evento-form');

    const API_URL = '../src/api/eventos.php';

    function fetchEventos() {
        fetch(API_URL)
            .then(response => response.json())
            .then(data => {
                eventosList.innerHTML = '';
                if (data.records) {
                    data.records.forEach(evento => {
                        const eventoDiv = document.createElement('div');
                        eventoDiv.innerHTML = `
                            <h3>${evento.nome}</h3>
                            <p>${evento.descricao}</p>
                            <p><strong>Início:</strong> ${evento.data_inicio}</p>
                            <p><strong>Fim:</strong> ${evento.data_fim}</p>
                        `;
                        eventosList.appendChild(eventoDiv);
                    });
                } else {
                    eventosList.innerHTML = '<p>Nenhum evento encontrado.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching events:', error);
                eventosList.innerHTML = '<p>Erro ao carregar eventos.</p>';
            });
    }

    createEventoForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(createEventoForm);
        const data = Object.fromEntries(formData.entries());

        fetch(API_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(result => {
            console.log(result);
            fetchEventos();
            createEventoForm.reset();
        })
        .catch(error => {
            console.error('Error creating event:', error);
        });
    });

    fetchEventos();
});
