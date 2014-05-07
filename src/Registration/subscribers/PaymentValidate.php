<?php
return function ($context, $post, $registration, $financial) {
    if (!isset($context['dbURI']) || empty($context['dbURI'])) {
        throw new \Exception('Context does not contain a dbURI');
    }
    if (!isset($context['formMarker'])) {
        throw new \Exception('Form marker not set in post');
    }
    $document = $post->{$context['formMarker']};
    if ($document === false || empty($document)) {
        throw new \Exception('Document not found in post');
    }
    
    //get total from order
    $total = $registration->registrationOrderTotal($orderId)['total'];

    //check card expiration date is in future

    //process card
    /*
    if ($financial->payment ($locationId, $customerId, $operatorId, $orderId, $description, $methods, $paymentInfo, $billingInfo, $response)) {

    }
    */

    $post->statusSaved();
};