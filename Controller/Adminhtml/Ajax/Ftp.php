<?php
namespace GoMage\Feed\Controller\Adminhtml\Ajax;

class Ftp extends \Magento\Framework\App\Action\Action implements \Magento\Framework\App\CsrfAwareActionInterface
{
    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * Ftp constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $params = [];
            $params['ftp_protocol'] = $this->getRequest()->getParam('ftpProtocol');
            $params['host'] = $this->replaceFtpHostname($this->getRequest()->getParam('ftpHost'));
            $params['port'] = $this->getRequest()->getParam('ftpPort');
            $params['user'] = $this->getRequest()->getParam('ftpUser');
            $params['password'] = $this->getRequest()->getParam('ftpPassword');
            $params['passive_mode'] = $this->getRequest()->getParam('ftpPassiveMode');

            $exeption = false;
            if (!$params['ftp_protocol']) {
                $exeption = $this->getFtpConnect($params, $exeption);
            } else {
                $exeption = $this->getSshConnect($params, $exeption);
            }
        $resultJson = $this->resultJsonFactory->create();
            if ($exeption) {
                return $resultJson->setData(['error' => true, 'value' => $exeption]);
            } else {
                return $resultJson->setData(['success' => true, 'value' => $params]);
            }
        }

    }

    /**
     * @param $params
     * @param $exeption
     * @return string
     */
    public function getFtpConnect($params, $exeption){
        $connection = ftp_connect($params['host'], $params['port']);
        if (!$connection) {
            return $exeption = 'Invalid FTP/FTPS access (Host Name or Port).';
        }
        if (!ftp_login($connection, $params['user'], $params['password'])) {
            return $exeption = 'Invalid FTP/FTPS access (User Name or Password).';
        }
        if (!ftp_pasv($connection, $params['passive_mode'])) {
            return $exeption = 'Invalid FTP/FTPS access (Passive/Active Mode).';
        }
        return $exeption;
    }

    /**
     * @param $params
     * @param $exeption
     * @return string
     */
    public function getSshConnect($params, $exeption){

        $connection = ssh2_connect($params['host'], $params['port']);
        if (!$connection) {
            return $exeption = 'Invalid SFTP/SSH access (Host Name or Port).';
        }
        if (!ssh2_auth_password($connection, $params['user'], $params['password'])) {
            return $exeption = 'Invalid SFTP/SSH access (User Name or Password).';
        }
        return $exeption;
    }

    /**
     * @param $dbhostname
     * @return string|string[]|null
     */
    public function replaceFtpHostname($dbhostname){
        $pattern = '/ftp./';
        $newHostname = preg_replace($pattern, '', $dbhostname);
        return $newHostname;
    }

    /**
     * @inheritDoc
     */
    public function createCsrfValidationException(
        \Magento\Framework\App\RequestInterface $request
    ): ?\Magento\Framework\App\Request\InvalidRequestException {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function validateForCsrf(\Magento\Framework\App\RequestInterface $request): ?bool
    {
        return true;
    }

}

