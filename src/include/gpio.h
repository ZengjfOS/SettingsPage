#ifndef __GPIO_DATA_H__
#define __GPIO_DATA_H__

#include <stdio.h>  
#include <stdlib.h>  
#include <unistd.h>  
#include <sys/types.h>  
#include <sys/stat.h>  
#include <fcntl.h>      
#include <termios.h>    
#include <errno.h>  
#include <pthread.h>
#include <cstring>
#include <json/json.h>

typedef class WSA_GPIO {

public:
    // board index for chip index mapping
    int *gpio_map;
    int gpio_count;

    bool running;

    WSA_GPIO *wsa_GPIO;

    Json::Value *root;

    int  init_gpio_port ( void );

    pthread_t thread;

    int interval_time_ms;
    static void *monitor_gpio_thread ( void *arg );
    void monitor_thread_close(void);

	int gpio_export(int Index);
	int gpio_unexport(int Index);
	int set_input(int Index);
	int set_output(int Index);
	int get_mode(int Index, char *buf);
	int get_input_value(int Index);
	int set_output_value(int Index, int value);
	int set_edge(int Index, char* edge);
	int get_edge(int Index, char* edge);

    void gpio_init(void);
    void gpio_close(void);
	int is_folder_exist(char* path);
	int is_file_exist(char* path);

} WSA_GPIO;

#endif // __GPIO_DATA_H__
