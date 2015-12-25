<?php

error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('error_reporting',  E_ALL);


	   
	   
function text2image($text, $width, $margin, $size, $font, $align, $stroke, $bg, $color, $scolor, $out) {
	
	
	$text=html_entity_decode($text);

        $lines = array();
	
	//break up the text on the line
	$imageForMetric = new Imagick();
	$drawForMetric = new ImagickDraw();
        
	$drawForMetric->setFont($font);
	$drawForMetric->setFontSize($size);
       
        $explicitLines = preg_split('/\n|\r\n?/', $text);
	$key=0;
        foreach ($explicitLines as $line) {
            $words = explode(" ", $line);
            $line = $words[0];
            for ($i = 1; $i < count($words); $i++) {
		
                if ($imageForMetric->queryFontMetrics($drawForMetric, $line." ".$words[$i])['textWidth'] >= $width-$margin*2) {
		    $key++;
                    $lines[$key]['text'] = $line;
		    
                    $line = $words[$i];
                } else {
                    $line .= " ".$words[$i];
                }
            }
	    $key++;
            $lines[$key]['text'] = $line;
	    $lines[$key]['type'] = 'last';
        }
	
	$imageForMetric->destroy();
	$drawForMetric->destroy();
        
	
	//line height
	$lineHeightPx =  $size*1.5;
	
	//margin top and bottom (can leave $pad=$margin;)
	if($size<16) $pad=$size/2; elseif($size<26) $pad=$size/3; elseif($size<36) $pad=$size/4; else $pad=0; 
        
	//height image
	$textHeight = count($lines) * $lineHeightPx+$pad*2;
       
	$image = new Imagick();
	$draw = new ImagickDraw();
        
        $pixel = new ImagickPixel( '#'.$bg );
	$image->newImage($width, $textHeight+$lineHeightPx/2, $pixel);
	
	$draw->setFillColor('#'.$color);
	$draw->setFont($font);
	$draw->setFontSize($size);
        
	//for setTextAlignment
	if($align=='center') {
	    $alignNumber=2;
	}elseif($align=='right'){
	    $alignNumber=3;
	}else{
	    $alignNumber=1;
	}
	
	$draw->setTextAlignment($alignNumber);
	$draw->setTextEncoding('UTF-8');
        
	//x start position 
	if($align=='center') {
	    $xpos=$width/2;
	}elseif($align=='right'){
	    $xpos=$width-$margin;
	}else{
	    $xpos=$margin;
	}
        
	//text stroke
        if($stroke) {
	    $drawstroke = new ImagickDraw();
	    $drawstroke->setFillColor('#'.$scolor);
	    $drawstroke->setFont($font);
	    $drawstroke->setFontSize($size);
	    $drawstroke->setTextAlignment($alignNumber);
	    $drawstroke->setTextEncoding('UTF-8');	
	}
        
	//y start position
	$ypos=$lineHeightPx+$pad;
	$i=0;

	foreach($lines as $value=>$line){
	    if($align=='justify' && (!isset($lines[$value]['type']) || $lines[$value]['type']!='last')) {
		//justify for all lines (except last)
		    $line=trim($lines[$value]['text']);
		    
		    $sp=0;
		    $j=0;
		    $line=preg_replace("/  +/"," ",$line); 
		    $spaces=substr_count($line,' ');
		    
		    $word=explode(' ', $line);
		    
		    //word metrics
		    $metrics=0;
		    foreach($word as $w){
			$metrics=$metrics+$image->queryFontMetrics($draw, $w)['textWidth'];
		    }
		    
		    $spacewidth=($width-$margin*2-$metrics)/$spaces;
		    foreach($word as $w){
	
			
			if($w!==''){
			    $j++;
			    if($j>1) {
				$sp=$sp+$spacewidth;
			    }
			    
			  if($stroke) {
				//draw stroke
                                $ys=0;
                                
                                $xs=$stroke;
                                $d=(5 - $stroke * 4)/4;
                               
                                while($xs>=$ys) {
                                    $image->annotateImage($drawstroke, $xpos+$sp+$stroke, $ypos + $i*$lineHeightPx+$ys, 0, $w);
                                    $image->annotateImage($drawstroke, $xpos+$sp+$stroke, $ypos + $i*$lineHeightPx-$ys, 0, $w);
                                    $image->annotateImage($drawstroke, $xpos+$sp-$stroke, $ypos + $i*$lineHeightPx+$ys, 0, $w);
                                    $image->annotateImage($drawstroke, $xpos+$sp-$stroke, $ypos + $i*$lineHeightPx-$ys, 0, $w);
                                    $image->annotateImage($drawstroke, $xpos+$sp+$ys, $ypos + $i*$lineHeightPx+$stroke, 0, $w);
                                    $image->annotateImage($drawstroke, $xpos+$sp+$ys, $ypos + $i*$lineHeightPx-$stroke, 0, $w);
                                    $image->annotateImage($drawstroke, $xpos+$sp-$ys, $ypos + $i*$lineHeightPx+$stroke, 0, $w);
                                    $image->annotateImage($drawstroke, $xpos+$sp-$ys, $ypos + $i*$lineHeightPx-$stroke, 0, $w);
                                    
                                    if ($d < 0) {
                                            $d += 2 * $ys + 1;
                                    } else {
                                            $d += 2 * ($ys - $xs) + 1;
                                            $xs--;
                                    }
                                    $ys++;
                                }
                                
                                
                            }
			    //draw word
			    $image->annotateImage($draw, $xpos+$sp, $ypos + $i*$lineHeightPx, 0, $w);
			    
			    //space width
			    $sp=$sp+$image->queryFontMetrics($draw, $w)['textWidth'];
			}
	
			
		    }
		
	    } else {
		if($stroke) { //draw stroke
		    $ys=0;
		    
		    $xs=$stroke;
		    $d=(5 - $stroke * 4)/4;
		   
		    while($xs>=$ys) {
			$image->annotateImage($drawstroke, $xpos+$stroke, $ypos + $i*$lineHeightPx+$ys, 0, $lines[$value]['text']);
			$image->annotateImage($drawstroke, $xpos+$stroke, $ypos + $i*$lineHeightPx-$ys, 0, $lines[$value]['text']);
			$image->annotateImage($drawstroke, $xpos-$stroke, $ypos + $i*$lineHeightPx+$ys, 0, $lines[$value]['text']);
			$image->annotateImage($drawstroke, $xpos-$stroke, $ypos + $i*$lineHeightPx-$ys, 0, $lines[$value]['text']);
			$image->annotateImage($drawstroke, $xpos+$ys, $ypos + $i*$lineHeightPx+$stroke, 0, $lines[$value]['text']);
			$image->annotateImage($drawstroke, $xpos+$ys, $ypos + $i*$lineHeightPx-$stroke, 0, $lines[$value]['text']);
			$image->annotateImage($drawstroke, $xpos-$ys, $ypos + $i*$lineHeightPx+$stroke, 0, $lines[$value]['text']);
			$image->annotateImage($drawstroke, $xpos-$ys, $ypos + $i*$lineHeightPx-$stroke, 0, $lines[$value]['text']);
			
			if ($d < 0) {
				$d += 2 * $ys + 1;
			} else {
				$d += 2 * ($ys - $xs) + 1;
				$xs--;
			}
			$ys++;
		    }
		    
		    
		}
		//draw line
		$image->annotateImage($draw, $xpos, $ypos + $i*$lineHeightPx, 0, $lines[$value]['text']);
	    }
	    
	    $i++;
	}
	
	//save image to png
	$image->setCompressionQuality(100); 
	$image->setCompression(Imagick::COMPRESSION_NO);
	$image->setImageFormat('png');
	$image->writeImage('png24:'.$out);
	$image->destroy();

}

$text='Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';

text2image($text, //text
	   300, //width image
	   10, //left and right margin
           20, //text size (pt)
	   './font/Ubuntu.ttf', //ttf font file path
	   'justify', //align text [left, right, center, justify]
	   2, //stroke
           'ffffff', //background color
           'ffffff', //text color
           '000000', //stroke color
           './img/text2image.png' //output file path
           ); 

echo '<img src="./img/text2image.png?rand='.rand(0,1000).'"/>';
?>