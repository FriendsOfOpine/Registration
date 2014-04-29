<?php
return function ($context, $post, $registration) {
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
    $optionResponse = $registration->registrationOptionsValidate($document['quantity']);
    if ($optionResponse !== true) {
        $post->errorFieldSet($context['formMarker'], $optionResponse);
        return;
    }
    $post->statusSaved();
};