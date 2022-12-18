# How to run

1. run ``composer install`` and`` composer dump-autoload`` on both client and server
    ```
        // grpc server
        $ cd yoripe-grpc
        $ composer install
        // grpc client
        $ cd yoripe-grpc-client
        $ composer install
    ```

2. migrate database on grpc server
    ```
        $ cd yoripe-grpc
        $ php artisan migrate
    ```

3. genereate server protobuf
    ```
        // cd yoripe-grpc/protobuf/
        $ ./gen_proto_sh
    ```

4. symlink grpc server protobuf with  client directory
    ```
        $ ln -s {project-directory}/yoripe-grpc/protobuf {project-directory}/yoripe-grpc-client/
    ```

5. run roadruner in server
    ```
        $ cd yoripe-grpc &&\
        ./rr serve
    ```
    
6. run php artisan serve on client
    ```
        $ cd youripe-grpc-client
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