.PHONY : clean

buildrootHome=/home/aplex/zengjf/zengjfos/buildroot-2016.08
gccPath=output/host/usr/bin/arm-none-linux-gnueabi-g++
includePath=output/build/libwebsockets-v1.7.5/lib/
libPath=output/target/usr/lib/

all:
	${buildrootHome}/${gccPath}  -shared -fPIC -I${buildrootHome}/${includePath}  *.cpp -I../include -o libjson.so 
	cp libjson.so ..

.PHONY: clean
clean:
	rm libjson.so
