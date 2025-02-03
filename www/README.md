# 💰 Desafio Desenvolvedor - Oliveira Trust

## 📌 Finalidade da API
- Gerenciar o upload e processamento de arquivos nos formatos CSV e XLSX, armazenando os dados no banco de dados.
- Permitir a consulta das informações dos documentos já armazenados, garantindo acesso eficiente e estruturado aos dados.

## 🚀 Instalação e Configuração

### 1️⃣ **Clone o repositório**

```bash
git clone https://github.com/messias-barreto/desafio-desenvolvedor.git
```

### 2️⃣ **Rode o Build do Docker**

```bash
docker compose build
```

### 3️⃣ **Crie o arquivo `.env` e edite-o de acordo com seu ambiente**
   
```bash
cp .env.example .env

#configuração do Banco de Dados

## Exemplo da Configuração do docker-compose.yml
DB_CONNECTION=mysql
DB_HOST=OLIVEIRA_TRUST_MYSQL
DB_PORT=3306
DB_DATABASE=db_desafio_desenvolvimento
DB_USERNAME=root
DB_PASSWORD=123123
```

### 4️⃣ ***Suba os Containers do Docker***

```bash
docker compose up -d
```
### 5️⃣ ***Execute Migrations e Seeders***

```bash
docker exec -it OLIVEIRA_TRUST_PHP83 php /usr/share/nginx/html/artisan migrate --seed
```

### 🔎 Verificando o Status das Filas

O processamento das filas ocorre via Supervisor. Para verificar se os workers estão ativos, utilize:

```bash
docker exec -it OLIVEIRA_TRUST_PHP83 supervisorctl status
```
Se as filas não estiverem rodando corretamente, reinicie os workers com:
```bash
docker exec -it OLIVEIRA_TRUST_PHP83 supervisorctl restart all
```
### 🔄 Reiniciando os Serviços

Caso as migrations já tenham sido executadas com sucesso e seja necessário reiniciar os serviços para garantir a estabilidade, utilize:
```bash
docker compose restart
``
Isso garantirá que todos os contêineres sejam reiniciados e que o ambiente esteja corretamente configurado. 🚀
```

### 📚 Documentação

Acesse a documentação da API no seguinte endereço: http://127.0.0.1:8084/docs/


