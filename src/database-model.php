<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


/** The class which is used for easy interacting with a database */
class DatabaseModel {
	protected mysqli $db;
	protected array $props;
	protected string $current_table = '';

	function __construct() {
		$this->props = [
			"host" => $_ENV["DB_HOST"],
			"user" => $_ENV["DB_USER"],
			"password" => $_ENV["DB_PASSWORD"],
			"name" => $_ENV["DB_NAME"],
			"port" => $_ENV["DB_PORT"]
		];

		$this->db = new mysqli(...array_values($this->props));

		if ($this->db->connect_error) {
			die("Connection failed: " . $this->db->connect_error);
		}
	}

	/**
	 * The function sets the current table to be used.
	 * @param string $table A string that represents the name of the table that you want to use.
	 */
	public function use_table(string $table): void {
		try {
			$this->current_table = $table;
		} catch (Exception $e) {
			echo "Caught exception: ",  $e->getMessage(), "\n";
		}
	}

	/**
	 * The query function executes a SQL query and returns the result set or a boolean value indicating
	 * success or failure.
	 * 
	 * @param string $sql_query A string that represents the SQL query that you want to execute. 
	 * It can be any valid SQL statement, such as SELECT, INSERT, UPDATE, DELETE, etc.
	 * 
	 * @return mysqli_result | bool either a mysqli_result object or a boolean value.
	 */
	public function query(string $sql_query): mysqli_result | bool {
		try {
			if ($this->current_table == '') {
				throw new Exception("The table is not specified! Please open some database table to work with");
			}

			return $this->db->query($sql_query);
		} catch (Exception $e) {
			echo "Caught exception: ",  $e->getMessage(), "\n";
		}
	}

	/**
	 * The function retrieves specified fields from the current table in a database and returns the
	 * results as an associative array.
	 * 
	 * @param string[] ...$fields List of field which you want to select from the table.
	 * 
	 * @return array An array of rows fetched from the database table. Each row is represented as an
	 * associative array, where the keys are the field names specified in the function arguments and the
	 * values are the corresponding values from the database table.
	 */
	public function get(string ...$fields): array {
		$result = $this->query("SELECT " . join(", ", $fields) . " FROM {$this->props['name']}.{$this->current_table}");
		return $result->fetch_all(MYSQLI_ASSOC);
	}

	/**
	 * The function finds records in a specified database table based on a given condition and returns the
	 * selected fields as an array.
	 * 
	 * @param string $condition The condition parameter is a string that represents the condition to be
	 * used in the WHERE clause of the SQL query. It specifies the criteria that the rows must meet in
	 * order to be included in the result set.
	 * 
	 * @return array An array of rows from the database table that match the given condition.
	 */
	public function find(string $condition): array {
		$result = $this->query("SELECT * FROM {$this->props['name']}.{$this->current_table} WHERE {$condition}");
		return $result->fetch_all(MYSQLI_ASSOC);
	}

	/**
	 * The function creates a new record in a specified database table with the given data.
	 * 
	 * @param array $record An associative array that contains the data to be inserted into 
	 * the database table. The keys of the array represent the column names, and the values
	 * represent the corresponding values to be inserted into those columns.
	 */
	public function create(array $record): void {
		$columns = join(", ", array_keys($record));
		$values = join(", ", array_map(fn ($item) => "'{$this->db->escape_string($item)}'", array_values($record)));

		$this->query("INSERT INTO {$this->props['name']}.{$this->current_table} ({$columns}) VALUES ({$values});");
	}

	/**
	 * The function updates a record in a database table using the provided ID and record data.
	 * 
	 * @param int $id The unique identifier of the record that needs to be updated in the database.
	 * @param array $record An array that contains the updated values for the record that 
	 * needs to be updated in the database. The keys of the array represent the column names,
	 * and the values represent the new values for those columns.
	 * @return bool A boolean value that indicates if the record has been updated or not 
	 */
	public function update(int $id, array $record): bool {
		if (empty($this->find("id = {$id}"))) {

			$values = join(", ", array_map(
				fn ($k, $v) => "{$k} = '{$this->db->escape_string($v)}'",
				array_keys($record),
				array_values($record)
			));

			$this->query("UPDATE {$this->props['name']}.{$this->current_table} SET {$values} WHERE id = {$id};");

			return true;
		} else {
			return false;
		}
	}

	/**
	 * The function deletes a record from a database table based on the provided ID.
	 * 
	 * @param int $id The unique identifier of the record that needs to be deleted from the database table.
	 * @return bool A boolean value that indicates if the record has been deleted or not 
	 */
	public function delete(int $id): bool {
		if (!empty($this->find("id = {$id}"))) {
			$this->query("DELETE FROM {$this->props['name']}.{$this->current_table} WHERE id = {$id};");
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Get all records from a database table.
	 * @return array All the data from the database table.
	 */
	public function all(): array {
		return $this->get("*");
	}

	/**
	 * Get the number of rows in a database table that satisfy a given condition.
	 * 
	 * @param string $condition A string that represents the condition to be used in the SQL query. 
	 * It is optional and can be left empty. If provided, it will be appended to the SQL query using the "WHERE" keyword.
	 * 
	 * @return int The count of rows from the specified table in the database that match the given condition.
	 */
	public function count(string $condition = ""): int {
		$result = $this->query("SELECT COUNT(*) FROM {$this->props['name']}.{$this->current_table} " . ($condition != "" ? "WHERE {$condition}" : ""));
		return $result->fetch_row()[0];
	}
}
