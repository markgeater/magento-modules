<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace TemilliamSoftware\RestrictRoleByIp\Block\Role\Tab;

/**
 * Info
 *
 * User role tab info
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class RestrictRoleByIpSettings extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface {
    /**
     * Password input filed name
     */
    const IDENTITY_VERIFICATION_PASSWORD_FIELD = 'current_password';

    /**
     * Get tab label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel() {
        return __('Restrict By IP');
    }

    /**
     * Get tab title
     *
     * @return string
     */
    public function getTabTitle() {
        return $this->getTabLabel();
    }

    /**
     * Can show tab
     *
     * @return bool
     */
    public function canShowTab() {
        return TRUE;
    }

    /**
     * Is tab hidden
     *
     * @return bool
     */
    public function isHidden() {
        return FALSE;
    }

    /**
     * Before html rendering
     *
     * @return $this
     */
    public function _beforeToHtml() {
        $this->_initForm();
        return parent::_beforeToHtml();
    }

    /**
     * Form initializatiion
     *
     * @return void
     */
    protected function _initForm() {
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Restrict Role By Ip')]);
        $fieldset->addField(
            'ip_addresses',
            'textarea',
            [
                'name' => 'ipaddresses',
                'label' => __('IP Addresses'),
                'field_extra_attributes' => 'data-validate="' . json_encode(['required' => TRUE]) . '"',
                'id' => 'ip_addresses',
                'required' => FALSE,
                'note' => 'Restrictions will take effect on next login. Leave blank if you do not want any IP restrictions for this role.<br />
                Enter individual IP addresses or ranges (in IPv4 CIDR notation), one rule per line.<br />
                <br />
                Example:<br />
                <br />
                192.168.1.1<br />
                192.168.1.2<br />
                192.200.1.0/24'
            ]
        );
        $role = $this->_coreRegistry->registry('current_role');
        $config = $role->getIpAddresses();
        if (empty($config)) {
            $cidrs = [];
        } else {
            $cidrs = json_decode($config);
            if (!$cidrs) {
                $cidrs = [];
            }
        }
        $data = [
            'ip_addresses' => implode(PHP_EOL, $cidrs),
        ];
        if ($role && is_array($role->getData())) {
            $data = array_merge($role->getData(), $data);
        }
        $form->setValues($data);
        $this->setForm($form);
    }

}
