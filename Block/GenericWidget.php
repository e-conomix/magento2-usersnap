<?php
/**
 * @author Dipl.-Ing. Andreas Schrammel, BSc. <schrammel@e-conomix.at>
 * @package Economix\Usersnap\Block
 * @copyright Copyright (c) 2019 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 05.12.2019
 */

namespace Economix\Usersnap\Block;

use Economix\Usersnap\Api\Constants;
use Economix\Usersnap\Helper\IpChecker;
use Magento\Framework\View\Element\Template;

class GenericWidget extends Template
{
    /**
     * @var IpChecker
     */
    private $ipChecker;
    
    public function __construct(
        IpChecker $ipChecker,
        Template\Context $context,
        array $data = []
    ) {
        $this->ipChecker = $ipChecker;
        
        parent::__construct($context, $data);
    }
    
    public function getProjectApiKey()
    {
        return $this->_scopeConfig->getValue(Constants::USERSNAP_CONFIG_PROJECT_API_KEY_PATH);
    }
    
    public function toHtml()
    {
        $enabled = (bool)$this->_scopeConfig->getValue(Constants::USERSNAP_CONFIG_ENABLED_PATH);
        $ipWhitelisted = $this->ipChecker->checkIpRestriction();
        
        return ($enabled && $ipWhitelisted) ? parent::toHtml() : '';
    }
}