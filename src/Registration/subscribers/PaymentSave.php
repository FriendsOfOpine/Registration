<?php
return function ($context, $post, $registration, $person) {
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

    //add activity to user
    $person->activityAdd('Registration', 'Registered for event: ' . $document['title']);

    //finalize order
    $registation->billingAddressSet($orderId, $addressLine1, $addressLine2, $city, $state, $zip, $country);
    $registration->contactSet($orderId, $firstName, $lastName, $phone, $email);
    $registration->paymentInfoSet ($orderId, $cardNumber, $expirationMonth, $expirationYear, $cardType);

    //redirect to receipt
    $context['formObject']->after = 'redirect';
    $context['formObject']->redirect = '/Registration/' . $document['event_slug'] . '/receipt/registration_orders:' . $orderId;
};