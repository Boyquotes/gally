<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile ElasticSuite to newer
 * versions in the future.
 *
 * @package   Elasticsuite
 * @author    ElasticSuite Team <elasticsuite@smile.fr>
 * @copyright 2022 Smile
 * @license   Licensed to Smile-SA. All rights reserved. No warranty, explicit or implicit, provided.
 *            Unauthorized copying of this file, via any medium, is strictly prohibited.
 */

declare(strict_types=1);

namespace Elasticsuite\Search\Elasticsearch\Request\Container\Configuration;

use Elasticsuite\Search\Elasticsearch\Request\ContainerConfigurationFactoryInterface;
use Elasticsuite\Search\Elasticsearch\Request\ContainerConfigurationInterface;

class GenericContainerConfigurationFactory implements ContainerConfigurationFactoryInterface
{
    public function create(array $data = []): ContainerConfigurationInterface
    {
        return new GenericContainerConfiguration(...$data);
    }
}
