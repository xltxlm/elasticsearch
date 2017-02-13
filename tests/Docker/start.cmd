cd %~pd0
chcp 65001
docker-compose up -d --force-recreate
rem sleep 6
ping 127.0.0.1 -n 20 > nul
rem 创建索引
docker exec -d docker_elasticsearch_1 bash -c "/opt/tests/Search/index.sh"
rem 查看已经创建的索引
docker exec -it docker_elasticsearch_1 bash -c "curl -XGET http://127.0.0.1:9200/_cat/indices?v"
docker exec -it docker_elasticsearch_1 /opt/tests/Search/index.sh
