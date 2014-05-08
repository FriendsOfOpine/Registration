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
    $paymentInfo = [
        'number' => $document['creditcard_number'],
        'expirationMonth' => $document['expiration_month'],
        'expirationMonth' => $document['expiration_year'],
        'cvv' => $document['security_code']
    ];
    $billingInfo = [
        'first_name' => $document['first_name'],
        'last_name' => $document['last_name'],
        'phone' => $document['phone'],
        'email' => $document['email'],
        'address' => $document['address'],
        'address2' => $document['address2'],
        'city' => $document['city'],
        'state' => $document['state'],
        'zipcode' => $document['zipcode'],
        'country' => (isset($document['country']) ? $document['country'] : 'US')
    ];

    if ($financial->payment (1, $customerId, $operatorId, $orderId, $description, $methods, $paymentInfo, $billingInfo, $paymentResponse)) {
        $post->errorFieldSet($context['formMarker'], $paymentResponse);
        return;
    } else {
        $post->statusSaved();
    }
};