#ifndef __ARM_WEBSOCKET_H__
#define __ARM_WEBSOCKET_H__

#include <libwebsockets.h>
#include <jsoncpp/json.h>
#include <uart.h>

typedef class ARM_WebSocket {

public:
    char buf[256];
    pthread_mutex_t mutex;
    struct lws *wsi;

    Json::Value root;
    Json::Reader reader;
    const char * json_data(const char *);

    struct lws_context_creation_info context_info;
    struct lws_context *context;
    struct lws_protocols (*protocols)[];

    WSA_UART wsa_uart;

    ARM_WebSocket();
    void send_data(char * data, int len);

} ARM_WebSocket;

#endif // __ARM_WEBSOCKET_H__
