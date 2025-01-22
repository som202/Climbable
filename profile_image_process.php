<?php

function compressImage($file, $targetWidth, $targetHeight, $destination) {
            list($originalWidth, $originalHeight, $imageType) = getimagesize($file);
        
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    $source = imagecreatefromjpeg($file);
                    break;
                case IMAGETYPE_PNG:
                    $source = imagecreatefrompng($file);
                    break;
                default:
                    die("Unsupported image type for compression.");
            }
        
            $newImage = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled(
                $newImage,
                $source,
                0, 0, 0, 0,
                $targetWidth, $targetHeight,
                $originalWidth, $originalHeight
            );
        
            switch ($imageType) {
                case IMAGETYPE_JPEG:
                    imagejpeg($newImage, $destination, 90); // Adjust quality
                    break;
                case IMAGETYPE_PNG:
                    imagepng($newImage, $destination, 9); // Adjust compression level
                    break;
            }
        
            imagedestroy($source);
            imagedestroy($newImage);
}

function insert_image($image_location, $uid) {
    require('db_connect.php');

    $stmt = $conn->prepare("UPDATE users SET picture_file = ? WHERE id = ?");
    $stmt->bind_param("si", $image_location, $uid);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}
?>