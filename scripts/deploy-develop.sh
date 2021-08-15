cd /srv/backstage

sudo -u www-data git fetch --all
sudo -u www-data git reset --hard origin/develop
sudo -u www-data git clean -f -d

mv .env.development .env
mv src/.env.development src/.env

cd src/public/wp-content/themes/app/html
yarn install
yarn build

cd /srv/backstage
sudo docker-compose -f docker-compose.yml --project-name backstage up --build --force-recreate -d

