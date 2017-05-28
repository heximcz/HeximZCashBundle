<?php
/**
 * Created by HeximCZ
 * Date: 5/26/17 11:30 PM
 */

namespace Hexim\HeximZcashBundle\Zcash;

class ZcashWallet extends ZcashWalletAbstract implements ZcashWalletInterface
{

    /**
     * @return array|bool
     */
    public function getWalletInfo()
    {
        return $this->getRpcResult('getwalletinfo',['aaa']);
    }

    /**
     * @param int $count
     * @param int $from
     * @param bool $includeWatchOnly
     * @return array|bool
     */
    public function listTransactions($count = 10, $from = 0, $includeWatchOnly = false)
    {
        $this->result = $this->getRpcResult('listtransactions',
            ["*", $count, $from, $includeWatchOnly]
        );

        if (is_array($this->checkResponse()))
            $this->fixScientificNumbers();
        return $this->result;
    }

    /**
     * @return array|bool
     */
    public function getNewAddress()
    {
        return $this->getRpcResult('getnewaddress',[]);
    }

    /**
     * @return array|bool
     */
    public function z_getNewAddress()
    {
        return $this->getRpcResult('z_getnewaddress',[]);
    }

    /**
     * @param string $address
     * @param int $confirmed - default 1
     * @return array|bool
     */
    public function getReceivedByAddress($address, $confirmed = 1)
    {
        // validate address
        $zcashUtil = new ZcashUtil($this->params);
        $result = $zcashUtil->validateAddress($address);
        if ($result['result']['isvalid']) {
            return $this->getRpcResult('getreceivedbyaddress',
                [$address, $confirmed]
            );
        }
        return false;
    }

}
