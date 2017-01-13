#!/usr/bin/env bash

# 最简单的分组查询 多字段
curl -XGET localhost:9200/jd/data/_search -d '
{
	"size": 0,
	"aggs": {
		"group_by_state": {
			"terms": {
				"field": "tag"
			}
		}
	}
}' | jshon


# 最简单的分组查询 多字段
curl -XGET localhost:9200/jd/data/_search -d '
{
	"size": 0,
	"aggs": {
		"group_by_state": {
			"terms": {
				"field": "tag"
			},
			"aggs":
			{
			    "group_by_id":{
			        "terms": {
                    "field": "id"
			        }
			    }
			}
		}
	}
}' | jshon
