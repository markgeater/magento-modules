<?php

namespace TemilliamSoftware\RestrictRoleByIp\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper {

    public function userIpCanAccessRole($role) {
        // Get the configured IP restrictions as an array.
        $role->getIpAddresses();
        // Get the Restrict Role by IP config for this role.
        $config = $role->getIpAddresses();
        // If there's no config, the role isn't restricted.
        if (empty($config)) {
            return TRUE;
        }
        // If there's a config, turn it into an array.
        $cidrs = json_decode($config, TRUE);
        // If the config array doesn't contain any items, the role isn't retricted.
        if (empty($cidrs)) {
            return TRUE;
        }
        // Get the user's current IP adrdess.
        $ipAddress = $this->getClientIpAddress();       
        // Iterate over the restrictions and return true if there's a match.
        foreach ($cidrs as $cidr) {
            if ($this->cidrMatch($ipAddress, $cidr)) {
                return TRUE;
            }
        }
        // There was no match within the configured restrictions, so return false - the user can't use this role.
        return FALSE;
    }

    public function cidrMatch($ipAddress, $cidr){
        $maskparts = explode('/', $cidr);
        $subnet = $maskparts[0];
        $bits = $maskparts[1] ?? 32;
        $ip = ip2long($ipAddress);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        return ($ip & $mask) == $subnet;
    }

    public function getClientIpAddress() {
        // Choose the best server variable for the remote IP address.
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        // For dev environments, translate IPv6 localhost to a standard IPv4 address.
        if ($ipAddress == '::1') {
            $ipAddress = '127.0.0.1';
        }
        return $ipAddress;
    }

    public function isValidCidrNotation(string $cidr) {
        // If the supplied string doesn't match the expected IP or IP + netmask pattern, it's invalid.
        if (!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}(\/[0-9]{1,2})?$/", $cidr)) {
            return FALSE;
        }
        // Get the IP and, if it exists, the netmask.
        $parts = explode("/", $cidr);
        $ip = $parts[0];
        $netmask = isset($parts[1]) ? $parts[1] : '';
        // If there's a netmask and it's invalid, the supplied string is invalid.
        if (($netmask != '') && ($netmask > 32)) {
            return FALSE;
        }
        // Check each of the octets of the IP address and reject if any of them is invalid.
        $octets = explode(".", $ip);
        foreach ($octets as $octet) {
            if ($octet > 255) {
                return FALSE;
            }
        }
        // No validation errors were found.
        return TRUE;
    }

}