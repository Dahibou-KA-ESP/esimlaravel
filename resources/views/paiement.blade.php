<?php
use Illuminate\Support\Facades\Session;
//echo "vcxvxcvvcxvx";
// $prime_ttc = Session::get('prime_ttc');
// Specify your API KEY
$api_key = "wave_sn_prod_NyYgZylRA183dwKEMq6Ck_SD0o0MP3AvSqlheCuimEZjSDfd70ZczN3hP1Eo1qFFEsGXJum_8Ye4EjssTRkrzAJ6BsXqMV9rIQ";

$checkout_params = [
    "amount" => 100 ,
    "currency" => "XOF",
    "error_url" => "https://example.com/error",
    "success_url" => "https://www.google.fr/"
];

// Define the request options
$curlOptions = [
  CURLOPT_URL => "https://api.wave.com/v1/checkout/sessions",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 5, 
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => json_encode($checkout_params),
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer {$api_key}",
    "Content-Type: application/json"
  ],
];

// Execute the request and get a response
$curl = curl_init();
curl_setopt_array($curl, $curlOptions);
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
    // You can now decode the response and use the checkout session. Happy coding ;)
    $checkout_session = json_decode($response, true);

    //  You can redirect the user by using the 'wave_launch_url' field.
    $wave_launch_url = $checkout_session["wave_launch_url"];
    
    //var_dump($wave_launch_url);
    //echo "dfsdsfsdfs";
    
   header('Location:'.$wave_launch_url.'');
  exit;
}

?>