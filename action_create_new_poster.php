<?php

require_once __dir__ . '/database/posterdao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if (isset($_POST['posterTitle']) &&
        isset($_POST['posterHeadline']) && 
        isset($_POST['posterDescription'])) {
            
        $poster = new Poster(
            $_POST['posterTitle'],
            $_POST['posterHeadline'],
            $_POST['posterDescription'],
            saveCoverImg()
        );
        
        PosterDAO::insert($poster);
        
        header("Location: poster_added_successfully.html");
        exit();
        
    }
    
}

function saveCoverImg(): ?string {
    
    if (isset($_FILES['posterCoverImg']) && $_FILES['posterCoverImg']['error'] === UPLOAD_ERR_OK) {
        
        $tmpFileName = $_FILES['posterCoverImg']['tmp_name'];
        $originFileName = basename($_FILES['posterCoverImg']['name']);
        $targetDir = __DIR__ . '/resources/poster/cover_img/';
        
        $fileExtension = strtolower(pathinfo($originFileName, PATHINFO_EXTENSION));
        $fileExtensionAllowed = ['png', 'jpeg', 'jpg'];
        
        if (in_array($fileExtension, $fileExtensionAllowed) && !getimagesize($tmpFileName)) {
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            
            $newName = uniqid("img_") . bin2hex(random_bytes(8)) . '.' . $fileExtension;
            $path = $targetDir . '/' . $newFileName;
            
            if (move_uploaded_file($tmpFileName, $path)) {
                return $newName;
            }
            
        }
        
        return null;
        
    }
    
}