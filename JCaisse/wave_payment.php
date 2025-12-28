<?php

    session_start();
    if(!$_SESSION['iduser']){
    header('Location:../index.php');
    }

    require('connection.php');

    require('declarationVariables.php');

    if (isset($_POST['idPS'])) {

        // var_dump();
        $result = "";
        $idPS = $_POST['idPS'];
        $nbMois = $_POST['nbMois'];
        $sql="select * from `aaa-payement-salaire` where idPS=".$idPS;
        // var_dump($sql);
        $res=@mysql_query($sql);
        
        $tab=@mysql_fetch_array($res);

        $idPS = $tab['idPS'];
        $idBoutique = $tab['idBoutique'];
        $montantFixePayement = $tab['montantFixePayement'];
        $client_reference = $idBoutique."-".$idPS."-".$_SESSION['iduser']."-".$nbMois;
        // var_dump($montantFixePayement.'//'.$idPS);
        // die();
    //    $_SESSION['symbole'].'+'.$periode.'+'.$tab['refTransfert'].'+'.$tab['telRefTransfert']

        # Specify your API KEY
        $api_key = "wave_sn_prod_HmImKOJ2QgWfjk_8H1i4zVM57n5iQvVJKfXtkcN5ihZq-PaM4JmHPmNBN_gZ6eq-cN1H0yeqkIcgHYEgsFms10o7--InuXPC1g";

        $checkout_params = [
            "amount" => $montantFixePayement*$nbMois,
            "currency" => "XOF",
            "client_reference" => $client_reference,
            "error_url" => "https://www.jcaisse2.org/JCaisse/wave_error.php",
            "success_url" => "https://www.jcaisse2.org/JCaisse/wave_success.php",
        ];

        # Define the request options
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

        # Execute the request and get a response
        $curl = curl_init();
        curl_setopt_array($curl, $curlOptions);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            # You can now decode the response and use the checkout session. Happy coding ;)
            $checkout_session = json_decode($response);

            # You can redirect the user by using the 'wave_launch_url' field.
            // if ($checkout_session->code!=='no-matching-api-key') {
            //     # code...
            //     $wave_launch_url = $checkout_session["wave_launch_url"];
            // } else {
            //     echo 'diff';
            // }
            // header('Location: '.$checkout_session->wave_launch_url);
            // var_dump($checkout_session);
            // exit;
            exit($checkout_session->wave_launch_url);
        }
    }

?>