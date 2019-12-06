<?php
/**
 * @author Dipl.-Ing. Andreas Schrammel, BSc. <schrammel@e-conomix.at>
 * @package Economix\Usersnap\Block
 * @copyright Copyright (c) 2019 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 05.12.2019
 */

namespace Economix\Usersnap\Block;


use Economix\Usersnap\Api\Constants;

class AdminhtmlWidget extends GenericWidget
{
    public function toHtml()
    {
        return (bool)$this->_scopeConfig->getValue(Constants::USERSNAP_CONFIG_BACKEND_ENABLED_PATH) ? parent::toHtml() : '';
        
        $enable = (bool)$this->_scopeConfig->getValue(Constants::USERSNAP_CONFIG_ENABLED_PATH);
        $enableBe = (bool)$this->_scopeConfig->getValue(Constants::USERSNAP_CONFIG_BACKEND_ENABLED_PATH);
        
        return ($enable && $enableBe) ? parent::toHtml() : '';
    }
}