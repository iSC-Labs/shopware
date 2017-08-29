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

namespace Shopware\Bundle\StoreFrontBundle\Manufacturer;

use Doctrine\DBAL\Connection;
use Shopware\Framework\Struct\FieldHelper;
use Shopware\Context\Struct\TranslationContext;
use Shopware\ProductManufacturer\Struct\ProductManufacturerHydrator;

/**
 * @category  Shopware
 *
 * @copyright Copyright (c) shopware AG (http://www.shopware.de)
 */
class ManufacturerGateway
{
    /**
     * @var ProductManufacturerHydrator
     */
    private $manufacturerHydrator;

    /**
     * The FieldHelper class is used for the
     * different table column definitions.
     *
     * This class helps to select each time all required
     * table data for the store front.
     *
     * Additionally the field helper reduce the work, to
     * select in a second step the different required
     * attribute tables for a parent table.
     *
     * @var \Shopware\Framework\Struct\FieldHelper
     */
    private $fieldHelper;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @param Connection           $connection
     * @param \Shopware\Framework\Struct\FieldHelper          $fieldHelper
     * @param ProductManufacturerHydrator $manufacturerHydrator
     */
    public function __construct(
        Connection $connection,
        FieldHelper $fieldHelper,
        ProductManufacturerHydrator $manufacturerHydrator
    ) {
        $this->connection = $connection;
        $this->manufacturerHydrator = $manufacturerHydrator;
        $this->fieldHelper = $fieldHelper;
    }

    /**
     * @param int[]              $ids
     * @param \Shopware\Context\Struct\TranslationContext $context
     *
     * @return \Shopware\ProductManufacturer\Struct\ProductManufacturer[] Indexed by the manufacturer id
     */
    public function getList(array $ids, TranslationContext $context)
    {
        $query = $this->connection->createQueryBuilder();

        $query->addSelect($this->fieldHelper->getManufacturerFields());

        $query->from('s_articles_supplier', 'manufacturer')
            ->leftJoin('manufacturer', 's_articles_supplier_attributes', 'manufacturerAttribute', 'manufacturerAttribute.supplierID = manufacturer.id')
            ->where('manufacturer.id IN (:ids)')
            ->setParameter(':ids', $ids, Connection::PARAM_INT_ARRAY);

        $this->fieldHelper->addManufacturerTranslation($query, $context);

        /** @var $statement \Doctrine\DBAL\Driver\ResultStatement */
        $statement = $query->execute();

        $data = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $manufacturers = [];
        foreach ($data as $row) {
            $id = $row['__manufacturer_id'];
            $manufacturers[$id] = $this->manufacturerHydrator->hydrate($row);
        }

        //sort elements by provided ids, sorting is defined by other queries like `best term match` or `max articles` or `sort alphanumeric`
        $sorted = [];
        foreach ($ids as $id) {
            if (!array_key_exists($id, $manufacturers)) {
                continue;
            }
            $sorted[$id] = $manufacturers[$id];
        }

        return $sorted;
    }
}
