<?php
include "../functions.php";
require('../fpdf/fpdf.php');

// This makes a new .php file for new font - got from google font with polish signs
// require('fpdf/makefont/makefont.php');
// MakeFont('fpdf/font/Aleo-Regular.ttf','ISO-8859-2');

class PDF extends FPDF {
  // Page header
  function Header()
  {
      // Logo
      $this->Image('../img/logo-300x110.jpg',10,8,35, 0,'','http://love-coding.pl');
      // Arial bold 15
      $this->SetFont('Arial','B',15);
      $this->SetTextColor(0);
      // Move to the right
      $this->Cell(35);
      // Title
      if ($_SESSION['lang'] === 'pl') {
        $this->Cell(140,10,'Pytania rekrutacyjne dla Junior Front-end Developera',0,0,'C');
      } else {
        $this->Cell(140,10,'Junior Front-end Developer Recruiment Questions',0,0,'C');
      }
      
      // Line break
      $this->Ln(20);
  }

  // Page footer
  function Footer()
  {
      // Position at 1.5 cm from bottom
      $this->SetY(-15);
      // Arial italic 8
      $this->SetFont('Arial','I',8);
      // Page number - wiedth = 0 means the cell is extended up to the right margin
      $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
      $this->Write(5,"love-coding.pl", 'http://love-coding.pl');
  }
}

  // Instanciation of inherited class
  $pdf = new PDF();
  // define new alias for total page numbers
  $pdf->AliasNbPages();
  $pdf->AddPage();
  // Add and set new font with polish signs
  $pdf->AddFont('Aleo','','Aleo.php');
  $pdf->SetFont('Aleo','',14);
  if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $userData = new UserData();
    $favouritesQuestions = $userData->getFavouritesQuestions($_SESSION['username']);
    for($i=0;$i<count($favouritesQuestions);$i++) {
        // Add and set new font with polish signs
        $pdf->AddFont('Aleo','','Aleo.php');
        $pdf->SetFont('Aleo','',14);
        
        $category = $favouritesQuestions[$i]["category"];
        $title = $favouritesQuestions[$i]["title"];
        $cellWidth = $pdf->GetStringWidth($category);

        // Set font and border color to category
        $pdf->SetDrawColor(255,190,7);
        $pdf->SetTextColor(255,183,0);
        // print category
        $pdf->Cell($cellWidth+7,10, ($i+1).". ".iconv('UTF-8', 'ISO-8859-2', $category),"LB",0);

        // Set font and border color to title
        $pdf->SetTextColor(0);
        // print title
        $pdf->MultiCell(0,10, iconv('UTF-8', 'ISO-8859-2', $title),0,1);

        $getId = $favouritesQuestions[$i]["id"];
        $displayAnswearsData = new DisplayAnswearsData();
        $getAnswears = $displayAnswearsData->getAllAnswearsToPDF($getId);
        foreach($getAnswears as $answear) {
            // Set font and border color to answear
            $pdf->SetFont('Aleo','',12);
            $pdf->SetFillColor(248,249,250);

            $answear = $answear['answear_text'];
            // Print cell with answear
            $pdf->MultiCell(0,10, "- ".iconv('UTF-8', 'ISO-8859-2', $answear),0,1, "L", true);
        }
        // new line
        $pdf->Ln();
    }
  } else {
      $pdf->MultiCell(0,10, iconv('UTF-8', 'ISO-8859-2', "You don't have permissions to seing this site"),0,1);
  }
  

  $pdf->Output("I", "recruiment-questions.pdf", false);
?>