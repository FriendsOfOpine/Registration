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
    $operatorId = false;
    $paymentResponse = [];
    $customerId = false;
    $authentication->check($customerId);
    $description = 'Event Registration';
    if (!isset($document['payment_method'])) {
        $document['payment_method'] = 'creditcard';
    }
    $methods = [['type' => $document['payment_method'], 'amount' => $total]];
    $paymentInfo = $financial->arrayToPaymentInfo((array)$document);
    $billingInfo = $financial->arrayToBillingInfo((array)$document);
    if ($financial->payment (1, $customerId, $operatorId, $orderId, $description, $methods, $paymentInfo, $billingInfo, $paymentResponse) !== true) {
        $post->errorFieldSet($context['formMarker'], $paymentResponse['errorMessage']);
        $registration->statusError($orderId, $paymentResponse['errorMessage']);
        return;
    }
    $post->statusSaved();
};