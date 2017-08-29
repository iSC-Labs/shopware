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

namespace Shopware\Bundle\StoreFrontBundle\Vote;

use Shopware\Context\Struct\ShopContext;
use Shopware\Bundle\StoreFrontBundle\Product\BaseProduct;

/**
 * @category  Shopware
 *
 * @copyright Copyright (c) shopware AG (http://www.shopware.de)
 */
interface VoteServiceInterface
{
    /**
     * @param BaseProduct[]        $products
     * @param \Shopware\Context\Struct\ShopContext $context
     *
     * @return array indexed by the product order number, each array element contains a \Shopware\Bundle\StoreFrontBundle\Vote list
     */
    public function getList($products, ShopContext $context);

    /**
     * @param BaseProduct[]                                                  $products
     * @param \Shopware\Context\Struct\ShopContext $context
     *
     * @return VoteAverage[] Indexed by the product order number - Sorted by the vote create date
     */
    public function getAverages($products, ShopContext $context);
}
