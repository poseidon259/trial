- Docker
    - docker-compose up -d 
    - docker image prune -af
    - docker-compose down
    - docker exec php bash
- Init
    - in docker exec php bash
    - composer install
    - php artisan migrate
    - php artisan passport:install
    - php artisan db:seed --class=RoleSeeder
    - php artisan db:seed --class=UserSeeder