<?php

namespace Balloon\Bundle\FormBuilderBundle\Tests\Form;

use Balloon\Bundle\FormBuilderBundle\Form\Builder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\CoreExtension;

/**
 * @author     Jules Boussekeyt <jules.boussekeyt@gmail.com>
 */
class BuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTypeOptions()
    {
        $builder = $this->getBuilder();
        $typeMock = $this->getTypeMock();
        $options = $builder->getTypeOptions($typeMock, array(
            'is_required' => 'true',
            'choices' => array('foo', 'bar', 'baz'),
        ));

        $this->assertSame(true, $options['is_required']);
        $this->assertEquals(8, count($options['choices']));
        $this->assertEquals(1, count($options['type']));
    }

    public function testBuildType()
    {
        $builder = $this->getBuilder();
        $form = $builder->buildType(new ChoiceType());

        $this->assertInstanceof('Symfony\Component\Form\Form', $form);
        $this->assertInstanceof('Symfony\Component\Form\Form', $form->get('choices'));
        $this->assertInstanceof('Symfony\Component\Form\Form', $form->get('expanded'));
        $this->assertInstanceof('Symfony\Component\Form\Form', $form->get('choices'));
    }

    public function testGetType()
    {
        $builder = $this->getBuilder();

        try {
            $builder->getType('unknown');
            $this->fail('->getType() should throw an exception if type do not exists');
        } catch (\InvalidArgumentException $e) {}

        $this->assertInstanceof('Symfony\Component\Form\Extension\Core\Type\FieldType', $builder->getType('field'));
    }


    public function getBuilder()
    {
        $formFactory = new FormFactory(array(new CoreExtension()));

        $tallHat = \Mockery::mock('\Balloon\Bundle\FormBuilderBundle\Form\TallHat', array(
        ));

        $config = array();

        return new Builder($formFactory, $tallHat, $config);
    }

    public function getTypeMock()
    {
        return \Mockery::mock('\Symfony\Component\Form\Extension\Core\Type\FieldType', array(
            'getDefaultOptions' => array(
                'choices'       => array(),
                'type'          => array('text', 'number', 'object'),
                'is_required'   => false,
            ),
            'getName' => null,
        ));
    }
}
