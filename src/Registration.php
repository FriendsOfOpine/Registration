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
		$document = $this->db->documentStage('registration_orders:' . $this->db->id(), [
			'status' => 'open',
			'event_id' => $event['_id'],
			'event_slug' => $event['code_name'],
			'subtotal' => 0,
			'discount' => 0,
			'tax' => 0,
			'shipping' => 0,
			'total' => 0
		]);
		$document->upsert();
		return $document->id();
	}

	public function orderFindByid ($orderId) {
		return $this->db->documentStage('registration_orders:' . $orderId)->current();
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
		foreach ($options as $option => $value) {
			if ($value == 0) {
				continue;
			}
			for ($i=0; $i < $value; $i++) {
				$this->registrationOptionAddOne($option, $orderURI);
			}
		}
	}

	public function registrationOrderTotal ($orderId) {
		$order = $this->db->documentStage('registration_orders:' . $orderId)->current();
		$subtotal = 0;
		foreach ($order['attendees'] as $attendee) {
			$subtotal += $attendee['price'];
		}
		$order->upsert([
			'subtotal' => $subtotal,
			'total' => $subtotal + $order['shipping'] + $order['tax'] - $order['discount']
		]);
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
}