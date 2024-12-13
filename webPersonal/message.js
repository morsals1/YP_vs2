function showElement(htmlContent) {
    const existingBox = document.querySelector('.result-box');
    if (existingBox) {
        existingBox.remove();
    }

    const box = document.createElement('div');
    box.className = 'result-box';
    box.style.position = 'fixed';
    box.style.top = '65%';
    box.style.left = '50%';
    box.style.transform = 'translateX(-50%)';
    box.style.padding = '10px 20px';
    box.style.backgroundColor = '#ffffff';
    box.style.borderRadius = '8px';
    box.style.zIndex = '1000';

    box.innerHTML = htmlContent;
    document.body.appendChild(box);
}

function showMessage(message, isError = false) {
    const messageBox = document.createElement('div');
    messageBox.style.position = 'fixed';
    messageBox.style.top = '20px';
    messageBox.style.left = '50%';
    messageBox.style.transform = 'translateX(-50%)';
    messageBox.style.padding = '10px 20px';
    messageBox.style.backgroundColor = isError ? '#ff4c4c' : '#4caf50';
    messageBox.style.color = 'white';
    messageBox.style.borderRadius = '5px';
    messageBox.style.zIndex = '1000';
    messageBox.textContent = message;

    document.body.appendChild(messageBox);

    setTimeout(() => {
        messageBox.remove();
    }, 3000);
}

function handleFormSubmit(event) {
    event.preventDefault();

    const form = document.querySelector('form');
    const formData = new FormData(form);

    fetch('seach.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(htmlContent => {
        if (htmlContent.includes('Информация о книге не найдена')) {
            showMessage('Информация о книге не найдена', true);
        } else {
            showElement(htmlContent);
        }
    })
    .catch(error => {
        showMessage('Ошибка при выполнении запроса', true);
    });
}

window.onload = function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', handleFormSubmit);
};
