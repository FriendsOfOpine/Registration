<?php
return function ($context, $post, $registration) {
    $document = $post->{$context['formMarker']};
    if ($document === false || empty($document)) {
        throw new \Exception('Document not found in post');
    }
    $orderId = array_pop(explode(':', $document['id']));
    $registration->registrationOptionsAddToOrder($document['quantity'], $document['id']);
    $registration->registrationOrderTotal($orderId);
    $context['formObject']->after = 'redirect';
    $context['formObject']->redirect = '/Registration/' . $document['event_slug'] . '/attendees/registration_orders:' . $orderId;
};