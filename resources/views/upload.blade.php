<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
    </style>
</head>

<body style="text-align:center">
    <h1>Upload File</h1> </br>
    <form action="upload" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="input-group" style="width:50%; margin: auto;">
            <div class="custom-file">
                <input type="file" class="custom-file-input" name="file">
                <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
            </div>
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">Upload File</button>
            </div>
        </div>
    </form>
</body>
