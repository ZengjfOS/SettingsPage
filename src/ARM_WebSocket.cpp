#include <ARM_WebSocket.h>
#include <libwebsockets.h>
#include <json/json.h>
#include <uart.h>
#include <string>
#include <iostream>
#include <fstream>

using namespace std;

extern ARM_WebSocket arm_websocket;
ARM_WebSocket::ARM_WebSocket()
{
    // init send data to client thread mutex
    pthread_mutex_init(&mutex, NULL);

    // parse local config file
    ifstream in("config/config.json", ios::binary);
    if (!in.is_open()) {
        cout << "error opening file." << endl;
        exit(-1);
    }

    if (reader.parse(in, config_root)) 
        cout << "Parse local config over, member size: " << config_root["gpio"].size() << endl;
    else {
        cout << "Parse local config error." << endl;
        exit(-1);
    }
}

const char * ARM_WebSocket::client_json_data(const char * key)
{
    return (client_root[key].asString()).c_str();
}

void ARM_WebSocket::send_data(char * data, int len)
{
	pthread_mutex_lock(&mutex);

    unsigned char *buf = (unsigned char*) malloc(LWS_SEND_BUFFER_PRE_PADDING + len + LWS_SEND_BUFFER_POST_PADDING);

    memcpy(buf+LWS_SEND_BUFFER_PRE_PADDING, data, len);
    lws_write(wsi, &buf[LWS_SEND_BUFFER_PRE_PADDING], len, LWS_WRITE_TEXT);
    
    free(buf);

	pthread_mutex_unlock(&mutex);
}
