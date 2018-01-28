<?php

//PRICE COMPARISON........................................................PRICE COMPARISON
    function comparePrice($list) {

        $result_0 = getPrice($list[0]->DeviceName);
        $result_1 = getPrice($list[1]->DeviceName);
        
        if ($result_0 <> "N/A" && $result_1 <> "N/A") {
            $output = [showResult($result_1 - $result_0), true];
        } else $output = [0,false];
        
        return [$output,[$result_0,$result_1]];

    }
        

    function getPrice($product) {
        //the URL to be scraped
        $baseURL = "https://www.amazon.co.uk/s/ref=nb_sb_noss_2?url=node%3D356496011&rh=n%3A560798%2Cn%3A1340509031%2Cn%3A5362060031%2Cn%3A356496011%2Ck%3A";

        //accessing the relevant product in the URL
        $input = str_replace(" ","+",$product);
        $html = file_get_contents($baseURL.$input);
        
        if ($html <> false) {
            //look for the string that has a pound sign next to it           
            if (preg_match('|£(.*)<|',$html,$match_pound)) {
                //extract only the figure from the above search
                if (preg_match_all('^[0-9]{1,3}(?:,?[0-9]{3})*(?:\.[0-9]{2})^', $match_pound[1], $match_figures) <> 0) {
                    return $match_figures[0][0];
                };
            };
        };
        return("N/A");
    };  

//CAMERA COMPARISON.....................................................CAMERA COMPARISON
    function compareCamera($list) {

        $total = 0;
        $test = false;
        $primary = nullControl($list,"primary_");
        
        if ($primary[2]) {
            $total += cameraScore($primary[0],$primary[1]);
            $test = true;
        };
                
        $secondary = nullControl($list,"secondary");
        
        if ($secondary[2]) {
            $total += cameraScore($secondary[0],$secondary[1]);
            $test = true;
        };
                
        $video = nullControl($list,"video");
        
        if ($video[2]) {
            $total += videoScore($video[0],$video[1]);    
            $test = true;
        };
                
        for ($i=0; $i < 2; $i++) {
            $string[$i] = "Primary camera: {$primary[$i]}; Secondary camera: {$secondary[$i]}; Video: {$video[$i]}";
        };
        
        if ($test) {
            $output = [showResult($total), true];
        } else {
            $output = [0,false];
        }
                
        return [$output,$string];
    }
    
    function cameraScore ($phone0,$phone1) {
        if (($phone0 <> "No") && ($phone1 <> "No")) {
            $isMatched_head_0 = preg_match('|.*?MP|',$phone0,$match_head_0);
            $isMatched_head_1 = preg_match('|.*?MP|',$phone1,$match_head_1);
            if ($isMatched_head_0 == 0 || $isMatched_head_1 == 0) {
                return 0;
            };
            $first4_0=substr($match_head_0[0],0,4);
            $first4_1=substr($match_head_1[0],0,4);
            if ((($first4_0 == "Dual") && ($first4_1 == "Dual")) || (($first4_0 <> "Dual") && ($first4_1 <> "Dual"))) {
                $isMatched_MP_0 = preg_match('|[0-9]{1,2}(?:\.[0-9])?|',$match_head_0[0],$match_MP_0);
                $isMatched_MP_1 = preg_match('|[0-9]{1,2}(?:\.[0-9])?|',$match_head_1[0],$match_MP_1);
                if ($isMatched_MP_0 == 0 || $isMatched_MP_1 ==0) {
                    return 0;
                };
                if ($match_MP_0 > $match_MP_1) {
                    return 1;
                } else if ($match_MP_0 < $match_MP_1) {                    
                    return -1;
                } else return 0;
            } else if (($first4_0 == "Dual") && ($first4_1 <> "Dual")) {
                return 1;
            } else return -1;
        } else if (($phone0 == "No") && ($phone1 <> "No")) {
            return -1;
        } else if (($phone0 <> "No") && ($phone1 == "No")) {
            return 1;
        } else return 0;
    }
    
    function videoScore ($phone0,$phone1) {
        if (($phone0 <> "No") && ($phone1 <> "No")) {
            //get what is before p (e.g. 720 for 720p@30fps)
            $isMatched_left_0 = preg_match('|.+?(?=p)|',$phone0,$match_left_0);
            $isMatched_left_1 = preg_match('|.+?(?=p)|',$phone1,$match_left_1);

            //get what is between @ and fps (e.g. 30 for 720p@30fps)
            $isMatched_right_0 = preg_match('|\@(.*?(?=fps))|',$phone0,$match_right_0);
            $isMatched_right_1 = preg_match('|\@(.*?(?=fps))|',$phone1,$match_right_1);

            //test if we have data everywhere
            if (($isMatched_left_0 == 0) || ($isMatched_left_1 == 0) || ($isMatched_right_1 == 0) || ($isMatched_right_1 == 0)) {
                return 0;
            };

            $score_video = 0;

            subTotalComparison($match_left_0[0], $match_left_1[0], $score_video);
            subTotalComparison($match_right_0[1],$match_right_1[1],$score_video);

            if ($score_video > 0) {
                return 1;
            } else if ($score_video < 0) {
                return -1;
            } else return 0;
            
        } else if (($phone0 == "No") && ($phone1 <> "No")) {
            return -1;
        } else if (($phone0 <> "No") && ($phone1 == "No")) {
            return 1;
        } else return 0;        
    }
    
//PERFORMANCE COMPARISON.....................................................PERFORMANCE COMPARISON    
    
    function comparePerformance($list) {
        $total = 0;
        $test = false;
        $cpu = nullControl($list,"cpu");
        
        if ($cpu[2]) {
            subTotalComparison(getTotalCPU($cpu[0]), getTotalCPU($cpu[1]),$total);
            $test = true;
        };
        
        $internal = nullControl($list,"internal");
        
        $input = [getRAM($internal[0]) , getRAM($internal[1])];
        
        if ($internal[2]) {
            subTotalComparison($input[0][0],$input[1][0],$total);
            $test = true;
        };
        
        for ($i=0; $i < 2; $i++) {
            $ex = "N/A";
            if (!empty($input[$i][1])) {
                $ex = $input[$i][1]."B";
            };
            $string[$i] = "CPU: {$cpu[$i]}; RAM: {$ex}";
        };

        if ($test) {
            $output = [showResult($total), true];
        } else {
            $output = [0, false];
        };
        
        return [$output,$string];
    }
    
    function getTotalCPU ($cpu) {
        // first split the string by core
        $str_copy = $cpu;
        
        $cores=[];
        
        $sub_core = strpos($str_copy,"core");
        if ($sub_core == FALSE) {
            array_push($cores, [1,$str_copy]);
        } else {
            while (true) {
                
                // get the value of the core (dual, quad or octo)
                $core_numbers_str = strtolower(substr($str_copy,$sub_core-5,4));
                switch ($core_numbers_str) {
                    case "dual":
                        $core_numbers_val = 2;
                        break;
                    case "quad":
                        $core_numbers_val = 4;
                        break;
                    case "octa":
                        $core_numbers_val = 8;
                        break;
                    default:
                        return FALSE;
                };

                //take out the core word from string
                $str_copy = substr($str_copy,$sub_core+5);
                
                //look for the next core word
                $sub_core = strpos($str_copy,"core");
                if ($sub_core == FALSE) {
                    array_push($cores, [$core_numbers_val,$str_copy]);
                    break;
                } else {
                    array_push($cores,[$core_numbers_val,substr($str_copy,0,$sub_core-5)]);
                };  
            };
        };
        $total = 0;
        foreach ($cores as $pair) {
            $total += getCorePerformance($pair);
        };
        return $total;
    };
    
    //gets total Hz for each core
    function getCorePerformance ($pair) {
        // see if we find 4x2.1 GHz format
        $isMatched_1 = preg_match_all("|[1-9]x[1-9].(?:(?!Hz).)*|",$pair[1],$matches_1);
        if ($isMatched_1) {
            //if yes, we ignore $pair[0] and look through each to get the total Hz
            $subtotal = 0;
            foreach ($matches_1[0] as $core) {
                $multiple = floatval($core[0]);
                $hertz = getHertz(substr($core,2));
                $subtotal += $multiple * $hertz;
            };
            return $subtotal;
        } else {
            //if not, we factor in $pair[0]
            $isMatched_2 = preg_match("|[1-9].(?:(?!Hz).)*|",$pair[1],$matches_2); 
            if (!$isMatched_2) return false;
            $hertz = getHertz($matches_2[0]);
            return $pair[0] * $hertz;
        };
    }

    //gets total Hz from each string input
    function getHertz ($core_string) {
        $hertz = floatval(substr($core_string,0,3));
        $magnitude_str = substr($core_string,-1,1);
        if ($magnitude_str == "G") {
            return ($hertz*1000);
        } else if ($magnitude_str == "M") {
            return ($hertz);
        } else return 0;
    }
        
    function getRAM($str) {
        $isMatched = preg_match("|(?<=[ ,\,])[0-9]{1,3} (?:(?!B RAM).)|",$str,$matches);
        if ($isMatched) {
            $length = strlen($matches[0]);
            $ram = floatval(substr($matches[0],0,($length-2)));
            $matches_end_letter = $matches[0][$length-1];
    
            switch ($matches_end_letter) {
                case "G":
                    return [1000*$ram,$matches[0]];
                    break;
                case "M":
                    return [$ram,$matches[0]];
                    break;
                default:
                    return 0;
            };

        } else return 0;
    }
           
//AGE COMPARISON.................................................AGE COMPARISON
    
    function compareAge($list) {
        
        $final = 0;
        $test = false;
        $age = nullControl($list,"status");
        if ($age[2]) {
            $age_1 = getAge($age[0]);
            $age_2 = getAge($age[1]);

            if (($age_1 <> false) && ($age_2 <> false)) {
                $test = true;
                foreach ($age_1 as $key => $value) {
                    if (($age_1[$key] == NULL) || ($age_2[$key] == NULL)) {
                        break;
                    } else if (($age_1[$key] - $age_2[$key]) <> 0) {
                        $final = showResult($age_1[$key] - $age_2[$key]);
                        break;
                    };
                };
            };
        };
        
        if ($test) {
            $output = [$final, true];
        } else {
            $output = [0, false];
        }
        
        return [$output,$age];
           
    }
    
    function getAge($str) {
                
        if ($str == "Discontinued") {
            $discontinued = 1;
        } else {
            $discontinued = -1;
        }
        
        $year = NULL;
        $month = NULL;
        $day = NULL;
        
        $isMatched = preg_match("|[0-9]{4}.*|",$str,$subString);
        
        if ($isMatched) {
            $year = intval(substr($subString[0],0,4));
            if (strlen($subString[0])>4) {
                $subString = substr($subString[0],5);
                $position = strpos($subString," ");
                if ($position) {
                    $monthString = substr($subString,0,strpos-1);
                    $isMatched = preg_match("|[0-9]{1,2}|",$subString,$matches);
                    if ($isMatched) {
                        $day = intval($matches[0]);
                    };    
                } else {
                    $monthString = $subString;
                };
                $date = date_parse($monthString);
                $month = $date['month'];
            };
                        
        };
        
        return [$discontinued, $year,$month,$day];

    }
    
//BATTERY COMPARISON..............................................BATTERY COMPARISON

    function compareBattery($list) {
        
        $total = 0;
        
        $standby = nullControl($list,"stand_by");
        
        $test = false;
        
        if ($standby[2]) {
            subTotalComparison(batteryLife($standby[0]),batteryLife($standby[1]),$total);
            $test = true;
        };
        
        $talktime = nullControl($list,"talk_time");
        
        if ($talktime[2]) {
            subTotalComparison(batteryLife($talktime[0]),batteryLife($talktime[1]),$total);
            $test = true;
        };
        
        for ($i=0; $i < 2; $i++) {
            $string[$i] = "Standby time: {$standby[$i]}; Talk time: {$talktime[$i]}";
        };
        
        if ($test) {
            $output = [showResult($total), true];
        } else {
            $output = [0, false];
        };
        
        return [$output,$string];
    };
    
    function batteryLife($string) {
        return standRegexMatch($string,"|[0-9]{1,4}(?= h)|");
    }
    
//SIZE AND WEIGHT COMPARISON....................................SIZE AND WEIGHT COMPARISON
    
    function compareSizeWeight($list) {

        $total = 0;
        $test = false;
        
        $dimensions = nullControl($list,"dimensions");
        if ($dimensions[2]) {
            subTotalComparison(getDimensions($dimensions[1]), getDimensions($dimensions[0]), $total);
            $test = true;
        };
                
        $weight = nullControl($list,"weight");
        if ($weight[2]) {
            subTotalComparison(getWeight($weight[1]), getWeight($weight[0]), $total);            
            $test = true;
        };      
        
        for ($i=0; $i < 2; $i++) {
            $string[$i] = "Dimensions: {$dimensions[$i]}; Weight: {$weight[$i]}";
        };    
        
        if ($test) {
            $output = [showResult($total), true];
        } else {
            $output = [0, false];
        }
        
        return [$output,$string];
        
    }
    
    function getDimensions ($string) {
        $isMatched = preg_match_all("|[0-9]{1,3}(?:\.[0-9]{1,2})?|",$string,$matches);
        if (!$isMatched) {
            return 0;
        };
        if (sizeof($matches[0]) >= 3) {
            $subTotal = 1;
            for ($i = 0; $i < 3; $i++) {
                $subTotal = $subTotal * floatval($matches[0][$i]);
            };
            return $subTotal;
        } else {
            return 0;
        };
    }
    
    function getWeight ($string) {
        return standRegexMatch($string,"|.*(?= g)|");
    }
    
//RESOLUTION COMPARISON.........................................RESOLUTION COMPARISON
    
    function compareResolution($list) {
        
        $total = 0;
        $output = [0, false];
        
        $resolution = nullControl($list,"resolution");
        if ($resolution[2]) {
            subTotalComparison(getPixels($resolution[0]), getPixels($resolution[1]), $total);
            subTotalComparison(getPixelDensity($resolution[0]), getPixelDensity($resolution[1]), $total);
            $output = [showResult($total), true];
        };  
             
        return [$output,[$resolution[0],$resolution[1]]];
    }
    
    function getPixels($string) {
        $isMatched_0 = preg_match("|[0-9]{1,4} x [0-9]{1,4}(?= pixels)|",$string, $matches_0);
        if ($isMatched_0) {
            $isMatched_1 = preg_match("|[0-9]{1,4}(?= x)|",$matches_0[0],$matches_1);
            $isMatched_2 = preg_match("|(?<=x )[0-9]{1,4}|",$matches_0[0],$matches_2);
            if (($isMatched_1) && ($isMatched_2)) {
                return intval($matches_1[0]) * intval($matches_2[0]);
            } else return 0;
        } else return 0;
    }
   
    function getPixelDensity($string) {
        return standRegexMatch($string,"|[0-9]{1,4}(?= ppi)|");
    };
    
//STORAGE COMPARISON.................................STORAGE COMOPARISON
    
    function compareStorage($list) {
            
        $internal = nullControl($list, "internal");
        $total = 0;
        
        $test = false;
        if ($internal[2]) {
            subTotalComparison(getInternalCap($internal[0]),getInternalCap($internal[1]),$total);
            $test = true;
        }
                
        $cardslot = nullControl($list,"card_slot");
        if ($cardslot[2]) {
            subTotalComparison(getCardCap($cardslot[0]),getCardCap($cardslot[1]),$total);
            $test = true;
        }
        
        for ($i=0; $i < 2; $i++) {
            $internal[$i] = preg_replace("/(, )(.*)[0-9]{1,3} .(B RAM)/", "", $internal[$i]);
            $string[$i] = "Internal storage: {$internal[$i]}; Card slot: {$cardslot[$i]}";
        };
        
        if ($test) {
            $output = [showResult($total), true];
        } else {
            $output = [0, false];
        }
        
        return [$output,$string];
    }
        
    function getInternalCap ($string) {
        return standRegexMatch($string,"|[0-9]{1,4}(?= GB,)|");
    };
    
    function getCardCap ($string) {
        if ($string == "No") return -1;        
        return standRegexMatch($string,"|[0-9]{1,4}(?= GB)|");
    }
    
//BRAND COMPARISON...........................................BRAND COMPARISON
    
    function compareBrand($list,$brand) {
        $total = 0;
        if (strtolower($list[0]->Brand) == strtolower($brand)) $total += 1;
        if (strtolower($list[1]->Brand) == strtolower($brand)) $total -= 1;
        return [[showResult($total),true],[$list[0]->Brand,$list[1]->Brand]];
    }
    
//INTERNET COMPARISON.....................................INTERNET COMPARISON
    
    function compareInternet($list) {
        
        $speed = nullControl($list,"speed");
        $total = 0;
        $output = [0, false];
        if ($speed[2]) {
            $pair_1 = getHighSpeed($speed[0]);
            $pair_2 = getHighSpeed($speed[1]);
            
            if (($pair_1 <> false) && ($pair_2 <> false)) {
                subTotalComparison($pair_1[0],$pair_2[0],$total);
                subTotalComparison($pair_1[1],$pair_2[1],$total);
                $output = [showResult($total), true];
            }
        }
        
        return [$output, [$speed[0],$speed[1]]];
    }
    
    function getHighSpeed($string) {
        $isMatched = preg_match_all("|(?<= )[0-9].*?/.*?(?= Mbps)|",$string,$matches);
        if ($isMatched) {
            $result = [];
            foreach ($matches[0] as $updown) {
                $slash_pos = strpos($updown,"/");
                $up = floatval(substr($updown,0,$slash_pos));
                $down = floatval(substr($updown,$slash_pos+1));
                if (empty($result)) {
                    $result = [$down,$up];
                    continue;
                };
                if (($result[0] < $down) && ($result[1] < $up)) {
                    $result = [$down,$up];
                }
            }
            return $result;
        } else return 0;
    }
    
//COMMON FUNCTIONS...................................COMMON FUNCTIONS
    function subTotalComparison ($left,$right,&$subtotal) {
        if (($left <> false) && ($right <> false)) {
            if ($left>$right) {
                $subtotal += 1;
            } else if ($right>$left) {
                $subtotal -= 1;
            };
        } else return false;
    }        
        
    function showResult ($score) {
        if ($score > 0) {
            $result = 1;
        } else if ($score < 0) {
            $result = -1;
        } else {
            $result = 0;
        };
        return $result;
    };
    
    function standRegexMatch ($string,$pattern) {
        $isMatched = preg_match($pattern,$string,$matches);
        if ($isMatched) {
            return intval($matches[0]);
        } else return false;
    };
    
    function nullControl($arr,$key) {
        $test = true;
        for ($i=0; $i<2; $i++) {
            if (empty($arr[$i]->$key)) {
                $test = false;
                $output[$i] = "N/A";
            } else $output[$i] = $arr[$i]->$key;
        }
        
        //3rd output indicates whether you can compare
        $output[2] = $test;
        return $output;
    };
  
    
?>