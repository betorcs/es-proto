[![Gitpod ready-to-code](https://img.shields.io/badge/Gitpod-ready--to--code-blue?logo=gitpod)](https://gitpod.io/#https://github.com/betorcs/es-proto)

### ES PROTO

É uma aplicação protótipo que demonstra utilização do ES 7.7 com symfony.


### Requisitos

- git
- docker-compose

**Por comodidade:**

- vscode
- vscode.rest-client - https://marketplace.visualstudio.com/items?itemName=humao.rest-client

### Como começar


Clone o projeto e inicie os containers docker e instale as dependencias

```bash
git clone git@netuno.arcasolutions.com:roberto.silva/es-proto.git
cd es-proto/docker
docker-compose up -d
docker exec -it docker_webapp_1 composer install
```

Abra o arquivo `test-req.rest` no vscode e execute as requisições.
