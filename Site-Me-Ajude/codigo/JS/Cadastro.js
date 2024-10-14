function validarFormulario() {
    const cpf = document.getElementById('cpf').value;
    const telefone = document.getElementById('telefone').value;
    const cep = document.getElementById('cep').value;
    const dataNascimento = document.getElementById('dataNascimento').value;

    // Validação do CPF (deve ter 11 dígitos)
    if (cpf.length !== 11 || isNaN(cpf)) {
        alert('CPF deve conter exatamente 11 dígitos numéricos.');
        return false;
    }

    // Validação do telefone (deve ter 11 dígitos)
    if (telefone.length !== 11 || isNaN(telefone)) {
        alert('Telefone deve conter exatamente 11 dígitos numéricos.');
        return false;
    }

    // Validação do CEP (deve ter 8 dígitos)
    if (cep.length !== 8 || isNaN(cep)) {
        alert('CEP deve conter exatamente 8 dígitos numéricos.');
        return false;
    }

    // Validação da data de nascimento (mais de 18 anos e um valor válido)
    const dataAtual = new Date();
    const dataNascimentoObj = new Date(dataNascimento);
    const anoNascimento = dataNascimentoObj.getFullYear();
    
    if (anoNascimento < 1908 || (dataAtual.getFullYear() - anoNascimento) < 18) {
        alert('A data de nascimento deve ser válida e a pessoa deve ter mais de 18 anos.');
        return false;
    }

    return true; // Se todas as validações passarem, o formulário será enviado
}
