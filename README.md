# Serverside DataTables for CodeIgniter 3

A CodeIgniter 3 library designed for seamless server-side processing with [DataTables](https://datatables.net/manual/server-side). It provides a fluent, chainable interface for building database queries, supporting automatic column selection, joins, filtering, ordering, pagination, and custom column manipulation. The library is optimized for both indexed (numeric arrays) and key-based (associative arrays) JSON output, making it ideal for AJAX-driven DataTables applications.

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
   - Copy `Library/Datatable.php` to `application/libraries/` in your CodeIgniter project.

2. **Configure CodeIgniter**:
   - Ensure the database library is autoloaded in `application/config/autoload.php`:
     ```php
     $autoload['libraries'] = array('database');
       
3. **Controller (application/controllers/User.php)**:
   - Copy paste the controller code as per example:
     ```php
     <?php
      defined('BASEPATH') OR exit('No direct script access allowed');
      
      class User extends CI_Controller {
          public function __construct()
          {
              parent::__construct();
              $this->load->library('datatable');
          }
      
          public function index()
          {
              $this->load->view('user_view');
          }
      
          public function get_users()
          {
              $output = $this->datatable
                  ->from('users') // Automatically selects all columns
                  ->where('status', 'active')
                  ->add_column('action', '<a href="edit/$1">Edit</a>', 'id')
                  ->generate('UTF-8', 'keybased');
      
              $this->output
                  ->set_content_type('application/json')
                  ->set_output($output);
          }
      }

4. **View (application/views/user_view.php)**:
   - Copy paste the view code from view file (DataTable.html) as per example:

## Methods
- set_database($db_name): Switch to a database defined in <span>config/database.php.
- select($columns = '', $backtick_protect = FALSE): Define columns for SELECT. If empty, uses all table columns.
- from($table): Set the table and auto-select columns if select</span> not called.
- join($table, $fk, $type = ''): Add a JOIN clause.
- where($key, $val = NULL, $backtick_protect = TRUE): Add a WHERE condition.
- or_where($key, $val = NULL, $backtick_protect = TRUE): Add an OR WHERE condition.
- like($key, $val = NULL, $backtick_protect = TRUE): Add a LIKE condition.
- filter($key, $val = NULL, $backtick_protect = TRUE): Add a static filter.
- group_by($column): Add a GROUP BY clause.
- order_by($column, $sort): Add an ORDER BY clause.
- distinct($column): Add a DISTINCT clause.
- add_column($column, $content, $match_replacement = NULL): Add a custom column.
- edit_column($column, $content, $match_replacement): Modify an existing column.
- unset_column($column): Remove a column from output.
- generate($charset = 'UTF-8', $output_mode = 'indexed'): Build query and return JSON $output_mode has two modes (indexed / keybased).
- getproduction($rows, $count, $charset = NULL, $output_mode = 'indexed'): Process custom data and return JSON.

## Debugging

1.**Log SQL Queries:**
```php
   log_message('debug', $this->ci->db->last_query());
```
2.**Inspect AJAX Requests:** Use the browser's Network tab to verify DataTables parameters (e.g., order[0][column]=1&amp;order[0][dir]=asc).

3.**Check Column Mapping:** Ensure columns[i][data] matches <span>$this->columns or aliases in select.

## Notes

- **Regex Support**: REGEXP is MySQL/PostgreSQL-specific. For SQL Server, use PATINDEX.
- **Performance**: Index columns used in WHERE, JOIN, or ORDER BY. Cache list_fields for large schemas.
- **Security**: Uses CodeIgniter's Query Builder for SQL injection prevention. Validate regex inputs.
- **Ordering Fix**: The get_ordering method excludes integer-like data values and includes robust fallbacks.

## License

MIT License. See WTF for details.

## Contributing

Contributions are welcome! Please submit issues or pull requests on GitHub.

## Credits

Developed for CodeIgniter 3 applications requiring DataTables server-side processing. Special thanks to the CodeIgniter and DataTables communities.
