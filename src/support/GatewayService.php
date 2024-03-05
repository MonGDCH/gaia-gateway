<?php

declare(strict_types=1);

namespace support\gateway;

use mon\util\Instance;
use GatewayClient\Gateway;
use process\gateway\Register;

/**
 * Gateway客户端服务封装
 * 
 * @see 注意：发送的数据不会经过Event.php，而是直接经由Gateway进程转发给客户端
 * 
 * @method void sendToAll(string $message, array $client_id_array = null, array $exclude_client_id = null, bool $raw = false) 向所有客户端连接(或者 client_id_array 指定的客户端连接)广播消息
 * @method void sendToClient(string $client_id, string $message) 向某个client_id对应的连接发消息
 * @method integer isUidOnline(string $uid) 判断某个uid是否在线，在线返回1 不在线则返回0
 * @method integer isUidsOnline(array $uids) 判断多个uid是否在线，在线返回1 不在线则返回0
 * @method integer isOnline(string $client_id) 判断client_id对应的连接是否在线，在线返回1 不在线则返回0
 * @method array getAllClientSessions(string $group = '') 获取所有在线client_id的session，client_id为 key
 * @method array getClientSessionsByGroup(string $group) 获取某个组的所有client_id的session信息
 * @method integer getAllClientIdCount() 获取所有在线client_id数
 * @method integer getClientIdCountByGroup(string $group = '') 获取某个组的在线client_id数
 * @method array getAllClientIdList() 获取集群所有在线client_id列表
 * @method array getClientIdByUid(string $uid) 获取与 uid 绑定的 client_id 列表
 * @method array batchGetClientIdByUid(array $uids) 批量获取与 uid 绑定的 client_id 列表
 * @method array getUidListByGroup(string $group) 获取某个群组在线uid列表
 * @method integer getUidCountByGroup(string $group) 获取某个群组在线uid数
 * @method array getAllUidList() 获取全局在线uid列表
 * @method integer getAllUidCount() 获取全局在线uid数
 * @method mixed getUidByClientId(string $client_id) 通过client_id获取uid
 * @method array getAllGroupIdList() 获取所有在线的群组id
 * @method array getAllGroupUidCount() 获取所有在线分组的uid数量，也就是每个分组的在线用户数
 * @method array getAllGroupUidList() 获取所有分组uid在线列表
 * @method array getAllGroupClientIdList() 获取所有群组在线client_id列表
 * @method array getAllGroupClientIdCount() 获取所有群组在线client_id数量，也就是获取每个群组在线连接数
 * @method bool closeClient(string $client_id, string $message = null) 踢掉某个客户端，并以$message通知被踢掉客户端
 * @method bool destoryCurrentClient() 踢掉当前客户端并直接立即销毁相关连接
 * @method void bindUid(string $client_id, $uid) 将 client_id 与 uid 绑定
 * @method void unbindUid(string $client_id, $uid) 将 client_id 与 uid 解除绑定
 * @method void joinGroup(string $client_id, $group) 将 client_id 加入组
 * @method void leaveGroup(string $client_id, $group) 将 client_id 离开组
 * @method void ungroup($group) 取消分组
 * @method void sendToUid($uid, string $message) 向所有 uid 发送
 * @method void sendToGroup($group, string $message, array $exclude_client_id = null, bool $raw = false) 向 group 发送
 * @method void setSession(string $client_id, array $session) 设置 session，原session值会被覆盖
 * @method void updateSession(string $client_id, array $session) 更新 session，实际上是与老的session合并
 * @method mixed getSession(array $client_id) 获取某个client_id的session
 * 
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class GatewayService
{
    use Instance;

    /**
     * 构造方法
     */
    public function __construct()
    {
        Gateway::$registerAddress = Register::getListenHost() . ':' . Register::getListenPort();
    }

    /**
     * 魔术方法调用
     *
     * @param string $name      方法名称
     * @param mixed $arguments  调用参数
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([Gateway::class, $name], $arguments);
    }
}
