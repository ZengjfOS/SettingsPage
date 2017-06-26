#include <stdio.h>
#include <dirent.h>
#include <unistd.h>
#include <sys/stat.h>
#include <gpio.h>
#include <iostream>
#include <fstream>
#include <ARM_WebSocket.h>

using namespace std;

extern ARM_WebSocket arm_websocket;

//检查目录是否存在
//-1:存在 0:不存在
int WSA_GPIO::is_folder_exist(char* path)
{
    DIR *dp;
    if ((dp = opendir(path)) == NULL) {
        return 0;
    }

    closedir(dp);
    return -1;
}

//检查文件(所有类型)是否存在
//-1:存在 0:不存在
int WSA_GPIO::is_file_exist(char* path)
{
    return !access(path, F_OK);
}

void WSA_GPIO::gpio_init(void){
    // gpioValue = {'high': 1, 'low': 0}
    
    // # gpio的几种触发方式
    // NONE    = 'none'
    // RISING  = 'rising'
    // FALLING = 'falling'
    // BOTH    = 'both'
    //
    int index = 0;

    gpio_count = arm_websocket.config_root["gpio"].size();
    gpio_map = (int *)malloc(sizeof(int) * gpio_count);

    for (; index < gpio_count; index++) {
        gpio_map[stoi(arm_websocket.config_root["gpio"][index]["board_index"].asString()) - 1] = stoi(arm_websocket.config_root["gpio"][index]["chip_index"].asString());

        gpio_export(index);

        if ( strcmp(arm_websocket.config_root["gpio"][index]["mode"].asString().c_str(), "in") == 0) {
            set_input(index);
        } else {
            set_output(index);
        }
    }

    /*
    for (index = 0; index < arm_websocket.config_root["gpio"].size(); index++){
        cout << "gpio int: " << gpio_map[index] << endl;
    }
    */

    running = true;
    interval_time_ms = 1000;
	
    pthread_create ( &thread, NULL, monitor_gpio_thread, &arm_websocket.wsa_gpio );

}

void WSA_GPIO::gpio_close(void) {
    if (pthread_cancel(thread)){
        printf("close uart recv thread error.");
    }

	free(gpio_map);
	
	sleep(1);
}

int WSA_GPIO::gpio_export(int index) {
    char buf[50] = {0};
    sprintf(buf, "/sys/class/gpio/gpio%d", gpio_map[index]);
    if (is_folder_exist(buf) == 0) {

        ofstream output("/sys/class/gpio/export");

        if (output.is_open()) {
            output << gpio_map[index];
            output.close();

            return 0;
        }
    }

    return -1;
}

int WSA_GPIO::gpio_unexport(int index) {
    char buf[50] = {0};
    sprintf(buf, "/sys/class/gpio/gpio%d", gpio_map[index]);
    if (!(is_folder_exist(buf) == 0)) {
        ofstream output("/sys/class/gpio/unexport");

        if (output.is_open()) {
            output << gpio_map[index];
            output.close();

            return 0;
        }
    }

    return -1;
}
        
int WSA_GPIO::set_input(int index) {
    char buf[50] = {0};
    sprintf(buf, "/sys/class/gpio/gpio%d/direction", gpio_map[index]);

	if (!is_file_exist(buf)) {
		ofstream output(buf);
		if (output.is_open()) {
			output << "in";
			output.close();

			return 0;
		}
	}	

    return -1;
}
        
int WSA_GPIO::set_output(int index) {
    char buf[50] = {0};
    sprintf(buf, "/sys/class/gpio/gpio%d/direction", gpio_map[index]);

	if (!is_file_exist(buf)) {
		ofstream output(buf);
		if (output.is_open()) {
			output << "out";
			output.close();

			return 0;
		}
	}	

    return -1;
}
int WSA_GPIO::get_mode(int index, char *mode) {
    char buf[50] = {0};
    sprintf(buf, "/sys/class/gpio/gpio%d/direction", gpio_map[index]);
    string read_buf;

	if (!is_file_exist(buf)) {
		ifstream input(buf);
		if (input.is_open()) {
			input >> read_buf;
			input.close();

			strcpy(mode, read_buf.c_str());

			return 0;
		}
	}	

    return -1;
}
        
int WSA_GPIO::get_input_value(int index) {
    char buf[50] = {0};
    string read_buf;
    sprintf(buf, "/sys/class/gpio/gpio%d/value", gpio_map[index]);

	if (!is_file_exist(buf)) {
		ifstream input(buf);
		if (input.is_open()) {
			input >> read_buf;
			input.close();

			return stoi(read_buf);
		}
	}

    return -1;
}
        
int WSA_GPIO::set_output_value(int index, int value) {
    char buf[50] = {0};
    sprintf(buf, "/sys/class/gpio/gpio%d/value", gpio_map[index]);

	if (!is_file_exist(buf)) {
		ofstream output(buf);
		if (output.is_open()) {
			output << value;
			output.close();

			return 0;
		}
	}

    return -1;
}
        
int WSA_GPIO::set_edge(int index, char* edge) {
    char buf[50] = {0};
    string read_buf;
    sprintf(buf, "/sys/class/gpio/gpio%d/edge", gpio_map[index]);

	if (!is_file_exist(buf)) {
		ifstream input(buf);
		if (input.is_open()) {
			input >> read_buf;
			input.close();

			strcpy(edge, read_buf.c_str());

			return 0;
		}
	}

    return -1;
}
        
int WSA_GPIO::get_edge(int index, char* edge) {
    char buf[50] = {0};
    sprintf(buf, "/sys/class/gpio/gpio%d/edge", gpio_map[index]);

	if (!is_file_exist(buf)) {
		ofstream output(buf);
		if (output.is_open()) {
			output << (void *)edge;
			output.close();

			return 0;
		}
	}

    return -1;
}

void * WSA_GPIO::monitor_gpio_thread ( void *arg ) {

    int ret = 0;
    WSA_GPIO *wsa_gpio = (WSA_GPIO *)arg;
    char buf[512] = {0};
	int index = 0;


    while ( 1 ) {

        Json::Value root;
        Json::Value data;

        root["categories"] = "gpio";
        root["type"] = "server";
		
		for (index = 0; index < wsa_gpio->gpio_count; index++) {

			data["index"] = index + 1;

			if (wsa_gpio->get_mode(index, buf) == 0) {
				data["mode"] = buf;
				bzero(buf, sizeof(buf));
			} else {
                if (index >= wsa_gpio->gpio_count)
                    break;
                continue;
            }
			if (strcmp(data["mode"].asString().c_str(), "in") == 0) {
				data["value"] = wsa_gpio->get_input_value(index);
				bzero(buf, sizeof(buf));
				
			} else {
                data.removeMember("value");
            }

        	root["data"].append(data);
		}

        memcpy(buf, root.toStyledString().c_str(), strlen(root.toStyledString().c_str()));
        printf("debug gpio receive data: %s.\n", buf);

        arm_websocket.send_data(buf, strlen(buf));

        bzero(buf, sizeof(buf));

        usleep(wsa_gpio->interval_time_ms * 1000);

        if (wsa_gpio->running == false) 
            break;
    }
}
