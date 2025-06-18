# DataTable for CodeIgniter 3

A CodeIgniter 3 library designed for seamless server-side processing with [DataTables](https://datatables.net/). It provides a fluent, chainable interface for building database queries, supporting automatic column selection, joins, filtering, ordering, pagination, and custom column manipulation. The library is optimized for both indexed (numeric arrays) and key-based (associative arrays) JSON output, making it ideal for AJAX-driven DataTables applications.

## Features

- **Fluent Query Building**: Chainable methods for `SELECT`, `FROM`, `JOIN`, `WHERE`, `LIKE`, `GROUP BY`, `ORDER BY`, and more.
- **Automatic Column Selection**: Automatically selects all table columns if `select` is not defined, using the table specified in `from`.
- **DataTables Integration**: Handles server-side processing parameters (e.g., `start`, `length`, `order`, `search`) for pagination, ordering, and filtering.
- **Custom Columns**: Add, edit, or unset columns with dynamic content (e.g., action buttons, formatted text).
- **Output Modes**: Supports `indexed` (numeric arrays) and `keybased` (associative arrays) JSON output.
- **Database Switching**: Switch between multiple database connections defined in `config/database.php`.
- **Security**: Uses CodeIgniter's Query Builder for SQL injection prevention.
- **PHP 8.2+ Compatibility**: Includes `#[AllowDynamicProperties]` to suppress deprecation warnings.

## Requirements

- PHP 7.4 or higher (8.2+ recommended)
- CodeIgniter 3.x
- MySQL, PostgreSQL, or another database supported by CodeIgniter
- DataTables 1.10+ for client-side integration

## Installation

1. **Download the Library**:
   - Clone or download this repository.
   - Copy `Datalib.php` to `application/libraries/` in your CodeIgniter project.

2. **Configure CodeIgniter**:
   - Ensure the database library is autoloaded in `application/config/autoload.php`:
     ```php
     $autoload['libraries'] = array('database');
