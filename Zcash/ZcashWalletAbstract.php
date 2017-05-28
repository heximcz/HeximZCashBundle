<?php
/**
 * Created by HeximCZ
 * Date: 5/28/17 5:37 PM
 */

namespace Hexim\HeximZcashBundle\Zcash;


class ZcashWalletAbstract
{
    /**
     * @var ZcashWrapper $wrapper
     */
    protected $wrapper;

    /**
     * Default command tail
     * @var array $defaultCommand
     */
    protected $defaultCommand = [
        "jsonrpc" => "1.0",
        "id" => "curl"
    ];

    /**
     * @var array|bool $result
     */
    protected $result;

    /**
     * @var array $params
     */
    protected $params;

    /**
     * ZcashWallet constructor.
     * @param array $params
     */
    public function __construct($params)
    {
        $this->params = $params;
        $this->wrapper = new ZcashWrapper($params);
    }

    /**
     * @param string $command
     * @param array $params
     * @return array|bool
     */
    protected function getRpcResult($command, $params)
    {
        $this->result = $this->wrapper->rpcZcashCommand(
            $this->mergeCommand([
                'method' => $command,
                'params' => $params
            ])
        );
        return $this->checkResponse();
    }

    protected function checkResponse()
    {
        if (is_null($this->result['error']) && $this->result['result'] != '')
            return $this->result;
        return $this->result = false;
    }

    protected function mergeCommand($array)
    {
        return array_merge($this->defaultCommand, $array);
    }

    /**
     * Convert scientific float: -6.7E-6 to -0.0000670
     */
    protected function fixScientificNumbers()
    {
        foreach ($this->result['result'] as $key => $value) {
            if (isset($value['amount']))
                $this->result['result'][$key]['amount'] = $this->convertScientificFloat($value['amount']);
            if (isset($value['fee']))
                $this->result['result'][$key]['fee'] = $this->convertScientificFloat($value['fee']);
        }
    }

    protected function convertScientificFloat($value)
    {
        return number_format($value, 8);
    }

}