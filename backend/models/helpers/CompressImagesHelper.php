<?php

namespace backend\models\helpers;

use Exception;
use Yii;

class CompressImagesHelper
{

    public function compressBase64Image($base64Image, $quality = 70, $maxWidth = 800)
    {
        try {
            // Extract the image data from base64 string
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

            // Create image from string
            $image = imagecreatefromstring($imageData);

            if ($image === false) {
                return false;
            }

            // Get original dimensions
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);

            // Calculate new dimensions while maintaining aspect ratio
            if ($originalWidth > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = (int) ($originalHeight * ($maxWidth / $originalWidth));
            } else {
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            }

            // Create new image with new dimensions
            $compressedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Copy and resize the old image into the new image
            imagecopyresampled($compressedImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);

            // Start output buffering
            ob_start();

            // Output JPEG image with specified quality
            imagejpeg($compressedImage, null, $quality);

            // Get the image data from buffer
            $compressedImageData = ob_get_clean();

            // Free memory
            imagedestroy($image);
            imagedestroy($compressedImage);

            // Return as base64
            return base64_encode($compressedImageData);
        } catch (Exception $e) {
            Yii::error("Error compressing image: " . $e->getMessage());
            return false;
        }
    }
}
