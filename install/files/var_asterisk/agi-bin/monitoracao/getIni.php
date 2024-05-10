<?php
function get_ini($file)
{

    // if cannot open file, return false
    if (!is_file($file))
        return false;

    $ini = file($file);

    // to hold the categories, and within them the entries
    $cats = array();

    foreach ($ini as $i) {
        if (@preg_match('/\[(.+)\]/', $i, $matches)) {
			$last = $matches[1];
			} elseif (@preg_match('/(.+)=(.+)/', $i, $matches)) {
				if(!preg_match('/^\;(.+)/',$i)) {
					preg_match('/(.+);($|.+)/',$matches[2],$valor);
					if(count($valor) == 0){
						$cats[$last][trim($matches[1])] = trim($matches[2]);
					}else{
						$cats[$last][trim($matches[1])] = trim($valor[1]);
					}
				}
        }
    }

    return $cats;

}

//$lista = get_parse_ini("/home/extend/comunix.conf",true);
$lista = get_ini("/home/extend/comunix.conf");
//print_r($lista);
//$uras = explode(",",$lista['trata_call']['ivr_ip']);
//print_r($uras);

switch(count($argv)){
	case 2:
		if(array_key_exists($argv[1],$lista)){
			$novo["$argv[1]"] = $lista["$argv[1]"];
			print_r($novo);
			print_r(json_encode($novo));
			echo "\n";
		}
		break;
	case 3:
		if(array_key_exists($argv[1],$lista)){
			if(array_key_exists($argv[2],$lista["$argv[1]"])){
				//print_r($argv);
				echo "$argv[1]=>$argv[2]=".$lista["$argv[1]"]["$argv[2]"]."\n";
				echo $lista["$argv[1]"]["$argv[2]"];
				$novo = explode(",",$lista["$argv[1]"]["$argv[2]"]);
				$novo2["$argv[1]"]["$argv[2]"] = $novo; 
				print_r($novo2);
				print_r(json_encode($novo2));
				echo "\n";
			}
		}
		break;
	case 4:
		if("$argv[3]" == "valor"){
			if(array_key_exists($argv[1],$lista)){
				if(array_key_exists($argv[2],$lista["$argv[1]"])){
					//print_r($argv);
					//echo "$argv[1]=>$argv[2]=".$lista["$argv[1]"]["$argv[2]"]."\n";
					//echo $lista["$argv[1]"]["$argv[2]"];
					$novo = explode(",",$lista["$argv[1]"]["$argv[2]"]);
					$novo2["$argv[1]"]["$argv[2]"] = $novo; 
					//print_r($novo2);
					//print_r(json_encode($novo2));
					//print_r($novo);
					echo $lista["$argv[1]"]["$argv[2]"]."\n";
					//print_r(strtolower(str_replace(" ","-",$lista["$argv[1]"]["$argv[2]"]."\n")));
				}
			}
			break;
		}
	default:
		print_r(json_encode($lista));
		echo "\n".count($argv);
}


/*
$string = "
[trata_call]
;share0_ip=192.168.1.24 ; server 0 where is running (trata_call)
share0_ip=192.168.1.24 ; server 0 where is running (trata_call)";

//$regex = "/(.+)=(.+)/";
$regex = "/^\;(.+)/";

preg_match($regex,$string,$rst);
print_r($rst);


$string2 = 'share0_ip=192.168.1.24 ; server 0 where is running (trata_call)';
$regex = "/(.+);(.+)/";

preg_match($regex,$string2,$rst);
$linha = trim($rst[1]);
$regex = "/(.+)=(.+)/";
preg_match($regex,$linha,$rst);

$nome = trim($rst[1]);
$valor = trim($rst[2]);

echo "$nome\n$valor \n";

 */

//$string2 = 'share0_ip=192.168.1.24 ; server 0 where is running (trata_call)';
//$string2 = 'share0_ip=192.168.1.24;';
//$regex = "/(.+);($|.+)/";
//preg_match($regex,$string2,$rst);
//print_r($rst);

?>
