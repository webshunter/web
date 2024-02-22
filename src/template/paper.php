<?php

class HtmlParser{
    private $html;
    function __construct($tag = "h1", $data = [])
    {
        $this->html = $this->$tag($data);
    }

    private function styles($data=[]){
        if(isset($data) && is_array($data)){
            $arr = [];
            foreach ($data as $key => $value) {
                $arr[] = "$key:$value;";
            }
            return join("",$arr);
        }else{
            return "";
        }
    }

    private function h1($data=[]){
        $h1 = "<h1";
        $h1 .= " style=\"".$this->styles($data["style"])."\" ";
        $h1 .= ">";
        $h1 .= $this->cekdata( $data["text"] );
        $h1 .= "</h1>";
        return $h1;
    }

    private function p($data=[]){
        $text = "<p";
        $text .= " style=\"".$this->styles($data["style"])."\" ";
        $text .= ">";
        $text .= $this->cekdata( $data["text"] );
        $text .= "</p>";
        return $text;
    }

    private function i($data=[]){
        $text = "<i";
        $text .= " style=\"".$this->styles($data["style"])."\" ";
        $text .= ">";
        $text .= $this->cekdata( $data["text"] );
        $text .= "</i>";
        return $text;
    }

    private function u($data=[]){
        $text = "<u";
        $text .= " style=\"".$this->styles($data["style"])."\" ";
        $text .= ">";
        $text .= $this->cekdata( $data["text"] );
        $text .= "</u>";
        return $text;
    }

    private function cekdata($data = null){
        return isset($data) ? $data : "";
    }

    private function thead($data=[]){
        if(isset($data) && is_array($data)){
            $html = [];
            foreach ($data as $key => $value) {
                $d = "<td";
                $d .= " style=\"".$this->styles($value["style"])."\" ";
                $d .= ">";
                $d .= $this->cekdata($value["text"]);
                $d .= "</td>";
                $html[] = $d;
            }
            return "<tr>".join("",$html)."</tr>";
        }else{
            return "";
        }
    }

    private function tbody($data=[]){
        if(isset($data) && is_array($data)){
            $html = [];
            foreach($data as $v){
                $html[] = $this->thead($v);
            }
            return join("", $html);
        }else{
            return "";
        }
    }

    private function table($data=[]){
        if(isset($data) && is_array($data)){
            $table = "<table style=\"width:100%;\"";
            $table .= isset($data["theme"]) ? " class=\"".$data["theme"]."\"":"";
            $table .= ">";
            $table .= "<thead>";
            $table .= $this->thead($data["head"]);
            $table .= "</thead>";
            $table .= "<tbody>";
            $table .= $this->tbody($data["body"]);
            $table .= "</tbody>";
            $table .= "</table>";
            return $table;
        }else{
            return "";
        }
    }

    public function result(){
        return $this->html;
    }
}

function renderHtml($tag="", $data=[]){
    $data = new HtmlParser($tag, $data);
    $r = $data->result();
    return $r;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title><?=$setup->title?></title>
    <link rel="stylesheet" type="text/css" href="/usp/assets/fonts/simple-line-icons/style.min.css">
    <style>
        @page { margin: 0 }
        body { margin: 0 }
        .sheet {
            margin: 0;
            overflow: hidden;
            position: relative;
            box-sizing: border-box;
            page-break-after: always;
        }

        /** Paper sizes **/
        body.A3               .sheet { width: 297mm; height: 419mm }
        body.A3.landscape     .sheet { width: 420mm; height: 296mm }
        body.A4               .sheet { width: 210mm; height: 296mm }
        body.A4.landscape     .sheet { width: 297mm; height: 209mm }
        body.A5               .sheet { width: 148mm; height: 209mm }
        body.A5.landscape     .sheet { width: 210mm; height: 147mm }
        body.letter           .sheet { width: 216mm; height: 279mm }
        body.letter.landscape .sheet { width: 280mm; height: 215mm }
        body.legal            .sheet { width: 216mm; height: 356mm }
        body.legal.landscape  .sheet { width: 357mm; height: 215mm }

        /** Padding area **/
        .sheet.padding-10mm { padding: 10mm }
        .sheet.padding-15mm { padding: 15mm }
        .sheet.padding-20mm { padding: 20mm }
        .sheet.padding-25mm { padding: 25mm }

        /** For screen preview **/
        @media screen {
        body { background: #e0e0e0 }
            .sheet {
                background: white;
                box-shadow: 0 .5mm 2mm rgba(0,0,0,.3);
                margin: 5mm auto;
            }
        }

        table td, table th{
            border-collapse: collapse;
        }

        .line tr{
            border-bottom:1px solid #aaa;
        }

        /** Fix for Chrome issue #273306 **/
        @media print {
            body.A3.landscape { width: 420mm }
            body.A3, body.A4.landscape { width: 297mm }
            body.A4, body.A5.landscape { width: 210mm }
            body.A5                    { width: 148mm }
            body.letter, body.legal    { width: 216mm }
            body.letter.landscape      { width: 280mm }
            body.legal.landscape       { width: 357mm }
        }
    </style>
    <style>
        @import  url('https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap');
         .gg-printer {
            background:
            linear-gradient(to left,
            currentColor 5px,transparent 0)
            no-repeat 0 10px/6px 2px,
            linear-gradient(to left,
            currentColor 5px,transparent 0)
            no-repeat 14px 10px/6px 2px,
            linear-gradient(to left,
            currentColor 5px,transparent 0)
            no-repeat 4px 4px/2px 2px;
            box-sizing: border-box;
            position: relative;
            display: block;
            transform: scale(var(--ggs,1));
            width: 24px;
            height: 14px;
            border: 2px solid transparent;
            border-bottom: 0;
            box-shadow:
            inset 0 2px 0,
            inset 2px 2px 0,
            inset -2px 2px 0,
            inset -2px 2px 0
            }

            .gg-printer::after,
            .gg-printer::before {
            content: "";
            display: block;
            box-sizing: border-box;
            position: absolute;
            width: 12px;
            border: 2px solid;
            left: 4px
            }

            .gg-printer::before {
            height: 6px;
            top: -4px
            }

            .gg-printer::after {
            height: 8px;
            top: 8px
            } 
        *{
            font-family: 'Roboto', sans-serif;
        }
        .print{
            position: fixed;
            display: inline-block;
            right: 20px;
            padding: 12px 10px;
            box-shadow: 0 0 20px #ddd;
            cursor: pointer;
            color: white;
            outline: none;
            border: none;
            border: none;
            border-radius: 10px;
            background: green;
            bottom: 20px;
            z-index: 999;
        }
        h1{
            font-size:14px;
        }
        h2{
            font-size:14px;
        }
        p{
            font-size:14px;
        }
        table{
            width:100%;
            border-collapse:collapse;
            font-size:14px;
        }
        .text-center{
            text-align:center;
        }
        .table thead{
            box-shadow: inset 0 0 1000px orange;
        }
        .table th,.table td{
            border: 1px solid #333333;
            font-size: 12px;
            padding: 3px;
        }
        @media print{
            button.print{
                display:none;
            }
            @page  {
                size: A4 <?=$setup->layout?>;
            }
        }
    </style>
</head>
<body class="A4 <?=$setup->layout?>">
    <button class="print" onclick="window.print()"><i class="gg-printer"></i></button>
    <?php foreach($setup->section as $section) : ?>
        <section class="sheet padding-10mm">
            <?php foreach($section as $key => $datasection) :?>
                <?php foreach($datasection as $key1 => $datasection1) :?>
                    <?= renderHtml($key1, $datasection1) ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </section>
    <?php endforeach; ?>
</body>
</html>
