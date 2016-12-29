#!/usr/bin/env bash

curl -XPUT localhost:9200/test/_mapping/jd -d '
{
    "query": {
        "match_all": {}
    }
}' | jshon
