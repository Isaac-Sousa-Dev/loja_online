function showToast(message, type) {
    Toastify({
        text: message,
        duration: 4000, // duração em milissegundos
        gravity: 'top', // posição do toast
        stopOnFocus: true,
        position: 'right', // alinhamento horizontal do toast
        style: {
            background: type === 'success' ? 'green' : 'red',
            height: '50px',
            color: 'white',
            display: 'flex',
            margin: '10px',
            marginTop: '63px',
            padding: '20px',
            alignItems: 'center',
            justifyContent: 'center',
            position: 'absolute',
            right: '0',
            borderRadius: '10px',
            boxShadow: '0 0 10px rgba(0, 0, 0, 0.1)',
            animation: 'slideInRight 0.5s',
            overflow: 'hidden',
        }
    }).showToast();
}

// Função para mostrar um toast de sucesso
function showSuccessToast(message) {
    showToast(message, 'success');
}

// Função para mostrar um toast de erro
function showErrorToast(message) {
    showToast(message, 'error');
}
