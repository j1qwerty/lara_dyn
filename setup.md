## new project
- `  composer create-project laravel/laravel myapp  `
-  `  cd myapp  `  and  `  php artisan serve  `

### breeze for auth
- auto generates .env ( add db config) , login and register, withs session.
```
cp .env.example .env

php artisan key:generate

```
- edit the db env for db. 

- install breeze 
```
composer require laravel/breeze --dev

php artisan breeze:install blade
```

```
php artisan migrate

php artisan serve 

```

## role based access.
### add role to db
- edit App/Models/User  and role
```php
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Add this
    ];
```
- create new migration
` php artisan make:migration add_role --table=users  `
- edit database/migrations/ add_role... 
```php
    public function up(): void
        {
            Schema::table('Users', function (Blueprint $table) {
                $table->enum('role',['admin','user'])->default('user')->after('password');
            });
        }

        public function down(): void
        {
            Schema::table('Users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
```

- run migration ` php artisan migrate `

### lets create role middleware to mannage routes based on roles easily. 
- ` php artisan make:middleware RoleMiddleware  `
- edit app/Http/Middleware/RoleMiddleware.php
```php
        namespace App\Http\Middleware;

        use Closure;
        use Illuminate\Http\Request;
        use Illuminate\Support\Facades\Auth;
        use Symfony\Component\HttpFoundation\Response;

        class RoleMiddleware
        {
            /**
             * Handle an incoming request.
             *
             * @param  array|string  $roles
             */
            public function handle(Request $request, Closure $next, ...$roles): Response
            {
                $user = Auth::user();

                if (!$user || !in_array($user->role, $roles)) {
                    abort(403, 'Unauthorized');
                }

                return $next($request);
            }
        }
```

- import and use middleware in routes/web.php
```php

    use App\Http\Middleware\RoleMiddleware;
    use Illuminate\Support\Facades\Route;

    Route::get('/', function () {
        return view('dashboard');
    })->middleware([
        'auth',
        'verified',
        RoleMiddleware::class . ':admin' // Add role check directly
    ])->name('dashboard');

```
