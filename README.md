For local development:

1. Configure .env files (./env ./srv/.env)

2. run docker-compose -f docker-compose.dev.yml --project-name backstage up --build --force-recreate -d

3. after first run you have to wait 3-5 mins while composer installing dependencies, after 2nd+ run you have to wait 5-10 secs

Wait before composer packages will be installed (you can monitor process in backend container logs)

Enter inside your docker container docker exec -it CONTAINER_ID /bin/bash

run cd /var/www/app && php artisan key:generate && php artisan storage:link && php artisan passport:keys