<?php

require_once 'config.php';

if (isset($_GET['what'])){

    if($_GET['what'] == 'members'){
        $sql = "SELECT * FROM members";
        $csv_cols = [
            "member_id",	
            "first_name",	
            "last_name",	
            "email",	
            "phone_number",	
            "photo_path",	
            "training_plan_id",	
            "trainer_id",	
            "access_card_pdf_path",	
            "created_at"];
    }else if($_GET['what'] == 'trainers'){
        $sql = "SELECT * FROM trainers";
        $csv_cols = [
            "trainer_id",	
            "first_name",	
            "last_name",	
            "email",
            "phone_number",	
            "created_at"];
    }else{
        echo "Export error";
        die();
    }

    $run = $conn->query($sql);

    $results = $run->fetch_all(MYSQLI_ASSOC);

    //otvaramo trenutni virtuelni fajl
    $output = fopen('php://output','w');

    header('Content-Type: text/csv'); //comma separated values 
    header('Content-Disposition: attachment; filename=' . $_GET['what'] . ".csv");

    fputcsv($output,$csv_cols);


    foreach($results as $result){
        fputcsv($output,$result);    
    }

    fclose($output);

}