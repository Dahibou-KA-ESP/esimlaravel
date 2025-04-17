<!-- resources/views/test/result.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Informations Extraites</title>
</head>
<body>
    <h1>Informations Extraites</h1>
    @if (!empty($informations['numero_immatriculation']) && !empty($informations['date_mise_en_circulation']))
        <p><strong>Numéro d'Immatriculation:</strong> {{ $informations['numero_immatriculation'] }}</p>
        <p><strong>Date de Mise en Circulation:</strong> {{ $informations['date_mise_en_circulation'] }}</p>
    @else
        <p>Aucune information trouvée.</p>
    @endif
</body>
</html>
