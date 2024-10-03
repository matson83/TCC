
const transcricao = json($transcricaoAjustada); // Obtém a transcrição ajustada do backend
const display = document.getElementById('transcricao');
const speed = 35; // Velocidade da digitação (milissegundos por caractere)

// Funções para salvar e carregar o estado do localStorage
function saveState(index, isCompleted) {
    localStorage.setItem('typingIndex', index);
    localStorage.setItem('isTypingCompleted', isCompleted);
}

function loadState() {
    const index = parseInt(localStorage.getItem('typingIndex'), 10) || 0;
    const isCompleted = localStorage.getItem('isTypingCompleted') === 'true';
    return { index, isCompleted };
}

const { index: initialIndex, isCompleted: initialCompleted } = loadState();
let index = initialIndex;
let isTypingCompleted = initialCompleted;

// Função para digitar a transcrição, com efeito de digitação letra por letra
function type() {
    if (index < transcricao.length) {
        display.innerHTML += transcricao.charAt(index);
        index++;
        saveState(index, false); // Salva o progresso da digitação
        setTimeout(type, speed);
    } else {
        isTypingCompleted = true;
        saveState(index, true); // Marca como completada
    }
}

// Função para exibir o conteúdo imediatamente
function showCompleteTranscription() {
    display.style.display = 'block';
    display.innerHTML = transcricao;
    isTypingCompleted = true;
    saveState(transcricao.length, true); // Salva como completada
}

// Verifica se a transcrição já foi completamente exibida
document.addEventListener('DOMContentLoaded', function () {
    if (!isTypingCompleted) {
        display.innerHTML = transcricao.slice(0, index); // Carrega o progresso já feito
        display.style.display = 'block'; // Exibe o parágrafo
        type(); // Inicia a digitação
    } else {
        // Se a digitação já foi completada, exibe diretamente
        showCompleteTranscription();
    }
});
