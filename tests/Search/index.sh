#!/usr/bin/env bash

#查看索引列表
curl localhost:9200/_cat/nodes?v
curl localhost:9200/_cat/indices?v
#删除掉指定的索引
curl -XDELETE http://localhost:9200/test | jshon
#创建索引
curl -XPUT http://localhost:9200/test

#创建表,并且指定字段类型
curl -XPUT localhost:9200/test/_mapping/jd -d'
{
    "_all": {
            "analyzer": "ik_max_word",
            "search_analyzer": "ik_max_word",
            "term_vector": "no",
            "store": "false"
    },
    "properties" : {
         "id": {
                "type": "long"
            },
            "cateid": {
                "type": "long"
            },
             "tag": {
                "type": "keyword"
            },
            "addtime": {
                    "type": "date",
                     "format": "yyy-MM-dd HH:mm:ss||yyyy-MM-dd"
            },
            "title": {
                    "type": "text",
                    "analyzer": "ik_max_word",
                    "search_analyzer": "ik_max_word",
                    "include_in_all": "true",
                    "boost": 8
            }
    }
}'
#查看索引结构
curl -XGET localhost:9200/test| jshon

