<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */

namespace Shopware\Category\Gateway;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Shopware\Category\Gateway\Query\CategoryIdentityQuery;
use Shopware\Category\Struct\CategoryHydrator;
use Shopware\Context\Struct\TranslationContext;
use Shopware\Framework\Struct\FieldHelper;
use Shopware\Search\Criteria;
use Shopware\Search\Search;
use Shopware\Search\SearchResultInterface;

class CategorySearcher extends Search
{
    /**
     * @var FieldHelper
     */
    private $fieldHelper;

    /**
     * @var CategoryHydrator
     */
    private $hydrator;

    public function __construct(Connection $connection, array $handlers, FieldHelper $fieldHelper, CategoryHydrator $hydrator)
    {
        parent::__construct($connection, $handlers);
        $this->fieldHelper = $fieldHelper;
        $this->hydrator = $hydrator;
    }

    protected function createQuery(Criteria $criteria, TranslationContext $context): QueryBuilder
    {
        return new CategoryIdentityQuery($this->connection, $this->fieldHelper, $context);
    }

    protected function createResult(array $rows, int $total): SearchResultInterface
    {
        $rows = array_map(function(array $row) {
            return $this->hydrator->hydrateIdentity($row);
        }, $rows);

        return new CategorySearchResult($rows, $total);
    }
}
