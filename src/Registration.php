<?php
/**
 * Opine\Registration
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
namespace Opine;

class Registration {
    private $db;
    private $current = false;
    private $eventId = false;
    private $registrantId = false;

    public function __construct ($db) {
        $this->db = $db;
    }

    public function eventFindBySlug ($slug) {
        $event = $this->db->collection('events')->findOne(['code_name' => $slug]);
        if (!isset($event['_id'])) {
            return false;
        }
        return $event;
    }

    public function eventVerifyOptions ($event) {
        if (!isset($event['registration_options']) || !is_array($event['registration_options']) || empty($event['registration_options'])) {
            return false;
        }
        return true;
    }

    public function orderIdMake ($event) {
        $id = $this->db->id();
        foreach ($event['registration_options'] as &$option) {
            $option['dbURI'] = str_replace('events:' . (string)$event['_id'], 'registration_orders:' . (string)$id, $option['dbURI']);
        }
        $document = $this->db->documentStage('registration_orders:' . $id, [
            'status' => 'open',
            'event_id' => $event['_id'],
            'event_slug' => $event['code_name'],
            'subtotal' => 0,
            'discount' => 0,
            'tax' => 0,
            'shipping' => 0,
            'total' => 0,
            'registration_options' => $event['registration_options'],
            'title' => $event['title']
        ]);
        $document->upsert();
        return $document->id();
    }

    public function statusComplete ($orderId) {
        $this->db->documentStage('registration_orders:' . (string)$orderId, ['status' => 'complete'])->upsert();
    }

    public function statusError ($orderId, $message) {
        $this->db->documentStage('registration_orders:' . (string)$orderId, ['status' => 'complete', 'error' => $message])->upsert();
    }

    public function orderFindByid ($orderId) {
        if (substr_count($orderId, ':') > 0) {
            $orderId = explode(':', $orderId)[1];
        }
        return $this->db->documentStage('registration_orders:' . (string)$orderId)->current();
    }

    public function registrationOptionsValidate ($options) {
        $quantity = 0;
        foreach ($options as $key => $value) {
            $quantity += $value;
        }
        if ($quantity == 0) {
            return 'Quantity can not be zero';
        }
        return true;
    }

    public function registrationOptionsAddToOrder ($options, $orderURI) {
        $this->db->documentStage($orderURI, ['attendees' => []])->upsert();
        foreach ($options as $option => $quantity) {
            if ($quantity == 0) {
                continue;
            }
            $this->registrationOptionSetQuantity($option, $quantity);
            for ($i=0; $i < $quantity; $i++) {
                $this->registrationOptionAddOne($option, $orderURI);
            }
        }
    }

    public function registrationOrderTotal ($orderId) {
        $order = $this->db->documentStage('registration_orders:' . (string)$orderId);
        $current = $order->current();
        $subtotal = 0;
        foreach ($current['attendees'] as $attendee) {
            $subtotal += $attendee['price'];
        }
        $totals = [
            'subtotal' => $subtotal,
            'total' => $subtotal + $current['shipping'] + $current['tax'] - $current['discount']
        ];
        $order->upsert($totals);
        return $totals;
    }

    private function registrationOptionSetQuantity ($optionDbUri, $quantity) {
        $this->db->documentStage($optionDbUri, ['quantity' => $quantity])->upsert();
    }

    private function registrationOptionAddOne ($dbURI, $orderURI) {
        $registrationOption = $this->db->documentStage($dbURI)->current();
        $orderOption = [
            'title' => $registrationOption['title'],
            'price' => $registrationOption['price'],
            'name' => null
        ];
        $this->db->documentStage($orderURI . ':attendees:' . (string)$this->db->id(), $orderOption)->upsert();
    }

    public function registrationAttendeesSet ($attendees) {
        foreach ($attendees as $dbURI => $name) {
            $this->db->documentStage($dbURI, ['name' => $name])->upsert();
        }
    }

    public function authenticatedUserToRegistrationOrder ($orderId) {
        //this takes the billing address, name, phone, email and pre-sets it for the order
    }

    public function billingAddressSet ($orderId, $address) {
        $this->db->documentStage('registration_orders:' . (string)$orderId, $address)->upsert();
    }

    public function contactSet ($orderId, $firstName, $lastName, $phone, $email) {
        $this->db->documentStage('registration_orders:' . (string)$orderId, [
            'first_name' => $firstName,
            'lastName' => $lastName,
            'phone' => $phone,
            'email' => strtolower($email)
        ])->upsert();
    }

    public function paymentInfoSet ($orderId, $paymentInfo) {
        $this->db->documentStage('registration_orders:' . (string)$orderId, $paymentInfo)->upsert();
    }
}