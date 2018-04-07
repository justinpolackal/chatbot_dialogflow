<?php
// webhook.php
//
// Dialogflow calls this service using POST request.
// Place this file in the document root of your server. Your server must be accessible at the below URL:
// http://<hostname>/webhook.php
//
// This service just extracts the intent and parameters (entities) from the user supplied text 
// and returns to Dialogflow.
//
$method = $_SERVER['REQUEST_METHOD'];
//
// Process only when request method is POST
//
if($method == 'POST'){
        $requestBody = file_get_contents('php://input');
        $json = json_decode($requestBody);

        $text = $json->result->resolvedQuery;

        $location = (!empty($json->result->parameters->location)) ? $json->result->parameters->location : '';
        $cuisine  = (!empty($json->result->parameters->cuisine)) ? $json->result->parameters->cuisine : '';
        $intent   = (!empty($json->result->metadata->intentName)) ? $json->result->metadata->intentName : '';
        
        $responseText = prepareResponse($intent, $text, $location, $cuisine);

        $response = new \stdClass();
        $response->speech = $responseText;
        $response->displayText = $responseText;
        $response->source = "webhook";
        echo json_encode($response);
}
else
{
        echo "Method not allowed";
}

function prepareResponse($intent, $text, $location, $cuisine)
    {
    return "You said: " . $text . " | I found Intent: " . $intent . "| with parameters: location=" . $location . " cuisine=" . $cuisine ;    
    }
?>
