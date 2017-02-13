#!/usr/bin/env bash

#查看节点列表
curl localhost:9200/_cat/nodes?v
#查看索引列表
curl localhost:9200/_cat/indices?v
#删除掉指定的索引
#curl -XDELETE http://localhost:9200/logs | jshon
#创建索引
curl -XPUT http://localhost:9200/logs

#创建表,并且指定字段类型
curl -XPUT localhost:9200/logs/_mapping/data -d'
{
    "properties" : {
         "id": {
                "type": "long"
            },
            "runClass": {
                "type": "keyword"
            },
            "addtime": {
                    "type": "date",
                     "format": "yyy-MM-dd HH:mm:ss||yyyy-MM-dd"
            }
    }
}'


#写入新数据
curl -XPOST http://localhost:9200/logs/data/1 -d'
{
    "id":1,
    "runClass":[
        "a::class",
        "b::class"
    ]
}' | jshon

curl -XPOST http://localhost:9200/logs/data/2 -d'
{
    "id":2,
    "runClass":[
        "c::class",
        "d::class"
    ]
}' | jshon

#测试查询结果
curl -XPOST http://localhost:9200/logs/data/2 -d'
{
    "query":{
        "bool":{
            "must":[
                { "term":{ "runClass":"a::class" } }
            ]
        }
    }
}' | jshon