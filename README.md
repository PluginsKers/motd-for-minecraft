# Motd_For_Minecraft
## 响应 ##

通过UDP协议获取MCPE的服务器状态    
就是平常说的motdpe接口。

懂的自然懂，接口地址：https://motd.52craft.cc/
请求方式GET/POST

参数|示例|描述
-|-|-
ip|mc.52craft.cc|服务器IP地址
port|2020|服务器端口

返回JSON数据
参数|示例|描述
-|-|-
status|online|服务器唯一状态识别
ip|mc.52craft.cc|返回查询IP
port|2020|返回查询端口
motd|BorderLands In Minecraft|服务器广播内容Motd
agreement|389|协议版本
version|1.14.30|客户端版本
online|3|服务器在线人数
max|10|服务器人数上限
gamemode|Survival|游戏模式
delay|64|连接服务器延迟(ms)

示例：https://motd.52craft.cc/?ip=mc.52craft.cc&port=2020

手动滑稽[滑稽]