<?php
/**
 * Created by HeximCZ
 * Date: 5/27/17 3:07 PM
 */

namespace Hexim\HeximZcashBundle\Zcash;

class ZcashWrapper
{
    /**
     * @var string $rpc_address
     */
    private $rpc_address;
    /**
     * @var string $rpc_user
     */
    private $rpc_user;
    /**
     * @var string $rpc_password
     */
    private $rpc_password;
    /**
     * @var int $curl_timeout
     */
    private $curl_timeout = 10;
    /**
     * @var string $return_data
     */
    private $return_data;
    /**
     * @var int $return_status
     */
    private $return_status = 0;

    /**
     * ZcashWrapper constructor.
     * @param array $params
     */
    public function __construct($params)
    {
        $this->rpc_address = $params['rpc_address'];
        $this->rpc_password = $params['rpc_password'];
        $this->rpc_user = $params['rpc_user'];
    }

    /**
     * @param array $command
     * @return array|bool
     * @throws \Exception
     */
    public function rpcZcashCommand($command)
    {
        if (is_array($command))
        {
            $this->postCommand($command);
            if ($this->checkReturnCodeStatus())
                return json_decode($this->return_data,true, 512,JSON_BIGINT_AS_STRING);
            return $this->return_data;
        }
        throw new \Exception("RPC input command is not an array!");
    }

    /**
     * @param $command
     * @throws \Exception
     */
    private function postCommand($command)
    {
        $con = curl_init();
        curl_setopt($con,CURLOPT_URL,$this->rpc_address);
        curl_setopt($con,CURLOPT_TIMEOUT,$this->curl_timeout);
        curl_setopt($con,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($con, CURLOPT_POST,true);
        curl_setopt($con,CURLOPT_POSTFIELDS,json_encode($command));
        curl_setopt($con, CURLOPT_USERPWD, $this->rpc_user.':'.$this->rpc_password);
        $this->return_data = curl_exec($con);
        $this->return_status = curl_getinfo($con,CURLINFO_HTTP_CODE);
        curl_close($con);
    }

    /**
     * @throws \Exception
     */
    private function checkReturnCodeStatus()
    {
        if ($this->return_status == 200)
            return true;
        if ($this->return_data === false)
            return false;
        if ($this->return_status == 401)
            throw new \Exception("Unauthorized!");
        if ($this->return_status == 403)
            throw new \Exception("Forbidden!");
        if ($this->return_status == 404)
            throw new \Exception("Not found!");
        if ($this->return_status == 500)
            throw new \Exception("Internal Server Error!");
        if ($this->return_status == 502)
            throw new \Exception("Bad Gateway!");
        if ($this->return_status == 503)
            throw new \Exception("Service Unavailable!");
        throw new \Exception("Any other error. Return code no.: " . $this->return_status);
    }
}