<!DOCTYPE html>
<html>
<head>
    <title>Upload Carte Grise</title>
</head>
<body>
    <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="image">Choisir une image de carte grise :</label>
        <input type="file" name="image" id="image" required>
        <button type="submit">Extract</button>
    </form>
</body>
</html>