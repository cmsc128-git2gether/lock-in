# Lock In (Working Title)
## Application Description
A simple todo list app with task priorities, tags, soft-deletes with undo, and column-based filtering and sorting. Made with Laravel and love <3.

## Features
* __User Creation__ - allows users to create profile and use the application.
* __Adding New Task__ - enables user to make a new to-do task by entering the title, due date, priority, and tag.
* __Editing Task__ - provides a edit form for the users to update changes on their tasks.
* __Deleting Task__ - deletes tasks of the user.
* __Undo Option__ - appears after deletion of tasks if the user changed their mind to delete their task.
* __Recently Deleted Recovery__ - deleted tasks can be recovered or permanently deleted by the user.
* __Sort and Filter__ - sorts and filters by due date, date added, priority and tags.
* __Password Reset__ - send password reset emails for user who requested.
* __Profile Page Management__ - allows user to update user profile.
* __Collaborative Tasks__ - allows user to share a collaborative list and add their fellow user as a member.


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
| Frontend | **Blade templates**| Blade keeps the view layer in PHP alongside the backend (no separate frontend framework/build step for components.) |
| Authentication approach | **Breeze** | Implements Laravel's authentication methods, such as login, register, password reset, email verification, and email information. |
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
| `GET` | `/forgot-password` | `PasswordResetLinkController@create` | Shows the  page where the user can enter their email to get a reset password link. |
| `POST` | `/forgot-password` | `PasswordResetLinkController@store` | Gets the reset password request of the user and submits it to the system. Sends the email to the user requesting for a password reset. |
| `GET` | `/reset-password/{token}` | `NewPasswordController@create` | Shows password reset form page where the user can enter a new password. This link is from the password reset email. |
| `POST` | `/reset-password/{token}` | `NewPasswordController@store` | Stores the new password of the user and logs the user again. |
| `GET` | `/verify-email` | `EmailVerificationPromptController` | Prompts the notice to the user for email verification sent in their emails. |
| `GET` | `/verify-email/{id}/{hash}` | `VerifyEmailController` | Page linked from the verification email. |
| `POST` | `/email/verification-notification` | `EmailVerificationNotificationController@store` | Enables the resend verification email for a fresh link. |
| `GET` | `/confirm-password` | `ConfirmablePasswordController@show` | Shows the page for user's password confirmation. |
| `POST` | `/confirm-password` | `ConfirmablePasswordController@store` | Allows the re-entering of password of the user and checks if it matches. |
| `PUT` | `/password` | `PasswordController@update` | Enables changes for users to change their password if the user requested. |
| `POST` | `/logout` | `AuthenticatedSessionController@destroy` | Ends the session of the user. |


### Example: Creating a task and task ownership(`POST /tasks`)

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

## Authentication: Sessions and Password Recovery

This app uses Laravel's built-in session authentication (Laravel Breeze).

### Sessions

Because HTTP is stateless, the app uses server-side sessions to remember who is logged in.

1. Upon login success,  session ID is regenerated to prevent session fixation, and the user's ID is stored in the session.
2. The browser keeps only an encrypted session cookie. On each request, Laravel uses it to load the current user.
3. On logout (`POST /logout`), the session is invalidated and the CSRF token is regenerated.


### Password Recovery

1. When the user requests for password recovery, the system loads the reset password (`GET /forgot-password`) page, which requires them to submit their email address.
2. Then upon submitting the form, it will looking for the user's email, generates a hashed token, and sends a password reset email to the user (`POST /forgot-password`).
3. User opens the emailed link, validates if it has not expired yet, and user can enter a new password. The system updates the password of the user.

## Your schema: how Users, Tasks, and (if implemented) collaborative lists relate to each other


1. tasks

    - Tracks task metadata, deadlines, and operational prioritization.
    • user_id (unsignedBigInteger, nullable): References the creator (users.id). Cascades on delete.
    • tag_id (unsignedBigInteger, nullable): References contextual filters (tags.id). Nullifies on delete.
    • title (string): The textual body of the task.
    • priority (string): Categorization spectrum (Unlabeled, Low, Medium, High).
    • due_at (dateTime, nullable): Deadlines and target completion thresholds.
    • is_done (boolean): State tracking toggle.
    • deleted_at (softDeletes): Provides an indexable baseline (['user_id', 'deleted_at']) to cleanly run data restorations.

2. group_members

    - A pivot interface bridging users to spaces while scoping operational permissions.
• group_id & user_id: Foreign keys bound securely using a dual composite index unique(['group_id', 'user_id']).
• role (string): Default configuration parameters set to member.

3. group_tasks

- An intersection pivot model tracking task allocations across collaborative work lists.
• group_id & task_id: Foreign keys mapping data states across containers (unique(['group_id', 'task_id'])). Keeps core task records decoupled from lists.

## Example API endpoints or equivalent operations, showing how ownership is checked
All endpoints verify that a user belongs to a group before making changes. Destructive actions (like editing or deleting) verify that the user is the creator of the task or the owner of the group.

## Schema of Users, Tasks, and Groups
__users__
```
Schema::create('users', function (Blueprint $table) {
   $table->id();
   $table->string('name');
   $table->string('email')->unique();
   $table->timestamp('email_verified_at')->nullable();
   $table->string('password');
   $table->rememberToken();
   $table->timestamps();
});
```

__tags__
```
Schema::create('tags', function (Blueprint $table) {
   $table->id();
   $table->string('name');
   $table->string('color');
   $table->timestamps();
   $table->string('icon')->default('tag');
});
```

__tasks__
```
Schema::create('tasks', function (Blueprint $table) {
   $table->id();
   $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); 
   $table->foreignId('tag_id')->nullable()->constrained('tags')->nullOnDelete();
   $table->string('title');
   $table->string('priority')->default('Unlabeled'); // unlabeled, low, medium, high
   $table->dateTime('due_at')->nullable();
   $table->boolean('is_done')->default(false);
   $table->timestamps();
   $table->softDeletes();  // nullable deleted_at
   $table->index(['user_id', 'deleted_at']); // for deletion and restore
});
```

__groups__
```
Schema::create('groups', function (Blueprint $table) {
   $table->id();
   $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
   $table->string('name')->nullable();
   $table->timestamps();
});
```

__group_members__
```
Schema::create('group_members', function (Blueprint $table) {
   $table->id();
   $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
   $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
   $table->string('role')->default('member');
   $table->timestamps();
   $table->unique(['group_id', 'user_id']);
});
```

__group_tasks__
```
Schema::create('group_tasks', function (Blueprint $table) {
   $table->id();
   $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
   $table->foreignId('task_id')->constrained('tasks')->cascadeOnDelete();
   $table->timestamps();
   $table->unique(['group_id', 'task_id']);
});
```

### Schema Relationships
* `tasks` table uses `user_id` and `tag_id` as foreign id. `user_id` is the owner of the task with a certain `id` and has a tag with `tag_id`.
* `groups` table has a foreign id `onwer_id` which is the creator of the list, the logged in user creating the collaborative list. 
* `group_members` lists the group members and owner with foreign id `user_id` and identifies the role, default on member unless `user_id == owner_id`.
* `group_tasks` has a foreign id `group_id` to identify owner and members included in the collaborative list, and `task_id` that points to the task made for that certain group.

### Logic Flow
* User can belong to many groups
* User can have many tasks
* Task can belong to a user
* Tasks can be involved in group
* Group can belong to many group members
* Group can belong to an owner

## Example: Task Ownership
Utilizes auth and auth routes from activity 2

Ensures routes are only accessible by authenticated user
```
Route::middleware(['auth'])->group(function () {
   // Routes for tasks and groups
}
```

Checks for Task where user_id should be the authenticated logged in user 
```
private function userTask($id): Task {
   return Task::where('user_id', auth()->id())->findOrFail($id);
}
```

Group Task Controller gates
```
Gate::authorize('view', $group);
$validated = $request->validate($this->rules()); //valid title check

DB::transaction(function () use ($validated, $request, $group) {
   $task = Task::create($validated + ['user_id' => $request->user()->id]);
   $group->tasks()->attach($task->id);
});

return redirect()->route('groups.show', $group);
```

Request body example for group task:
```
task_id=72
group_id=1
```
returns to route('groups.show')
