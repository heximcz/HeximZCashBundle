<?php
/**
 * Created by HeximCZ
 * Date: 5/28/17 5:39 PM
 */

namespace Hexim\HeximZcashBundle\Zcash;


class ZcashUtil extends ZcashWalletAbstract implements ZcashUtilInterface
{
    /**
     * @param string $address
     * @return array|bool
     */
    public function validateAddress($address)
    {
        return $this->getRpcResult('validateaddress',
            [$address]
        );
    }

    public function z_validateAddress($address)
    {
        return $this->getRpcResult('z_validateaddress',
            [$address]
        );
    }

}