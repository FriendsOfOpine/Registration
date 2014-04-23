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
        $this->route->get('/Registration/{event}', function ($event) {
            echo 'Registration for: ', $event;
        });

        $this->route->get('/Registration/{event}/attendees', function () {
            echo 'Registration attendees for: ', $event;
        });

        $this->route->get('/Registration/{event}/payment', function () {
            echo 'Registration attendees for: ', $event;
        });

        $this->route->get('/Registration/{event}/receipt', function () {
            echo 'Registration attendees for: ', $event;
        });
    }

    public function build ($bundleRoot) {}

    private function authenticationBuild ($authorizations) {}

    public function upgrade ($bundleRoot) {}
}