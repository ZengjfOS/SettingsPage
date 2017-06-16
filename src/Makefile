SRC = ARM_WebSocket.cpp \
	  main.cpp \
	  uart/uart.cpp

all:
	g++ $(SRC) -lwebsockets -lpthread -Iinclude libjson.so -o lwsserver

.PHONY: clean
clean:
	rm lwsserver

