<?php


function POST_CurlAPIRequest($postURL, $postData){

    
    $curl_Instance = curl_init($postURL);

    curl_setopt($curl_Instance, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
    curl_setopt($curl_Instance, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_Instance, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl_Instance, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl_Instance, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($curl_Instance);

    // close the connection
    curl_close($curl_Instance);
    
    return $response;


}


function GET_CurlAPIRequest($getURL){
    
    //Initialize Curl Instance
    $curl_Instance =curl_init();

    //Set Curl Headers/Request
    curl_setopt($curl_Instance, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl_Instance, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl_Instance, CURLOPT_URL, $getURL);
    curl_setopt($curl_Instance, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl_Instance, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
    curl_setopt($curl_Instance, CURLOPT_VERBOSE, true);
    curl_setopt($curl_Instance, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl_Instance, CURLOPT_SSL_VERIFYPEER, false);

    //Get response Data and Parse from String to JSON
    $GET_requestResponse= json_decode(curl_exec($curl_Instance), true) ;

    //Close Curl Instance
    curl_close($curl_Instance);

    return $GET_requestResponse;
}