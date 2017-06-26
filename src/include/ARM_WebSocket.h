#ifndef __ARM_WEBSOCKET_H__
#define __ARM_WEBSOCKET_H__

#include <libwebsockets.h>
#include <json/json.h>
#include <uart.h>
#include <gpio.h>

typedef class ARM_WebSocket {

public:
    // pointer to libwebsocket
    struct lws *wsi;

    // parse json data from client browser
    Json::Value client_root;
    // parse local json configure data
    Json::Value config_root;

    Json::Reader reader;
    Json::FastWriter writer;
    const char * client_json_data(const char *);


    struct lws_context_creation_info context_info;
    struct lws_context *context;
    struct lws_protocols (*protocols)[];

    // UART
    WSA_UART wsa_uart;
    // GPIO
    WSA_GPIO wsa_gpio;

    ARM_WebSocket();

    // thread safe for send data to client
    pthread_mutex_t mutex;
    // send data to browser client
    void send_data(char * data, int len);

} ARM_WebSocket;

#endif // __ARM_WEBSOCKET_H__
