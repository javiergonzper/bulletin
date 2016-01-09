FROM phusion/baseimage:latest
MAINTAINER Jesus Macias <jmacias@solidgear.es>
ENV DEBIAN_FRONTEND noninteractive

# Fix a Debianism of the nobody's uid being 65534
RUN usermod -u 99 nobody
RUN usermod -g 100 nobody

# Enable ssh for root
RUN sed -i 's/#PermitRootLogin yes/PermitRootLogin yes/' /etc/ssh/sshd_config
# Enable this option to prevent SSH drop connections
RUN printf "ClientAliveInterval 15\\nClientAliveCountMax 8" >> /etc/ssh/sshd_config

# Set correct environment variables
ENV HOME /root
ENV PHANTOM_JS_VERSION 1.9.7-linux-x86_64

RUN apt-get update
RUN apt-get install -y curl bzip2 libfreetype6 libfontconfig

RUN curl -sSL https://bitbucket.org/ariya/phantomjs/downloads/phantomjs-$PHANTOM_JS_VERSION.tar.bz2 | tar xjC /
RUN ln -s /phantomjs-$PHANTOM_JS_VERSION/bin/phantomjs /usr/bin/phantomjs

# Install owncloud dependencies
RUN apt-get update -q && apt-get install -y --force-yes php5 php5-cli git

ADD https://raw.githubusercontent.com/ariya/phantomjs/master/examples/rasterize.js /rasterize.js

RUN echo "$(tail -n +2 /rasterize.js)" > /rasterize.js

VOLUME ["/opt/bulletin"]

# Expose port
EXPOSE 22

# Use baseimage-docker's init system
CMD ["/sbin/my_init"]