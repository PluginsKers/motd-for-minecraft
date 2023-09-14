## Motd_For_Minecraft


![](https://cdn.jsdelivr.net/gh/pluginskers/cdn/2021/20210427201617.png)


通过`UDP/TCP`协议获取MC的服务器状态

请求方式：`GET/POST`

### 请求参数 ###

参数|示例|描述
-|-|-
ip|mc.52craft.cc|服务器IP地址
port|2020|服务器端口
java|null|Java服务器接口

### 返回参数 ###

参数|示例|描述
-|-|-
code|200|接口响应状态码
status|online|服务器状态
ip|mc.52craft.cc|返回查询IP
port|2020|返回查询端口
real|222.22.333.22|真实IP地址
location|北京省 联通|线路所在位置
motd|BorderLands In Minecraft|服务器广播内容Motd
agreement|389|协议版本
version|1.14.30|客户端版本
online|3|服务器在线人数
max|10|服务器人数上限
gamemode|Survival|游戏模式
delay|64|连接服务器延迟(ms)

### 错误码 ###

错误码|描述
-|-
200|正常
201|无法与输入地址握手
202|无法建立UDP连接（基岩版）
203|无法解析Hex（基岩版）


该项目现已停止维护！但仍会继续处理Issue
