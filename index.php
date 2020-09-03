<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start(); 
header("Cache-control: private"); 
include("common_includes/includes.php");

//loginchecker();
//temp end
//Print_R($_POST);

//test call for debug var dump.
//var_error_log();

	if (isset($_GET['op'] ) ){
		$op = ($_GET["op"]);

		if($op==="logout"){
			$_SESSION['pri'] = 0;
		}

		if($op==="checklogin"){
			loginchecker();
		}else{

			if (class_exists($op . "_c")){
				$constr = "new " . $op . "_c();";
				eval($constr);
			}else{
				//trigger_error("Unable to load path: $op ", E_USER_WARNING);
			}
		}
	}else{
		
		if(isset($_POST['op'] ) ){
			$op = ($_POST["op"]);

			if($op==="logout"){
				
				$_SESSION['pri'] = 0;
			}

			if($op==="checklogin"){
				loginchecker();
			}else{
				
				if (class_exists($op . "_c")){
					$constr = "new " . $op . "_c();";
					eval($constr);
				}else{
					//trigger_error("Unable to load path: $op ", E_USER_WARNING);
					$pg=new basic_c;
				}
			}
		
		}else{


			//if a 'default' page is required, enter it here with a statement similar to:
			//
			$pg=new basic_c;
		
		}


	}

die;



	function routemapping(){
			error_log("HELLO AND WELCOME\n");




		 
		$object = new MyObject();
		var_error_log( $object );


			$base_url = getCurrentUri_ForRoutes();

			$routes = array();
			$routes = explode('/', $base_url);
			foreach($routes as $route)
			{
				if(trim($route) != '')
					array_push($routes, $route);
			}
			/*
			Now, $routes will contain all the routes. $routes[0] will correspond to first route. For e.g. in above example $routes[0] is search, $routes[1] is book and $routes[2] is fitzgerald
			*/
		 

				if($routes[1] == 'itemone'){
					var_dump("ITEM ONE");
				}else{
					echo "---" . $routes[1] . "---";
				}

	}

	function getCurrentUri_ForRoutes(){
		$basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
		$uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
		if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
		$uri = '/' . trim($uri, '/');
		return $uri;
	}







?>
