<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BlobController extends Controller
{
    public function generateBlob($blobId)
    {
        // Récupérer le contenu de l'image à partir de la session
        $imageContent = session()->get('blob_' . $blobId);

        if ($imageContent) {
            // Définir les en-têtes pour indiquer que c'est une image
            $headers = [
                'Content-Type' => 'image/png', // Modifier le type MIME selon le format de l'image
                'Content-Length' => strlen($imageContent),
            ];

            // Retourner une réponse avec le contenu de l'image et les en-têtes appropriés
            return response($imageContent, 200, $headers);
        }

        // Si le contenu de l'image n'est pas trouvé, retourner une réponse vide ou une erreur
        abort(404);
    }
}
