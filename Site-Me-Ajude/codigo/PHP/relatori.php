<?php
require('fpdf.php');

// Criar uma nova instância do FPDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Título do relatório
$pdf->Cell(0, 10, 'Relatório de Doações', 0, 1, 'C');

// Adicionar uma linha em branco
$pdf->Ln(10);

// Definir cabeçalhos da tabela
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(40, 10, 'Nome', 1);
$pdf->Cell(50, 10, 'E-mail', 1);
$pdf->Cell(50, 10, 'Data de Nascimento', 1);
$pdf->Ln();

// Dados dos usuários
$usuarios = [
    ['nome' => 'João Silva', 'email' => 'joao@email.com', 'data_nascimento' => '1990-01-15'],
    ['nome' => 'Maria Oliveira', 'email' => 'maria@email.com', 'data_nascimento' => '1985-05-22'],
];

// Adicionar os dados à tabela
$pdf->SetFont('Arial', '', 12);
foreach ($usuarios as $usuario) {
    $pdf->Cell(40, 10, $usuario['nome'], 1);
    $pdf->Cell(50, 10, $usuario['email'], 1);
    $pdf->Cell(50, 10, $usuario['data_nascimento'], 1);
    $pdf->Ln();
}

// Salvar o PDF
$pdf->Output('D', 'relatorio_usuarios.pdf'); // 'D' para download
?>

