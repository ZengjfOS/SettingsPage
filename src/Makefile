buildrootHome=/home/aplex/zengjf/zengjfos/buildroot-2016.08
gccPath=output/host/usr/bin/arm-none-linux-gnueabi-g++
# includePath=output/build/libwebsockets-v1.7.5/lib/ 
libPath=output/target/usr/lib/ 

SRC = ARM_WebSocket.cpp \
	  main.cpp \
	  uart/uart.cpp

# all:
	# g++ $(SRC) -lwebsockets -lpthread -Iinclude libjson.so -o lwsserver

all:
	${buildrootHome}/${gccPath} \
		-I${buildrootHome}/output/target/include/ \
		-L${buildrootHome}/${libPath} \
		-L${buildrootHome}/output/target/lib/ \
		-lpthread \
		-Iinclude \
		-lwebsockets \
		-ljsoncpp \
		$(SRC) \
		-o lwsserver

.PHONY: clean
clean:
	rm lwsserver

