#!/usr/bin/env bash

curl -XGET localhost:9200/test/jd/_search -d '
{
    "query": {
        "match_all": {}
    }
}' | jshon
