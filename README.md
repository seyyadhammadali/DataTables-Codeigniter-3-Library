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
     ```php

### Methods
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

### Debugging

<ol dir="auto">
     <li>
        <p>
            <strong>Log SQL Queries</strong>:
        </p>
          <code>log_message('debug', $this->ci->db->last_query());</code>
    </li>
    <li>
        <strong>Inspect AJAX Requests</strong>: Use the browser's Network tab to verify DataTables parameters (e.g., <span>order[0][column]=1&amp;order[0][dir]=asc</span>).
    </li>
    <li>
        <strong>Check Column Mapping</strong>: Ensure <span>columns[i][data]</span> matches <span>$this-&gt;columns</span> or aliases in <span>select</span>.
    </li>
</ol>
<h2 dir="auto">
    Notes
</h2>
<ul dir="auto">
    <li>
        <strong>Regex Support</strong>: <span>REGEXP</span> is MySQL/PostgreSQL-specific. For SQL Server, use <span>PATINDEX</span>.
    </li>
    <li>
        <strong>Performance</strong>: Index columns used in <span>WHERE</span>, <span>JOIN</span>, or <span>ORDER BY</span>. Cache <span>list_fields</span> for large schemas.
    </li>
    <li>
        <strong>Security</strong>: Uses CodeIgniter's Query Builder for SQL injection prevention. Validate regex inputs.
    </li>
    <li>
        <strong>Ordering Fix</strong>: The <span>get_ordering</span> method excludes integer-like <span>data</span> values and includes robust fallbacks.
    </li>
</ul>
<h2 dir="auto">
    License
</h2>
<p style="white-space:pre-wrap;" dir="auto">
    MIT License. See WTF for details.
</p>
<h2 dir="auto">
    Contributing
</h2>
<p style="white-space:pre-wrap;" dir="auto">
    Contributions are welcome! Please submit issues or pull requests on GitHub.
</p>
<h2 dir="auto">
    Credits
</h2>
<p style="white-space:pre-wrap;" dir="auto">
    Developed for CodeIgniter 3 applications requiring DataTables server-side processing. Special thanks to the CodeIgniter and DataTables communities.
</p>
