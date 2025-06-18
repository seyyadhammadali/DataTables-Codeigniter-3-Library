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
        <pre><code class="language-plaintext">&lt;?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this-&gt;load-&gt;library('datalib');
    }

    public function index()
    {
        $this-&gt;load-&gt;view('user_view');
    }

    public function get_users()
    {
        // Example 1: Automatic column selection with join and custom column         $output = $this-&gt;datalib
            -&gt;from('users')
            -&gt;join('roles', 'roles.id = users.role_id', 'left')
            -&gt;where('users.status', 'active')
            -&gt;add_column('action', '&lt;a href="edit/$1"&gt;Edit&lt;/a&gt;', 'id')
            -&gt;edit_column('name', '&lt;strong&gt;$1&lt;/strong&gt;', 'name')
            -&gt;unset_column('role_id')
            -&gt;generate('UTF-8', 'keybased');

        $this-&gt;output
            -&gt;set_content_type('application/json')
            -&gt;set_output($output);
    }

    public function get_users_explicit()
    {
        // Example 2: Explicit column selection with static filter         $output = $this-&gt;datalib
            -&gt;select('users.id, users.name as username, users.email, roles.role_name')
            -&gt;from('users')
            -&gt;join('roles', 'roles.id = users.role_id', 'left')
            -&gt;filter('users.status', 'active')
            -&gt;add_column('profile', '&lt;a href="profile/$1"&gt;View&lt;/a&gt;', 'id')
            -&gt;generate('UTF-8', 'indexed');

        $this-&gt;output
            -&gt;set_content_type('application/json')
            -&gt;set_output($output);
    }

    public function get_custom_data()
    {
        // Example 3: Using getproduction with custom data         $rows = [
            ['id' =&gt; 1, 'name' =&gt; 'John Doe', 'email' =&gt; 'john@example.com'],
            ['id' =&gt; 2, 'name' =&gt; 'Jane Smith', 'email' =&gt; 'jane@example.com']
        ];
        $count = count($rows);

        $this-&gt;datalib-&gt;from('users'); // Set columns for exec_replace         $output = $this-&gt;datalib
            -&gt;add_column('action', '&lt;button onclick="delete($1)"&gt;Delete&lt;/button&gt;', 'id')
            -&gt;getproduction($rows, $count, 'UTF-8', 'keybased');

        $this-&gt;output
            -&gt;set_content_type('application/json')
            -&gt;set_output($output);
    }
}</code></pre>
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
        <pre><code class="language-plaintext">&lt;!DOCTYPE html&gt; &lt;html&gt; &lt;head&gt;     &lt;title&gt;User Management&lt;/title&gt;     &lt;link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"&gt;     &lt;script src="https://code.jquery.com/jquery-3.7.1.min.js"&gt;&lt;/script&gt;     &lt;script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"&gt;&lt;/script&gt; &lt;/head&gt; &lt;body&gt;     &lt;h2&gt;Users List&lt;/h2&gt;     &lt;table id="userTable" class="display" style="width:100%"&gt;         &lt;thead&gt;             &lt;tr&gt;                 &lt;th&gt;ID&lt;/th&gt;                 &lt;th&gt;Name&lt;/th&gt;                 &lt;th&gt;Email&lt;/th&gt;                 &lt;th&gt;Role&lt;/th&gt;                 &lt;th&gt;Created At&lt;/th&gt;                 &lt;th&gt;Action&lt;/th&gt;             &lt;/tr&gt;         &lt;/thead&gt;     &lt;/table&gt; 
    &lt;script&gt;         $(document).ready(function() {
            $('#userTable').DataTable({
                serverSide: true,
                ajax: {
                    url: '&lt;?= base_url('user/get_users') ?&gt;',
                    type: 'POST'                 },
                columns: [
                    { data: 'id' },
                    { data: 'name' },
                    { data: 'email' },
                    { data: 'role_name' },
                    { data: 'created_at' },
                    { data: 'action', orderable: false, searchable: false }
                ],
                order: [[1, 'asc']] // Default sort by name             });
        });
    &lt;/script&gt; &lt;/body&gt; &lt;/html&gt;</code></pre>
        <div>
            &nbsp;
        </div>
    </div>
</div>
<h4 dir="auto">
    Configuration
</h4>
<ol dir="auto">
    <li>
        <strong>Database Config (</strong><span><strong>application/config/database.php</strong></span><strong>)</strong>:
    </li>
</ol>
<div dir="auto">
    <div>
        <pre><code class="language-plaintext">$active_group = 'default';
$db['default'] = array(
    'dsn' =&gt; '',
    'hostname' =&gt; 'localhost',
    'username' =&gt; 'root',
    'password' =&gt; '',
    'database' =&gt; 'your_database',
    'dbdriver' =&gt; 'mysqli',
    'dbprefix' =&gt; '',
    'pconnect' =&gt; FALSE,
    'db_debug' =&gt; TRUE,
    'cache_on' =&gt; FALSE,
    'cachedir' =&gt; '',
    'char_set' =&gt; 'utf8',
    'dbcollat' =&gt; 'utf8_general_ci' );</code></pre>
    </div>
</div>
<ol start="2" dir="auto">
    <li>
        <strong>Autoload Library (</strong><span><strong>application/config/autoload.php</strong></span><strong>)</strong>:
    </li>
</ol>
<div dir="auto">
    <div>
        <pre><code class="language-plaintext">php

$autoload['libraries'] = array('database');</code></pre>
        <div>
            &nbsp;
        </div>
    </div>
</div>
<ol start="3" dir="auto">
    <li>
        <strong>Place </strong><span><strong>Datalib.php</strong></span>:
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
        <pre><code class="language-plaintext">{
    "draw": 1,
    "recordsTotal": 2,
    "recordsFiltered": 2,
    "data": [
        {
            "id": "1",
            "name": "&lt;strong&gt;John Doe&lt;/strong&gt;",
            "email": "john@example.com",
            "status": "active",
            "created_at": "2025-06-18 15:52:00",
            "role_name": "Admin",
            "action": "&lt;a href=\"edit/1\"&gt;Edit&lt;/a&gt;"         },
        {
            "id": "3",
            "name": "&lt;strong&gt;Bob Johnson&lt;/strong&gt;",
            "email": "bob@example.com",
            "status": "active",
            "created_at": "2025-06-18 15:52:00",
            "role_name": "Admin",
            "action": "&lt;a href=\"edit/3\"&gt;Edit&lt;/a&gt;"         }
    ]
}</code></pre>
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
            <code style="font-size:inherit;line-height:inherit;white-space:pre;"><span>log_message(</span></code><span style="color:rgb(80,161,79);"><code style="font-size:inherit;line-height:inherit;white-space:pre;">'debug'</code></span><code style="font-size:inherit;line-height:inherit;white-space:pre;"><span>, </span></code><span style="color:rgb(166,38,164);"><code style="font-size:inherit;line-height:inherit;white-space:pre;">$this</code></span><code style="font-size:inherit;line-height:inherit;white-space:pre;"><span>-&gt;ci-&gt;db-&gt;last_query());</span></code>
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
                        <pre><code class="language-plaintext">PHP
$this-&gt;datalib-&gt;from('users')-&gt;generate('UTF-8', 'keybased');</code></pre>
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
