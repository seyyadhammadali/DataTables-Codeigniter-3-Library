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
<h3 dir="auto">
    Full Usage Example
</h3>
<p style="white-space:pre-wrap;" dir="auto">
    Below is a comprehensive example demonstrating the library's features, including automatic column selection, joins, filtering, ordering, pagination, custom columns, and both output modes. The example assumes a CodeIgniter 3 application with a MySQL database containing <span>users</span> and <span>roles</span> tables.
</p>
<h4 dir="auto">
    Controller (<span>application/controllers/User.php</span>)
</h4>
<div dir="auto">
    <div>
        <div>
            &nbsp;
        </div>
    </div>
</div>
<div dir="auto">
    <div>
        <pre><?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->library('datalib');
    }

    public function index()
    {
        $this->load->view('user_view');
    }

    public function get_users()
    {
        $output = $this->datalib
            ->from('users') // Automatically selects all columns
            ->where('status', 'active')
            ->add_column('action', '<a href="edit/$1">Edit</a>', 'id')
            ->generate('UTF-8', 'keybased');

        $this->output
            ->set_content_type('application/json')
            ->set_output($output);
    }
}</pre>
        <div>
            &nbsp;
        </div>
    </div>
</div>
<h4 dir="auto">
    View (<span>application/views/user_view.php</span>)
</h4>
<div dir="auto">
    <div>
        <div>
            &nbsp;
        </div>
    </div>
</div>

<ol start="2" dir="auto">
    <li>
        <strong>Autoload Library (</strong><span><strong>application/config/autoload.php</strong></span><strong>)</strong>:
    </li>
</ol>
<div dir="auto">
    <div>
        <pre>$autoload['libraries'] = array('database');</pre>
        <div>
            &nbsp;
        </div>
    </div>
</div>
<ol start="3" dir="auto">
    <li>
        <strong>Place </strong><span><strong>DataTable.php</strong></span>:
        <ul dir="auto">
            <li>
                Save the updated library in <span>application/libraries/Datalib.php</span>.
            </li>
        </ul>
    </li>
</ol>
<h2 dir="auto">
    Methods
</h2>
<ul dir="auto">
    <li>
        <span>set_database($db_name)</span>: Switch to a database defined in <span>config/database.php</span>.
    </li>
    <li>
        <span>select($columns = '', $backtick_protect = FALSE)</span>: Define columns for <span>SELECT</span>. If empty, uses all table columns.
    </li>
    <li>
        <span>from($table)</span>: Set the table and auto-select columns if <span>select</span> not called.
    </li>
    <li>
        <span>join($table, $fk, $type = '')</span>: Add a <span>JOIN</span> clause.
    </li>
    <li>
        <span>where($key, $val = NULL, $backtick_protect = TRUE)</span>: Add a <span>WHERE</span> condition.
    </li>
    <li>
        <span>or_where($key, $val = NULL, $backtick_protect = TRUE)</span>: Add an <span>OR WHERE</span> condition.
    </li>
    <li>
        <span>like($key, $val = NULL, $backtick_protect = TRUE)</span>: Add a <span>LIKE</span> condition.
    </li>
    <li>
        <span>filter($key, $val = NULL, $backtick_protect = TRUE)</span>: Add a static filter.
    </li>
    <li>
        <span>group_by($column)</span>: Add a <span>GROUP BY</span> clause.
    </li>
    <li>
        <span>order_by($column, $sort)</span>: Add an <span>ORDER BY</span> clause.
    </li>
    <li>
        <span>distinct($column)</span>: Add a <span>DISTINCT</span> clause.
    </li>
    <li>
        <span>add_column($column, $content, $match_replacement = NULL)</span>: Add a custom column.
    </li>
    <li>
        <span>edit_column($column, $content, $match_replacement)</span>: Modify an existing column.
    </li>
    <li>
        <span>unset_column($column)</span>: Remove a column from output.
    </li>
    <li>
        <span>generate($charset = 'UTF-8', $output_mode = 'indexed')</span>: Build query and return JSON.
    </li>
    <li>
        <span>getproduction($rows, $count, $charset = NULL, $output_mode = 'indexed')</span>: Process custom data and return JSON.
    </li>
</ul>
<h2 dir="auto">
    Debugging
</h2>
<ol dir="auto">
    <li>
        <p>
            <strong>Log SQL Queries</strong>:
        </p>
        <div dir="auto">
            <div>
                <div>
                    <span>php</span>
                </div>
                <div>
                    <div>
                        <div style="opacity:1;">
                            <button type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 20 5-5 5 5"></path><path d="m7 4 5 5 5-5"></path></svg><span>Collapse</span></button><button type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"></line><path d="M3 12h15a3 3 0 1 1 0 6h-4"></path><polyline points="16 16 14 18 16 20"></polyline><line x1="3" x2="10" y1="18" y2="18"></line></svg><span>Wrap</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ol>
<p>
    <button type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path></svg><span>Copy</span></button>
</p>
<ol dir="auto">
    <li>
        <div dir="auto">
            <div>
                <div style="border-image-outset:;border-image-repeat:;border-image-slice:;border-image-source:;border-image-width:;border-radius:0px 0px 12px 12px;border-top-style:none;color:var(--fg-primary);display:block;font-family:var(--font-ibm-plex-mono);font-size:0.9em;line-height:1.5em;margin-top:0px;overflow-x:auto;padding:16px;">
                    <code style="font-size:inherit;line-height:inherit;white-space:pre;"><span>log_message(</span></code><span style="color:rgb(80,161,79);"><code style="font-size:inherit;line-height:inherit;white-space:pre;">'debug'</code></span><code style="font-size:inherit;line-height:inherit;white-space:pre;"><span>, </span></code><span style="color:rgb(166,38,164);"><code style="font-size:inherit;line-height:inherit;white-space:pre;">$this</code></span><code style="font-size:inherit;line-height:inherit;white-space:pre;"><span>-&gt;ci-&gt;db-&gt;last_query());</span></code>
                </div>
                <div>
                    &nbsp;
                </div>
                <div>
                    &nbsp;
                </div>
            </div>
        </div>
        <p>
            Enable logging in <span>application/config/config.php</span>:
        </p>
        <div dir="auto">
            <div>
                <div>
                    <span>php</span>
                </div>
                <div>
                    <div>
                        <div style="opacity:1;">
                            <button type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 20 5-5 5 5"></path><path d="m7 4 5 5 5-5"></path></svg><span>Collapse</span></button><button type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" x2="21" y1="6" y2="6"></line><path d="M3 12h15a3 3 0 1 1 0 6h-4"></path><polyline points="16 16 14 18 16 20"></polyline><line x1="3" x2="10" y1="18" y2="18"></line></svg><span>Wrap</span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ol>
<p>
    <button type="button"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"></rect><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"></path></svg><span>Copy</span></button>
</p>
<ol dir="auto">
    <li>
        <div dir="auto">
            <div>
                <div style="border-image-outset:;border-image-repeat:;border-image-slice:;border-image-source:;border-image-width:;border-radius:0px 0px 12px 12px;border-top-style:none;color:var(--fg-primary);display:block;font-family:var(--font-ibm-plex-mono);font-size:0.9em;line-height:1.5em;margin-top:0px;overflow-x:auto;padding:16px;">
                    <span style="color:rgb(152,104,1);"><code style="font-size:inherit;line-height:inherit;white-space:pre;">$config</code></span><code style="font-size:inherit;line-height:inherit;white-space:pre;"><span>[</span></code><span style="color:rgb(80,161,79);"><code style="font-size:inherit;line-height:inherit;white-space:pre;">'log_threshold'</code></span><code style="font-size:inherit;line-height:inherit;white-space:pre;"><span>] = </span></code><span style="color:rgb(152,104,1);"><code style="font-size:inherit;line-height:inherit;white-space:pre;">4</code></span><code style="font-size:inherit;line-height:inherit;white-space:pre;"><span>;</span></code>
                </div>
                <div>
                    &nbsp;
                </div>
                <div>
                    &nbsp;
                </div>
            </div>
        </div>
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
