#!/usr/bin/env bash

#读取原来的数据
curl -XGET http://localhost:9200/jd/data/2  | jshon

#重复写入新数据
curl -XPOST http://localhost:9200/js/data/2 -d'
{
    "id":2,
    "cateid":101,
    "tag":"手机",
    "money":160,
    "addtime":"2016-12-25 17:20:01",
    "title":"已经被修改的内容"
}
' | jshon

#读取原来的数据
curl -XGET http://localhost:9200/jd/data/2  | jshon