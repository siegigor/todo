To run project (with docker and docker-compose):
````
make init
````
To run unit and feature tests:
````
make tests
````
To run code check (code-sniffer and psalm):
````
make check
````
Test user credentials (App\DataFixtures\UsersFixture.php):
````
[
    'email' => 'test1@gmail.com',
    'password' => 'todo1'
],
[
    'email' => 'test2@gmail.com',
    'password' => 'todo2'
]
````