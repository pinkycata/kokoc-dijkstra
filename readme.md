docker build -t my-app .
docker run --name my-app my-app
docker exec -it my-app bash

php src/index.php - запуск кода
php vendor/phpunit/phpunit/phpunit src/tests/DijkstraTest.php - запуск тестов
