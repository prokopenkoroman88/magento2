<?php

namespace Vendorname\Randomthree\Block;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Model\Product;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;//+6.12.21
    public $context;
    public $imageHelper;
    public $productRepository;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $data);
        $this->context=$context;//+
        $this->imageHelper = $imageHelper;
        $this->productRepository = $productRepository;
    }

    /**
     * Returns collection of products
     *
     * @param Number $elementsCount
     * @return Array
     */
    public function getProductCollection($elementsCount){
        $type_id='configurable';//'simple'

        //Получение массива случайных ссылок всех товаров
        $collectionIds = $this->_productCollectionFactory->create();
        $collectionIds->addAttributeToSelect(array('entity_id'));
        $collectionIds->addFieldToFilter(array(array('attribute' => 'type_id', 'eq' => $type_id)));
        $collectionIds->load();
        $collectionIds=$collectionIds->toArray();
        shuffle($collectionIds);

        //Получение массива 3 случайных ссылок товаров
        $randomIds=[];
        for($i=0; $i<$elementsCount; $i++){
            $id=$collectionIds[$i]['entity_id'];//$i+1
            array_push($randomIds, $id);
        };

        //Отбор 3 товаров по отобранным ссылкам
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect(array('name','price','url_key','thumbnail'));//'*'
        $collection->addFieldToFilter(array(array('attribute' => 'type_id', 'eq' => $type_id)));//
        $collection->addFieldToFilter(array(array('attribute' => 'entity_id', 'in' => $randomIds)));
        $collection->load();

        return  $collection;
    }
}
