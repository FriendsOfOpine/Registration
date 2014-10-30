<?php
namespace Registration;

class Controller {
    private $layout;
    private $registration;

    public function __construct ($layout, $formRoute, $registration) {
        $this->layout = $layout;
        $this->formRoute = $formRoute;
        $this->registration = $registration;
    }

	public function registration ($eventSlug) {
        $event = $this->registration->eventFindBySlug($eventSlug);
        if ($event === false) {
            $this->error('Unknown event');
            return false;
        }
        if (isset($event['login_required']) && $event['login_required'] == 't') {
            //$this->authentication->checkAndRedirect();
        }
        $orderId = $this->registration->orderIdMake($event);
        header('Location: /Registration/' . $eventSlug . '/options/registration_orders:' . $orderId);
    }

    public function options ($eventSlug, $orderId) {
        $data = [];
        if ($this->inputValidation('options', $eventSlug, $orderId, $data) === false) {
            return;
        }
        $this->layout->
            app($data['app'])->
            layout($data['layout'])->
            args('form', [
                'id' => $orderId
            ])->
            template()->
            write();
    }

    public function attendees ($eventSlug, $orderId) {
        $data = [];
        if ($this->inputValidation('attendees', $eventSlug, $orderId, $data) === false) {
            return;
        }
        $this->layout->
            app($data['app'])->
            layout($data['layout'])->
            args('form', [
                'id' => $orderId
            ])->template()->
            write();
    }

    public function paymetnt ($eventSlug, $orderId) {
        $data = [];
        if ($this->inputValidation('payment', $eventSlug, $orderId, $data) === false) {
            return;
        }
        $this->layout->
            app($data['app'])->
            layout($data['layout'])->
            args('form', [
                'id' => $orderId
            ])->
            template()->
            write();
    }

    public function receipt ($eventSlug, $orderId) {
        $data = [];
        if ($this->inputValidation('receipt', $eventSlug, $orderId, $data) === false) {
            return;
        }
        $this->layout->
            app($data['app'])->
            layout($data['layout'])->
            regionAdd('data', ['type' => 'array'], $data)->
            template()->
            write();
    }

    private function inputValidation ($mode, $eventSlug, $orderId, &$data) {
        $data['event'] = $this->registration->eventFindBySlug($eventSlug);
        if ($data['event'] === false) {
            $this->error('Unknown event');
            return false;
        }
        if ($this->registration->eventVerifyOptions($data['event']) === false) {
            $this->error('Event has no registration options');
            return false;
        }
        $data['order'] = $this->registration->orderFindById($orderId);
        if ($data['order'] === false) {
            $this->error('Invalid order.');
            return false;
        }
        if (in_array($mode, ['attendees', 'payment'])) {
            if (isset($data['order']['status']) && $data['order']['status'] == 'completed') {
                $this->error('Order has been completed.');
                return false;
            }
        }
        $data['app'] = 'bundles/Registration/app/forms/' . $mode;
        $data['layout'] = 'Registration/forms/' . $mode;
        if ($mode == 'receipt') {
            $data['app'] = str_replace('/forms/', '/documents/', $data['app']);
            $data['layout'] = str_replace('/forms/', '/documents/', $data['layout']);
        }
        if (!empty($data['event']['config_' . $mode . '_app'])) {
            $data['app'] = $event['config_' . $mode . '_app'];
        }
        if (!empty($event['config_' . $mode . '_layout'])) {
            $data['layout'] = $event['config_' . $mode . '_layout'];
        }
    }

    private function error ($message) {
        $this->layout->
            app('bundles/Registration/app/')->
            layout('Registration/error')->
            regionAdd('error', ['type' => 'array'], $message)->
            template()->
            write();
    }
}