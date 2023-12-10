<?php
	class DBHandler {
		public function executeSelectQuery($con, $sql) {
			$result = mysqli_query($con, $sql);

			if (!$result) {
				return $result;
			}

			$rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
			mysqli_free_result($result);

			return $rows;
		}

		public function executeQuery($con, $sql) {
			// prepare statement
			$stmt = mysqli_prepare($con, $sql);

			// quit if preparation not successful
			if (!$stmt) {
				return false;
			}
			
			// get the params other than the $con and $sql 
			$params = array_slice(func_get_args(), 2);

			// get param types for bind
			$paramTypes = '';
			for ($i = 0; $i < count($params); $i++) {
				$paramType = gettype($params[$i]);
				// TODO: add other types as they are needed
				switch ($paramType) {
					case 'double':
						$paramTypes .= 'd';
						break;
					default:
						$paramTypes .= 's'; 
						break;
				}
			}

			// bind the params with the types 
			$params = array_merge([$stmt, $paramTypes], $params);

			$bindParams = [];
			foreach ($params as $key => &$value) {
				$bindParams[$key] = &$value;
			}
			call_user_func_array('mysqli_stmt_bind_param', $bindParams);

			// execute statemens 
			$result = mysqli_stmt_execute($stmt);

			// close statement 
			mysqli_stmt_close($stmt);

			return $result;
		}
	}
?>
