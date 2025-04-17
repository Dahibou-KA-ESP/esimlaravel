<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use thiagoalessio\TesseractOCR\TesseractOCR;


class TestOcrController extends Controller
{
    public function index(){
        return view('test.upload');
    }

    public function upload(Request $request)
    {
        
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $request->file('image')->store('public/cartes_grises');
                var_dump($path);exit;


      $informations = $this->processImage($path);
        // var_dump($informations);exit;
        return view('test.result', compact('informations'));
    }

    private function processImage($path)
    {
        $imagePath = storage_path('app/' . $path);
        $text = (new TesseractOCR($imagePath))->run();
        // Débogage: Affichage du texte extrait
        var_dump($text); exit;
        $informations =  $this->extractInformationFromText($text);

        return $informations;
    }

    private function extractInformationFromText($text)
    {
        $lines = explode("\n", $text);
        $informations = [
            'numero_immatriculation' => '',
            'date_mise_en_circulation' => '',
        ];

        if(isset($lines[4])) {
            $line = trim($lines[4]);
            // Débogage: Affichage de chaque ligne analysée
            // echo $line . "<br>";

            // // Expression régulière pour extraire les parties
            // preg_match('/^(.*?)\s+(.*?)\\\/', $line, $matches);

            // // Récupération des parties individuelles
            // $partie_1 = trim($matches[1]);  // Enlevez les espaces supplémentaires
            // $partie_2 = trim($matches[2]);

            // // Affichage des parties extraites
            // echo "Partie 1: " . $partie_1 . "\n";
            // echo "Partie 1: " . $partie_1 . "\n";

        
            // $informations['numero_immatriculation'] =  $partie_1;
            // $informations['date_mise_en_circulation'] = $partie_2;

            
            // Ajuster l'expression régulière pour correspondre au format exact du texte
            if ( preg_match('/^(.*?)\s+(.*?)\\\/', $line, $matches)) {
                // Récupération des parties individuelles
            $partie_1 = trim($matches[1]);  // Enlevez les espaces supplémentaires
            $partie_2 = trim($matches[2]);
            $informations['numero_immatriculation'] =  $partie_1;
            $informations['date_mise_en_circulation'] = $partie_2;
            }
          
        }

        return $informations;
    }

    

}
