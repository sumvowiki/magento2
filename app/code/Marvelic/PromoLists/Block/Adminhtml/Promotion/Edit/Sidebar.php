<?php

namespace Marvelic\PromoLists\Block\Adminhtml\Promotion\Edit;

use Exception;
use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Framework\Registry;
use Marvelic\PromoLists\Model\Promotion;
use Zend_Locale_Data;
use Zend_Locale_Exception;

class Sidebar extends Template
{
    /**
     * @var string
     */
//    protected $_template = "promotion/edit/sidebar.phtml";

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Registry $registry
     * @param Context  $context
     */
    public function __construct(
        ResolverInterface $localeResolver,
        Registry $registry,
        Context $context
    ) {
        $this->localeResolver = $localeResolver;
        $this->registry       = $registry;

        parent::__construct($context);
    }

    /**
     * @return Promotion
     */
    public function getPromotion()
    {
        return $this->registry->registry('promotion');
    }

    /**
     * @param string $param
     * @param string $default
     *
     * @return string
     * @throws Zend_Locale_Exception
     */
    public function getLocaleData($param, $default = '')
    {
        try {
            $text = Zend_Locale_Data::getContent($this->localeResolver->getLocale(), $param);
        } catch (Exception $e) {
            $text = $default;
        }

        return $text;
    }
}
