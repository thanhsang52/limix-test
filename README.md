
## 🚀 Laravel Blog Post API
This is a simple Laravel-based RESTful API for managing blog posts, including Swagger (OpenAPI) documentation.

## ⚙️ Requirements
- PHP >= 8.1
- Composer
- Laravel >= 10
- SQLite 

## 🛠 Setup Instructions

### 1. Clone the repo & Install dependencies
```bash
git clone <your-repo-url>
cd blog-api
composer install
```

### 2. Create environment file
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Chỉnh sửa `.env` để dùng SQLite
```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```
> Create file `database.sqlite` empty if not exist:
```bash
touch database/database.sqlite
```

### 4. Migrate database
```bash
php artisan migrate
```

### 5. Seed (optional)
```bash
php artisan db:seed
```

### 6. Serve the application
```bash
php artisan serve
```

Server will run at `http://127.0.0.1:8000`

## 📘 API Documentation - Swagger (OpenAPI)

This project uses [Swagger](https://swagger.io/specification/) via `l5-swagger` to automatically generate interactive API documentation.

### 🔧 Setup Swagger Documentation

> Ensure you have `l5-swagger` installed. If not:

```bash
composer require "darkaonline/l5-swagger"
```

Then publish its config:

```bash
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
```

### 🛠 Generate API Docs

After adding OpenAPI annotations to your controllers (e.g., `PostController`, `UserController`), run:

```bash
php artisan l5-swagger:generate
```

This will generate documentation under `storage/api-docs`.

### 🔍 View API Docs

You can view the Swagger UI in your browser:

```
http://localhost:8000/api/documentation
```

Make sure your Laravel app is running and CORS settings allow local browser access.

### 📂 Annotated Controllers

- `App\Http\Controllers\PostController`
- `App\Http\Controllers\UserController`

## 🔗 API Endpoints

### Users
- `GET /api/users` - Get All User
- `POST /api/users` - Create New User
- `GET /api/users/{id}` - Get User By ID
- `PUT /api/users/{id}` - Update User
- `DELETE /api/users/{id}` - Delete User

### Posts
- `GET /api/posts` - Get All Posts
- `POST /api/posts` - Create New Post
- `GET /api/posts/{id}` - Get Post By ID
- `PUT /api/posts/{id}` - Update Post
- `DELETE /api/posts/{id}` - Delete Post

## 🧪 sample POST
### Create new user
```json
{
  "name": "Sang Huynh",
  "email": "sang@example.com"
}
```

### Create new post
```json
{
  "title": "Laravel Blog",
  "content": "This is a blog post.",
  "author_id": 1
}
```

## 🧬 Seed sample data

```php
use App\Models\User;
use App\Models\Post;

User::factory()->create([
    'name' => 'Sang Huynh',
    'email' => 'sang@example.com',
]);

Post::create([
    'title' => 'Hello Laravel',
    'content' => 'This is seeded post',
    'author_id' => 1
]);
```



## 👨‍💻 Author

Created by Sang Huynh

---

## 📝 License

This project is open-source and free to use under the [MIT license](LICENSE).

---