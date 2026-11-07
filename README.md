# gaia-gateway

`Gaia`框架定制的`workerman/gateway-worker`

## 环境要求

- PHP >= 8.1
- gaia-framework

## 安装

```bash
composer require mongdch/gaia-gateway

php gaia vendor:publish gaia\gateway
```

发布后会生成以下文件：

| 发布路径 | 说明 |
|---|---|
| `bin/gateway.php` | 启动入口 |
| `config/gateway/*.php` | 进程配置（app / business / gateway / register） |
| `support/gateway/*.php` | 进程驱动类与服务封装 |
| `app/gateway/Event.php` | 事件回调处理类（业务逻辑写在这里） |

## 启动

```bash
php ./bin/gateway start
```

- Linux 支持 `start | stop | restart | reload | status`
- Windows 仅支持 `start`，修改代码后会自动重启进程

## 配置

支持的环境变量（`.env`）：

| 环境变量 | 默认值 | 说明 |
|---|---|---|
| `GATEWAY_WS_PORT` | `12301` | gateway 对外 websocket 监听端口 |
| `GATEWAY_COUNT` | `0` | gateway 进程数，0 为按 CPU 核心数分配 |
| `GATEWAY_START_PORT` | `4900` | gateway 内部通讯起始端口，多进程时依次占用，注意不要与其它服务冲突 |
| `GATEWAY_LAN_IP` | `127.0.0.1` | 本机 IP，分布式部署时改为本机内网 IP |
| `GATEWAY_REGISTER_PORT` | `1236` | register 进程监听端口 |
| `BUSINESS_COUNT` | `0` | business 进程数，0 为按 CPU 核心数分配 |
| `GATEWAY_SECRET_KEY` | 空 | 内部通讯秘钥 |

## 安全须知

`GATEWAY_SECRET_KEY` 用于 gateway / business / register 进程间通讯鉴权：

- 单机部署（register 监听 `127.0.0.1`）风险较低，仍建议配置
- **分布式部署时必须配置**，否则任何能访问 register/gateway 端口的进程都可以伪造内部命令（踢人、全员广播等）
- 三个进程使用同一个秘钥

## 分布式部署

将 gateway / business / register 部署在不同机器时：

1. 各机器设置 `GATEWAY_LAN_IP` 为自己的内网 IP
2. register 机器的 `GATEWAY_REGISTER_PORT` 端口需对内网其它机器开放
3. 统一配置 `GATEWAY_SECRET_KEY`
4. 多机部署时各机器的 `GATEWAY_START_PORT` 互不影响

## 测试

```bash
composer test
```
