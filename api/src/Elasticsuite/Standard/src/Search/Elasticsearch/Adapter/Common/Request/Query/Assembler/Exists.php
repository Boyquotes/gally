<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile ElasticSuite to newer
 * versions in the future.
 *
 * @package   Elasticsuite
 * @author    ElasticSuite Team <elasticsuite@smile.fr>
 * @copyright 2022-present Smile
 * @license   Open Software License v. 3.0 (OSL-3.0)
 */

declare(strict_types=1);

namespace Elasticsuite\Search\Elasticsearch\Adapter\Common\Request\Query\Assembler;

use Elasticsuite\Search\Elasticsearch\Adapter\Common\Request\Query\AssemblerInterface;
use Elasticsuite\Search\Elasticsearch\Request\QueryInterface;

/**
 * Assemble an ES exists field query.
 */
class Exists implements AssemblerInterface
{
    /**
     * {@inheritDoc}
     */
    public function assembleQuery(QueryInterface $query): array
    {
        if (QueryInterface::TYPE_EXISTS !== $query->getType()) {
            throw new \InvalidArgumentException("Query assembler : invalid query type {$query->getType()}");
        }

        /** @var \Elasticsuite\Search\Elasticsearch\Request\Query\Exists $query */
        $searchQuery = ['exists' => ['field' => $query->getField()]];

        if ($query->getName()) {
            $searchQuery['exists']['_name'] = $query->getName();
        }

        return $searchQuery;
    }
}
