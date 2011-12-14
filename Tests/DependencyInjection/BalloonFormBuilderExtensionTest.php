<?php

namespace Balloon\Bundle\FormBuilderBundle\Tests\DependencyInjection;

use Balloon\Bundle\FormBuilderBundle\DependencyInjection\Configuration;
use Balloon\Bundle\FormBuilderBundle\DependencyInjection\BalloonFormBuilderExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

/**
 * @author     Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class BalloonFormBuilderExtensionTest extends \PHPUnit_Framework_TestCase
{
    private $configuration;

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testFormLoadThrowsExceptionUnlessFieldsSet()
    {
        $loader = new BalloonFormBuilderExtension();
        $config = $this->getEmptyConfig();
        unset($config['fields']);
        $loader->load(array($config), new ContainerBuilder());
    }

    public function testFormLoadWithCustomClasses()
    {
        $loader = new BalloonFormBuilderExtension();
        $config = $this->createFullConfiguration();
        $this->assertEquals('FormEntity', $this->configuration->getParameter('balloon_form_entity'));
        $this->assertEquals('FieldEntity', $this->configuration->getParameter('balloon_field_entity'));
        $this->assertEquals('AnswerEntity', $this->configuration->getParameter('balloon_answer_entity'));
        $this->assertEquals('FieldAnswerEntity', $this->configuration->getParameter('balloon_field_answer_entity'));
    }

    public function testFormLoadWithFields()
    {
        $loader = new BalloonFormBuilderExtension();
        $config = $this->createFullConfiguration();
        $configuration = $this->configuration->getParameter('balloon_form_config');
        $this->assertArrayHasKey('field', $configuration);
        $this->assertArrayHasKey('label', $configuration['field']);
        $this->assertEquals(null, $configuration['field']['label']);
    }

    public function testFormLoadWithEmptyResources()
    {
        $loader = new BalloonFormBuilderExtension();
        $config = $this->createEmptyConfiguration();
        $configuration = $this->configuration->getParameter('balloon_form_resources');
        $this->assertEquals(array(), $configuration);
    }

    protected function createEmptyConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new BalloonFormBuilderExtension();
        $config = $this->getEmptyConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);

        return $config;
    }

    protected function createFullConfiguration()
    {
        $this->configuration = new ContainerBuilder();
        $loader = new BalloonFormBuilderExtension();
        $config = $this->getFullConfig();
        $loader->load(array($config), $this->configuration);
        $this->assertTrue($this->configuration instanceof ContainerBuilder);

        return $config;
    }

    public function getEmptyConfig()
    {
        $yaml = <<<EOF
fields:
    field:
        label: ~
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    public function getFullConfig()
    {
        $yaml = <<<EOF
form_entity:            FormEntity
field_entity:           FieldEntity
answer_entity:          AnswerEntity
field_answer_entity:    FieldAnswerEntity
fields:
    field:
        label: ~
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }
}
