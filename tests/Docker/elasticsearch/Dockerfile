FROM elasticsearch:5.1.1
COPY deb.list /etc/apt/sources.list
COPY / /root/

RUN cp /usr/share/zoneinfo/Asia/Shanghai /etc/localtime \
    && rm -f /etc/apt/sources.list.d/* \
    && chmod +x /root/*.sh \
    && rm -f /usr/share/elasticsearch/config/elasticsearch.yml && ln -snf /elasticsearch/elasticsearch.yml /usr/share/elasticsearch/config/elasticsearch.yml \
    && mkdir /usr/share/elasticsearch/plugins/ik && cp /root/plugins/elasticsearch-analysis-ik-5.1.1.zip /usr/share/elasticsearch/plugins/ik \
    && cd /usr/share/elasticsearch/plugins/ik && unzip  elasticsearch-analysis-ik-5.1.1.zip
    #&& apt-get -y update && apt-get -y install jshon
