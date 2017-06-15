#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <pthread.h>
#include <libwebsockets.h>
#include <ARM_WebSocket.h>
#include <jsoncpp/json.h>
#include <uart.h>

pthread_t thread[2];
pthread_mutex_t mut;
int number=0, i;
int thread_index = 0;

static int callback_http(struct lws *wsi,
    enum lws_callback_reasons reason, void *user,
    void *in, size_t len)
{
	return 0;
}


void *thread_task(void *)
{
    /*
	int i =0;
	printf ("thread_task : I'm thread start %d\n", thread_index++);
	
	for (i = 0; i < 10; i++)
	{
		printf("thread_task %d: number = %d\n", thread_index, number);
		// pthread_mutex_lock(&mut);
		    number++;
		// pthread_mutex_unlock(&mut);
		sleep(1);
	}
	
	pthread_exit(NULL);
    */
	printf("thread_task start.\n");
    uart_init("/dev/ttyUSB0", "115200", "1234567890");
	printf("thread_task stop.\n");
}

static int callback_dumb_increment(struct lws *wsi,
    enum lws_callback_reasons reason,
    void *user, void *in, size_t len)
{
	int temp = 0;

    switch (reason) {
        case LWS_CALLBACK_ESTABLISHED: // just log message that someone is connecting
            printf("connection established\n");
            break;
        case LWS_CALLBACK_RECEIVE: { // the funny part

            /*
            if((temp = pthread_create(&thread[0], NULL, thread_task, NULL)) != 0)  //comment2     
                printf("线程1创建失败!/n");
            else
                printf("线程1被创建/n");
            */

            printf("received data: %s\n", (char *) in);

            Json::Value root;
            Json::Reader reader;

            if (reader.parse((char *)in, root, false))
            {
                printf("\n\ncategories: %s\n\n", (root["categories"].asString()).c_str());
                if (strcmp((root["categories"].asString()).c_str(), "uart") == 0)
                {
                    printf("\n\nUARTPorts: %s\n", (root["UARTPorts"].asString()).c_str());
                    printf("UARTBaudRate: %s\n", (root["UARTBaudRate"].asString()).c_str());
                    printf("UARTStopBit: %s\n", (root["UARTStopBit"].asString()).c_str());
                    printf("UARTDataLen: %s\n", (root["UARTDataLen"].asString()).c_str());
                    printf("UARTCheckBit: %s\n", (root["UARTCheckBit"].asString()).c_str());
                    printf("UARTIntervalSendData: %s\n", (root["UARTIntervalSendData"].asString()).c_str());
                    printf("UARTSendData: %s\n", (root["UARTSendData"].asString()).c_str());
                }

            }

            unsigned char *buf = (unsigned char*) malloc(LWS_SEND_BUFFER_PRE_PADDING + len + LWS_SEND_BUFFER_POST_PADDING);
            
            int i;
            for (i=0; i < len; i++) {
                buf[LWS_SEND_BUFFER_PRE_PADDING + (len - 1) - i ] = ((char *) in)[i];
            }
            
            lws_write(wsi, &buf[LWS_SEND_BUFFER_PRE_PADDING], len, LWS_WRITE_TEXT);
            
            // release memory back into the wild
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

ARM_WebSocket arm_websocket;
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
