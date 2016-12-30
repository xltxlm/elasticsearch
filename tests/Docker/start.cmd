cd %~pd0
docker-compose up -d --force-recreate
docker-compose scale  elasticsearch=2

