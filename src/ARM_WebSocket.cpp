#include <ARM_WebSocket.h>
#include <libwebsockets.h>
#include <jsoncpp/json.h>
#include <uart.h>

extern ARM_WebSocket arm_websocket;
ARM_WebSocket::ARM_WebSocket()
{
}
const char * ARM_WebSocket::json_data(const char * key)
{
    return (arm_websocket.root[key].asString()).c_str();
}
