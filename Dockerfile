FROM ubuntu:24.04
LABEL authors="cypherbits"
ENV LC_ALL=C.UTF-8
RUN apt-get update -y && apt-get dist-upgrade software-properties-common -y
RUN add-apt-repository ppa:ondrej/php
RUN apt-get update -y && apt-get install php8.3 php8.3-dev -y
COPY . /making
RUN cd /making && phpize && ./configure --enable-blake3 && make && make install
CMD bash