#include <uart.h>
#include <ARM_WebSocket.h>
#include <jsoncpp/json.h>

extern ARM_WebSocket arm_websocket;

int WSA_UART::uart_init(void)
{
    char buf[128] = {0};

    memcpy(port, "/dev/", sizeof("/dev/"));
    memcpy(port + (sizeof("/dev/") - 1), arm_websocket.json_data("UARTPorts"), sizeof(arm_websocket.json_data("UARTPorts")));

    memcpy(buf, arm_websocket.json_data("UARTBaudRate"), sizeof(arm_websocket.json_data("UARTBaudRate")));
    baudrate = get_baudrate (buf);
    memset(buf, 0, sizeof(buf));

    memcpy(buf, arm_websocket.json_data("UARTStopBit"), sizeof(arm_websocket.json_data("UARTStopBit")));
    stop_bit = atoi(buf);
    memset(buf, 0, sizeof(buf));

    memcpy(buf, arm_websocket.json_data("UARTDataLen"), sizeof(arm_websocket.json_data("UARTDataLen")));
    data_len = atoi(buf);
    memset(buf, 0, sizeof(buf));

    strcpy(check_bit, arm_websocket.json_data("UARTCheckBit"));

    memcpy(buf, arm_websocket.json_data("UARTIntervalSendData"), sizeof(arm_websocket.json_data("UARTIntervalSendData")));
    interval_send_data = atoi(buf);
    memset(buf, 0, sizeof(buf));

    strcpy(send_buf, arm_websocket.json_data("UARTSendData"));

    /*
    printf("UARTPorts: %s\n", port);
    printf("UARTBaudRate: %d\n", baudrate);
    printf("UARTStopBit: %d\n", stop_bit);
    printf("UARTDataLen: %d\n", data_len);
    printf("UARTCheckBit: %s\n", check_bit);
    printf("UARTIntervalSendData: %d\n", interval_send_data);
    printf("UARTSendData: %s\n", send_buf);
    */

    if (init_uart_port () == 0) {

        pthread_create ( &recv_thread, NULL, recv_data_thread, &arm_websocket.wsa_uart );
        pthread_create ( &send_thread, NULL, send_data_thread, &arm_websocket.wsa_uart );

        running = true;
    } else {
        fd = -1;
        memset(port, 0 ,sizeof(port));
        baudrate = 0;
        stop_bit = 0;
        data_len = 0;
        memset(check_bit, 0 ,sizeof(check_bit));
        interval_send_data = 0;
        memset(send_buf, 0 ,sizeof(send_buf));
        running = false;
    }

    return 0;  
}

void WSA_UART::uart_close(void) 
{
    running == false;

    if (pthread_cancel(recv_thread)){
        printf("close uart recv thread error.");
    }

    if (pthread_cancel(send_thread)){
        printf("close uart send thread error.");
    }

    sleep(1);

    close(fd);
    fd = -1;

    memset(port, 0 ,sizeof(port));
    baudrate = 0;
    stop_bit = 0;
    data_len = 0;
    memset(check_bit, 0 ,sizeof(check_bit));
    interval_send_data = 0;
    memset(send_buf, 0 ,sizeof(send_buf));
}

void * WSA_UART::recv_data_thread(void *arg) {

    int ret = 0;
    int i = 0;
    WSA_UART *wsa_uart = (WSA_UART *)arg;

    while ( 1 ) {
        ret = arm_websocket.wsa_uart.uart_recv ( 
                wsa_uart->fd, 
                wsa_uart->recv_buf, 
                sizeof(wsa_uart->recv_buf) );

        printf("rev data len: %d.\n", ret);

        printf ( "rev: %s.\n", wsa_uart->recv_buf );
        arm_websocket.send_data(wsa_uart->recv_buf, strlen(wsa_uart->recv_buf));
        bzero ( wsa_uart->recv_buf, sizeof(wsa_uart->recv_buf) );

        usleep(wsa_uart->interval_send_data * 1000);

        if (wsa_uart->running == false) 
            break;
    }
}

void * WSA_UART::send_data_thread(void *arg) {

    int ret = 0;
    int i = 0;
    WSA_UART *wsa_uart = (WSA_UART *)arg;

    while ( 1 ) {

        printf("zengjf send_buf: %s.\n", wsa_uart->send_buf);
        ret = arm_websocket.wsa_uart.uart_send ( 
                wsa_uart->fd, 
                wsa_uart->send_buf, 
                strlen ( wsa_uart->send_buf) );  

        printf("send data len: %d.\n", ret);

        usleep(wsa_uart->interval_send_data * 1000);

        if (wsa_uart->running == false) 
            break;
    } 
      
}

int WSA_UART::uart_recv(int fd, char *data, int datalen) {  

    int ret = 0;

    ret = read ( fd, data, datalen );
      
    return ret;  
}

int WSA_UART::uart_send(int fd, char *data, int datalen) {  

    int len = 0;  

    len = write ( fd, data, datalen );     //实际写入的长度  
    if(len == datalen) {  
        return len;  
    } else {  
        tcflush(fd, TCOFLUSH);          //TCOFLUSH刷新写入的数据但不传送  
        return -1;  
    }  
      
    return 0;  
}  

int WSA_UART::init_uart_port( void ) {  

    //fd = open( port, O_RDWR | O_NOCTTY | O_NDELAY );  
    arm_websocket.wsa_uart.fd = open ( arm_websocket.wsa_uart.port, O_RDWR );  
    if ( arm_websocket.wsa_uart.fd < 0 ) {  
        perror ( "open" );  
        return -1;  
    }  
      
    // 串口主要设置结构体termios <termios.h>  
    struct termios options;  
      
    /**
     * tcgetattr函数用于获取与终端相关的参数，参数fd为终端的文件描述符，
     * 返回的结果保存在termios结构体中 
     */  
    tcgetattr ( arm_websocket.wsa_uart.fd, &options );  
    /**2. 修改所获得的参数*/  
    options.c_cflag |= (CLOCAL | CREAD);    //设置控制模式状态，本地连接，接收使能  
    options.c_cflag &= ~CSIZE;              //字符长度，设置数据位之前一定要屏掉这个位  
    options.c_cflag &= ~CRTSCTS;            //无硬件流控  
    options.c_cflag |= CS8;                 //8位数据长度  
    options.c_cflag &= ~CSTOPB;             //1位停止位  
    options.c_iflag |= IGNPAR;              //无奇偶检验位  
    options.c_oflag = 0;                    //输出模式  
    options.c_lflag = 0;                    //不激活终端模式  
    cfsetospeed ( &options, arm_websocket.wsa_uart.baudrate );        //设置波特率  
    //cfsetospeed(&options, B2000000);//设置波特率  
      
    /**3. 设置新属性，TCSANOW：所有改变立即生效*/  
    tcflush ( arm_websocket.wsa_uart.fd, TCIFLUSH );           //溢出数据可以接收，但不读  
    tcsetattr ( arm_websocket.wsa_uart.fd, TCSANOW, &options );  
      
    return 0;  
}  

int WSA_UART::get_baudrate ( char* baudrateString ) {
    int ret = atoi ( baudrateString );
    switch ( ret ) {
        case 0:
            printf ( "baudrate %s.\n", "0" );
            ret = B0;
            break;
        case 50:
            printf ( "baudrate %s.\n", "50" );
            ret = B50;
            break;
        case 75:
            printf ( "baudrate %s.\n", "75" );
            ret = B75;
            break;
        case 110:
            printf ( "baudrate %s.\n", "110" );
            ret = B110;
            break;
        case 134:
            printf ( "baudrate %s.\n", "134" );
            ret = B134;
            break;
        case 150:
            printf ( "baudrate %s.\n", "150" );
            ret = B150;
            break;
        case 200:
            printf ( "baudrate %s.\n", "200" );
            ret = B200;
            break;
        case 300:
            printf ( "baudrate %s.\n", "300" );
            ret = B300;
            break;
        case 600:
            printf ( "baudrate %s.\n", "600" );
            ret = B600;
            break;
        case 1200:
            printf ( "baudrate %s.\n", "1200" );
            ret = B1200;
            break;
        case 1800:
            printf ( "baudrate %s.\n", "1800" );
            ret = B1800;
            break;
        case 2400:
            printf ( "baudrate %s.\n", "2400" );
            ret = B2400;
            break;
        case 4800:
            printf ( "baudrate %s.\n", "4800" );
            ret = B4800;
            break;
        case 9600:
            printf ( "baudrate %s.\n", "9600" );
            ret = B9600;
            break;
        case 19200:
            printf ( "baudrate %s.\n", "19200" );
            ret = B19200;
            break;
        case 38400:
            printf ( "baudrate %s.\n", "38400" );
            ret = B38400;
            break;
        case 57600:
            printf ( "baudrate %s.\n", "57600" );
            ret = B57600;
            break;
        case 115200:
            printf ( "baudrate %s.\n", "115200" );
            ret = B115200;
            break;
        case 230400:
            printf ( "baudrate %s.\n", "230400" );
            ret = B230400;
            break;
        case 460800:
            printf ( "baudrate %s.\n", "460800" );
            ret = B460800;
            break;
        case 500000:
            printf ( "baudrate %s.\n", "500000" );
            ret = B500000;
            break;
        case 576000:
            printf ( "baudrate %s.\n", "576000" );
            ret = B576000;
            break;
        case 921600:
            printf ( "baudrate %s.\n", "921600" );
            ret = B921600;
            break;
        case 1000000:
            printf ( "baudrate %s.\n", "1000000" );
            ret = B1000000;
            break;
        case 1152000:
            printf ( "baudrate %s.\n", "1152000" );
            ret = B1152000;
            break;
        case 1500000:
            printf ( "baudrate %s.\n", "1500000" );
            ret = B1500000;
            break;
        case 2000000:
            printf ( "baudrate %s.\n", "2000000" );
            ret = B2000000;
            break;
        case 2500000:
            printf ( "baudrate %s.\n", "2500000" );
            ret = B2500000;
            break;
        case 3000000:
            printf ( "baudrate %s.\n", "3000000" );
            ret = B3000000;
            break;
        case 3500000:
            printf ( "baudrate %s.\n", "3500000" );
            ret = B3500000;
            break;
        case 4000000:
            printf ( "baudrate %s.\n", "4000000" );
            ret = B4000000;
            break;
        default:
            printf ( "baudrate is not exist %s.\n", "0" );
            ret = B0;
    }
    //printf ("baudrate %s.\n", baudrateString);
    return ret;
}
