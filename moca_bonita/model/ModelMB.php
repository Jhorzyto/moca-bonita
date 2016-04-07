<?php
namespace MocaBonita\model;
/**
* Base model for new created models based on the wordpress $wpdb object that treats connections with database.
*
* @author Rômulo Batista
* @category WordPress
* @package moca_bonita\wp
* @copyright Copyright (c) 2015-2016 Núcleo de Tecnologia da Informação - NTI, Universidade Estadual do Maranhão - UEMA
*/

use \Exception;

abstract class ModelMB {
	/**
    * Object of a wordpress database class
    *
    * @var string
    */
	protected $wpdb;

	/**
    * The name of the table to be used
    *
    * @var string
    */
	protected $table;

	/**
    * Class constructor
    *
    */
	public function __construct(){
		global $wpdb;
		$this->wpdb = $wpdb;
	}

	/**
    * Retrieve generic results from database
    *
    * @param string $query The SQL query
    * @return An object or a array (associative or not)
    */
	public function getResults($query){
		$result = $this->wpdb->get_results($query);

		if($result === false || $this->wpdb->last_error)
			throw new Exception( $this->wpdb->last_error );

		return $result;
	}

	/**
    * Returns a single variable from the database
    *
    * @param string $query The SQL query
    * @return An object or a array (associative or not)
    */
	public function getVar($query){
		$result = $this->wpdb->get_var( $query );

		if($result === false || $this->wpdb->last_error)
			throw new Exception( $this->wpdb->last_error );

		return $result;
	}

	/**
    * Retrieve generic results from database
    *
    * @param string $query The SQL query
    * @param string One of four pre-defined constants
    * @return An object or a array (associative or not)
    */
	public function getResultsT($query, $outputType = OBJECT){
		$result = $this->wpdb->get_results($query, $outputType);

		if($result === false || $this->wpdb->last_error)
			throw new Exception( $this->wpdb->last_error );

		return $result;
	}

	/**
    * Retrieve an entire row from a query
    *
    * @param string $query The SQL query
    * @param string $query One of three pre-defined constants
    * @param rowOffSet $rowOffSet The desired row
    * @return An object or a array (associative or not)
    */
	public function getRow($query, $outputType = OBJECT, $rowOffSet = 0){
		$result = $this->wpdb->get_row($query, $outputType, $rowOffSet);

		if($result === false || $this->wpdb->last_error)
			throw new Exception( $this->wpdb->last_error );

		return $result;
	}

	/**
    * Retrieve an entire columns from a query
    *
    * @param string $query The SQL query
    * @param string $colOffSet What information you wish to retrieve
    * @return An object or a array (associative or not)
    */
	public function getColumn($query, $colOffSet = 0){
		$result = $this->wpdb->get_column($query, $colOffSet);

		if($result === false || $this->wpdb->last_error)
			throw new Exception( $this->wpdb->last_error );

		return $result;
	}

	/**
    * Insert a row in a table
    *
    * @param string $query The SQL query
    * @param array $data Data to insert
    * @param array|string $format An array of formats to be mapped to each of the values in data
    * @return The generated ID
    */
	public function insert($table, $data, $format){

		$result = $this->wpdb->insert($table, $data, $format);

		if($result === false || $this->wpdb->last_error)
			throw new Exception( $this->wpdb->last_error );

		return $this->wpdb->insert_id;
	}

	/**
    * Replace a row in a table if it exists or insert a new row in a table if the row did not already exist
    *
    * @param string $table The name of the table to replace data in
    * @param array $data Data to replace
    * @param array $where A named array of WHERE clauses
    * @param array|string $format An array of formats to be mapped to each of the values in data
    * @param array|string $whereFormat An array of formats to be mapped to each of the values in $where
    * @return The generated ID
    */
	public function replace($table, $data, $where, $format = null, $whereFormat = null){
		$result = $this->wpdb->update($table, $data, $where, $format, $whereFormat);

		if($result === false || $this->wpdb->last_error)
			throw new Exception( $this->wpdb->last_error );

		return $result;
	}

	/**
    * Delete row
    *
    * @param string $table The name of the table to replace data in
    * @param array $where A named array of WHERE clauses
    * @param array $whereFormat An array of formats to be mapped to each of the values in $where
    * @return The generated ID
    */
	public function remove($table, $where, $whereFormat = null){
		$result = $this->wpdb->delete($table, $where, $whereFormat);

		if($result === false || $this->wpdb->last_error)
			throw new Exception( $this->wpdb->last_error );

		return $result;
	}

	/**
    * Returns the result of a query
    *
    * @param string $table The name of the table to replace data in
    * @return An integer value indicating the number of rows affected/selected for SELECT, INSERT, DELETE, UPDATE, etc. For CREATE, ALTER, TRUNCATE and DROP SQL statementsreturns TRUE on success or FALSE when error
    */
	public function query($query){
		$this->wpdb->query($query);
	}

	/**
    * Begin a transaction
    *
    */
	public function beginTransaction(){
		return $this->wpdb->query("START TRANSACTION;");
	}

	/**
    * Commit a transaction
    *
    */
	public function commit(){
		return $this->wpdb->query("COMMIT;");
	}

	/**
    * Rollback if a transaction goes wrong
    *
    */
	public function rollBack(){
		return $this->wpdb->query("ROLLBACK;");
	}

	/**
	 * Prepare query with data
	 *
	 */
	public function prepare($query, array $arguments){
		return $this->wpdb->prepare($query, $arguments);
	}

	/**
	 * Escape like query
	 *
	 */
	public function escLike($query){
		return $this->wpdb->esc_like($query);
	}

	public function getAll(array $columns = [], array $where = [])
	{
		try {
            $_columns = empty($columns) ? '*' : implode(', ', $columns);
            $_where = empty($where) ? '' : 'WHERE '.implode(' = ', $where);
			$query = "SELECT {$_columns} FROM {$this->table} {$_where}";
			return $this->getResultsT($query, ARRAY_A);
		} catch (\Exception $e) {
			throw $e;
		}
	}
}
