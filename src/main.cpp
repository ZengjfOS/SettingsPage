#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <pthread.h>
#include <libwebsockets.h>

#include <ARM_WebSocket.h>
#include <jsoncpp/json.h>
#include <uart.h>

ARM_WebSocket arm_websocket;

static int callback_http(struct lws *wsi,
    enum lws_callback_reasons reason, void *user,
    void *in, size_t len)
{
	return 0;
}

static int callback_dumb_increment(struct lws *wsi,
    enum lws_callback_reasons reason,
    void *user, void *in, size_t len)
{
	int temp = 0;

    switch (reason) {
        case LWS_CALLBACK_ESTABLISHED:      // just log message that someone is connecting
            printf("connection established\n");
            break;
        case LWS_CALLBACK_RECEIVE: {        // the funny part

            printf("received data: %s\n", (char *) in);

            if (arm_websocket.reader.parse((char *)in, arm_websocket.root, false)) {

                printf("\n\ncategories: %s\n", arm_websocket.json_data("categories"));

                if (strcmp(arm_websocket.json_data("categories"), "uart") == 0) {
                    if (strcmp(arm_websocket.json_data("command"), "open") == 0) {
                        printf("UARTPorts: %s\n", arm_websocket.json_data("UARTPorts"));
                        printf("UARTBaudRate: %s\n", arm_websocket.json_data("UARTBaudRate"));
                        printf("UARTStopBit: %s\n", arm_websocket.json_data("UARTStopBit"));
                        printf("UARTDataLen: %s\n", arm_websocket.json_data("UARTDataLen"));
                        printf("UARTCheckBit: %s\n", arm_websocket.json_data("UARTCheckBit"));
                        printf("UARTIntervalSendData: %s\n", arm_websocket.json_data("UARTIntervalSendData"));
                        printf("UARTSendData: %s\n", arm_websocket.json_data("UARTSendData"));

                        // arm_websocket.wsa_uart.uart_init("/dev/zero", "115200", "0123456789");
                        arm_websocket.wsa_uart.uart_init(
                            (char *)(arm_websocket.json_data("UARTPorts")), 
                            (char *)(arm_websocket.json_data("UARTBaudRate")), 
                            (char *)(arm_websocket.json_data("UARTStopBit")),
                            (char *)(arm_websocket.json_data("UARTDataLen")),
                            (char *)(arm_websocket.json_data("UARTCheckBit")),
                            (char *)(arm_websocket.json_data("UARTIntervalSendData")),
                            (char *)(arm_websocket.json_data("UARTSendData"))
                        );
                    } else {
                        arm_websocket.wsa_uart.uart_close();
                        printf("UARTPorts close.\n");
                    }
                }

            }

            unsigned char *buf = (unsigned char*) malloc(LWS_SEND_BUFFER_PRE_PADDING + len + LWS_SEND_BUFFER_POST_PADDING);
            
            int i;
            for (i=0; i < len; i++) {
                buf[LWS_SEND_BUFFER_PRE_PADDING + (len - 1) - i ] = ((char *) in)[i];
            }
            
            lws_write(wsi, &buf[LWS_SEND_BUFFER_PRE_PADDING], len, LWS_WRITE_TEXT);
            
            free(buf);
            break;
        }
        default:
            break;
    }
    
    return 0;
}



static struct lws_protocols protocols[] = {
    /* first protocol must always be HTTP handler */
    {
        "http-only",   // name
        callback_http, // callback
        0              // per_session_data_size
    },
    {
        "dumb-increment-protocol", // protocol name - very important!
        callback_dumb_increment,   // callback
        0                          // we don't use any per session data
    },
    {
        NULL, NULL, 0   /* End of list */
    }
};

int main(void) {

    // server url will be http://localhost:9000
    arm_websocket.context_info.port = 9000; 
    arm_websocket.context_info.iface = NULL; 
    arm_websocket.context_info.protocols = protocols;
    arm_websocket.context_info.extensions = NULL;
    arm_websocket.context_info.ssl_cert_filepath = NULL;
    arm_websocket.context_info.ssl_private_key_filepath = NULL;
    arm_websocket.context_info.ssl_ca_filepath = NULL;
    arm_websocket.context_info.gid = -1;
    arm_websocket.context_info.uid = -1; 
    arm_websocket.context_info.options = 0; 
    arm_websocket.context_info.ka_time = 0; 
    arm_websocket.context_info.ka_probes = 0; 
    arm_websocket.context_info.ka_interval = 0;

    // create lws context representing this server
    arm_websocket.context = lws_create_context(&arm_websocket.context_info);
    if (arm_websocket.context == NULL) {
        fprintf(stderr, "lws init failed\n");
        return -1;
    }
    
    printf("starting server...\n");
    
    // infinite loop, to end this server send SIGTERM. (CTRL+C)
    while (1) {
        lws_service(arm_websocket.context, 50);
        // lws_service will process all waiting events with their
        // callback functions and then wait 50 ms.
        // (this is a single threaded webserver and this will keep our server
        // from generating load while there are not requests to process)
    }
    
    lws_context_destroy(arm_websocket.context);
    
    return 0;
}
