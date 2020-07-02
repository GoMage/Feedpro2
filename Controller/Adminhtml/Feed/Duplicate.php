<?php

/**
 * GoMage.com
 *
 * GoMage Feed Pro M2
 *
 * @category     Extension
 * @copyright    Copyright (c) 2010-2018 GoMage.com (https://www.gomage.com)
 * @author       GoMage.com
 * @license      https://www.gomage.com/licensing  Single domain license
 * @terms of use https://www.gomage.com/terms-of-use
 * @version      Release: 1.2.0
 * @since        Class available since Release 1.2.0
 */

namespace GoMage\Feed\Controller\Adminhtml\Feed;

use GoMage\Core\Helper\Data as coreHelper;
use GoMage\Feed\Controller\Adminhtml\Feed as FeedController;
use GoMage\Feed\Model\FeedFactory;
use Magento\Backend\App\Action;
use Magento\Backend\Model\Session;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Json\Helper\Data as jsonHelper;

/**
 * Class Duplicate
 * @package GoMage\Feed\Controller\Adminhtml\Feed
 */
class Duplicate extends FeedController
{
    /**
     * @var FeedFactory
     */
    private $feedFactory;

    /**
     * Duplicate constructor.
     * @param Action\Context $context
     * @param FeedFactory $feedFactory
     * @param jsonHelper $jsonHelper
     * @param Session $session
     * @param coreHelper $coreHelper
     */
    public function __construct(
        Action\Context $context,
        FeedFactory $feedFactory,
        jsonHelper $jsonHelper,
        Session $session,
        coreHelper $coreHelper
    ) {
        parent::__construct($context, $feedFactory, $jsonHelper, $session, $coreHelper);
        $this->feedFactory = $feedFactory;
    }

    /**
     * Save feed
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        try {
            /** @var \GoMage\Feed\Model\Feed $model */
            $model = $this->feedFactory->create();
            $id = $this->getRequest()->getParam('id');

            if (!$id) {
                return $redirectResult->setPath('gomage_feed/feed/index');
            }

            $model->load($id);

            $duplicatedFeed = clone $model;
            $duplicatedFeed->setId(null);

            $name = 'Duplicated ' . $duplicatedFeed->getName();
            $duplicatedFeed->setName($name)
                ->setCronGeneratedAt(null)
                ->setCronUploadedAt(null)
                ->setStatus(0);

            $duplicatedFeed->save();
            $duplicatedFeedId = (int)$duplicatedFeed->getId();

            if ($duplicatedFeedId) {
                $this->messageManager->addSuccess(__('You duplicated the feed.'));
                return $redirectResult->setPath(
                    'gomage_feed/feed/edit',
                    [
                        'id' => $duplicatedFeedId,
                    ]
                );
            }
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while duplicate the feed.'));
        }

        return $redirectResult->setPath('gomage_feed/feed/index');
    }
}
