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
        if (!(bool)$this->scopeConfig->getValue(Constants::USERSNAP_IP_ENABLED_PATH)) {
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
        if ($this->hasExactMatch() ||
            $this->hasNetmaskMatch() ||
            $this->hasRangeMatch()
        ) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if given IP address has exact match in whitelist
     *
     * @return bool
     */
    private function hasExactMatch()
    {
        $ipAddress = $this->_remoteAddress->getRemoteAddress();
        
        return in_array($ipAddress, $this->whitelist);
    }
    
    /**
     * Check if IP has a match to a whitelisted IP with netmask
     * Credits to Paul Gregg (https://pgregg.com/projects/php/ip_in_range/ip_in_range.phps)
     *
     * @return bool
     */
    private function hasNetmaskMatch()
    {
        $ipAddress = $this->_remoteAddress->getRemoteAddress();
        
        foreach ($this->whitelist as $ipRange) {
            if (strpos($ipRange, '/') === false) {
                return false;
            }
            
            list($ipRange, $netmask) = explode('/', $ipRange, 2);
            if (strpos($netmask, '.') !== false) {
                // $netmask is a 255.255.0.0 format
                $netmask = str_replace('*', '0', $netmask);
                $decimalNetmask = ip2long($netmask);
                
                if ((ip2long($ipAddress) & $decimalNetmask) == (ip2long($ipRange) & $decimalNetmask)) {
                    return true;
                }
            } else {
                // $netmask is a CIDR size block
                // fix the range argument
                $x = explode('.', $ipRange);
                while (count($x) < 4) {
                    $x[] = '0';
                }
                list($a, $b, $c, $d) = $x;
                $ipRange = sprintf(
                    "%u.%u.%u.%u",
                    empty($a) ? '0' : $a,
                    empty($b) ? '0' : $b,
                    empty($c) ? '0' : $c,
                    empty($d) ? '0' : $d
                );
                $decimalIpRange = ip2long($ipRange);
                $decimalIpAddress = ip2long($ipAddress);
                
                $decimalWildcard = pow(2, (32 - $netmask)) - 1;
                $decimalNetmask = ~$decimalWildcard;
                
                if (($decimalIpAddress & $decimalNetmask) == ($decimalIpRange & $decimalNetmask)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Check if IP has a match to a whitelisted IP with netmask
     * Credits to Paul Gregg (https://pgregg.com/projects/php/ip_in_range/ip_in_range.phps)
     *
     * @return bool
     */
    private function hasRangeMatch()
    {
        $ipAddress = $this->_remoteAddress->getRemoteAddress();
        
        foreach ($this->whitelist as $ipRange) {
            // Check if IP is in A.B.*.* format
            if (strpos($ipRange, '*') !== false) {
                // Just convert to A-B format by setting * to 0 for A and 255 for B
                $lowerIpAddress = str_replace('*', '0', $ipRange);
                $upperIpAddress = str_replace('*', '255', $ipRange);
                $ipRange = $lowerIpAddress . '-' . $upperIpAddress;
            }
    
            // Check if IP is in A-B format
            if (strpos($ipRange, '-') !== false) { // A-B format
                list($lowerIpAddress, $upperIpAddress) = explode('-', $ipRange, 2);
                $decimalLowerIpAddress = (float)sprintf("%u", ip2long($lowerIpAddress));
                $decimalUpperIpAddress = (float)sprintf("%u", ip2long($upperIpAddress));
                $decimalIpAddress = (float)sprintf("%u", ip2long($ipAddress));
        
                if (($decimalIpAddress >= $decimalLowerIpAddress) && ($decimalIpAddress <= $decimalUpperIpAddress)) {
                    return true;
                }
            }
        }
        
        return false;
    }
}