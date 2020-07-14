FROM gitpod/workspace-full

USER root
RUN apt-get update \
  && apt-get install docker-compose

RUN docker-compose up

USER gitpod
