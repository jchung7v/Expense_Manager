<?php 
session_start();
include("./inc_header.php");
include("./inc_header_main.php");
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <form action="./actions/import_csv.php" method="post" enctype="multipart/form-data" class="p-3 bg-grey">
                <div class="mb-3">
                    <label for="fileToUpload" class="form-label">Select file to upload:</label>
                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload">
                </div>
                <button type="submit" class="btn btn-primary" name="submit">Upload Selected File</button>
            </form>
        </div>
    </div>
</div>

<?php include("inc_footer.php"); ?>