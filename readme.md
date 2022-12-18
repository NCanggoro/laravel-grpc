# How to run

1. genereate server protobuf
    ```
        $ ./yoripe-grpc/protobuf/gen_proto_sh
    ```
2. migrate database
    ```
        $ php artisan migrate
    ```
2. symlink grpc server protobuf with  client directory
    ```
        $ ln -s {project-directory}/yoripe-grpc/protobuf {project-directory}/yoripe-grpc-client/
    ```
3. run ``composer install`` and`` composer dump-autoload`` on both client and server
4. run roadruner in server
    ```
        $ cd yoripe-grpc
        $ ./rr serve
    ```
5. run php artisan serve on client
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