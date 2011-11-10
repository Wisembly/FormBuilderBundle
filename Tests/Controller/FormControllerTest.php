<?php

namespace Balloon\Bundle\FormBuilderBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public static function tearDownAfterClass()
    {
        static::createClient()->getContainer()->get('database_connection')
            ->exec("DELETE FROM Form WHERE name IN ('toto', 'A form')");
    }

    public function testList()
    {
        $client = static::createClient();

        $client->getContainer()->get('database_connection')
            ->exec("INSERT INTO Form (id, name) VALUES (1, 'toto')");

        $crawler = $client->request('GET', '/form');
        $this->assertTrue($crawler->filter('html:contains("toto")')->count() > 0);

        $client->getContainer()->get('database_connection')
            ->exec("DELETE FROM Form WHERE name = 'toto'");
    }

    public function testCreate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/form');
        $crawler = $client->click($crawler->selectLink('create a form')->link());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * There's two ways to manage a form:
     *
     * 1. Just created it, data is in the session
     *
     * 2. You edit it, data comes from the database
     */
    public static function formsProvider()
    {
        $sessionFormid = rand(0, 1000);
        $sessionPre = function($client) use($sessionFormid) {
            $client->getContainer()->get('session')->set('forms_'.$sessionFormid, array(array('type' => 'field')));
        };

        $dbFormid = rand(0, 1000);
        $dbPre = function($client) use($dbFormid) {
            $client->getContainer()->get('doctrine.orm.entity_manager')
                ->getConnection()
                ->exec("INSERT INTO Form (id, name) VALUES ($dbFormid, 'toto')");
        };

        return array(
            array($sessionPre, $sessionFormid),
            array($dbPre, $dbFormid),
        );
    }

    /** @dataProvider formsProvider */
    public function testAddField($preFunction, $formid)
    {
        $client = static::createClient();
        $preFunction($client);

        $crawler = $client->request('GET', '/form/edit/'.$formid);
        $crawler = $client->click($crawler->selectLink('text')->link());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('save')->form(array('form[label]' => 'field label'));
        $crawler = $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("field label")')->count() > 0);

        $session = $client->getContainer()->get('session');
        $this->assertTrue($session->has('forms_'.$formid));

        $field = $session->get('forms_'.$formid);
        $this->assertArrayHasKey(0, $field);
        $this->assertEquals('field', $field[0]['type']);
        $this->assertEquals('field label', $field[0]['label']);
    }

    /** @dataProvider formsProvider */
    public function testSaveForm($preFunction, $formid)
    {
        $client = static::createClient();
        $preFunction($client);

        $crawler = $client->request('GET', '/form/edit/'.$formid);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $form = $crawler->selectButton('save')->form(array('form[name]' => 'A form'));
        $session = $client->getContainer()->get('session')->get('forms_'.$formid);
        $crawler = $client->submit($form, array('form[name]' => 'A form'));

        // fix session cleared by $client->submit()
        // TODO fixme - session get cleared

        $this->markTestIncomplete('session get cleared, wtf??');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("A form")')->count() > 0);
    }
}
