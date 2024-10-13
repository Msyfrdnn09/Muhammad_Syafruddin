let display = document.getElementById('display');
let currentInput = '';

function append(value) {
    // Cegah input ganda yang tidak diinginkan
    if (display.innerText === '0' && value !== '.') {
        display.innerText = '';
    }

    // Cegah operator ganda yang tidak valid
    const lastChar = currentInput.slice(-1);
    if (isOperator(lastChar) && isOperator(value)) {
        currentInput = currentInput.slice(0, -1); // Ganti operator sebelumnya
    }

    display.innerText += value;
    currentInput += value;
}

function clearDisplay() {
    display.innerText = '0';
    currentInput = '';
}

// Cek apakah karakter terakhir adalah operator
function isOperator(char) {
    return ['+', '-', '*', '/', '%', '^'].includes(char);
}

function calculate() {
    try {
        // Ganti operator pangkat (^) dengan operator JavaScript (**)
        let expression = currentInput.replace(/\^/g, '**');

        // Gunakan Function constructor untuk keamanan (menghindari eval())
        let result = new Function('return ' + expression)();

        // Tampilkan hasil di layar
        display.innerText = result;
        currentInput = result.toString();
    } catch (error) {
        // Jika terjadi error, tampilkan pesan kesalahan
        display.innerText = 'Error';
        currentInput = '';
    }
}
