# How to run

1. run ``composer install`` and`` composer dump-autoload`` on both client and server
    ```
        # grpc server
        $ cd grpc-server
        $ composer install
        # grpc client
        $ cd grpc-client
        $ composer install
    ```

2. migrate database on grpc server
    ```
        $ cd grpc-server
        $ php artisan migrate
    ```

3. genereate server protobuf
    ```
        # cd grpc-server/protobuf/
        $ ./gen_proto_sh
    ```

4. symlink grpc server protobuf with  client directory
    ```
        $ ln -s {project-directory}/grpc-server/protobuf {project-directory}/grpc-client/
    ```

5. run roadruner in server
    ```
        $ cd grpc-server &&\
        ./rr serve
    ```
    
6. run php artisan serve on client
    ```
        $ cd grpc-client
        $ php artisan sreve
    ```

# Route list
- POST /api/register
    - Param:
        - email
        - password
        - name
- POST /api/login
    - Param:
        - email
        - password
- POST /api/user 
    - Param
        - id
