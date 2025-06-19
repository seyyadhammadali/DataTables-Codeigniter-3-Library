<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
/**
  * Ignited Datatables
  *
  * This is a wrapper class/library based on the native Datatables server-side implementation by Allan Jardine
  * found at http://datatables.net/examples/data_sources/server_side.html for CodeIgniter
  *
  * @package    CodeIgniter
  * @subpackage libraries
  * @category   library
  * @version    0.7
  * @author     Hammad Ali <hali35275@gmail.com>
  * @link       https://github.com/seyyadhammadali/DataTables-Codeigniter-3-Library
  */
class Datatable
{
  
    /**
     * Global container variables for chained argument results
     */
    protected $ci;
    protected $table;
    protected $distinct;
    protected $group_by;
    protected $select = array();
    protected $joins = array();
    protected $columns = array();
    protected $column_aliases = array();
    protected $where = array();
    protected $filter = array();
    protected $add_columns = array();
    protected $edit_columns = array();
    protected $unset_columns = array();

    /**
     * Copies an instance of CI
     */
    public function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * Set the database if multiple are defined in config/database.php
     */
    public function set_database($db_name)
    {
        $db_data = $this->ci->load->database($db_name, TRUE);
        $this->ci->db = $db_data;
    }

    /**
     * Generates the SELECT portion of the query
     * If no columns are specified, retrieves all columns from the table
     * Rejects aliases containing brackets, parentheses, or 'use'
     */
    public function select($columns = '', $backtick_protect = FALSE)
    {
        // Clear existing select to allow override
        $this->select = array();
        $this->columns = array();
        $this->column_aliases = array();

        if (empty($columns) && !empty($this->table)) {
            $columns = implode(',', $this->ci->db->list_fields($this->table));
        }

        if (!empty($columns)) {
            foreach ($this->explode(',', $columns) as $val) {
                $val = trim($val);
                // Parse alias (e.g., 'status AS user_status')
                if (preg_match('/(.+)\s+AS\s+(\w+)/i', $val, $matches)) {
                    // print_r($val.'=====');
                    $original = trim($matches[1]);
                    $alias = trim($matches[2]);
                    // print_r($matches);
                    // Validate alias: reject if contains [], (), or 'use'
                    if (preg_match('/[\[\]\(\)]|\buse\b|\bcase\b/i', $original)) {
                        log_message('debug', "Alias '$alias' rejected due to SQL keywords or invalid characters.");
                        $this->columns[] = $alias;
                        $this->select[$alias] = $alias;
                        $this->column_aliases[$alias] = "";
                    } else {
                        $this->columns[] = $alias;
                        $this->select[$alias] = $original;
                        $this->column_aliases[$alias] = $original;
                    }
                } else {
                   
                    $column = trim($val);
                    // print_r($column);
                    if (!preg_match('/[\[\]\(\)]|\buse\b|\bcase\b/i', $column)) {
                        $this->columns[] = $column;
                        $this->select[$column] = $column;
                        $this->column_aliases[$column] = $column;
                    }
                }
               
            }
            
            // die();
            $this->ci->db->select($columns, $backtick_protect);
        }

        return $this;
    }

    /**
     * Generates the DISTINCT portion of the query
     */
    public function distinct($column)
    {
        $this->distinct = $column;
        $this->ci->db->distinct($column);
        return $this;
    }

    /**
     * Generates the GROUP_BY portion of the query
     */
    public function group_by($column)
    {
        $this->group_by = $column;
        $this->ci->db->group_by($column);
        return $this;
    }

    /**
     * Generates the ORDER_BY portion of the query
     */
    public function order_by($column, $sort)
    {
        $this->order_by = $column;
        $this->ci->db->order_by($column, $sort);
        return $this;
    }

    /**
     * Generates the FROM portion of the query
     * Automatically sets SELECT clause with all columns if not already set
     */
    public function from($table)
    {
        $this->table = $table;
        $this->ci->db->from($table);

        // Automatically set SELECT if not already defined
        if (empty($this->select)) {
            $columns = implode(',', $this->ci->db->list_fields($table));
            foreach ($this->explode(',', $columns) as $val) {
                $column = trim($val);
                $this->columns[] = $column;
                $this->select[$column] = $column;
                $this->column_aliases[$column] = $column;
            }
            $this->ci->db->select($columns, FALSE);
        }

        return $this;
    }

    /**
     * Generates the JOIN portion of the query
     */
    public function join($table, $fk, $type = '')
    {
        $this->joins[] = array($table, $fk, $type);
        $this->ci->db->join($table, $fk, $type);
        return $this;
    }

    /**
     * Generates the WHERE portion of the query
     */
    public function where($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
        $this->where[] = array($key_condition, $val, $backtick_protect);
        $this->ci->db->where($key_condition, $val, $backtick_protect);
        return $this;
    }

    /**
     * Generates the OR WHERE portion of the query
     */
    public function or_where($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
        $this->where[] = array($key_condition, $val, $backtick_protect);
        $this->ci->db->or_where($key_condition, $val, $backtick_protect);
        return $this;
    }

    /**
     * Generates the LIKE portion of the query
     */
    public function like($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
        $this->where[] = array($key_condition, $val, $backtick_protect);
        $this->ci->db->like($key_condition, $val, $backtick_protect);
        return $this;
    }

    /**
     * Generates the WHERE portion for static filters
     */
    public function filter($key_condition, $val = NULL, $backtick_protect = TRUE)
    {
        $this->filter[] = array($key_condition, $val, $backtick_protect);
        return $this;
    }

    /**
     * Sets additional column variables for adding custom columns
     */
    public function add_column($column, $content, $match_replacement = NULL)
    {
        $this->add_columns[$column] = array('content' => $content, 'replacement' => $this->explode(',', $match_replacement));
        return $this;
    }

    /**
     * Sets additional column variables for editing columns
     * @param string $column Column name to edit
     * @param string $content Content or PHP expression
     * @param string $match_replacement Columns for placeholder replacement
     * @param bool $execute_php Whether to evaluate content as PHP expression
     */
    public function edit_column($column, $content, $match_replacement, $execute_php = FALSE)
    {
        $this->edit_columns[$column][] = array(
            'content' => $content,
            'replacement' => $this->explode(',', $match_replacement),
            'execute_php' => $execute_php
        );
        return $this;
    }

    /**
     * Unsets a column
     */
    public function unset_column($column)
    {
        $this->unset_columns[] = $column;
        return $this;
    }

    /**
     * Builds the query and generates output
     * @param string $charset Character set for output
     * @param string $output_mode 'indexed' for numeric arrays, 'keybased' for associative arrays
     */
    public function generate($charset = 'UTF-8', $output_mode = 'indexed')
    {
        $this->get_paging();
        $this->get_ordering();
        $this->get_filtering();
        return $this->produce_output($charset, $output_mode);
    }

    /**
     * Generates the LIMIT portion of the query
     */
    protected function get_paging()
    {
        $start = $this->ci->input->post('start', TRUE) ?: 0;
        $length = $this->ci->input->post('length', TRUE) ?: 1000;
        if ($length != -1) {
            $this->ci->db->limit($length, $start);
        }
    }

    /**
     * Generates the ORDER BY portion of the query
     */
    protected function get_ordering()
    {
        $order = $this->ci->input->post('order', TRUE);
        $columns_post = $this->ci->input->post('columns', TRUE);

        // Build column mapping, excluding integer-like 'data' values
        $mColArray = array_values(array_filter(array_map(fn($col) =>
            isset($col['data']) && !ctype_digit((string)$col['data']) ? $col['data'] : null,
            $columns_post ?: []
        )));

        // Fallbacks
        $mColArray = $mColArray ?: $this->columns;
        $columns = array_values(array_diff($this->columns, $this->unset_columns));
        $mColArray = array_values(array_diff($mColArray, $this->unset_columns));
        $columns = $columns ?: $mColArray;

        // Apply ordering
        if (!empty($order)) {
            foreach ($order as $ord) {
                $colIdx = (int) $ord['column'];
                $dir = in_array(strtolower($ord['dir']), ['asc', 'desc']) ? $ord['dir'] : 'asc';

                if (
                    isset($mColArray[$colIdx]) &&
                    !empty($columns_post[$colIdx]['orderable']) &&
                    $columns_post[$colIdx]['orderable'] === 'true' &&
                    in_array($mColArray[$colIdx], $columns)
                ) {
                    // Use original column name for ordering
                    $column_name = isset($this->column_aliases[$mColArray[$colIdx]]) ? $this->column_aliases[$mColArray[$colIdx]] : $mColArray[$colIdx];
                    $this->ci->db->order_by($column_name, $dir);
                }
            }
        }
    }

    /**
     * Generates the filtering portion of the query
     */
    protected function get_filtering()
    {
        $columns_post = $this->ci->input->post('columns', TRUE);

        // Build column mapping, excluding integer-like 'data' values
        $mColArray = array_values(array_filter(array_map(fn($col) =>
            isset($col['data']) && !ctype_digit((string)$col['data']) ? $col['data'] : null,
            $columns_post ?: []
        )));

        // Fallbacks
        $mColArray = $mColArray ?: $this->columns;
        $columns = array_values(array_diff($this->columns, $this->unset_columns));
        $mColArray = array_values(array_diff($mColArray, $this->unset_columns));
        $columns = $columns ?: $mColArray;

        // Global search
        $search = $this->ci->input->post('search', TRUE);
        $searchValue = $search['value'] ?? '';
        $isRegex = isset($search['regex']) && $search['regex'] === 'true';

        if ($searchValue !== '') {
            $sWhere = '';
            foreach ($mColArray as $i => $colName) {
                if (
                    isset($columns_post[$i]['searchable']) &&
                    $columns_post[$i]['searchable'] === 'true' &&
                    in_array($colName, $columns)
                ) {
                    // Use original column name for filtering
                    $column_name = isset($this->column_aliases[$colName]) ? $this->column_aliases[$colName] : $colName;
                    if(empty($column_name)){
                        continue;
                    }
                    if ($isRegex) {
                        $sWhere .= $column_name . " REGEXP '" . $this->ci->db->escape_str($searchValue) . "' OR ";
                    } else {
                        $sWhere .= $column_name . " LIKE '%" . $this->ci->db->escape_like_str($searchValue) . "%' OR ";
                    }
                }
            }
            $sWhere = rtrim($sWhere, ' OR ');
            if ($sWhere !== '') {
                $this->ci->db->where('(' . $sWhere . ')');
            }
        }

        // Individual column filtering
        foreach ($mColArray as $i => $colName) {
            if (
                isset($columns_post[$i]['search']['value']) &&
                $columns_post[$i]['search']['value'] !== '' &&
                in_array($colName, $columns)
            ) {
                // Use original column name for filtering
                $column_name = isset($this->column_aliases[$colName]) ? $this->column_aliases[$colName] : $colName;
                $searchStr = $columns_post[$i]['search']['value'];
                $isColRegex = isset($columns_post[$i]['search']['regex']) && $columns_post[$i]['search']['regex'] === 'true';
                $searchParts = explode(',', $searchStr);

                foreach ($searchParts as $val) {
                    $val = trim($val);
                    if (preg_match("/(<=|>=|=|<|>)(\s*)(.+)/i", $val, $matches)) {
                        $this->ci->db->where($column_name . ' ' . $matches[1], $matches[3]);
                    } else if ($isColRegex) {
                        $this->ci->db->where($column_name . " REGEXP '" . $this->ci->db->escape_str($val) . "'");
                    } else {
                        $this->ci->db->like($column_name, $val, 'both', TRUE);
                    }
                }
            }
        }

        // Apply static filters
        foreach ($this->filter as $val) {
            $this->ci->db->where($val[0], $val[1], $val[2]);
        }
    }

    /**
     * Executes the query and returns the result
     */
    protected function get_display_result()
    {
        return $this->ci->db->get();
    }

    /**
     * Produces JSON output
     * @param string $charset Character set for output
     * @param string $output_mode 'indexed' for numeric arrays, 'keybased' for associative arrays
     */
    protected function produce_output($charset, $output_mode = 'indexed')
    {
        $aaData = array();
        $rResult = $this->get_display_result();
        $iTotal = $this->get_total_results();
        $iFilteredTotal = $this->get_total_results(TRUE);

        foreach ($rResult->result_array() as $row_key => $row_val) {
            $aaData[$row_key] = ($output_mode === 'indexed') ? array_values($row_val) : $row_val;

            foreach ($this->add_columns as $key => $val) {
                if ($output_mode === 'keybased') {
                    $aaData[$row_key][$key] = $this->exec_replace($val, $aaData[$row_key]);
                } else {
                    $aaData[$row_key][] = $this->exec_replace($val, $aaData[$row_key]);
                }
            }

            foreach ($this->edit_columns as $modkey => $modval) {
                foreach ($modval as $val) {
                    $index = array_search($modkey, $this->columns);
                    $aaData[$row_key][($output_mode === 'keybased') ? $modkey : $index] = $this->exec_replace($val, $aaData[$row_key]);
                }
            }

            if ($output_mode === 'indexed') {
                $aaData[$row_key] = array_values(array_diff_key(
                    $aaData[$row_key],
                    array_intersect($this->columns, $this->unset_columns)
                ));
            } else {
                $aaData[$row_key] = array_diff_key(
                    $aaData[$row_key],
                    array_intersect($this->columns, $this->unset_columns)
                );
            }
        }

        $sColumns = array_diff($this->columns, $this->unset_columns);
        $sColumns = array_merge_recursive($sColumns, array_keys($this->add_columns));

        $sOutput = array(
            'draw' => intval($this->ci->input->post('draw', TRUE)),
            'recordsTotal' => $iTotal,
            'recordsFiltered' => $iFilteredTotal,
            'data' => $aaData
        );

        return strtolower($charset) === 'utf-8' ? json_encode($sOutput) : $this->jsonify($sOutput);
    }

    /**
     * Gets total result count
     */
    protected function get_total_results($filtering = FALSE)
    {
        if ($filtering) {
            $this->get_filtering();
        }

        foreach ($this->joins as $val) {
            $this->ci->db->join($val[0], $val[1], $val[2]);
        }

        foreach ($this->where as $val) {
            $this->ci->db->where($val[0], $val[1], $val[2]);
        }

        return $this->ci->db->count_all_results($this->table);
    }

    /**
     * Executes replacements for custom columns, with optional PHP expression evaluation
     */
    protected function exec_replace($custom_val, $row_data)
    {
        $content = $custom_val['content'];
        $execute_php = isset($custom_val['execute_php']) && $custom_val['execute_php'] === TRUE;

        // Handle ternary expressions if execute_php is true
        if (
            $execute_php &&
            preg_match('/^\$1\s*(==|===|!=|!==)\s*(\S+)\s*\?\s*"([^"]*)"\s*:\s*"([^"]*)"$/', $content, $matches) &&
            isset($custom_val['replacement'][0]) && in_array($custom_val['replacement'][0], $this->columns)
        ) {
            $operator = $matches[1];
            $compare_value = $matches[2];
            $true_value = $matches[3];
            $false_value = $matches[4];
            $value = $row_data[array_search($custom_val['replacement'][0], $this->columns)];

            // Evaluate the comparison
            switch ($operator) {
                case '==':
                    return $value == $compare_value ? $true_value : $false_value;
                case '===':
                    return $value === $compare_value ? $true_value : $false_value;
                case '!=':
                    return $value != $compare_value ? $true_value : $false_value;
                case '!==':
                    return $value !== $compare_value ? $true_value : $false_value;
                default:
                    return $content; // Fallback if operator is unrecognized
            }
        }

        // Standard string replacement logic
        $replace_string = '';
        if (isset($custom_val['replacement']) && is_array($custom_val['replacement'])) {
            foreach ($custom_val['replacement'] as $key => $val) {
                $sval = preg_replace("/(?<!\w)([\'\"])(.*)\\1(?!\w)/i", '$2', trim($val));

                if (preg_match('/(\w+)\((.*)\)/i', $val, $matches) && function_exists($matches[1])) {
                    $func = $matches[1];
                    $args = preg_split("/[\s,]*\\\"([^\\\"]+)\\\"[\s,]*|" . "[\s,]*'([^']+)'[\s,]*|" . "[,]+/", $matches[2], 0, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

                    foreach ($args as $args_key => $args_val) {
                        $args_val = preg_replace("/(?<!\w)([\'\"])(.*)\\1(?!\w)/i", '$2', trim($args_val));
                        $args[$args_key] = (in_array($args_val, $this->columns)) ? $row_data[array_search($args_val, $this->columns)] : $args_val;
                    }

                    $replace_string = call_user_func_array($func, $args);
                } elseif (in_array($sval, $this->columns)) {
                    $replace_string = $row_data[array_search($sval, $this->columns)];
                } else {
                    $replace_string = $sval;
                }

                $content = str_ireplace('$' . ($key + 1), $replace_string, $content);
            }
        }

        return $content;
    }

    /**
     * Balances open/close characters
     */
    protected function balanceChars($str, $open, $close)
    {
        $openCount = substr_count($str, $open);
        $closeCount = substr_count($str, $close);
        return $openCount - $closeCount;
    }

    /**
     * Explodes string while respecting delimiters
     */
    protected function explode($delimiter, $str, $open = '(', $close = ')')
    {
        $retval = array();
        $hold = array();
        $balance = 0;
        $parts = explode($delimiter, (string)$str);

        foreach ($parts as $part) {
            $hold[] = $part;
            $balance += $this->balanceChars($part, $open, $close);

            if ($balance <= 0) {
                $retval[] = implode($delimiter, $hold);
                $hold = array();
                $balance = 0;
            }
        }

        if (count($hold) > 0) {
            $retval[] = implode($delimiter, $hold);
        }

        return array_filter($retval);
    }

    /**
     * JSON encoding workaround for non-UTF-8 charsets
     */
    protected function jsonify($result = FALSE)
    {
        if (is_null($result)) {
            return 'null';
        }

        if ($result === FALSE) {
            return 'false';
        }

        if ($result === TRUE) {
            return 'true';
        }

        if (is_scalar($result)) {
            if (is_float($result)) {
                return floatval(str_replace(',', '.', strval($result)));
            }

            if (is_string($result)) {
                static $jsonReplaces = array(array('\\', '/', '\n', '\t', '\r', '\b', '\f', '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
                return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $result) . '"';
            }

            return $result;
        }

        $isList = TRUE;

        for ($i = 0, reset($result); $i < count($result); $i++, next($result)) {
            if (key($result) !== $i) {
                $isList = FALSE;
                break;
            }
        }

        $json = array();

        if ($isList) {
            foreach ($result as $value) {
                $json[] = $this->jsonify($value);
            }

            return '[' . join(',', $json) . ']';
        }

        foreach ($result as $key => $value) {
            $json[] = $this->jsonify($key) . ':' . $this->jsonify($value);
        }

        return '{' . join(',', $json) . '}';
    }

    /**
     * Produces output for provided data
     * @param array $rows Input data rows
     * @param int $count Total record count
     * @param string|null $charset Character set for output
     * @param string $output_mode 'indexed' for numeric arrays, 'keybased' for associative arrays
     */
    public function getproduction($rows, $count, $charset = NULL, $output_mode = 'indexed')
    {
        $aaData = array();
        $iTotal = $count;
        $iFilteredTotal = $count;

        foreach ($rows as $row_key => $row_val) {
            $aaData[$row_key] = ($output_mode === 'indexed') ? array_values($row_val) : $row_val;

            foreach ($this->add_columns as $field => $val) {
                if ($output_mode === 'keybased') {
                    $aaData[$row_key][$field] = $this->exec_replace($val, $aaData[$row_key]);
                } else {
                    $aaData[$row_key][] = $this->exec_replace($val, $aaData[$row_key]);
                }
            }

            foreach ($this->edit_columns as $modkey => $modval) {
                foreach ($modval as $val) {
                    $index = array_search($modkey, $this->columns);
                    $aaData[$row_key][($output_mode === 'keybased') ? $modkey : $index] = $this->exec_replace($val, $aaData[$row_key]);
                }
            }

            if ($output_mode === 'indexed') {
                $aaData[$row_key] = array_values(array_diff_key(
                    $aaData[$row_key],
                    array_intersect($this->columns, $this->unset_columns)
                ));
            } else {
                $aaData[$row_key] = array_diff_key(
                    $aaData[$row_key],
                    array_intersect($this->columns, $this->unset_columns)
                );
            }
        }

        $sColumns = array_diff($this->columns, $this->unset_columns);
        $sColumns = array_merge_recursive($sColumns, array_keys($this->add_columns));

        $sOutput = array(
            'draw' => intval($this->ci->input->post('draw', TRUE)),
            'recordsTotal' => $iTotal,
            'recordsFiltered' => $iFilteredTotal,
            'data' => $aaData
        );

        return strtolower($charset) === 'utf-8' ? json_encode($sOutput) : $this->jsonify($sOutput);
    }
}
