<?php

require('./fpdf.php');

class PDF extends FPDF
{
   // Cabecera de página
   function Header()
   {
       // Logo 1 en el extremo izquierdo (5mm de margen)
       $this->Image('logo1.png', 5, 5, 45); // Logo 1, tamaño aumentado a 30px
   
       // Logo 2 en el extremo derecho
       $this->Image('logo2.png', 165, 5, 45); // Logo 2, tamaño aumentado a 30px
   
       // Título del documento
       $this->SetFont('Arial', 'B', 19);
       $this->Cell(45); // Mover a la derecha
       $this->SetTextColor(0, 0, 0); // Color del texto en negro
       $this->Cell(110, 15, utf8_decode('CHIPITICHOTAS'), 1, 1, 'C', 0); // AnchoCelda, AltoCelda, título, borde(1-0), saltoLinea(1-0), posición(L-C-R), ColorFondo(1-0)
   
       $this->Ln(3); // Salto de línea
       $this->SetTextColor(0, 0, 0); // Color del texto para el resto del contenido (negro)
   }
   
   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
      $hoy = date('d/m/Y');
      $this->Cell(355, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
   }
}

$pdf = new PDF();
$pdf->AddPage(); /* aquí entran dos para parámetros (orientación,tamaño) V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la página / y total de páginas

$pdf->SetFont('Arial', '', 12);

// Aquí comienza el apartado con los textos que me proporcionaste:

// Título del apartado
$pdf->SetTextColor(173, 216, 230); // Azul pastel (RGB: 173, 216, 230)
$pdf->SetFont('Arial', 'B', 15);
$pdf->Cell(50); // mover a la derecha
$pdf->Cell(100, 10, utf8_decode("SABÍAS QUE..."), 0, 1, 'C', 0);
$pdf->Ln(7);

// Contenido
$content = [
    "Criptografía Cuántica: La criptografía cuántica utiliza las propiedades de la física cuántica, como la superposición y el entrelazamiento, para crear sistemas de comunicación prácticamente inviolables. Aunque aún está en sus etapas iniciales, se espera que las computadoras cuánticas puedan romper los sistemas criptográficos actuales, lo que ha impulsado la investigación en métodos como el cifrado post-cuántico.",
    "Blockchain y Criptomonedas: Blockchain, la tecnología detrás de Bitcoin y otras criptomonedas, no solo asegura los datos, sino que protege todo un sistema económico descentralizado. Además de las criptomonedas, blockchain se está utilizando para mejorar la seguridad en áreas como la trazabilidad de cadenas de suministro, contratos inteligentes y votaciones digitales.",
    "Criptografía Homomórfica: La criptografía homomórfica permite realizar cálculos sobre datos cifrados sin descifrarlos, lo que ofrece un nivel de privacidad excepcional. Aunque todavía enfrenta desafíos en términos de eficiencia, se espera que revolucione sectores como la salud y la inteligencia, donde la privacidad es fundamental.",
];


foreach ($content as $paragraph) {
    $pdf->MultiCell(0, 10, utf8_decode($paragraph)); // Agregar el contenido del texto
    $pdf->Ln(5); // Salto de línea
}

// Generar el PDF
$pdf->Output('Prueba.pdf', 'I'); //nombreDescarga, Visor(I->visualizar - D->descargar)
?>
