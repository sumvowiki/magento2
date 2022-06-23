<?php

namespace Marvelic\PromoLists\Model\ResourceModel\Promotion;

use Magento\Catalog\Model\Product;
use Magento\SalesRule\Model\Rule;
use Magento\Eav\Model\Entity\Collection\AbstractCollection;
use Marvelic\PromoLists\Api\Data\PromotionInterface;
use Marvelic\PromoLists\Model\Category;
use Marvelic\PromoLists\Model\Promotion;

class Collection extends AbstractCollection
{
    public function _afterLoad()
    {
        foreach ($this->_items as $item) {
            $item->load($item->getId());
        }

        return parent::_afterLoad();
    }

//    /**
//     * @param Product $product
//     *
//     * @return $this
//     */
//    public function addRelatedProductFilter($product)
//    {
//        $this->getSelect()
//            ->where("EXISTS (SELECT * FROM `{$this->getTable('promolist_promotion_product')}`
//                AS `promotion_product`
//                WHERE e.entity_id = promotion_product.promotion_id
//                AND promotion_product.product_id in (?))", [0, $product->getId()]);
//
//        return $this;
//    }

    /**
     * @param string $q
     *
     * @return $this
     */
    public function addSearchFilter($q)
    {
        $likeExpression = $this->_resourceHelper->addLikeEscape($q, ['position' => 'any']);

        $this->addAttributeToSelect('name');

        $this->addAttributeToFilter([
            ['attribute' => 'name', 'like' => $likeExpression],
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    public function addVisibilityFilter()
    {
        $this->addAttributeToFilter(PromotionInterface::STATUS, 1);

        return $this;
    }

    /**
     * @return $this
     */
    public function defaultOrder()
    {
        $this->addAttributeToSort('order', self::SORT_ORDER_DESC);
        $this->getSelect()->order('created_at DESC');

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function _construct()
    {
        $this->_init('Marvelic\PromoLists\Model\Promotion', 'Marvelic\PromoLists\Model\ResourceModel\Promotion');
    }
}
