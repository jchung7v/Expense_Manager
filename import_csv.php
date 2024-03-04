<?php

if (isset($_FILES['fileToUpload'])) {
    include 'backend.php'; // Make sure this path correctly points to where your backend.php file is located.
    
    $uploadDirectory = "uploads/"; // Ensure this uploads directory exists in your project folder.
    $originalFileName = basename($_FILES["fileToUpload"]["name"]);
    $targetFilePath = $uploadDirectory . $originalFileName;

    // Create uploads directory if it doesn't exist
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    // Attempt to move the uploaded file to your designated uploads directory
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFilePath)) {
        $db = new ExpenseDatabase();
        $db->importCSV($targetFilePath);

        // Define the new file name by appending .imported to the current file name
        $newFilePath = $targetFilePath . '.imported';
        rename($targetFilePath, $newFilePath);

        echo "File imported successfully and renamed to '$newFilePath'.";
    } else {
        echo "There was an error uploading your file.";
    }
} else {
    echo "No file uploaded.";
}
?>
