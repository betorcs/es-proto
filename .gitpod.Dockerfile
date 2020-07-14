FROM gitpod/workspace-full

RUN apt-get update \
  && apt-get install docker-compose

RUN docker-compose up
