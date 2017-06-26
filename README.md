# ARM WebSocket

## Generate jsoncpp shared lib

* Ubuntu
  * download jsoncpp from github
  * `mkdir -p build/debug`
  * `cd build/debug`
  * `cmake -DCMAKE_BUILD_TYPE=debug -DBUILD_STATIC_LIBS=ON -DBUILD_SHARED_LIBS=OFF -DARCHIVE_INSTALL_DIR=. -G "Unix Makefiles" ../..`
  * `make`
* Buildroot
  * choose the jsoncpp part path in [Makefile.buildroot](src/Makefile.buildroot), just need to modify the path of output lib
  * recompile

## Data Format

### UART Protocol

* Client JSON Data Protocol Demo
	```json
		{
			"categories":"uart",
			"type":"client",
			"command":"open",
			"UARTPorts":"ttyUSB0",
			"UARTBaudRate":"115200",
			"UARTStopBit":"1",
			"UARTDataLen":"8",
			"UARTCheckBit":"None",
			"UARTIntervalSendData":"1000",
			"UARTSendData":"1234567890\n"
		}
	```
* Server JSON Data Protocol Demo
    ```json
		{
		   "categories" : "uart",
		   "type" : "server",
		   "command" : "receive",
		   "data" : "1234567890\n",
		   "cmp_index" : 12,
		   "recv_index" : 12,
		   "send_index" : 12
		}
    ```
