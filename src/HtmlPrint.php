<?php
namespace Gugusd999\Web;
class HtmlPrint {

    private $layout;
    private $paper;
    private $title;
    private $section = [];

    function __construct($paper ="A4", $lay = "p"){
        $this->paper = $paper;
        $this->layout = $lay == "l"? "landscape" : "portrait";
        $this->title = "Dokument";
    }

    public function title($txt=""){
        $this->title = $txt;
        return $this;
    }

    public function section($section = []){
        $this->section[] = $section;
        return $this;
    }

    public function render(){
        $setup = $this;
        require_once __DIR__."/template/paper.php";        
    }
}