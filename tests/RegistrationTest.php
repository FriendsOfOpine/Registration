<?php
namespace Opine;

use PHPUnit_Framework_TestCase;
use Opine\Container\Service as Container;
use Opine\Config\Service as Config;
use MongoId;

class RegistrationTest extends PHPUnit_Framework_TestCase {
    private $registration;
    private $db;
    private $eventID = '5314f7553698bb5228b15cc2';

    public function setup () {
        $root = __DIR__ . '/../public';
        $config = new Config($root);
        $container = Container::instance($root, $config, $root . '/../config/container.yml');
        $this->registration = $container->get('registration');
        $this->db = $container->get('db');
        $this->eventID = new MongoId($this->eventID);

        //eventbyslug//
        $this->db->collection('events')->update(
            ['_id' => $this->eventID],
            [
                '_id' => $this->eventID,
                'code_name' => 'this-event'
            ],
            ['upsert' => true]
        );

    }

    public function testRegistration () {
        $this->assertTrue(true);
    }

    public function testEventFindBySlug () {
        $result = $this->registration->eventFindBySlug($this->eventID);
        $this->assertFalse($result);
    }

}