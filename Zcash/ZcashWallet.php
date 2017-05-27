<?php
/**
 * Created by HeximCZ
 * Date: 5/26/17 11:30 PM
 */

namespace Hexim\HeximZcashBundle\Zcash;

use Hexim\HeximZcashBundle\Zcash\ZcashWrapper;


class ZcashWallet implements ZcashWalletInterface
{
    /**
     * @var ZcashWrapper $wrapper
     */
    private $wrapper;

    /**
     * Default command tail
     * @var array $defaultCommand
     */
    private $defaultCommand = [
        "jsonrpc" => "1.0",
        "id" => "curl"
    ];

    private $result;

    /**
     * ZcashWallet constructor.
     * @param array $params
     */
    public function __construct($params)
    {
        $this->wrapper = new ZcashWrapper($params);
    }

    /**
     * @return array
     */
    public function getWalletInfo()
    {
        return $this->wrapper->rpcZcashCommand(
            $this->mergeCommand([
                'method' => 'getwalletinfo',
                'params' => []
            ])
        );
    }

    /**
     * The total number of transactions in the wallet
     * @return int
     */
    public function getTxCount()
    {
        $data = $this->getWalletInfo();
        return $data['result']['txcount'];
    }

    /**
     * @param int $count
     * @param int $from
     * @param bool $includeWatchOnly
     * @return array
     */
    public function listTransactions($count = 10, $from = 0, $includeWatchOnly = false)
    {
        $this->result = $this->wrapper->rpcZcashCommand(
            $this->mergeCommand([
                'method' => 'listtransactions',
                'params' => ["*",$count,$from,$includeWatchOnly]
            ])
        );

        if ($this->checkResponse())
            $this->fixScientificNumbers();
        return $this->result;
    }

    public function getNewAddress()
    {
        // TODO: Implement getNewAddress() method.
    }

    public function z_getNewAddress()
    {
        // TODO: Implement z_getNewAddress() method.
    }

    /* private functions */

    private function mergeCommand($array)
    {
        return array_merge($this->defaultCommand, $array);
    }

    private function checkResponse()
    {
        if (is_null($this->result['error']) && $this->result['result']!='')
            return true;
        throw new \Exception("Result error!");
    }

    /**
     * Fix: example -6.7E-6 to -0.000067
     */
    private function fixScientificNumbers()
    {
        foreach ($this->result['result'] as $key => $value)
        {
            if (isset($value['amount']))
                $this->result['result'][$key]['amount'] = $this->formatZecNumber($value['amount']);
            if (isset($value['fee']))
                $this->result['result'][$key]['fee'] = $this->formatZecNumber($value['fee']);
        }
    }

    private function formatZecNumber($value)
    {
        return number_format($value,8);
    }
}