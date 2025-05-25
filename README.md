# Product Catalog Search System

A Laravel-based application for managing a product catalog with full-text search powered by Elasticsearch. This project demonstrates how to integrate Laravel and Elasticsearch using a custom service class for scalable, high-performance search.

---

## Features

- **Product Management**: Store and manage products using Eloquent models.
- **Full-Text Search**: Fast, flexible search using Elasticsearch.
- **Custom Elastic Service**: Direct integration with Elasticsearch using a custom service class (no Laravel Scout).
- **Custom Artisan Command**: Quickly create and seed Elasticsearch indices from your database.

---

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL (or compatible database)
- Elasticsearch 8.x

---

## Installation

1. **Clone the repository**
    ```sh
    git clone https://github.com/thakur-kt/laravel-elasticsearch.git
    cd laravel-elasticsearch
    ```

2. **Install dependencies**
    ```sh
    composer install
    ```

3. **Copy and configure your environment**
    ```sh
    cp .env.example .env
    ```
    Edit `.env` and set your database and Elasticsearch connection:
    ```
    DB_DATABASE=products-catalog
    DB_USERNAME=your_db_user
    DB_PASSWORD=your_db_password

    ELASTICSEARCH_HOST=localhost:9200
    ```

4. **Generate application key**
    ```sh
    php artisan key:generate
    ```

5. **Run migrations and seeders**
    ```sh
    php artisan migrate --seed
    ```

6. **(Optional) Start Laravel development server**
    ```sh
    php artisan serve
    ```

---

## Usage

### Create and Seed Elasticsearch Index

To create an Elasticsearch index and seed it with all products from your database, run:

```sh
php artisan elastic:create-seed-index {index}
```
- `{index}`: (Optional) The name of the index to create. Defaults to `products`.

Example:
```sh
php artisan elastic:create-seed-index
```

---

## Project Structure

- `app/Console/Commands/ElasticCreateAndSeedIndex.php`  
  Artisan command to create and seed the Elasticsearch index.
- `app/Services/ElasticService.php`  
  Custom service class for interacting with Elasticsearch.
- `app/Models/Product.php`  
  Eloquent model for products.

---

## Troubleshooting

- **Elasticsearch version errors**  
  Make sure your PHP client and Elasticsearch server versions match (both should be 8.x).

- **Index/Alias errors**  
  If you get errors about aliases, ensure you are deleting or creating the correct index, not an alias.

- **Connection errors**  
  Ensure your `ELASTICSEARCH_HOST` in `.env` matches your running Elasticsearch instance.

---

## Running Elasticsearch and Kibana with Docker

You can quickly start Elasticsearch and Kibana using Docker:
cd laravel-elasticsearch
docker composer up -d 

**Test in browser:**

- [http://localhost:9200](http://localhost:9200) (Elasticsearch)
- [http://localhost:5601](http://localhost:5601) (Kibana)

---

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).