<?php
return function ($context, $post, $registration, $financial, $authentication) {
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
    $orderId = array_pop(explode(':', $document['id']));
    $total = $registration->registrationOrderTotal($orderId)['total'];
    $paymentResponse = [];
    $customerId = false;
    $authentication->check($customerId);
    $description = 'Event Registration';
    $methods = ['creditcard'];
    $paymentInfo = [];
    $billingInfo = [];

    if ($financial->payment (1, $customerId, $operatorId, $orderId, $description, $methods, $paymentInfo, $billingInfo, $paymentResponse)) {
        $post->errorFieldSet($context['formMarker'], $paymentResponse);
        return;
    } else {
        $post->statusSaved();
    }
};