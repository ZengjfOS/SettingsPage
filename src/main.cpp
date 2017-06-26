#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <pthread.h>
#include <libwebsockets.h>

#include <ARM_WebSocket.h>
#include <json/json.h>
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

    arm_websocket.wsi = wsi;

    switch (reason) {
        case LWS_CALLBACK_ESTABLISHED:      // just log message that someone is connecting
            printf("connection established\n");
            break;
        case LWS_CALLBACK_RECEIVE: {        // the funny part

            printf("received data: %s\n", (char *) in);

            if (arm_websocket.reader.parse((char *)in, arm_websocket.client_root, false)) {

                printf("\n\ncategories: %s\n", arm_websocket.client_json_data("categories"));

                if (strcmp(arm_websocket.client_json_data("categories"), "uart") == 0) {
                    if (strcmp(arm_websocket.client_json_data("command"), "open") == 0) {

                        if (arm_websocket.wsa_uart.running == true) {
                            arm_websocket.wsa_uart.uart_close();
                            printf("UARTPorts close.\n");
                        } 

                        printf("UARTPorts: %s\n", arm_websocket.client_json_data("UARTPorts"));

                        arm_websocket.wsa_uart.uart_init();
                    } else {
                        arm_websocket.wsa_uart.uart_close();
                        printf("UARTPorts close.\n");
                    }
                } else if(strcmp(arm_websocket.client_json_data("categories"), "gpio") == 0) {
                    if (strcmp(arm_websocket.client_json_data("command"), "open") == 0) {

                        if (arm_websocket.wsa_gpio.running == true) {
                            arm_websocket.wsa_gpio.gpio_close();
                            printf("GPIO close.\n");
                        } 


                        arm_websocket.wsa_gpio.gpio_init();
                    } else {
                        arm_websocket.wsa_gpio.gpio_close();
                        printf("UARTPorts close.\n");
                    }
                }

            }

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
    
    printf("starting server with thread: %d...\n", lws_get_count_threads(arm_websocket.context));
	
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
