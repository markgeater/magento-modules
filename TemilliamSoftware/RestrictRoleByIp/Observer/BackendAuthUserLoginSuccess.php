<?php

namespace TemilliamSoftware\RestrictRoleByIp\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Backend\Model\Auth\StorageInterface;

use TemilliamSoftware\RestrictRoleByIp\Helper\Data;

class BackendAuthUserLoginSuccess implements ObserverInterface {

    protected $_authStorage;
    protected $_helper;

    public function __construct(
        StorageInterface $authStorage,
        Data $helper
    ) {
        $this->_authStorage = $authStorage;
        $this->_helper = $helper;
    }

    public function execute(Observer $observer) {
        // If the user isn't logged in, there's nothing to do.
        if (!$this->_authStorage->isLoggedIn()) {
            return;
        }
        // Get a handle of the user.
        $user = $observer->getEvent()->getUser();
        // Get their assigned role.
        $role = $user->getRole();
        // Check if the user's IP is allowed to use the role.
        $canAccess = $this->_helper->userIpCanAccessRole($role);
        // If they can use the role, there's nothing to do.
        if ($canAccess) {
            return;
        }
        // This IP address cannot access the role. Eject the user.
        $this->_authStorage->processLogout();
    }
}