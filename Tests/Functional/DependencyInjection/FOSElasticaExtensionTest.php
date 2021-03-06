<?php


namespace FOS\ElasticaBundle\Tests\Functional\DependencyInjection;


use FOS\ElasticaBundle\DependencyInjection\FOSElasticaExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class FOSElasticaExtensionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function shouldAddParentParamToObjectPersisterCall()
    {

        $config = Yaml::parse(file_get_contents(__DIR__.'/config/config.yml'));

        $containerBuilder = new ContainerBuilder;
        $containerBuilder->setParameter('kernel.debug', true);

        $extension = new FOSElasticaExtension;

        $extension->load($config, $containerBuilder);

        $this->assertTrue($containerBuilder->hasDefinition('fos_elastica.object_persister.test_index.child_field'));

        $persisterCallDefinition = $containerBuilder->getDefinition('fos_elastica.object_persister.test_index.child_field');

        $arguments = $persisterCallDefinition->getArguments();
        $arguments = $arguments['index_3'];

        $this->assertArrayHasKey('_parent', $arguments);
        $this->assertEquals('parent_field', $arguments['_parent']['type']);
    }

} 