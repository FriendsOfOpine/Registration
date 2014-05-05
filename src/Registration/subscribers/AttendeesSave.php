<?php
return function ($context, $post, $registration) {
    $document = $post->{$context['formMarker']};
    if ($document === false || empty($document)) {
        throw new \Exception('Document not found in post');
    }
    $orderId = array_pop(explode(':', $document['id']));
    $registration->registrationAttendeesSet($document['name']);
    $total = $registration->registrationOrderTotal($orderId)['total'];
    $context['formObject']->after = 'redirect';
    if ($total > 0) {
        $context['formObject']->redirect = '/Registration/' . $document['event_slug'] . '/payment/registration_orders:' . $orderId;
    } else {
        $context['formObject']->redirect = '/Registration/' . $document['event_slug'] . '/receipt/registration_orders:' . $orderId;
    }
};