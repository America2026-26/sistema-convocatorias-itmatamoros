<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## SQL Server and account management

Set the following values in the local `.env` file for the SQL Server instance; do not commit database credentials:

```dotenv
DB_CONNECTION=sqlsrv
DB_HOST=127.0.0.1
DB_PORT=1433
DB_DATABASE=sistema_convocatorias
DB_USERNAME=your_sql_server_user
DB_PASSWORD=your_sql_server_password
DB_ENCRYPT=true
DB_TRUST_SERVER_CERTIFICATE=false
```

PHP must have the `sqlsrv` and `pdo_sqlsrv` extensions and the Microsoft SQL Server ODBC driver installed. Create the database first, then run `php artisan migrate`.
For a local development server using a self-signed certificate only, `DB_TRUST_SERVER_CERTIFICATE=true` allows the encrypted connection without validating that certificate. Keep it `false` in production and configure a certificate trusted by the host instead.

Public registration always creates a `usuario`; administrator-created accounts may be `usuario`, `dictaminador`, or `admin`. Create the first administrator from the terminal with `php artisan app:create-admin-user`. Admin-only account management and the access/change history are available from the navigation menu. Successful logins and account creation, updates, and deletions are timestamped in `audit_logs`; passwords are never included in that history.

## Role workspaces

- `/admin/dashboard`: live counts, account management, and paginated audit history.
- `/dictaminador/dashboard`: live counts, new and expiring calls, and links to the review workspace.
- Dictaminadores can create, edit, close, and delete calls without applications. Calls with submitted applications cannot be deleted; close them instead to preserve records.
- `/dictaminador/postulantes`: applications grouped by call, with private PDF access and approve/reject review actions.
- Applicants can submit their profile information and an optional PDF from an open call. Application decisions appear in the in-app notification inbox; the bell shows unread counts for applicants and reviewers.

Application documents are saved on Laravel's private `local` disk and are only available to the owner and reviewers through authenticated routes.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
