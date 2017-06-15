all:
	g++ -shared -fPIC -o libjson.so *.cpp -I../include
	cp libjson.so ../

.PHONY: clean
clean:
	rm libjson.so
