#!/usr/bin/env bash

curl -XGET localhost:9200/jd/data/_search -d '
{
    "query": {
        "match_all": {}
    }
}' | jshon
