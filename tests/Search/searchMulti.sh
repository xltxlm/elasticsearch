#!/usr/bin/env bash

multi_match
#所有字段检索
curl -XPOST http://localhost:9200/jd/data/_search -d'
{
    "query" : {
        "query_string":{
            "query":"手机",
            "analyzer":"ik_max_word"
        }
    }
}
'|jshon

#所有字段检索 看看能不能命中id
curl -XPOST http://localhost:9200/jd/data/_search -d'
{
    "query" : {
        "query_string":{
            "query":"6208554939514357761",
            "analyzer":"ik_max_word"
        }
    }
}
'|jshon

