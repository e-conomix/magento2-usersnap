<?php
/**
 * @author Dipl.-Ing. Andreas Schrammel, BSc. <schrammel@e-conomix.at>
 * @package Economix\Usersnap\Helper
 * @copyright Copyright (c) 2019 E-CONOMIX GmbH (https://www.e-conomix.at)
 * @created 06.12.2019
 */

namespace Economix\Usersnap\Helper;

use Economix\Usersnap\Api\Constants;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;

class IpChecker extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var array
     */
    private $whitelist;
    
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context
    ) {
        $whitelist = $scopeConfig->getValue(Constants::USERSNAP_IP_WHITELIST_PATH);
        $whitelist = preg_replace('~\R~u', "\n", $whitelist);
        $this->whitelist = explode("\n", $whitelist);
        
        parent::__construct($context);
    }
    
    /**
     * @return bool
     */
    public function checkIpRestriction()
    {
        if (!(bool) $this->scopeConfig->getValue(Constants::USERSNAP_IP_ENABLED_PATH)) {
            return true;
        }
        
        return $this->isInWhitelist();
    }
    
    /**
     * Return if the IP of the current request is whitelisted
     *
     * @return bool
     */
    private function isInWhitelist()
    {
        if ($this->ipHasExactMatch()) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if given IP address has exact match in whitelist
     *
     * @return bool
     */
    private function ipHasExactMatch()
    {
        $ipAddress = $this->_remoteAddress->getRemoteAddress();
        
        return in_array($ipAddress, $this->whitelist);
    }
}