<?php
namespace Opine;

class RegistrationTest extends \PHPUnit_Framework_TestCase {
    private $registration;
    private $db;
    private $eventID = '5314f7553698bb5228b15cc2';

    public function setup () {
        date_default_timezone_set('UTC');
        $root = getcwd();
        $container = new Container($root, $root . '/container.yml');
        $this->registration = $container->registration;
        $this->db = $container->db;
        $this->eventID = new \MongoId($this->eventID);

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