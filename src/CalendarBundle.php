<?php

declare(strict_types=1);

namespace CalendarBundle;

use CalendarBundle\Controller\CalendarController;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use CalendarBundle\Serializer\Serializer;

class CalendarBundle extends AbstractBundle {

    public function getConfigTreeBuilder(): TreeBuilder
    {
        return new TreeBuilder('tattali_calendar');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->autowire(CalendarController::class)
            ->addTag('container.service_subscriber')
            ->addTag('controller.service_arguments')
        ;

    }


}
