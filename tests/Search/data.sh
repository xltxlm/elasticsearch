#!/usr/bin/env bash

#写入数据
curl -XPOST http://localhost:9200/test/jd/1 -d'
{
    "id":1,
    "cateid":101,
    "tag":"手机",
    "addtime":"2016-12-21 17:20:01",
    "title":"荣耀 V8 4GB+32GB 冰河银 移动联通4G手机 双卡双待双通"
}
' | jshon

#查看刚才写入的id=1的数据
curl -XGET http://localhost:9200/test/jd/1 | jshon

#准确的查询 id=1的数据
curl -XPOST http://localhost:9200/test/jd/_search -d'
{
    "query" : {
        "term" : {"id":1}
    }
}
'|jshon

#重复写入新数据
curl -XPOST http://localhost:9200/test/jd/2 -d'
{
    "id":2,
    "cateid":101,
    "tag":"手机",
    "addtime":"2016-12-25 17:20:01",
    "title":"联想 ZUK Z2 Pro手机（Z2121）尊享版 6G+128G 陶瓷白 移动联通电信4G手机 双卡双待"
}
' | jshon

curl -XPOST http://localhost:9200/test/jd/3 -d'
{
    "id":3,
    "cateid":101,
    "tag":"手机",
    "addtime":"2016-12-22 17:20:01",
    "title":"vivo Y67 全网通 4GB+32GB 移动联通电信4G手机 双卡双待 香槟金"
}
' | jshon

curl -XPOST http://localhost:9200/test/jd/4 -d'
{
    "id":4,
    "cateid":102,
    "tag":"电脑",
    "addtime":"2016-12-22 15:20:01",
    "title":" 戴尔 DELL燃7000 R1525G 14.0英寸微边框笔记本电脑(i5-7200U 4GB 128GB + 500G 940MX 2G独显 Win10)金戴尔 DELL燃7000 R1525G 14.0英寸微边框笔记本电脑(i5-7200U 4GB 128GB + 500G 940MX 2G独显 Win10)金戴尔 DELL燃7000 R1525G 14.0英寸微边框笔记本电脑(i5-7200U 4GB 128GB + 500G 940MX 2G独显 Win10)金戴尔 DELL燃7000 R1525G 14.0英寸微边框笔记本电脑(i5-7200U 4GB 128GB + 500G 940MX 2G独显 Win10)金戴尔 DELL燃7000 R1525G 14.0英寸微边框笔记本电脑(i5-7200U 4GB 128GB + 500G 940MX 2G独显 Win10)金戴尔 DELL燃7000 R1525G 14.0英寸微边框笔记本电脑(i5-7200U 4GB 128GB + 500G 940MX 2G独显 Win10)金戴尔 DELL燃7000 R1525G 14.0英寸微边框笔记本电脑(i5-7200U 4GB 128GB + 500G 940MX 2G独显 Win10)金戴尔 DELL燃7000 R1525G 14.0英寸微边框笔记本电脑(i5-7200U 4GB 128GB + 500G 940MX 2G独显 Win10)金 分享 关注商品举报 戴尔 DELL燃7000 R1525G 14.0英寸微边框笔记本电脑(i5-7200U 4GB 128GB + 500G 940MX 2G独显 Win10)金"
}
' | jshon

