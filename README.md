# Laravel Migration Generator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ganeshk007/migration-generator.svg?style=flat-square)](https://packagist.org/packages/ganeshk007/migration-generator)
[![Total Downloads](https://img.shields.io/packagist/dt/ganeshk007/migration-generator.svg?style=flat-square&cacheBust=2)](https://packagist.org/packages/ganeshk007/migration-generator)

A Laravel package to generate migration files from existing database tables — perfect for legacy projects that don't have migrations. This tool helps developers generate migration files for all tables in one go, improving workflow and making future table changes manageable.

---

## 🚀 Features

- 🔄 Generate migrations from existing tables  
- 📂 Support for generating all or selected tables  
- ⚙️ Custom configuration via a published config file  
- 🧼 Clean and Laravel-style migration syntax  
- 🧾 Supports indexing, unique, nullable, and default column properties  

---

## 📦 Installation

```bash
composer require ganeshk007/migration-generator
```

---

## ⚙️ Configuration (Optional)

To publish the configuration file:

```bash
php artisan vendor:publish --tag=config
```

This creates a config file at:

```
config/migration-generator.php
```

You can configure which tables to include or exclude:

```php
return [

    // List of tables to exclude (e.g. Laravel system tables)
    'exclude_tables' => [
        'jobs',
        'failed_jobs',
        'password_resets',
        'oauth*'
    ],
];
```

---

## ⚙️ Usage

Generate migration files using:

```bash
php artisan migration:generate {table?}
```

### Optional Parameters

- `table`: (optional) The name of a specific table to generate a migration for. If not provided, migrations will be generated for all tables (excluding those in config).

**Examples**:

```bash
php artisan migration:generate
php artisan migration:generate users
```

---

## 🧪 Example Output

For a `users` table, the generated migration will look like:

```php
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

---

## 👨‍💻 Contributing

Pull requests are welcome! Here's how to contribute:

1. Fork the repository  
2. Create your feature branch: `git checkout -b feature/your-feature`  
3. Commit your changes: `git commit -am 'Add new feature'`  
4. Push to the branch: `git push origin feature/your-feature`  
5. Open a Pull Request  

---

## 🧾 License

This package is open-sourced software licensed under the [MIT license](LICENSE).

---

## 🙋 Author

**Ganesh Kumar**  
🔗 https://github.com/Ganeshk007
