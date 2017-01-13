cd %~pd0
docker-compose up -d --force-recreate
rem sleep 6
ping 127.0.0.1 -n 6 > nul
docker exec -d docker_elasticsearch_1 bash -c "curl -XPUT http://127.0.0.1:9200/core2"
docker exec -d docker_elasticsearch_1 bash -c "curl -XPUT http://127.0.0.1:9200/_cat/indices?v"
