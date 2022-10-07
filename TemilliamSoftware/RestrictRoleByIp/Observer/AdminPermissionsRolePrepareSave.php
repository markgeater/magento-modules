<?php

namespace TemilliamSoftware\RestrictRoleByIp\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;


class AdminPermissionsRolePrepareSave implements ObserverInterface {

    protected $_helper;
    protected $_messageManager;

    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \TemilliamSoftware\RestrictRoleByIp\Helper\Data $helper
    ) {
        $this->_messageManager = $messageManager;
        $this->_helper = $helper;
    }

    public function execute(Observer $observer) {
        // Get the Restrict Role by IP configuration as submitted through the form.
        $ipRequestData = $observer->getRequest()->getParam('ipaddresses', false);
        // The rules are configured one per line, turn the input into an array.
        $ipAddresses = explode(PHP_EOL, $ipRequestData);
        $errors = 0;
        $finalSettings = [];
        // Iterate over the rules.
        foreach ($ipAddresses as $ipAddress) {
            $ipAddress = trim($ipAddress);
            // Ignore blank lines.
            if (empty($ipAddress)) {
                continue;
            }
            // Only accept properly-formatted rules.
            if ($this->_helper->isValidCidrNotation($ipAddress)) {
                $finalSettings[] = $ipAddress;
            } else {
                $errors++;
            }
        }
        // Notify the user if any of the submitted rules was rejected.
        if ($errors) {
            $this->_messageManager->addNoticeMessage(__("{$errors} invalid IP restriction setting(s) were detected and removed."));
        }
        // Store the configuration in the role record as a JSON-encoded array.
        $ipData = json_encode($finalSettings);
        $observer->getObject()->setIpAddresses($ipData);

    }
}