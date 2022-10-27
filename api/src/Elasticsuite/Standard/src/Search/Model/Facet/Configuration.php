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

namespace Elasticsuite\Search\Model\Facet;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Elasticsuite\Category\Model\Category;
use Elasticsuite\Entity\Filter\RangeFilterWithDefault;
use Elasticsuite\Entity\Filter\SearchFilterWithDefault;
use Elasticsuite\Metadata\Model\SourceField;
use Elasticsuite\User\Constant\Role;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'FacetConfiguration',
    collectionOperations: [
        'get',
    ],
    itemOperations: [
        'get',
        'put' => [
            'security' => "is_granted('" . Role::ROLE_ADMIN . "')",
            'normalization_context' => ['groups' => ['facet_configuration:read']],
            'denormalization_context' => ['groups' => ['facet_configuration:write']],
        ],
        'patch' => [
            'security' => "is_granted('" . Role::ROLE_ADMIN . "')",
            'normalization_context' => ['groups' => ['facet_configuration:read']],
            'denormalization_context' => ['groups' => ['facet_configuration:write']],
        ],
        'delete' => ['security' => "is_granted('" . Role::ROLE_ADMIN . "')"],
    ],
    graphql: [
        'item_query' => [
            'normalization_context' => ['groups' => ['facet_configuration:read', 'facet_configuration:graphql_read']],
            'denormalization_context' => ['groups' => ['facet_configuration:read', 'facet_configuration:graphql_read']],
        ],
        'collection_query' => [
            'normalization_context' => ['groups' => ['facet_configuration:read', 'facet_configuration:graphql_read']],
            'denormalization_context' => ['groups' => ['facet_configuration:read', 'facet_configuration:graphql_read']],
        ],
        'update' => [
            'security' => "is_granted('" . Role::ROLE_ADMIN . "')",
            'normalization_context' => ['groups' => ['facet_configuration:read', 'facet_configuration:graphql_read']],
            'denormalization_context' => ['groups' => ['facet_configuration:write']],
        ],
        'delete' => ['security' => "is_granted('" . Role::ROLE_ADMIN . "')"],
    ],
    normalizationContext: ['groups' => ['facet_configuration:read']],
    denormalizationContext: ['groups' => ['facet_configuration:read']],
    attributes: [
        'elasticsuite' => [
            // Allows to add cache tag "/source_fields" in the HTTP response to invalidate proxy cache when a source field is saved.
            'cache_tag' => ['resource_classes' => [SourceField::class]],
        ],
    ],
)]
#[ApiFilter(SearchFilterWithDefault::class, properties: ['category' => 'exact', 'displayMode' => 'exact', 'sortOrder' => 'exact'], arguments: ['defaultValues' => self::DEFAULT_VALUES])]
#[ApiFilter(RangeFilterWithDefault::class, properties: ['coverageRate', 'maxSize'], arguments: ['defaultValues' => self::DEFAULT_VALUES])]
class Configuration
{
    public const DISPLAY_MODE_AUTO = 'auto';
    public const DISPLAY_MODE_ALWAYS_DISPLAYED = 'displayed';
    public const DISPLAY_MODE_ALWAYS_HIDDEN = 'hidden';

    private const DEFAULT_VALUES = [
        'displayMode' => self::DISPLAY_MODE_AUTO,
        'coverageRate' => 90,
        'maxSize' => 10,
        'sortOrder' => 'result_count',
        'isRecommendable' => false,
        'isVirtual' => false,
    ];

    #[Groups(['facet_configuration:read'])]
    private string $id;

    #[Groups(['facet_configuration:read'])]
    private SourceField $sourceField;

    #[Groups(['facet_configuration:read'])]
    private ?Category $category;

    #[ApiProperty(
        attributes: [
            'hydra:supportedProperty' => [
                'hydra:property' => [
                    'rdfs:label' => 'Display',
                ],
                'elasticsuite' => [
                    'visible' => true,
                    'editable' => true,
                    'position' => 20,
                    'input' => 'select',
                    'options' => [
                        'values' => [
                            ['value' => self::DISPLAY_MODE_AUTO, 'label' => 'Auto'],
                            ['value' => self::DISPLAY_MODE_ALWAYS_DISPLAYED, 'label' => 'Displayed'],
                            ['value' => self::DISPLAY_MODE_ALWAYS_HIDDEN, 'label' => 'Hidden'],
                        ],
                    ],
                ],
            ],
        ],
    )]
    #[Groups(['facet_configuration:read', 'facet_configuration:write'])]
    private ?string $displayMode = null;

    #[ApiProperty(
        attributes: [
            'hydra:supportedProperty' => [
                'hydra:property' => [
                    'rdfs:label' => 'Coverage',
                ],
                'elasticsuite' => [
                    'visible' => true,
                    'editable' => true,
                    'position' => 30,
                    'input' => 'percentage',
                    'validation' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
            ],
        ],
    )]
    #[Groups(['facet_configuration:read', 'facet_configuration:write'])]
    private ?int $coverageRate = null;

    #[ApiProperty(
        attributes: [
            'hydra:supportedProperty' => [
                'hydra:property' => [
                    'rdfs:label' => 'Max size',
                ],
                'elasticsuite' => [
                    'visible' => true,
                    'editable' => true,
                    'position' => 40,
                    'validation' => [
                        'min' => 0,
                    ],
                ],
            ],
        ],
    )]
    #[Groups(['facet_configuration:read', 'facet_configuration:write'])]
    private ?int $maxSize = null;

    #[ApiProperty(
        attributes: [
            'hydra:supportedProperty' => [
                'hydra:property' => [
                    'rdfs:label' => 'Sort order',
                ],
                'elasticsuite' => [
                    'visible' => true,
                    'editable' => true,
                    'position' => 50,
                    'input' => 'select',
                    // Todo: move the options values in the proper class and add validation constraint (will be done in the ticket ESPP-226)
                    'options' => [
                        'values' => [
                            ['value' => 'result_count', 'label' => 'Result count'],
                            ['value' => 'admin_sort', 'label' => 'Admin sort'],
                            ['value' => 'name', 'label' => 'Name'],
                            ['value' => 'relevance', 'label' => 'Relevance'],
                        ],
                    ],
                ],
            ],
        ],
    )]
    #[Groups(['facet_configuration:read', 'facet_configuration:write'])]
    private ?string $sortOrder = null;

    #[ApiProperty(
        attributes: [
            'hydra:supportedProperty' => [
                'hydra:property' => [
                    'rdfs:label' => 'Facet recommenders',
                ],
                'elasticsuite' => [
                    'visible' => false,
                    'editable' => true,
                    'position' => 60,
                ],
            ],
        ],
    )]
    #[Groups(['facet_configuration:read', 'facet_configuration:write'])]
    private ?bool $isRecommendable = null;

    #[ApiProperty(
        attributes: [
            'hydra:supportedProperty' => [
                'hydra:property' => [
                    'rdfs:label' => 'Virtual attributes',
                ],
                'elasticsuite' => [
                    'visible' => false,
                    'editable' => true,
                    'position' => 70,
                ],
            ],
        ],
    )]
    #[Groups(['facet_configuration:read', 'facet_configuration:write'])]
    private ?bool $isVirtual = null;

    #[Groups(['facet_configuration:read'])]
    private ?string $defaultDisplayMode = null;

    #[Groups(['facet_configuration:read'])]
    private ?int $defaultCoverageRate = null;

    #[Groups(['facet_configuration:read'])]
    private ?int $defaultMaxSize = null;

    #[Groups(['facet_configuration:read'])]
    private ?string $defaultSortOrder = null;

    #[Groups(['facet_configuration:read'])]
    private ?bool $defaultIsRecommendable = null;

    #[Groups(['facet_configuration:read'])]
    private ?bool $defaultIsVirtual = null;

    public function __construct(SourceField $sourceField, ?Category $category)
    {
        $this->sourceField = $sourceField;
        $this->category = $category;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getSourceField(): SourceField
    {
        return $this->sourceField;
    }

    public function setSourceField(SourceField $sourceField): void
    {
        $this->sourceField = $sourceField;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    public function getDisplayMode(): ?string
    {
        return $this->displayMode ?? $this->getDefaultDisplayMode();
    }

    public function setDisplayMode(?string $displayMode): void
    {
        $this->displayMode = '' == $displayMode ? null : $displayMode;
    }

    public function getCoverageRate(): ?int
    {
        return $this->coverageRate ?? $this->getDefaultCoverageRate();
    }

    public function setCoverageRate(?int $coverageRate): void
    {
        $this->coverageRate = '' == $coverageRate ? null : $coverageRate;
    }

    public function getMaxSize(): ?int
    {
        return $this->maxSize ?? $this->getDefaultMaxSize();
    }

    public function setMaxSize(?int $maxSize): void
    {
        $this->maxSize = $maxSize;
    }

    public function getSortOrder(): ?string
    {
        return $this->sortOrder ?? $this->getDefaultSortOrder();
    }

    public function setSortOrder(?string $sortOrder): void
    {
        $this->sortOrder = $sortOrder;
    }

    public function getIsRecommendable(): ?bool
    {
        return $this->isRecommendable ?? $this->getDefaultIsRecommendable();
    }

    public function setIsRecommendable(?bool $isRecommendable): void
    {
        $this->isRecommendable = $isRecommendable;
    }

    public function getIsVirtual(): ?bool
    {
        return $this->isVirtual ?? $this->getDefaultIsVirtual();
    }

    public function setIsVirtual(?bool $isVirtual): void
    {
        $this->isVirtual = $isVirtual;
    }

    public function getDefaultDisplayMode(): ?string
    {
        return $this->defaultDisplayMode;
    }

    public function getDefaultCoverageRate(): ?int
    {
        return $this->defaultCoverageRate;
    }

    public function getDefaultMaxSize(): ?int
    {
        return $this->defaultMaxSize;
    }

    public function getDefaultSortOrder(): ?string
    {
        return $this->defaultSortOrder;
    }

    public function getDefaultIsRecommendable(): ?bool
    {
        return $this->defaultIsRecommendable;
    }

    public function getDefaultIsVirtual(): ?bool
    {
        return $this->defaultIsVirtual;
    }

    #[ApiProperty(
        attributes: [
            'hydra:supportedProperty' => [
                'hydra:property' => [
                    'rdfs:label' => 'Attribute code',
                ],
                'elasticsuite' => [
                    'visible' => true,
                    'editable' => false,
                    'position' => 10,
                ],
            ],
        ],
    )]
    #[Groups(['facet_configuration:read'])]
    public function getSourceFieldCode(): string
    {
        return $this->getSourceField()->getCode();
    }

    public function initDefaultValue(self $defaultConfiguration)
    {
        $this->defaultDisplayMode = $defaultConfiguration->getDisplayMode() ?? self::DEFAULT_VALUES['displayMode'];
        $this->defaultCoverageRate = $defaultConfiguration->getCoverageRate() ?? self::DEFAULT_VALUES['coverageRate'];
        $this->defaultMaxSize = $defaultConfiguration->getMaxSize() ?? self::DEFAULT_VALUES['maxSize'];
        $this->defaultSortOrder = $defaultConfiguration->getSortOrder() ?? self::DEFAULT_VALUES['sortOrder'];
        $this->defaultIsRecommendable = $defaultConfiguration->getIsRecommendable() ?? self::DEFAULT_VALUES['isRecommendable'];
        $this->defaultIsVirtual = $defaultConfiguration->getIsVirtual() ?? self::DEFAULT_VALUES['isVirtual'];
    }

    public static function getAvailableDisplayModes(): array
    {
        return [
            self::DISPLAY_MODE_AUTO,
            self::DISPLAY_MODE_ALWAYS_DISPLAYED,
            self::DISPLAY_MODE_ALWAYS_HIDDEN,
        ];
    }
}
