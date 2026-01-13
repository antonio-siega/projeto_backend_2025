// script_checkin.js
document.getElementById('checkinForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const feedback = document.getElementById('feedback');
    const input = document.getElementById('token_input');
    const formData = new FormData(this);

    fetch('validar_chekin.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        feedback.className = ''; 
        feedback.classList.add(data.status === 'sucesso' ? 'success' : 'error');
        
        let html = `<strong>${data.mensagem}</strong>`;
        if(data.detalhes) {
            html += `<br><small>Titular: ${data.detalhes.nome} | Evento: ${data.detalhes.evento}</small>`;
        }
        
        feedback.innerHTML = html;
        input.value = ''; 
        input.focus();
    })
    .catch(error => {
        console.error('Erro:', error);
        feedback.className = 'error';
        feedback.innerHTML = 'Erro de comunicação com o servidor.';
    });
});