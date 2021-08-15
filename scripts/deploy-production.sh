cd /srv/backstage

sudo docker image prune -a --force
sudo docker container prune --force

git fetch --all
git reset --hard origin/master
git clean -f -d

mv .env.production .env
mv src/.env.production src/.env

cd src/public/wp-content/themes/app/html
yarn install
yarn build

sudo cp scripts/nginx-production.conf /etc/nginx/sites-available/backstage-fca.com

cd /srv/backstage
sudo docker-compose -f docker-compose.yml --project-name backstage up --build --force-recreate -d
