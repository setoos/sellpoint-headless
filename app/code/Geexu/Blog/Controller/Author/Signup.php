<?php

namespace Geexu\Blog\Controller\Author;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;
use Geexu\Blog\Helper\Data;
use Geexu\Blog\Model\Config\Source\SideBarLR;

/**
 * Class View
 * @package Geexu\Blog\Controller\Author
 */
class Signup extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;

    /**
     * @var ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var Data
     */
    protected $_helperBlog;

    /**
     * View constructor.
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param ForwardFactory $resultForwardFactory
     * @param Data $helperData
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        Data $helperData
    ) {
        $this->_helperBlog = $helperData;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;

        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|Redirect|ResultInterface|Page
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $this->_helperBlog->setCustomerContextId();

        if (!$this->_helperBlog->isEnabled()
            || !$this->_helperBlog->isEnabledAuthor()
            || ($this->_helperBlog->isAuthor() && !$this->_helperBlog->getConfigGeneral('customer_approve'))) {
            $resultRedirect->setPath('customer/account');

            return $resultRedirect;
        }

        if ($this->_helperBlog->isAuthor()) {
            $page = $this->resultPageFactory->create();
            $page->getConfig()->setPageLayout(SideBarLR::LEFT);
            $page->getConfig()->getTitle()->set('Signup Author');

            return $page;
        }

        if ($this->_helperBlog->isLogin()) {
            $resultRedirect->setPath('mpblog/*/information');
        } else {
            $resultRedirect->setPath('customer/account');
        }

        return $resultRedirect;
    }
}
