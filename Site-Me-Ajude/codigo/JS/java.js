

// Função responsável por gerar o QR Code
function generateQRCode() {
    // Coleta os valores dos campos 'name', 'cpf', 'amount' e 'payment-method' do formulário
    const name = document.getElementById('name').value.trim();
    const cpf = document.getElementById('cpf').value.trim();
    const amount = document.getElementById('amount').value.trim();
    const paymentMethod = document.getElementById('payment-method').value;

    // Verifica se todos os campos obrigatórios estão preenchidos
    if (!name || !cpf || !amount) {
        alert('Por favor, preencha todos os campos obrigatórios antes de gerar o QR Code.'); // Alerta se algum campo estiver vazio
        return; // Para a execução do código se a validação falhar
    }

    // Cria os dados para o QR Code com as informações preenchidas
    const qrCodeData = `Nome: ${name}\nCPF: ${cpf}\nValor: R$${amount}\nMétodo de Pagamento: ${paymentMethod}`;
    
    // Gera o QR Code no canvas especificado (assumindo que uma biblioteca como 'QRCode' esteja sendo usada)
    QRCode.toCanvas(document.getElementById('qrcode'), qrCodeData, function (error) {
        if (error) console.error(error); // Exibe erros no console se houver algum problema na geração do QR Code
        console.log('QR Code gerado com sucesso!'); // Log de sucesso ao gerar o QR Code
    });
}

// Função para exibir uma prévia de imagem no campo especificado
function previewImage(event) {
    var reader = new FileReader(); // Cria um novo objeto FileReader para ler o arquivo

    // Define o que fazer quando o arquivo for carregado
    reader.onload = function() {
        var output = document.getElementById('preview'); // Seleciona o elemento onde a imagem será exibida
        output.src = reader.result; // Define o 'src' da imagem para o resultado do arquivo carregado
        output.style.display = 'block'; // Exibe o elemento de imagem (caso esteja oculto)
    };
    
    // Lê o arquivo selecionado pelo input de arquivos
    reader.readAsDataURL(event.target.files[0]);
}

/*-------------------------------------------------------------------------------------------------------------- */
       
