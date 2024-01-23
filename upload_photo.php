<?php

$photo = $_FILES['photo'];  //paramName uhvatio

//sa basename - bicemo sigurni da ce dati samo ime slike a ne i putanju slike
$photo_name = basename($photo['name']);

// sada pravimo nase
$photo_path = 'member_photos/' . $photo_name;

//provera da li je ekstenzija dobra
$allowed_ext = ['jpg','jpeg','png','gif'];

$ext = pathinfo($photo_name,PATHINFO_EXTENSION);

if(in_array($ext,$allowed_ext) && $photo['size'] < 2000000){
    move_uploaded_file($photo['tmp_name'],$photo_path);

    echo json_encode(['success' => true, 'photo_path' => $photo_path]); //vracamo photo_path da bismo
    //znali gde da ga sacuvamo u bazi
} else {
    echo json_encode(['succes' => false, 'error' => 'Invalid file']);
}

