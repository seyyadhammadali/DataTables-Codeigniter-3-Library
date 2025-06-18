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
<div dir="auto">
    <div>
        <pre><code>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <h2>Users List</h2>
    <table id="userTable" class="display" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

    <script>
        $(document).ready(function() {
            $('#userTable').DataTable({
                serverSide: true,
                ajax: {
                    url: '<?= base_url('user/get_users') ?>',
                    type: 'POST'
                },
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'status' },
                    { data: 'action', orderable: false, searchable: false }
                ],
                order: [[1, 'asc']]
            });
        });
    </script>
</body>
</html>
       </code> </pre>
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
<h4 dir="auto">
    &nbsp;
</h4>
<h4 dir="auto">
    Explanation of Features Used
</h4>
<ol dir="auto">
    <li>
        <strong>Automatic Column Selection</strong>:
        <ul dir="auto">
            <li>
                In <span>get_users</span>, calling <span>from('users')</span> without <span>select</span> automatically selects all columns (<span>id</span>, <span>name</span>, <span>email</span>, <span>role_id</span>, <span>status</span>, <span>created_at</span>).
            </li>
        </ul>
    </li>
    <li>
        <strong>Explicit Column Selection</strong>:
        <ul dir="auto">
            <li>
                In <span>get_users_explicit</span>, <span>select('users.id, users.name as username, users.email, roles.role_name')</span> overrides automatic selection, with alias support.
            </li>
        </ul>
    </li>
    <li>
        <strong>Joins</strong>:
        <ul dir="auto">
            <li>
                <span>join('roles', 'roles.id = users.role_id', 'left')</span> links <span>users</span> to <span>roles</span>, allowing <span>role_name</span> to be displayed.
            </li>
        </ul>
    </li>
    <li>
        <strong>Filtering</strong>:
        <ul dir="auto">
            <li>
                <span>where('users.status', 'active')</span> applies a dynamic filter.
            </li>
            <li>
                <span>filter('users.status', 'active')</span> applies a static filter.
            </li>
            <li>
                Global and individual column searches are handled via DataTables' <span>search[value]</span> and <span>columns[i][search][value]</span>.
            </li>
        </ul>
    </li>
    <li>
        <strong>Ordering</strong>:
        <ul dir="auto">
            <li>
                The <span>get_ordering</span> method processes DataTables' <span>order[i][column]</span> and <span>order[i][dir]</span>, with fallback to <span>$this-&gt;columns</span>.
            </li>
            <li>
                Default sorting is set in DataTables (<span>order: [[1, 'asc']]</span>).
            </li>
        </ul>
    </li>
    <li>
        <strong>Pagination</strong>:
        <ul dir="auto">
            <li>
                <span>get_paging</span> handles <span>start</span> and <span>length</span> parameters for server-side pagination.
            </li>
        </ul>
    </li>
    <li>
        <strong>Custom Columns</strong>:
        <ul dir="auto">
            <li>
                <span>add_column('action', '&lt;a href="edit/$1"&gt;Edit&lt;/a&gt;', 'id')</span> adds an action column with a link using the <span>id</span>.
            </li>
            <li>
                <span>edit_column('name', '&lt;strong&gt;$1&lt;/strong&gt;', 'name')</span> wraps the <span>name</span> column in bold tags.
            </li>
            <li>
                <span>unset_column('role_id')</span> removes the <span>role_id</span> column from the output.
            </li>
        </ul>
    </li>
    <li>
        <strong>Output Modes</strong>:
        <ul dir="auto">
            <li>
                <span>generate('UTF-8', 'keybased')</span> produces associative arrays (<span>{"id":1,"name":"John"}</span>).
            </li>
            <li>
                <span>generate('UTF-8', 'indexed')</span> produces numeric arrays (<span>["1","John"]</span>).
            </li>
        </ul>
    </li>
    <li>
        <strong>Custom Data with </strong><span><strong>getproduction</strong></span>:
        <ul dir="auto">
            <li>
                In <span>get_custom_data</span>, <span>getproduction</span> processes a custom dataset, applying <span>add_column</span> transformations.
            </li>
        </ul>
    </li>
</ol>
<h4 dir="auto">
    Sample Output (<span>get_users</span>)
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
        <pre>
{
    "draw": 1,
    "recordsTotal": 2,
    "recordsFiltered": 2,
    "data": [
        {
            "id": "1",
            "name": "<strong>John Doe</strong>",
            "email": "john@example.com",
            "status": "active",
            "created_at": "2025-06-18 15:52:00",
            "role_name": "Admin",
            "action": "<a href=\"edit/1\">Edit</a>"
        },
        {
            "id": "3",
            "name": "<strong>Bob Johnson</strong>",
            "email": "bob@example.com",
            "status": "active",
            "created_at": "2025-06-18 15:52:00",
            "role_name": "Admin",
            "action": "<a href=\"edit/3\">Edit</a>"
        }
    ]
}
        </pre>
        <div>
            &nbsp;
        </div>
        <div>
            &nbsp;
        </div>
    </div>
</div>
<h4 dir="auto">
    Debugging Tips
</h4>
<p style="white-space:pre-wrap;" dir="auto">
    If issues arise (e.g., ordering not working, as mentioned previously):
</p>
<ol dir="auto">
    <li>
        <p>
            <strong>Log SQL Query</strong>:<br>
            <br>
            <pre>log_message('debug', $this->ci->db->last_query());</pre>
        </p>
        <p>
            Set <span>$config['log_threshold'] = 4</span> in <span>config.php</span> and check <span>application/logs</span>.
        </p>
    </li>
    <li>
        <strong>Inspect AJAX Request</strong>: Use the browser's Network tab to verify DataTables sends correct parameters (e.g., <span>order[0][column]=1&amp;order[0][dir]=asc</span>).
    </li>
    <li>
        <strong>Check Column Mapping</strong>: Ensure <span>columns[i][data]</span> in DataTables matches <span>$this-&gt;columns</span> or aliases (e.g., <span>username</span> for <span>name as username</span>).
    </li>
    <li>
        <p>
            <strong>Test Simple Query</strong>:
        </p>
        <div dir="auto">
            <div>
                <div>
                    <div>
                        <pre>$this->datalib->from('users')->generate('UTF-8', 'keybased');</pre>
                        <div>
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>
</ol>
<h4 dir="auto">
    Notes
</h4>
<ul dir="auto">
    <li>
        <strong>Regex Support</strong>: <span>REGEXP</span> is MySQL/PostgreSQL-specific. For SQL Server, use <span>PATINDEX</span>.
    </li>
    <li>
        <strong>Performance</strong>: For large tables, index columns used in <span>where</span>, <span>join</span>, or <span>order_by</span>. Cache <span>list_fields</span> results if needed.
    </li>
    <li>
        <strong>Security</strong>: The library uses CodeIgniter's query builder for SQL injection prevention. Validate user inputs for regex searches.
    </li>
    <li>
        <strong>PHP Compatibility</strong>: The <span>#[AllowDynamicProperties]</span> attribute ensures PHP 8.2+ compatibility.
    </li>
</ul>
<p style="white-space:pre-wrap;" dir="auto">
    If you encounter issues or need additional features (e.g., custom regex handling, database switching), please provide details like error messages, DataTables configuration, or specific requirements.
</p>
