<?php

namespace Balloon\Bundle\FormBuilderBundle\Tests\Controller;

use Balloon\Bundle\FormBuilderBundle\Entity\Form;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FormControllerTest extends WebTestCase
{
    private static $repository;
    private static $formId;

    public static function setUpBeforeClass()
    {
        $em = self::createClient()->getContainer()->get('doctrine.orm.entity_manager');
        $em->getConnection()->exec('INSERT INTO Form (name) VALUES (\'toto\')');

        self::$repository = $em->getRepository('BalloonFormBuilderBundle:Form');
        self::$formId = self::$repository->findOneByName('toto')->getId();

        $options = serialize(array('label' => 'field label', 'required' => true, 'max_length' => 50));
        $em->getConnection()->exec("INSERT INTO FormField (form_id, type, options) VALUES (".self::$formId.", 'field', '$options')");
    }

    public static function tearDownAfterClass()
    {
        static::createClient()->getContainer()->get('database_connection')
            ->exec("DELETE FROM FormFieldAnswer");

        static::createClient()->getContainer()->get('database_connection')
            ->exec("DELETE FROM FormAnswer");

        static::createClient()->getContainer()->get('database_connection')
            ->exec("DELETE FROM FormField");

        static::createClient()->getContainer()->get('database_connection')
            ->exec("DELETE FROM Form");
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/form');
        $this->assertTrue($crawler->filter('html:contains("toto")')->count() > 0, $client->getResponse()->getContent());
    }

    public function testCreateForm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/form');
        $crawler = $client->click($crawler->selectLink('create a form')->link());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAddField()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/form/create');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $crawler = $client->click($crawler->selectLink('field')->link());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $form = $crawler->selectButton('save')->form(array('form[label]' => 'field label'));
        $crawler = $client->submit($form);
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertEquals(1, $crawler->filter('html:contains("field label")')->count());

        $session = $client->getContainer()->get('session');
        $formid = str_replace('/form/edit/', '', $client->getRequest()->getRequestUri());
        $this->assertTrue($session->has('forms_'.$formid));
        $field = $session->get('forms_'.$formid);
        $this->assertArrayHasKey(0, $field);
        $this->assertEquals('field', $field[0]['type']);
        $this->assertEquals('field label', $field[0]['label']);
    }

    public function testEditForm()
    {
        $client = self::createClient();

        $crawler = $client->request('GET', '/form/edit/'.self::$formId);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $form = $crawler->selectButton('save')->form(array('form[name]' => 'A form'));
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect(), $client->getResponse()->getContent());

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertTrue($crawler->filter('html:contains("A form")')->count() > 0);
    }

    public function testEditField()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/form/edit/'.self::$formId);
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $crawler = $client->click($crawler->selectLink('edit')->link());
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $form = $crawler->selectButton('save')->form(array('form[label]' => 'field label edited'));
        $crawler = $client->submit($form);

        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertTrue($crawler->filter('html:contains("field label")')->count() > 0, $client->getResponse()->getContent());

        $session = $client->getContainer()->get('session');
        $this->assertTrue($session->has('forms_'.self::$formId));
        $field = $session->get('forms_'.self::$formId);
        $this->assertArrayHasKey(0, $field);
        $this->assertEquals('field', $field[0]['type']);
        $this->assertEquals('field label edited', $field[0]['label']);
    }

    public function testDeleteField()
    {
        $client = self::createClient();

        // delete a field
        $crawler = $client->request('GET', '/form/edit/'.self::$formId);
        $crawler = $client->click($crawler->selectLink('delete')->link());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testAnswer()
    {
        $client = $this->createClient();
    }

    public function testDeleteForm()
    {
        $client = self::createClient();

        // delete a form
        $crawler = $client->request('GET', '/form');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter('html:contains("A form")')->count());
        $crawler = $client->click($crawler->selectLink('delete')->link());
        $crawler = $client->followRedirect();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(0, $crawler->filter('html:contains("A form")')->count(), $client->getResponse()->getContent());
    }
}
