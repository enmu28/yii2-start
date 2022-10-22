FROM elasticsearch:7.0.1

MAINTAINER NguyenQuocDai <quocdaijr@gmail.com>

RUN echo "* - nofile 262144" >> /etc/security/limits.conf && \
    echo "* - memlock 262144" >> /etc/security/limits.conf && \
    echo vm.max_map_count=262144 > /etc/sysctl.d/max_map_count.conf && \
    echo vm.swappiness=0 > /etc/sysctl.d/swappiness.conf

ENV ES_JAVA_OPTS="-Xms512m -Xmx512m"
ENV MAX_OPEN_FILES=262144
ENV MAX_LOCKED_MEMORY=unlimited

ADD ./config/elasticsearch.yml /usr/share/elasticsearch/config/elasticsearch.yml
ADD ./plugins/analysis-icu-7.0.1.zip /tmp/analysis-icu-7.0.1.zip
RUN bin/elasticsearch-plugin install file:///tmp/analysis-icu-7.0.1.zip

RUN cp /usr/share/zoneinfo/Asia/Ho_Chi_Minh /etc/localtime

EXPOSE 9400 9500

#CMD ["elasticsearch", "-Ecluster.name=ahihi-cluster"]