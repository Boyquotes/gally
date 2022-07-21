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

namespace Elasticsuite\Search\Elasticsearch\Adapter\Common\Request\Aggregation\Assembler\Pipeline;

use Elasticsuite\Search\Elasticsearch\Adapter\Common\Request\Aggregation\AssemblerInterface;
use Elasticsuite\Search\Elasticsearch\Request\Aggregation\Pipeline\BucketSelector as BucketSelectPipeline;
use Elasticsuite\Search\Elasticsearch\Request\AggregationInterface;
use Elasticsuite\Search\Elasticsearch\Request\PipelineInterface;

/**
 * Assemble a bucket selector ES pipeline aggregation.
 */
class BucketSelector implements AssemblerInterface
{
    /**
     * {@inheritDoc}
     */
    public function assembleAggregation(AggregationInterface $aggregation): array
    {
        if (PipelineInterface::TYPE_BUCKET_SELECTOR !== $aggregation->getType()) {
            throw new \InvalidArgumentException("Aggregation assembler : invalid pipeline type {$aggregation->getType()}.");
        }

        /** @var BucketSelectPipeline $aggregation */
        $aggParams = [
            'buckets_path' => $aggregation->getBucketsPath(),
            'script' => $aggregation->getScript(),
            'gap_policy' => $aggregation->getGapPolicy(),
        ];

        return ['bucket_selector' => $aggParams];
    }
}