<?php
/**
 * Created by PhpStorm.
 * User: eduardo
 * Date: 14/04/14
 * Time: 11:23
 */

namespace Cacic\WSBundle\Helper;

/******************************************
 *      IPv4 Network Class
 *      Author: Mike Mackintosh
 *      Date: 12/27/2011 2121
 *
 *      @Usage: new IPv4 ('10.1.1.1', 28);
 *
 *      Source: http://www.highonphp.com/tag/php-subnet-calculator
 *
 *****************************************/

class IPv4
{
    private $ip_long;
    public $ip;
    public $cidr;

    function __construct($ip,$cidr)
    {
        $this->ip = $ip;
        $this->ip_long = ip2long($ip);
        $this->cidr = $cidr;
    }

    function __toString(){
        $methods = get_class_methods($this);

        foreach($methods as $meth){
            if(substr($meth, 0, 2) != '__' && $meth != 'mask2cidr' && $meth != 'cidr2mask'){
                $r[] = $this->$meth();
            }
        }

        return json_encode($r);
    }

    static function mask2cidr($mask)
    {
        $mask = ip2long($mask);
        $base = ((1<<32)-1);
        return 32-log(($mask ^ $base)+1,2);
    }

    static function cidr2mask($cidr)
    {
        $mask = ip2long('255.255.255.255');
        $base = ((1<<$cidr)-1);
        return 32-log(($mask ^ $base)+1,2);
    }

    function address(){
        return $this->ip;
    }

    function cidr() {
        return $this->cidr;
    }

    function netmask()
    {
        $netmask = ((1<<32) -1) << (32-$this->cidr);
        return long2ip($netmask);
    }

    function network()
    {
        $network = $this->ip_long & (ip2long($this->netmask()));
        return long2ip($network);
    }

    function broadcast()
    {
        $broadcast = ip2long($this->network()) | (~(ip2long($this->netmask())));
        return long2ip($broadcast);
    }

    function wildcard()
    {
        $inverse = ~(((1<<32) -1) << (32-$this->cidr));
        return long2ip($inverse);
    }

    function availablehosts()
    {
        $hosts = (ip2long($this->broadcast()) - ip2long($this->network())) -1;
        return $hosts;
    }

    function availablenetworks()
    {
        return pow(2, 24)/($this->availablehosts()+2);
    }
}