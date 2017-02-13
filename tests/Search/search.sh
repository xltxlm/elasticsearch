#!/usr/bin/env bash

curl -XGET localhost:9200/jd/data/_search -d '
{
    "query": {
        "match_all": {}
    }
}' | jshon


#查找手机类型的数据 , 对于keyword模糊匹配,查找不到任何数据
curl -XPOST http://localhost:9200/jd/data/_search -d'
{
    "query" : {
        "term" : {"tag":"手" }
    }
}
'|jshon

#查找手机类型的数据 , 精准匹配
curl -XPOST http://localhost:9200/jd/data/_search -d'
{
    "query" : {
        "term" : {"tag":"手机" }
    }
}
'|jshon

#查找手机类型的数据 , 精准匹配 - 按照时间倒序
curl -XPOST http://localhost:9200/jd/data/_search -d'
{
    "query" : {
        "term" : {"tag":"手机" }
    },
    "sort" :
        [
            {"addtime":"desc"}
        ]
}
'|jshon


#双重检索  并且操作, 精准匹配 - 按照时间倒序
curl -XPOST http://localhost:9200/jd/data/_search -d'
{
    "query" : {
        "bool":{
            "must":[
                {"term" : {"tag":"手机" }},
                {"term" : {"id":"2" }}
            ]
        }
    },
    "sort" :
    [
        {"addtime":"desc"}
    ]
}
'|jshon

#双重检索  或操作, 精准匹配 - 按照时间倒序
curl -XPOST http://localhost:9200/jd/data/_search -d'
{
    "query" : {
        "bool":{
            "should":[
                {"term" : {"tag":"手机" }},
                {"term" : {"id":"4" }}
            ]
        }
    },
    "sort" :
    [
        {"addtime":"desc"}
    ]
}
'|jshon


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




