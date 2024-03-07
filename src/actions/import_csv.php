<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Status</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border-radius: 5px;
            background: #333;
        }
        .btn-back {
            display: inline-block;
            padding: 10px 15px;
            margin-top: 20px;
            background-color: #6200ea;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn-back:hover {
            background-color: #3700b3;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if (isset($_FILES['fileToUpload'])) {
        include '../backend.php'; // Make sure this path correctly points to where your backend.php file is located.

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

    <a href="javascript:history.back()" class="btn-back">Go Back</a>
</div>

</body>
</html>
