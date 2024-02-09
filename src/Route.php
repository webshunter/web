<?php
namespace Gugusd999\Web;
use Gugusd999\Web\Text;
use Gugusd999\Web\Files;

class Route {
    private static $route = [];
    private static $middleware = [];
    private static $use = [];
    private static $activeadd = NULL;
    private static $datamidleware = [];

    function __construct($base = "./", $url_base = "") {
        ini_set('display_errors', 0);
        $this->setEnv('SETUP_PATH', $base);
        $this->setEnv('PATH', $url_base);
        $this->setEnv('ASSET', $url_base);
        if(isset($_SERVER["REQUEST_URI"]))
        {
            $this->setEnv('URL', urldecode( explode('?',$_SERVER["REQUEST_URI"])[0] ));
        }
        $this->setEnv('ROOT', dirname($_SERVER['DOCUMENT_ROOT']));
        $this->setEnv('APP', $_SERVER['DOCUMENT_ROOT']);
        $this->setEnv('IP', $this->get_client_ip());
        if(file_exists(SETUP_PATH.'.env')){
            $getenv = parse_ini_file(SETUP_PATH.'.env');
            foreach ($getenv as $key => $value) {
                $this->setEnv($key, $value);
            }
        }
    }

    private function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    private function setEnv($name="", $val = ""){
        if(!defined($name)){
            $_ENV[$name] = $val;
            define($name, $val);
        }
    }  

    // midleware setup
    public function middleware(...$arg){
        if(isset($arg[0])){
            if(is_callable($arg[0])){
                if(!isset(self::$middleware[self::$activeadd])){
                    self::$middleware[self::$activeadd] = [];
                }
                self::$middleware[self::$activeadd][] = $arg[0];
            }else{
                if(!isset(self::$middleware[self::$activeadd])){
                    self::$middleware[self::$activeadd] = [];
                }
                self::$middleware[self::$activeadd][] = $arg[0];
            }
        }
        return $this;
    }

    public function addMidleware(...$arg){
        if(isset($arg[0]) && isset($arg[1])){
            self::$datamidleware[$arg[0]] = $arg[1];
        }
    }

    public function cors($option = null){
        if($option === "all"){
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST');
        }
        return $this;
    }

    public function use(...$arg){
        if(isset($arg[0])){
            if(is_string($arg[0])){
                if(!isset(self::$use[self::$activeadd])){
                    self::$use[self::$activeadd] = [];
                }
                self::$use[self::$activeadd][] = $arg[0];
            }
        }
        return $this;
    }

    public static function session(...$arg){
        if(isset($arg[0]) && $arg[0] == true){
            defined('SESSION') or die();
            if(files::exist(SETUP_PATH.'session53539'.SESSION) === false){
                mkdir(SETUP_PATH.'session53539'.SESSION);
            }
            $filesession = SETUP_PATH.'session53539'.SESSION;
            if(session_status() === ''){
                ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/'.$filesession));
                ini_set('session.gc_probability', 1);
            }

            session_start();
        }
    }

    // add new route
    public static function add(...$argv){
        $newRoute = [];
        if(isset($argv[0])){
            if(substr( $argv[0], 0,1) != '/'){
                $newRoute["url"] = PATH."/".$argv[0];
            }else{
                $newRoute["url"] = PATH.$argv[0];
            }
            self::$activeadd = $newRoute['url'];
            $pathofroute = [];
            $pathofparams = [];
            $newRoute['totpath'] = 0;
            $newRoute['action-type'] = NULL;
            $newRoute['action'] = NULL;
            foreach(explode("/",$newRoute['url']) as $key => $pathRoute){
                if(strpos($pathRoute, "{") !== false && strpos($pathRoute, "}") !== false){
                    $pathofparamsnew = [];
                    $pathofparamsnew['position'] = $key;
                    $pathofparamsnew['nameparams'] = $pathRoute;
                    $pathofparams[] = $pathofparamsnew;
                    $pathofroute[] = $pathRoute;
                    $newRoute['totpath'] +=1;
                }else{
                    $newRoute['totpath'] +=1;
                    $pathofroute[] = $pathRoute;
                }
            }
            $newRoute['routepath'] = $pathofroute;
            $newRoute['params'] = $pathofparams;

            // cek seccond parameters
            if(isset($argv[1])){
                $action = $argv[1];
                if( is_callable($action) ){
                    $newRoute['action-type'] = 'function';
                    $newRoute['action'] = $argv[1];
                }else{
                    if(strpos($argv[1],'@') !== false){
                        $newRoute['action-type'] = 'controller';
                        $newRoute['action'] = $argv[1];
                    }
                }
            }
        }
        self::$route[] = $newRoute;
        return (new self);
    }

    // validation route after call
    private static function validating(...$argv){
        $htmlerror = '<div><img width="250px" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhLS0gVXBsb2FkZWQgdG86IFNWRyBSZXBvLCB3d3cuc3ZncmVwby5jb20sIEdlbmVyYXRvcjogU1ZHIFJlcG8gTWl4ZXIgVG9vbHMgLS0+Cjxzdmcgd2lkdGg9IjgwMHB4IiBoZWlnaHQ9IjgwMHB4IiB2aWV3Qm94PSIwIDAgNjQgNjQiIGRhdGEtbmFtZT0iTGF5ZXIgMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGRlZnM+PHN0eWxlPi5jbHMtMXtmaWxsOiMxOTA5MzM7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZS8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMTgsNi41QTEuNSwxLjUsMCwxLDAsMTYuNSw1LDEuNSwxLjUsMCwwLDAsMTgsNi41Wm0wLTJhLjUuNSwwLDEsMS0uNS41QS41LjUsMCwwLDEsMTgsNC41WiIvPjxjaXJjbGUgY2xhc3M9ImNscy0xIiBjeD0iMjIiIGN5PSI1IiByPSIxIi8+PGNpcmNsZSBjbGFzcz0iY2xzLTEiIGN4PSIxNCIgY3k9IjUiIHI9IjEiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik0zMiwxOC41QTE0LjUsMTQuNSwwLDEsMCw0Ni41LDMzLDE0LjUxLDE0LjUxLDAsMCwwLDMyLDE4LjVabTAsMjdBMTIuNSwxMi41LDAsMSwxLDQ0LjUsMzMsMTIuNTIsMTIuNTIsMCwwLDEsMzIsNDUuNVoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik02Mi4yLDU2LjU0QTMzLjY5LDMzLjY5LDAsMCwwLDUxLjMyLDQ1LjQzYTIsMiwwLDAsMC0yLjI2LjA5TDQ3LjQ5LDQ0QTE4Ljk1LDE4Ljk1LDAsMCwwLDM1LDE0LjI2VjVhNSw1LDAsMCwwLTUtNUg2QTUsNSwwLDAsMCwxLDVWOWExLDEsMCwwLDAtMSwxdjRhMSwxLDAsMCwwLDEsMXYyYTEsMSwwLDAsMC0xLDF2NGExLDEsMCwwLDAsMSwxVjU5YTUsNSwwLDAsMCw1LDVIMzBhNSw1LDAsMCwwLDUtNVY1MS43NGExOSwxOSwwLDAsMCw5LjEtNC4xbDEuNDIsMS40MmEyLDIsMCwwLDAtLjA5LDIuMjZBMzMuNjksMzMuNjksMCwwLDAsNTYuNTQsNjIuMmwuNjMuNjNhNCw0LDAsMCwwLDUuNjYtNS42NlpNNjAuMTUsNTdsLTEuNTYsMS41N0w1Nyw2MC4xNWMtLjcyLS40Ni0xLjQxLS45NS0yLjA4LTEuNDVsMS44OC0xLjg4YS41LjUsMCwwLDAtLjcxLS43MWwtMiwyYy0uNTItLjQyLTEtLjg2LTEuNTItMS4zMWw0LjE4LTQuMTdBMzIuNDUsMzIuNDUsMCwwLDEsNjAuMTUsNTdaTTQ5LDMzQTE3LDE3LDAsMCwxLDMzLjg5LDQ5Ljg5bC0uNDQsMEMzMyw1MCwzMi40OSw1MCwzMiw1MGExNywxNywwLDAsMSwwLTM0Yy40OSwwLDEsMCwxLjQ1LjA3bC40NCwwQTE3LDE3LDAsMCwxLDQ5LDMzWk02LDJIMzBhMywzLDAsMCwxLDMsMi41SDMwYTQuMzQsNC4zNCwwLDAsMC0zLjM1LDEuNjVBMy40NSwzLjQ1LDAsMCwxLDI0LDcuNUgxMkEzLjQ1LDMuNDUsMCwwLDEsOS4zNSw2LjE1LDQuMzQsNC4zNCwwLDAsMCw2LDQuNUgzLjA1QTMsMywwLDAsMSw2LDJaTTMzLDUydjdhMywzLDAsMCwxLTMsM0g2YTMsMywwLDAsMS0zLTNWNS41SDZBMy40NSwzLjQ1LDAsMCwxLDguNjUsNi44NSw0LjM0LDQuMzQsMCwwLDAsMTIsOC41SDI0YTQuMzQsNC4zNCwwLDAsMCwzLjM1LTEuNjVBMy40NSwzLjQ1LDAsMCwxLDMwLDUuNWgzVjE0bC0xLDBhMTksMTksMCwwLDAtMTYuMzcsOS40MUwxMiwyMC44YTMuNDYsMy40NiwwLDEsMC0uNjMuNzhsMy43OSwyLjdhMTguODcsMTguODcsMCwwLDAsMiwyMC40N0wxMi41NSw0OGEyLjUsMi41LDAsMCwwLTEuMDUsMnYyLjUyYTMuNSwzLjUsMCwxLDAsMSwwVjUwYTEuNDksMS40OSwwLDAsMSwuNjMtMS4yMmw0LjYxLTMuM0ExOC45NCwxOC45NCwwLDAsMCwzMiw1MlpNOSwyMS41QTIuNSwyLjUsMCwxLDEsMTEuNSwxOSwyLjUsMi41LDAsMCwxLDksMjEuNVptMywzMkEyLjUsMi41LDAsMSwxLDkuNSw1NiwyLjUsMi41LDAsMCwxLDEyLDUzLjVabTM0Ljg5LTguNzMsMS40NCwxLjQ0LTIuMTIsMi4xMkw0NC44NCw0N0ExOS4yNiwxOS4yNiwwLDAsMCw0Ni44OSw0NC43N1ptLjI0LDUuNDcsMy4xMi0zLjEyYTMyLjEyLDMyLjEyLDAsMCwxLDUuODUsNC43Nkw1MS44OCw1Ni4xQTMxLjczLDMxLjczLDAsMCwxLDQ3LjEzLDUwLjI0Wk02MS40MSw2MS40MWEyLDIsMCwwLDEtMi44MiwwbDIuODItMi44MmEyLDIsMCwwLDEsMCwyLjgyWiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTMzLjIzLDI1YTEuNTUsMS41NSwwLDAsMC0yLjQ2LDBMMjMuNDQsMzUuNjFBMi4xNiwyLjE2LDAsMCwwLDI1LjIxLDM5SDM4Ljc5YTIuMTYsMi4xNiwwLDAsMCwxLjc3LTMuMzlabTUuNywxMS44OWEuMTUuMTUsMCwwLDEtLjE0LjA5SDI1LjIxYS4xNS4xNSwwLDAsMS0uMTQtLjA5LjE2LjE2LDAsMCwxLDAtLjE2bDYuOTItMTAsNi45MiwxMEEuMTYuMTYsMCwwLDEsMzguOTMsMzYuOTFaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMzIsMjkuNWEuNS41LDAsMCwwLS41LjV2M2EuNS41LDAsMCwwLDEsMFYzMEEuNS41LDAsMCwwLDMyLDI5LjVaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNMzIuMTksMzQuNTRhLjUuNSwwLDAsMC0uMzgsMCwuNTMuNTMsMCwwLDAtLjI3LjI3LjQzLjQzLDAsMCwwLDAsLjE5LjQ3LjQ3LDAsMCwwLC4xNS4zNS4zNi4zNiwwLDAsMCwuMTYuMTEuNDcuNDcsMCwwLDAsLjM4LDAsLjM2LjM2LDAsMCwwLC4xNi0uMTEuNDguNDgsMCwwLDAsMC0uN0EuMzYuMzYsMCwwLDAsMzIuMTksMzQuNTRaIi8+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNNDMuNjUsMTMuMzVhLjQ4LjQ4LDAsMCwwLC43LDBsMi0yYS40OS40OSwwLDEsMC0uNy0uN2wtMiwyQS40OC40OCwwLDAsMCw0My42NSwxMy4zNVoiLz48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik00OC43OCwxMy41NWwtMiwxYS41LjUsMCwwLDAsLjIyLjk1LjU0LjU0LDAsMCwwLC4yMi0uMDVsMi0xYS41LjUsMCwwLDAtLjQ0LS45WiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTQyLjUsMTFWOWEuNS41LDAsMCwwLTEsMHYyYS41LjUsMCwwLDAsMSwwWiIvPjwvc3ZnPg==">
            </img></div>';
        try{
            if(isset($argv[0])){
                $parameterone = $argv[0];
                $routeactive = self::$route[$parameterone];
                
                if($routeactive['action-type'] != NULL){
                    if($routeactive['action-type'] == 'function'){
                        $pathurl = explode('/',URL);
                        $params = [];
                        foreach($routeactive['params'] as $getpar){
                            $params[] = $pathurl[$getpar['position']];
                        }
                        $params = (array) $params;

                        if(isset(self::$use[$routeactive['url']])){
                            foreach(self::$use[$routeactive['url']] as $usecall){
                                include_once SETUP_PATH.$usecall;
                            }
                        }

                        if(isset(self::$middleware[$routeactive['url']])){
                            foreach(self::$middleware[$routeactive['url']] as $midlecall){
                                if(is_callable($midlecall)){
                                    $midlecall();
                                }else{
                                    self::$datamidleware[$midlecall]();
                                }
                            }
                        }
                        $routeactive['action'](...$params);
                    }else{
                        $pathurl = explode('/',URL);
                        $params = [];
                        foreach($routeactive['params'] as $getpar){
                            $params[] = $pathurl[$getpar['position']];
                        }
                        $params = (array) $params;
                        if(isset(self::$use[$routeactive['url']])){
                            foreach(self::$use[$routeactive['url']] as $usecall){
                                include_once SETUP_PATH.$usecall;
                            }
                        }

                        $path = explode("@",$routeactive['action']);

                        $pathDir = SETUP_PATH.$path[0];
                        

                        if(file_exists($pathDir.'.php')){

                            include_once $pathDir.'.php';
                            if(isset(self::$middleware[$routeactive['url']])){
                                foreach(self::$middleware[$routeactive['url']] as $midlecall){
                                    if(is_callable($midlecall)){
                                        $midlecall();
                                    }else{
                                        self::$datamidleware[$midlecall]();
                                    }
                                }
                            }
                            $pat = explode('/',$path[0]);
                            $pcount = count($pat) - 1;
                            $nameclass = ucfirst($pat[$pcount]);
                            $namefunc = $path[1];
                            $ripText = " ".$nameclass."::".$namefunc."(...\$params); ";
                            eval($ripText);
                        }else{
                            $route = self::$route;
                            foreach($route as $y => $founderror){
                                if($founderror['url'] == '/404'){
                                    (new self)->validating($y);
                                    die();
                                }
                            }
                            echo "/404 <br>page not found";
                        }

                    }
                }
            }
        } catch (\Throwable $e) {
            // Tangani kesalahan di sini
            echo "<div style=\"font-family: calibri;font-size: 18px; margin: 40px 50px;padding: 30px; box-shadow:0 0 10px #aaa;\">";
            echo $htmlerror.'<h1 style="margin:0;">Error</h1>Terjadi kesalahan: ' . 
            str_replace(",","<br>",  $e->getMessage() );
            echo "</div>";
        }
        die();
    }

    // starting route
    public static function call(){
        $route = self::$route;
        // cari data yang sesuai dengan URL
        // cek url;
        foreach($route as $key => $routedata){
            if($routedata['url'] == URL){
                (new self)->validating($key);
                die();
            }
        }

        $pathurl = explode('/',URL);

        $countpathurl = count($pathurl);
        
        // cek url base on count path of url and filter it
        $pathofroot = array_filter($route, function(...$arg) use ($countpathurl, $pathurl) {
            if($arg[0]['totpath'] == $countpathurl){
                return $arg;
            }
        }, ARRAY_FILTER_USE_BOTH  );
        
        
        $capable = array_map(function(...$arg) use($pathurl){
            $data = $arg[0];
            $data['compability'] = 0;
            foreach($data['routepath'] as $kk => $root){
                if($pathurl[$kk] == $root){
                    $data['compability'] += 1;
                }
            }
            return $data['compability'];
        }, $pathofroot);
        
        
        $rr = [-1,-2];

        foreach($capable as $n){
            $rr[] = $n;
        }

        $capable = max(...$rr);

        
        $get = array_map(function(...$arg) use($pathurl){
            $data = $arg[0];
            $data['compability'] = 0;
            foreach($data['routepath'] as $kk => $root){
                if($pathurl[$kk] == $root){
                    $data['compability'] += 1;
                }
            }
            return $data;
        }, $pathofroot);
        

        $getdata = array_map(function(...$arg){
            return $arg[0];
        },array_filter($get, function(...$arg) use ($capable) {
            if($arg[0]['compability'] == $capable && $capable > 1){
                return $arg[0];
            }
        }));

        function serachParamTrueOrFalse(...$arg){
            function isparams(...$ssa){
                $res = false;
                foreach($ssa[1] as $s){
                    if($s['position'] == $ssa[0]){
                        $res = true;
                    }
                }
                return $res;
            }
            $result = true;
            foreach($arg[2] as $key => $prm){
                if(isparams($key, $arg[1]) != true){
                    if($arg[0][$key] != $prm){
                        $result = false;
                    };
                }
            }
            return $result;
        }

        if(count($getdata) > 0){
            foreach($getdata as $key => $calldata){
                $url = $calldata['url'];
                if(serachParamTrueOrFalse($calldata['routepath'], $calldata["params"], $pathurl) == true){
                    foreach($route as $numroute => $routes){
                        if($routes['url'] == $url){
                            (new self)->validating($numroute);
                            die();
                        }
                    }
                }else{
                    foreach($route as $y => $founderror){
                        if($founderror['url'] == PATH . '/404'){
                            (new self)->validating($y);
                            die();
                        }
                    }
                    echo "/404 <br>page not found";
                };
                die();
            };
        }else{
            foreach($route as $y => $founderror){
                if($founderror['url'] == PATH . '/404'){
                    (new self)->validating($y);
                    die();
                }
            }
            echo "/404 <br>page not found";
        }

    }
}