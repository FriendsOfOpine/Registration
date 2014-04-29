<?php
return function ($context, $post, $registration) {
	$document = $post->{$context['formMarker']};
    if ($document === false || empty($document)) {
        throw new \Exception('Document not found in post');
    }
	$registration->registrationOptionsAddToOrder($document['quantity'], $document['id']);
	$orderId = array_pop(explode(':', $document['id']));
	$context['formObject']->after = 'redirect';
	$context['formObject']->redirect = '/Registration/' . $document['event_slug'] . '/attendees/' . $orderId;
};