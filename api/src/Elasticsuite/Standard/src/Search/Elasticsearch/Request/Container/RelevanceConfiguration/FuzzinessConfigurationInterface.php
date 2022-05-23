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

namespace Elasticsuite\Search\Elasticsearch\Request\Container\RelevanceConfiguration;

/**
 * FuzzinessConfiguration object interface.
 */
interface FuzzinessConfigurationInterface
{
    /**
     * Get Fuzziness value.
     */
    public function getValue(): string|int;

    /**
     * Get Prefix Length.
     */
    public function getPrefixLength(): int;

    /**
     * Get Max. Expansions.
     */
    public function getMaxExpansion(): int;
}
