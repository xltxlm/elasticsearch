#!/usr/bin/env bash

curl -XDELETE http://localhost:9200/core2
curl -XPUT http://localhost:9200/core2


curl -XGET http://localhost:9200/core/video/_mapping

curl -XPOST http://localhost:9200/core2/video/_mapping -d'
{
    "video" : {
        "properties" : {
            "id" : {"type" : "long"},
            "title" : {
                "type": "text",
                "analyzer": "ik_max_word",
                "search_analyzer": "ik_max_word"
            }
        }
    }
}
'
curl -XGET http://localhost:9200/core2/video/_mapping