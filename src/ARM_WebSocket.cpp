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
    return (root[key].asString()).c_str();
}

void ARM_WebSocket::send_data(char * data, int len)
{
    unsigned char *buf = (unsigned char*) malloc(LWS_SEND_BUFFER_PRE_PADDING + len + LWS_SEND_BUFFER_POST_PADDING);
    
    memcpy(buf+LWS_SEND_BUFFER_PRE_PADDING, data, len);
    
    lws_write(wsi, &buf[LWS_SEND_BUFFER_PRE_PADDING], len, LWS_WRITE_TEXT);
    
    free(buf);
}
