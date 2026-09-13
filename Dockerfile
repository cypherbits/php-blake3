ARG BASE_IMAGE=ubuntu:24.04
FROM ${BASE_IMAGE}
LABEL authors="cypherbits"
ENV LC_ALL=C.UTF-8
RUN apt-get update -y && apt-get dist-upgrade software-properties-common -y
# Stock Ubuntu 26.04 (resolute) already ships PHP 8.5; only older bases need the PPA
RUN if [ "$(. /etc/os-release && echo "${VERSION_ID%%.*}")" = "24" ]; then add-apt-repository ppa:ondrej/php; fi
RUN apt-get update -y && apt-get install php8.5 php8.5-dev -y
COPY . /making
RUN cd /making && phpize && ./configure --enable-blake3 && make && make install
CMD bash
