<?php

namespace Marvelic\PromoLists\Block\Adminhtml\Promotion\Edit\Tab;

use Magento\Backend\Block\Widget\Context;
use Magento\Backend\Block\Widget\Form;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;
use Marvelic\PromoLists\Model\Promotion;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Marvelic\PromoLists\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;

class General extends Generic implements TabInterface
{
    /**
     * @var CategoryCollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var FormFactory
     */
    protected $formFactory;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var WysiwygConfig
     */
    protected $wysiwygConfig;

//    /**
//     * @param CategoryCollectionFactory $promotionCollectionFactory
//     * @param WysiwygConfig $wysiwygConfig
//     * @param FormFactory   $formFactory
//     * @param Registry      $registry
//     * @param Context       $context
//     */
//    public function __construct(
//        CategoryCollectionFactory $promotionCollectionFactory,
//        WysiwygConfig $wysiwygConfig,
//        FormFactory $formFactory,
//        Registry $registry,
//        Context $context
//    ) {
//        $this->categoryCollectionFactory = $promotionCollectionFactory;
//        $this->wysiwygConfig = $wysiwygConfig;
//        $this->formFactory   = $formFactory;
//        $this->registry      = $registry;
//
//        parent::__construct($context);
//    }

    /**
     * @param CategoryCollectionFactory $promotionCollectionFactory
     * @param \Magento\Backend\Block\Template\Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        CategoryCollectionFactory $promotionCollectionFactory,
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        $this->categoryCollectionFactory = $promotionCollectionFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * @return $this
     * @throws LocalizedException
     */
    protected function _prepareForm()
    {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('promotion_');

        /** @var Promotion $promotion */
        $promotion = $this->_coreRegistry->registry('promotion');

        $fieldset = $form->addFieldset('setting_fieldset', [
            'legend' => __('General Setting'),
            'class' => 'fieldset-wide'
        ]);

        if ($promotion->getId()) {
            $fieldset->addField('entity_id', 'hidden', [
                'name'  => 'promotion[entity_id]',
                'value' => $promotion->getId(),
            ]);
        }

        $fieldset->addField('name', 'text', [
            'label'    => __('Title'),
            'name'     => 'promotion[name]',
            'value'    => $promotion->getName(),
            'required' => true,
        ]);

        $fieldset->addField('status', 'select', [
            'label'  => __('Status'),
            'name'   => 'promotion[status]',
            'value'  => $promotion->getStatus(),
            'options' => [0 => __('Disabled'), 1 => __('Enabled')],
        ]);

//        if ($this->blogStoreview->isMultiStore()) {
//            $container = 'blog_post_store_views';
//            $fieldset->addField('store_ids', 'hidden', [
//                'name'             => 'post[store_ids]',
//                'value'            => implode(',', $post->getStoreIds()),
//                'after_element_js' => $this->blogStoreview->getField(
//                    $post,
//                    $container
//                ),
//            ]);
//        } else {
//            $fieldset->addField('store_ids', 'hidden', [
//                'name'  => 'post[store_ids]',
//                'value' => 0,
//            ]);
//            $fieldset->addField('store_ids_note', 'note', [
//                'text' => __('All Store Views'),
//            ]);
//        }

        if (!$this->_storeManager->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_ids',
                'select',
                [
                    'name' => 'store_ids',
                    'label' => __('Store'),
                    'title' => __('Store'),
                    'values' => implode(',', $promotion->getStoreIds()),
                    'required' => true
                ]
            );
            $renderer = $this->getLayout()->createBlock(
                \Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element::class
            );
            $field->setRenderer($renderer);
        } else {
            $fieldset->addField('store_id', 'hidden', ['name' => 'store_id']);
            $promotion->setStoreIds($this->_storeManager->getStore(true)->getId());
        }

        $fieldset->addField('created_at', 'date', [
            'label'       => __('Published on'),
            'name'        => 'promotion[created_at]',
            'value'       => $promotion->getCreatedAt(),
            'date_format' => 'MMM d, y',
            'time_format' => 'h:mm a',
        ]);

        $categoryCollection = $this->categoryCollectionFactory->create()
            ->addAttributeToSelect(['name']);

        $fieldset->addField('category_ids', 'checkboxes', [
            'name'   => 'promotion[category_ids][]',
            'value'  => $promotion->getCategoryIds(),
            'values' => $categoryCollection->toOptionArray(),
        ]);

//        $fieldset->addField('cover_image', 'image', [
//            'required' => false,
//            'name'     => 'cover_image',
//            'value'    => $promotion->getCoverImageUrl(),
//        ]);
//
//        $fieldset->addField('cover_image', 'text', [
//            'required' => false,
//            'label'    => __('Alt'),
//            'name'     => 'promotion[cover_image]',
//            'value'    => $promotion->getCoverImageUrl(),
//        ]);

//
//        $editorConfig = $this->wysiwygConfig->getConfig(['tab_id' => $this->getTabId()]);
//
//        $fieldset->addField('content', 'editor', [
//            'name'    => 'promotion[content]',
//            'value'   => $promotion->getContent(),
//            'wysiwyg' => true,
//            'style'   => 'height:35em',
//            'config'  => $editorConfig,
//        ]);
//
//        $fieldset->addField('short_content', 'editor', [
//            'label'   => __('Excerpt'),
//            'name'    => 'promotion[short_content]',
//            'value'   => $promotion->getShortContent(),
//            'wysiwyg' => true,
//            'style'   => 'height:5em',
//            'config'  => $editorConfig,
//        ]);
        $form->setValues($promotion->getData());
        $this->setForm($form);
        return parent::_prepareForm();
    }
    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('General Setting');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('General Setting');
    }
    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

}
