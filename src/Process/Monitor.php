<?php
/**
 *
 * @description 系统监控进程
 *
 * @package     Kovey\Components\Process
 *
 * @time        2020-01-19 14:52:47
 *
 * @author      kovey
 */
namespace Kovey\Library\Process;

use Kovey\Library\Config\Manager;
use Kovey\Rpc\Client\Client;
use Kovey\Logger\Logger;
use Kovey\Library\Util\Json;
use Swoole\Timer;

class Monitor extends ProcessAbstract
{
    /**
     * @description 初始化
     *
     * @return null
     */
    protected function init()
    {
        $this->processName = 'kovey framework cluster monitor';
    }

    /**
     * @description 业务处理
     *
     * @return null
     */
    protected function busi()
    {
        $this->listen(function ($pipe) {
            $logger = $this->read();
            if (!is_array($logger)) {
                return;
            }

            $this->sendToMonitor('save', Json::encode($logger));
        });

        Timer::tick(60000, function () {
            $result = sys_getloadavg();
            $this->sendToMonitor('load', $result, Manager::get('server.server.name'));
            Logger::writeInfoLog(__LINE__, __FILE__, 'sys load average: ' . Json::encode($result));
        });
    }

    /**
     * @description 发送数据到监控系统
     *
     * @param string $method
     *
     * @param ...mixed $args
     *
     * @return null
     */
    protected function sendToMonitor($method, ...$args)
    {
        go(function ($method, $args) {
            $cli = new Client(Manager::get('rpc.monitor'));
            if (!$cli->connect()) {
                Logger::writeWarningLog(__LINE__, __FILE__, $cli->getError());
                return;
            }

            if (!$cli->send(array(
                'p' => 'Monitor',
                'm' => $method,
                'a' => $args
            ))) {
                $cli->close();
                Logger::writeWarningLog(__LINE__, __FILE__, $cli->getError());
                return;
            }

            $result = $cli->recv();
            $cli->close();

            if (empty($result)) {
                Logger::writeWarningLog(__LINE__, __FILE__, 'response error');
                return;
            }

            if ($result['code'] > 0) {
                if ($result['type'] != 'success') {
                    Logger::writeWarningLog(__LINE__, __FILE__, $result['err']);
                }
            }

            if (!$result['result']) {
                Logger::writeWarningLog(__LINE__, __FILE__, 'save fail, logger: ' . $buffer);
            }
        }, $method, $args);
    }
}
