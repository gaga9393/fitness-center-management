<?php

require_once 'config.php';
require_once 'fpdf/fpdf.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $training_plan_id = $_POST['training_plan_id'];
    $trainer_id = 0;
    $photo_path = $_POST['photo_path'];
    $access_card_pdf_path = "";

    $sql = "INSERT INTO members (first_name, last_name, email, phone_number,
                    photo_path, training_plan_id, trainer_id, access_card_pdf_path) 
        VALUES      (?, ?, ?, ?, ?, ?, ?, ?)";

    $run = $conn->prepare($sql);
    $run->bind_param("sssssiis",$first_name,$last_name,$email,$phone_number,$photo_path,$training_plan_id,$trainer_id,$access_card_pdf_path);
    $run->execute();       
    
    //kada izvrsimo query, dobicemo nazad podatke koje smo uneli
    //izvlacimo id od tek kreiranog member-a:
    $member_id = $conn->insert_id;
    
    //kod kod koji generise pdf i smesta ga u folder koji smo napravili:
    // ucitavamo klasu biblioteke koju smo ubacili

    $pdf = new FPDF();
    $pdf->AddPage(); //pravimo novu stranicu u tom fajlu
    $pdf->SetFont('Arial','B',16);

    //gde ce da upise te podatke:
    //kreiramo polje, sa podacima gde ce polje da se nalazi
    $pdf->Cell(40,10,'Access Card');
    $pdf->Ln(); //nova linija
    $pdf->Cell(40,10,'Member ID: ' . $member_id);
    $pdf->Ln();
    $pdf->Cell(40,10,'Name: ' . $first_name . " " . $last_name);
    $pdf->Ln();
    $pdf->Cell(40,10,'Email: ' . $email);
    $pdf->Ln();
    
    $filename = 'access_cards/access_card_' . $member_id . ".pdf";
    $pdf->Output('F', $filename);

    // upise tu lokaciju i u bazu
    $sql = "UPDATE members SET access_card_pdf_path = '$filename' 
            WHERE member_id = $member_id ";
    $conn->query($sql);   
    $conn->close();     

    $_SESSION['success_message'] = 'Clan teretane uspesno dodat.';
    header('location: admin_dashboard.php');
    exit();

}

?>