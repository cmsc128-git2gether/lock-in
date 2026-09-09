# Lock In (Working Title)

A simple todo list app with task priorities, tags, soft-delete with undo, and column-based filtering and sorting. Made with Laravel and love <3.

## Project Structure
Laravel splits backend logic across two folders (`routes` and `app`).
- `app/` Controllers, Models (business/CRUD logic)
- `routes/` Route definitions (API/web endpoints)
- `resources/` Frontend views (Blade templates), CSS, JS
- `database/` Migrations and seeders

## Tech Stack

| Layer | Choice | Why |
|---|---|---|
| Backend framework | **Laravel** (PHP) | Built-in routing, Eloquent ORM, and migrations make CRUD + relationships  fast to set up without manually coding SQL. |
| Database | **MySQL** | Relational structure fits the data well — tasks belong to tags via a foreign key (`tag_id`), and Eloquent's relationship methods (`belongsTo`/`hasMany`) can easily map between models. |
| Frontend | **Blade templates**| Blade keeps the view layer in PHP alongside the backend (no separate frontend framework/build step for components. |
| Asset bundling | **Vite** | Laravel's default asset bundler. Compiles and hot-reloads CSS/JS during development (`npm run dev`). |


## Running the App Locally

### Requirements
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL (or another supported DB)

### Setup Steps

1. **Clone the repo**
   ```
   git clone https://github.com/cmsc128-git2gether/lock-in
   ```

2. **Install PHP dependencies**
   ```
   composer install
   ```

3. **Install JS dependencies**
   ```bash
   npm install
   ```

4. **Copy the environment file and generate an app key**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure your database**\
   Open `.env` and set:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=toDo
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   Make sure the database itself exists (create it manually in MySQL if needed).

6. **Run migrations and seed the database**
   ```
   php artisan migrate --seed
   ```
   This creates the `tasks` and `tags` tables, and seeds a few predefined tags (School, Personal, Others).

7. **Build frontend assets**
   ```
   npm run dev
   ```

8. **Start the local server**
   ```
   php artisan serve
   ```

9. **Open the App** \
   Ctrl+Click on the local host url to access the app.



## Data Operations (Routes)

Routes are used directly by Blade forms and JavaScript `fetch` calls within the app itself. All routes are defined in `routes/web.php`.

| Method | Endpoint | Controller Method | Description |
|---|---|---|---|
| `GET` | `/` | `TaskController@index` | Loads the task list, tags, and priority options for the main page. |
| `POST` | `/tasks` | `TaskController@store` | Creates a new task (title, due date, priority, tag). Used by the "Add New Task" popup. |
| `PATCH` | `/tasks/{id}` | `TaskController@update` | Changes bool value of a task's `is_done` status. Toggled by checkbox. |
| `PATCH` | `/tasks/{id}/submit` | `TaskController@submit` | Saves edits to an existing task (title, due date, priority, tag) from the Edit popup. |
| `DELETE` | `/tasks/{id}/destroy` | `TaskController@destroy` | Soft-deletes a task (sets `deleted_at`). Triggered via `fetch` from the Delete button; the row hides immediately and shows an "Undo" toast. |
| `PATCH` | `/tasks/{id}/restore` | `TaskController@restore` | Restores a soft-deleted task (clears `deleted_at`). Triggered via `fetch` when "Undo" is clicked within the toast window. |

### Example: Creating a task (`POST /tasks`)

Request body:
```
title=Finish CMSC 130 lab
due_at=2026-09-15T23:59
priority=High
tag_id=1
```

Response: \
redirects back to `/` with the new task visible in the list.

## Screenshots of the App
<img src="https://i.ibb.co/DPxqt9Kn/Screenshot-2026-09-09-at-23-26-38-Todo-App.png" alt="Screenshot-2026-09-09-at-23-26-38-Todo-App" border="0">
<a href="https://ibb.co/zWq2yVSH"><img src="https://i.ibb.co/9mQ4FHq3/Screenshot-2026-09-09-at-23-30-08-Todo-App.png" alt="Screenshot-2026-09-09-at-23-30-08-Todo-App" border="0"></a>
<a href="https://ibb.co/20tjnN02"><img src="https://i.ibb.co/9mTq8tmX/Screenshot-2026-09-09-at-23-30-38-Todo-App.png" alt="Screenshot-2026-09-09-at-23-30-38-Todo-App" border="0"></a>

