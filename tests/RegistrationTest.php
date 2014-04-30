<?php
namespace Opine;

class RegistrationTest extends \PHPUnit_Framework_TestCase {
    private $registration;
    private $db;

    public function setup () {
        date_default_timezone_set('UTC');
        $root = getcwd();
        $container = new Container($root, $root . '/container.yml');
        $this->registration = $container->registration;
        $this->db = $container->db;

        //eventbyslug//
    $this->db->collection('events')->update(
        ['_id' => $this->eventsID],
        [
            '_id' => $this->eventsID,
            'code_name' => 'event']
            );

    }

    public function testRegistration () {
        $this->assertTrue(true);
    }

     public function testEventBySlug () {
        $result = $this->event->code_name($this->event);
        $this->assertTrue($result);
    }


}