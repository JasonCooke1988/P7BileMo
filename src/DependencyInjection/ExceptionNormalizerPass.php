<?php


namespace App\DependencyInjection;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


class ExceptionNormalizerPass implements CompilerPassInterface
{

    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        $exceptionListener = $container->findDefinition('app.exception_subscriber');
        $normalizers = $container->findTaggedServiceIds('app.normalizer');

        foreach ($normalizers as $normalizer) {
            $exceptionListener->addMethodCall('addNormalizer', [new Reference(key($normalizers))]);
        }
    }
}
