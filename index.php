function CreateAvatar($sName = "Example", $iSize = 250, $bUseShadow = false, $arColorBG = array(95, 145, 245), $arColorText = array(240, 240, 240), $bUseRandomColor = false){
    $sPathToFile = "";//путь к сохраненной картинке
    $iPositionCenter = (int)($iSize / 2);   //центр изображения
    $iSizeFigure = (int)$iSize;//используем полный размер изображения
    $arColors = array("RED" => "95", "GREEN" => 145, "BLUE" => 245);

    $image = imagecreate($iSize, $iSize);
    $colorBGGeneral = imagecolorallocate($image, 225, 225, 225);
    imagecolortransparent($image, $colorBGGeneral);

    //используем тень?
    if($bUseShadow === true){
        $iSizeFigure = (int)($iSize / 100 * 90);//используем 9/10 от полной картинки
        $iSizeShadow = (int)($iSize - $iSizeFigure);//размер тени
        $iShadowPartsCount = 10;//количество частей тени
        $nShadowPartSize = $iSizeShadow / $iShadowPartsCount;//размер 1й части тени
        for($i = 0; $i < $iShadowPartsCount; $i++){
            $iSizeShadowCircle = $iSize - ($i * $nShadowPartSize);
            $iOpacity = 127 - ((127 - 5) / $iShadowPartsCount) * $i;
            $colorBGFigure = imagecolorallocatealpha($image, 95, 95, 95, $iOpacity);
            imagefilledarc($image, $iPositionCenter, $iPositionCenter, $iSizeShadowCircle, $iSizeShadowCircle,  0, 360, $colorBGFigure, IMG_ARC_PIE );
        }
    }

    //рисуем фон
    list($arColors["RED"], $arColors["GREEN"], $arColors["BLUE"]) = $arColorBG;

    if($bUseRandomColor === true){//если используется случайный цвет
        foreach($arColors as &$iColor){
            $iColor = rand(0, 255);
        }
    }
    $colorBGFigure = imagecolorallocate($image, $arColors["RED"], $arColors["GREEN"], $arColors["BLUE"]);
    imagefilledarc($image, $iPositionCenter, $iPositionCenter, $iSizeFigure, $iSizeFigure,  0, 360, $colorBGFigure, IMG_ARC_PIE );

    //выводим текст
    if(empty($sName)){
        $sName = "UN";
    }else{
        $arWords = explode(" ", $sName);
        $iCountWords = count($arWords);
        if($iCountWords > 1){
            $sName = $arWords[0][0].$arWords[1][0];//берем первые буквы первых двух слов
        }else{
            $sName = $arWords[0].$arWords[1];//берем первые две буквы
        }
    }
    $sText = mb_strtoupper($sName);

    $sFontName = "cousine.ttf";
    $iFontSize = (int)($iSizeFigure / 2);

    list($arColors["RED"], $arColors["GREEN"], $arColors["BLUE"]) = $arColorText;

    if($bUseRandomColor === true){//если используется случайный цвет
        foreach($arColors as &$iColor){
            $iColor = rand(0, 255);
        }
    }
    $colorText = imagecolorallocate($image, $arColors["RED"], $arColors["GREEN"], $arColors["BLUE"]);

    $iTextPositionX = (int)($iPositionCenter - ($iFontSize / 1.3));
    $iTextPositionY = (int)($iPositionCenter + ($iFontSize / 3));

    imagefttext($image, $iFontSize, 0, $iTextPositionX, $iTextPositionY, $colorText, $sFontName, $sText);

    //создаем файл и уничтожаем объект изображения
    $sFileName = "icon_".time().".png";
    $sPathToFile = explode($_SERVER["HTTP_HOST"], __DIR__)[1]."/".$sFileName;//путь к файлу
    imagepng($image, __DIR__."/".$sFileName);
    imagedestroy($image);
    return $sPathToFile;
}
