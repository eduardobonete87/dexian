# Projeto Dexian

Este projeto é uma API RESTful construída com Laravel, contendo os módulos de Cliente, Produto e Pedido. A aplicação roda em containers Docker com PHP 8 e MySQL.

---

# Pré-requisitos

- [Git](https://git-scm.com/)
- [Docker e Docker Compose](https://www.docker.com/)
- [Composer](https://getcomposer.org/)

---

# Passo a passo para rodar o projeto

# Clone o repositório

```bash
git clone https://github.com/eduardobonete87/dexian.git
cd dexian

cp .env.example .env

```


# Copie o .env

```bash
cp .env.example .env
```


# Rode os containers

```bash
docker-compose up -d
```

# Instale as dependências

```bash
docker exec -it app bash
composer install
```

# Gere a chave da aplicação

```bash
php artisan key:generate
```

# Rode as migrations

```bash
php artisan migrate
```

# Acesse no navegador
http://localhost:9000
