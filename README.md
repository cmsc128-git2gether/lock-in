# Lock In (Working Title)

A simple todo list app with task priorities, tags, soft-delete with undo, and column-based filtering and sorting. Made with Laravel and love <3.

## Project Structure
Laravel splits backend logic across two folders (`routes` and `app`).
- `app/` Controllers, Models (business/CRUD logic)
- `routes/` Route definitions (API/web endpoints)
- `resources/` Frontend views (Blade templates), CSS, JS
- `database/` Migrations, seeders, and factories

## Tech Stack

| Layer | Choice | Why |
|---|---|---|
| Backend framework | **Laravel** (PHP) | Built-in routing, Eloquent ORM, and migrations make CRUD + relationships  fast to set up without manually coding SQL. |
| Database | **MySQL** | Relational structure fits the data well. Tasks belong to tags via a foreign key (`tag_id`), and Eloquent's relationship methods (`belongsTo`/`hasMany`) can easily map between models. |
| Frontend | **Blade templates**| Blade keeps the view layer in PHP alongside the backend (no separate frontend framework/build step for components. |
| Asset bundling | **Vite** | Laravel's default asset bundler. Compiles and hot-reloads CSS/JS during development (`npm run dev`). |


## Running the App Locally

### Requirements
- PHP >= 8.2
- Composer
- Node.js & npm
- MySQL (or another supported DB)
- mailtrap

### Mailtrap Setup
1. **Create an account using email**\
   Register account [here](https://mailtrap.io/).

2. **Look for your sandbox credentials**\
   Get your username and password, save it for your mailtrap configuration later on. This will serve as your inbox for email verification and password reset requests.



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

5. **Configure your database and mailer**\
   Open `.env` and set:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=toDo
   DB_USERNAME=root
   DB_PASSWORD=

   /* blocks of code */

   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=587
   MAIL_USERNAME=<you_username>
   MAIL_PASSWORD=<your_password>
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="hello@demomailtrap.co"
   MAIL_FROM_NAME="${APP_NAME}"
   ```
   Make sure the database itself exists (create it manually in MySQL if needed).

6. **Run migrations and seed the database**
   ```
   php artisan migrate --seed
   ```
   This creates the `users`, `tags`, and `tasks` tables, and seeds a few predefined tags (School, Personal, Others).

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

10. **Create your Account** \
   Register and verify your email using [mailtrap](https://mailtrap.io/)


## Data Operations (Routes)

Routes are used directly by Blade forms and JavaScript `fetch` calls within the app itself. 

Tasks and Profile routes are defined in `routes/web.php`.

| Method | Endpoint | Controller Method | Description |
|---|---|---|---|
| `GET` | `/home` | `TaskController@index` | Loads the task list, tags, and priority options for the main page. |
| `POST` | `/tasks` | `TaskController@store` | Creates a new task (title, due date, priority, tag). Used by the "Add New Task" popup. |
| `PATCH` | `/tasks/{id}` | `TaskController@update` | Changes bool value of a task's `is_done` status. Toggled by checkbox. |
| `PATCH` | `/tasks/{id}/submit` | `TaskController@submit` | Saves edits to an existing task (title, due date, priority, tag) from the Edit popup. |
| `DELETE` | `/tasks/{id}/destroy` | `TaskController@destroy` | Soft-deletes a task (sets `deleted_at`). Triggered via `fetch` from the Delete button; the row hides immediately and shows an "Undo" toast. |
| `PATCH` | `/tasks/{id}/restore` | `TaskController@restore` | Restores a soft-deleted task (clears `deleted_at`). Triggered via `fetch` when "Undo" is clicked within the toast window. |
| `GET` | `/profile` | `ProfileController@edit` | Enables profile editing. |
| `PATCH` | `/profile` | `ProfileController@update` | Saves the edit of the user profile. |
| `DELETE` | `/profile` | `ProfileController@destroy` | Deletes the user profile and information. |

\
Authentication routes are defined in `routes/auth.php`.

| Method | Endpoint | Controller Method | Description |
|---|---|---|---|
| `GET` | `/register` | `RegisteredUserController@create` | Shows registration page. |
| `POST` | `/register` | `RegisteredUserController@store` | Enables the form submission for registration. Validates the user input for auth. Creates the profile of the newly registered user. |
| `GET` | `/login` | `AuthenticatedSessionController@create` | Shows login page. |
| `POST` | `/login` | `AuthenticatedSessionController@store` | Handles the login form submission, checking the existing account of the user to the database. Starts the session of the user in using the web app. |
| `GET` | `/forget-password` | `PasswordResetLinkController@create` | Shows the  page where the user can enter their email to get a reset password link. |
| `POST` | `/forget-password` | `PasswordResetLinkController@store` | Gets the reset password request of the user and submits it to the system. Sends the email to the user requesting for a password reset. |
| `GET` | `/reset-password/{token}` | `NewPasswordController@create` | Shows password reset form page where the user can enter a new password. This link is from the password reset email. |
| `POST` | `/reset-password/{token}` | `NewPasswordController@store` | Stores the new password of the user and logs the user again. |
| `GET` | `/verify-email` | `EmailVerificationPromptController` | Prompts the notice to the user for email verification sent in their emails. |
| `GET` | `/verify-email/{id}/{hash}` | `VerifyEmailController` | Page linked from the verification email. |
| `POST` | `/email/verification-notification` | `EmailVerificationNotificationController@store` | Enables the resend verification email for a fresh link. |
| `GET` | `/confirm-password` | `ConfirmablePasswordController@show` | Shows the page for user's password confirmation. |
| `POST` | `/confirm-password` | `ConfirmablePasswordController@store` | Allows the re-entering of password of the user and checks if it matches. |
| `PUT` | `/password` | `PasswordController@update` | Enables changes for users to change their password if the user requested. |
| `POST` | `/logout` | `AuthenticatedSessionController@destroy` | Ends the session of the user. |


### Example: Creating a task (`POST /tasks`)

Request body:
```
title=Finish CMSC 130 lab
due_at=2026-09-15T23:59
priority=High
tag_id=1
user_id=1
```

Response: \
redirects back to `/` with the new task visible in the list.

## Screenshots of the App
<img src="https://i.ibb.co/DPxqt9Kn/Screenshot-2026-09-09-at-23-26-38-Todo-App.png" alt="Screenshot-2026-09-09-at-23-26-38-Todo-App" border="0">
<a href="https://ibb.co/zWq2yVSH"><img src="https://i.ibb.co/9mQ4FHq3/Screenshot-2026-09-09-at-23-30-08-Todo-App.png" alt="Screenshot-2026-09-09-at-23-30-08-Todo-App" border="0"></a>
<a href="https://ibb.co/20tjnN02"><img src="https://i.ibb.co/9mTq8tmX/Screenshot-2026-09-09-at-23-30-38-Todo-App.png" alt="Screenshot-2026-09-09-at-23-30-38-Todo-App" border="0"></a>

