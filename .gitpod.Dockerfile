FROM gitpod/workspace-full

USER root
RUN apt-get update \
  && apt-get install -y docker-compose

RUN docker-compose up

USER gitpod
