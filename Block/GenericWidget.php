<?php
/**
 * @author Dipl.-Ing. Andreas Schrammel, BSc. <schrammel@e-conomix.at>
 * @package Economix\Usersnap\Block
 * @copyright Copyright (c) 2019 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 05.12.2019
 */

namespace Economix\Usersnap\Block;

use Economix\Usersnap\Api\Constants;
use Magento\Framework\View\Element\Template;

class GenericWidget extends Template
{
    public function getProjectApiKey()
    {
        return $this->_scopeConfig->getValue(Constants::USERSNAP_PROJECT_API_KEY_PATH);
    }
    
    public function toHtml()
    {
        return (bool)$this->_scopeConfig->getValue(Constants::USERSNAP_ENABLED_PATH) ? parent::toHtml() : '';
    }
}