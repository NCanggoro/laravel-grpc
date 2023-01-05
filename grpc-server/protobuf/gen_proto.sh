mkdir build &&
protoc --proto_path=src --php_out=build --grpc_out=build --plugin=protoc-gen-grpc=/usr/bin/protoc-gen-php-grpc src/*.proto &&
protoc --proto_path=src --php_out=build --grpc_out=build  --plugin=protoc-gen-grpc=/usr/bin/grpc_php_plugin src/*.proto
