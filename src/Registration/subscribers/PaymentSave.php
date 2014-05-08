<?php
return function ($context, $post, $registration, $financial, $person) {
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
    $order = $registration->orderFindByid($orderId);
    $paymentInfo = $financial->arrayToPaymentInfo((array)$document, true);
    $billingInfo = $financial->arrayToBillingInfo((array)$document);

    //add activity to user
    if ($person->availableFindOrCreate($billingInfo) === true) {
        $description = 'Registered for event: ' . $order['title'];
        $person->recordAdd($document['id'], 'Registration', $description);
        $person->activityAdd('Registration', $description);
        $person->addressBillingSet($billingInfo);
    }

    //finalize order
    $registration->billingAddressSet($orderId, $billingInfo);
    $registration->contactSet($orderId, $document['first_name'], $document['last_name'], $document['phone'], $document['email']);
    $registration->paymentInfoSet ($orderId, $paymentInfo);
    $registration->statusComplete($orderId);

    //redirect to receipt
    $context['formObject']->after = 'redirect';
    $context['formObject']->redirect = '/Registration/' . $document['event_slug'] . '/receipt/registration_orders:' . $orderId;
};