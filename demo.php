<?php

require_once __DIR__ . '/vendor/autoload.php';

use Fpdf\Fpdf;

// Create a new FPDF instance
$pdf = new FPDF();

// Add a page
$pdf->AddPage();

// Set font for the title
$pdf->SetFont('Arial', 'B', 16);

// Title
$pdf->Cell(0, 10, 'Hello, World!', 0, 1, 'C');

// Set font for the content
$pdf->SetFont('Arial', '', 12);

// Content
$pdf->Cell(0, 10, 'This is a sample PDF document generated using FPDF with autoloading.', 0, 1, 'L');

// Output PDF to browser
$pdf->Output();
