<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

	function optionsCatalogue (Request $request, Response $response, $args) {
	    
	    // Evite que le front demande une confirmation à chaque modification
	    $response = $response->withHeader("Access-Control-Max-Age", 600);
	    
	    return addHeaders ($response);
	}

	function hello(Request $request, Response $response, $args) {
		$array = [];
		$array ["nom"] = $args ['name'];
		$response->getBody()->write(json_encode ($array));
		return $response;
	}
	
	/*
	function  getSearchCalatogue (Request $request, Response $response, $args) {
	    $flux = '[{"titre":"linux","ref":"001","prix":"20"},{"titre":"java","ref":"002","prix":"21"},{"titre":"windows","ref":"003","prix":"22"},{"titre":"angular","ref":"004","prix":"23"},{"titre":"unix","ref":"005","prix":"25"},{"titre":"javascript","ref":"006","prix":"19"},{"titre":"html","ref":"007","prix":"15"},{"titre":"css","ref":"008","prix":"10"}]';
		
	   $response->getBody()->write($flux);
	   
	    return addHeaders ($response);
	}
	*/
	function  getSearchCalatogue (Request $request, Response $response, $args) {
	    $filtre = $args['filtre'];
	    $flux = '[{"titre":"linux","ref":"001","prix":"20"},{"titre":"java","ref":"002","prix":"21"},{"titre":"windows","ref":"003","prix":"22"},{"titre":"angular","ref":"004","prix":"23"},{"titre":"unix","ref":"005","prix":"25"},{"titre":"javascript","ref":"006","prix":"19"},{"titre":"html","ref":"007","prix":"15"},{"titre":"css","ref":"008","prix":"10"}]';
	   
	    if ($filtre) {
	      $data = json_decode($flux, true); 
	    	
		$res = array_filter($data, function($obj) use ($filtre)
		{ 
		    return strpos($obj["titre"], $filtre) !== false;
		});
		$response->getBody()->write(json_encode(array_values($res)));
	    } else {
		 $response->getBody()->write($flux);
	    }

	    return addHeaders ($response);
	}

	// API Nécessitant un Jwt valide
	function getCatalogue (Request $request, Response $response, $args) {
	    $flux = '[{"titre":"linux","ref":"001","prix":"20"},{"titre":"java","ref":"002","prix":"21"},{"titre":"windows","ref":"003","prix":"22"},{"titre":"angular","ref":"004","prix":"23"},{"titre":"unix","ref":"005","prix":"25"},{"titre":"javascript","ref":"006","prix":"19"},{"titre":"html","ref":"007","prix":"15"},{"titre":"css","ref":"008","prix":"10"}]';
	    
	    $response->getBody()->write($flux);
	    
	    return addHeaders ($response);
	}

	function optionsUtilisateur (Request $request, Response $response, $args) {
	    
	    // Evite que le front demande une confirmation à chaque modification
	    $response = $response->withHeader("Access-Control-Max-Age", 600);
	    
	    return addHeaders ($response);
	}

	// API Nécessitant un Jwt valide
	function getUtilisateur (Request $request, Response $response, $args) {
	    
	    $payload = getJWTToken($request);
	    $login  = $payload->userid;
	    
		$flux = '{"nom":"martin","prenom":"jean"}';
	    
	    $response->getBody()->write($flux);
	    
	    return addHeaders ($response);
	}

	// APi d'authentification générant un JWT
	function postLogin(Request $request, Response $response, $args) {
		$body = $request->getParsedBody();
		
		if (isset($body['login']) && isset($body['password'])) {
			$login = $body['login'];
			$password = $body['password'];
	
			if ($login === 'hugo' && $password === 'gohu') {
				$flux = '{"nom":"TREGNAGO","prenom":"Hugo (ça rime)"}';
				$response = createJwT($response);
				$response->getBody()->write($flux);
				return addHeaders($response);
			} else {
				$responseBody = ['error' => 'Identifiants differents de hugo gohu'];
				return $response->withStatus(401)
								->withHeader('Content-Type', 'application/json')
								->getBody()
								->write(json_encode($responseBody));
			}
		} else {
			$responseBody = ['error' => 'Champs "login" et "password" requis'];
			return $response->withStatus(400)
							->withHeader('Content-Type', 'application/json')
							->getBody()
							->write(json_encode($responseBody));
		}
	}
	

