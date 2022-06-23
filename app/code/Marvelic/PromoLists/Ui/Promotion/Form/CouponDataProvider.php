<?php

namespace Marvelic\PromoLists\Ui\Promotion\Form;

use Magento\SalesRule\Model\ResourceModel\Rule\Collection;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\SalesRule\Model\ResourceModel\Rule\CollectionFactory;
use Magento\SalesRule\Model\Rule;
use Magento\Ui\DataProvider\Modifier\ModifierInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;

class CouponDataProvider extends \Magento\SalesRule\Model\Rule\DataProvider
{

    /**
     * @var PoolInterface
     */
    private $modifiersPool;


    /**
     * @param $name
     * @param $primaryFieldName
     * @param $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\SalesRule\Model\Rule\Metadata\ValueProvider $metadataValueProvider
     * @param array $meta
     * @param array $data
     * @param DataPersistorInterface|null $dataPersistor
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        \Magento\Framework\Registry $registry,
        \Magento\SalesRule\Model\Rule\Metadata\ValueProvider $metadataValueProvider,
        array $meta = [],
        array $data = [],
        DataPersistorInterface $dataPersistor = null,
        PoolInterface $modifiersPool = null
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $collectionFactory, $registry,
            $metadataValueProvider, $meta, $data, $dataPersistor);
        $this->modifiersPool = $modifiersPool ?: ObjectManager::getInstance()->get(PoolInterface::class);
    }

    public function getCollection()
    {
        /** @var Collection $collection */
        $collection = parent::getCollection();

        return $collection->load();
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        $items = $this->getCollection()->getItems();

        /** @var Rule $rule */
        foreach ($items as $key => $rule) {
            $rule->load($rule->getId());
            if($rule->getCouponType() == 2){
                $datas[$key] = $rule->getData();
            }

        }

        $data = [
            'totalRecords' => $this->getCollection()->getSize(),
            'items' => array_values($datas),
        ];
        return $data;
    }

    /**
     * @inheritdoc
     * @since 103.0.0
     */
    public function getMeta()
    {
        $meta = parent::getMeta();

        /** @var ModifierInterface $modifier */
        foreach ($this->modifiersPool->getModifiersInstances() as $modifier) {
            $meta = $modifier->modifyMeta($meta);
        }

        return $meta;
    }

    /**
     * Get metadata values
     *
     * @return array
     */
    protected function getMetadataValues()
    {
        return [];
    }
}
