<?php
/**
 * Opine\Registration\Application
 *
 * Copyright (c)2013, 2014 Ryan Mahoney, https://github.com/Opine-Org <ryan@virtuecenter.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
namespace Opine\Registration;

class Application {
    private $route;
    private $separation;
    private $root;
    private $bundleRoot;
    private $registration;

    public function __construct ($container, $root, $bundleRoot) {
        $this->route = $container->route;
        $this->separation = $container->separation;
        $this->root = $root;
        $this->bundleRoot = $bundleRoot;
        $this->formRoute = $container->formRoute;
        $this->registration = $container->registration;
    }

    public function app () {
        $this->route->get('/Registration/{eventSlug}', function ($eventSlug) {
            $event = []; $order = []; $app = ''; $layout = '';
            if ($this->inputValidation('options', $eventSlug, false, $event, $order, $app, $layout) === false) {
                return;
            }
            $this->separation->
                app($app)->
                layout($layout)->
                addBinding('event', ['type' => 'array'], $event)->
                template()->
                write();
        });

        $this->route->get('/Registration/{eventSlug}/attendees/{orderId}', function ($eventSlug, $orderId) {
            $event = []; $order = []; $app = ''; $layout = '';
            if ($this->inputValidation('attendees', $eventSlug, $orderId, $event, $order)) {
                return;
            }
            $this->separation->
                app($app)->
                layout($layout)->
                addBinding('event', ['type' => 'array'], $event)->
                template()->
                write();
        });

        $this->route->get('/Registration/{eventSlug}/payment/{orderId}', function ($eventSlug, $orderId) {
            $event = []; $order = []; $app = ''; $layout = '';
            if ($this->inputValidation('payment', $eventSlug, $orderId, $event, $order)) {
                return;
            }
            $this->separation->
                app($app)->
                layout($layout)->
                addBinding('event', ['type' => 'array'], $event)->
                template()->
                write();
        });

        $this->route->get('/Registration/{eventSlug}/receipt/{orderId}', function ($eventSlug, $orderId) {
            $event = []; $order = []; $app = ''; $layout = '';
            if ($this->inputValidation('receipt', $eventSlug, $orderId, $event, $order)) {
                return;
            }
            $this->separation->
                app($app)->
                layout($layout)->
                addBinding('event', ['type' => 'array'], $event)->
                template()->
                write();
        });
    }

    private function inputValidation ($mode, $eventSlug, $orderId=false, &$event=false, &$order=false, &$app=false, &$layout=false) {
        $event = $this->registration->eventFindBySlug($eventSlug);
        if ($event === false) {
            $this->error('Unknown event');
            return false;
        }
        if (in_array($mode, ['attendees', 'payment', 'receipt'])) {
            $order = $this->registration->orderFindById($orderId);
            if ($order === false) {
                $this->error('Invalid order.');
                return false;
            }
            if (in_array($mode, ['attendees', 'payment'])) {
                if (isset($order['status']) == 'completed') {
                    $this->error('Order has been completed.');
                    return false;
                }
            }
        }
        $app = 'bundles/Registration/app/' . $mode;
        $layout = 'Registration/' . $mode;
        if (!empty($event['config_' . $mode . '_app'])) {
            $app = $event['config_' . $mode . '_app'];
        }
        if (!empty($event['config_' . $mode . '_layout'])) {
            $layout = $event['config_' . $mode . '_layout'];
        }
    }

    private function error ($message) {
        $this->separation->
            app('bundles/Registration/app/')->
            layout('Registration/error')->
            bindingAdd('error', ['type' => 'array'], $message)->
            template()->
            write();
    }

    public function build ($bundleRoot) {}

    private function authenticationBuild ($authorizations) {}

    public function upgrade ($bundleRoot) {}
}