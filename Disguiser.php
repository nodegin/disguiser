<?php

class Disguiser {
	
	private static $enabled = true;
	private static $minify = true;
	private static $prefix = 'X_';
	private static $mapFile = './disguiser.json';
	
	public static function strip($input)
	{
		// strip comments, new lines, spaces, tabs
		$comments = [
			"`^([\t\s]+)`ism"						=> null,
			"`^\/\*(.+?)\*\/`ism" 					=> null,
			"`([\n\A;]+)\/\*(.+?)\*\/`ism" 			=> "$1",
			"`([\n\A;\s]+)//(.+?)[\n\r]`ism" 		=> "$1\n",
			"`(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+`ism"	=> "\n"
		];
		return str_replace(['	','	','	',"\r\n","\r","\n"], null, preg_replace(array_keys($comments), $comments, $input));
	}
	
	public static function disguise($input)
	{
		if(!self::$enabled) return $input;
		
		if(self::$minify) $input = self::strip($input);
		
		/* do not use self::$renameMap due to this function is dynamic */
		/* static var will keep previous value in memory */
		$renameMap = [
			'classes' => [],
			'names' => [],
		];
		
		if(preg_match_all('/'.self::$prefix.'([a-zA-Z][a-zA-Z0-9_-]+)/m',$input,$classes))
		{
			foreach($classes[1] as $class)
			{
				$class = trim($class);
				$renameMap['classes'][] = $class;
				$class = explode('-', $class); // Explode if delimiter exists
				if(count($class) > 1) $renameMap['names'] = array_merge($renameMap['names'], $class);
				else $renameMap['names'][] = $class[0];
			}
			
			$renameMap['classes'] = array_values(array_unique($renameMap['classes']));
			$renameMap['names'] = array_values(array_unique($renameMap['names']));
			
			/* This part must inside matches */
			if(file_exists(self::$mapFile))
			{
				$existMap = json_decode(file_get_contents(self::$mapFile), true);
				$existClasses = count($existMap['classes']);
				$existNames = count($existMap['names']);
				if($existClasses > 0)
				{
					$rangedClasses = range($existClasses, $existClasses + count($renameMap['classes']) - 1);
					$rangedNames = range($existNames, $existNames + count($renameMap['names']) - 1);
					$combinedClasses = array_combine($rangedClasses, $renameMap['classes']);
					$combinedNames = array_combine($rangedNames, $renameMap['names']);
					$renameMap['classes'] = array_values(array_unique(array_merge($existMap['classes'], $combinedClasses)));
					$renameMap['names'] = array_values(array_unique(array_merge($existMap['names'], $combinedNames)));
					file_put_contents(self::$mapFile, json_encode($renameMap, JSON_FORCE_OBJECT));
				}
			}
			else file_put_contents(self::$mapFile, json_encode($renameMap, JSON_FORCE_OBJECT));
		}
		
		/* Above code whatever use any serialization function is no matter for storing the array, */
		/* Below must use `json_encode` and `json_decode` function for replacing classes array */
		/* You are safe to do anything with the array after writing to disk */
		
		/* Reverse the array:
		  for example, we have [abc, abcxy, xyz]
		  without reverse will produce the following:
		  1. replace abc first
		  2. abcxy will become "a"xy where `abc` translate to `a` after basify in $renameMap
		  with reverse will produce the following:
		  1. reverse into [xyz, abcxy, abc]
		  2. replace xyz, then abcxy, abcxy will become `b` after basify in $renameMap
		  3. abc will become `c` after basify
		*/
		arsort($renameMap['classes']);
		
		$newClassesArray = json_encode($renameMap['classes']);
		foreach($renameMap['names'] as $key => $name)
		{
			do
			{
				$newClassesArray = preg_replace_callback('/("|-)('.$name.'+)("|-)/', function($matches) use ($key){
					return $matches[1].self::basify($key).$matches[3];
				}, $newClassesArray, -1, $count);
			}while($count > 0);
		}
		array_walk($renameMap['classes'], function(&$item){ $item = self::$prefix.$item; });
		
		return str_replace($renameMap['classes'], json_decode($newClassesArray, true), $input);
	}
	
	private static function basify($num,$b = 52)
	{
		$base = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$r = $num % $b ;
		$res = $base[$r];
		$q = floor($num / $b);
		while($q)
		{
			$r = $q % $b;
			$q = floor($q / $b);
			$res = $base[$r].$res;
		}
		return $res;
	}

}