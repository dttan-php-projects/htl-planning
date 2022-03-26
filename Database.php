<?php

	function connection($db=null) 
	{
		if ($db==null) $db = "au_avery_htl";
		$host = "147.121.56.227";
		$username = "planning";
		$password = "PELS&Auto@{2020}";
		$conn = mysqli_connect($host,$username,$password,$db) or die("Mất kết nối đến SERVER $host Vui lòng gọi bộ phận liên quan hệ thống!!!");
		$conn->query("SET NAMES 'utf8'");

		return $conn;
	}

	function MiQuery($Query,$conn)
	{
		$result = $conn->query($Query);
		if ($result) {
			$results = mysqli_fetch_all($result,MYSQLI_ASSOC);	
		} else {
			$results = array();
		}
		
		if(count($results) == 1)
		{
			$Value = "";
			$i = 0;
			foreach($results[0] as $K => $V){
				$Value = $V;
				$i++;
			}
			if($i == 1) return $Value;
		}

		mysqli_close($conn);

		return $results;
	}

	function MiNonQuery($Query,$conn)
	{
		$result = $conn->query($Query);
	}

	function toQuery($Query,$conn) {

		$results = array();
		$result = mysqli_query($conn, $Query);
		if (!$result) return array();

		$rowCount = mysqli_num_rows($result);
		if ($rowCount > 0 ) {
			if ($rowCount == 1 ) {
				$results = mysqli_fetch_array($$result, MYSQLI_ASSOC);		
			} else {
				$results = mysqli_fetch_all($$result, MYSQLI_ASSOC);		
			}
		}
		
		mysqli_close($conn);
		return $results;
	}


	function toQueryAll($conn, $query)
	{
		$result = mysqli_query($conn, $query);
		if (!$result ) return array();
		$result = mysqli_fetch_all($result, MYSQLI_ASSOC);  

		mysqli_close($conn);
		return $result;
	
	}

	// Sử dụng cho đăng nhập
	function MiNonQuery2($Query,$dbMi = null) {
		if($dbMi == null) $dbMi = connection();
		$dbMi->query("SET NAMES 'utf8'");
		if(!$dbMi->query($Query)){
			echo $dbMi->error;
			mysqli_close($dbMi);
			return false;
		} else {
			mysqli_close($dbMi);
			return true;
		}
	}
