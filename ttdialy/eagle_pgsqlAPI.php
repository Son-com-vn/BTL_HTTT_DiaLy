<?php
    if(isset($_POST['functionname']))
    {
        $paPDO = initDB();
        $paSRID = '4326';
        $paPoint = $_POST['paPoint'];
        $functionname = $_POST['functionname'];
        
        $aResult = "null";
        if ($functionname == 'getGeoEagleToAjax')
            $aResult = getGeoEagleToAjax($paPDO, $paSRID, $paPoint);
        else if ($functionname == 'getInfoEagleToAjax')
            $aResult = getInfoEagleToAjax($paPDO, $paSRID, $paPoint);
        
        echo $aResult;
    
        closeDB($paPDO);
    }

    function initDB()
    {
        // Kết nối CSDL
        $paPDO = new PDO('pgsql:host=localhost;dbname=TestCSDL;port=5432', 'postgres', 'sa');
        return $paPDO;
    }
    function query($paPDO, $paSQLStr)
    {
        try
        {
            // Khai báo exception
            $paPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Sử đụng Prepare 
            $stmt = $paPDO->prepare($paSQLStr);
            // Thực thi câu truy vấn
            $stmt->execute();
            
            // Khai báo fetch kiểu mảng kết hợp
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            
            // Lấy danh sách kết quả
            $paResult = $stmt->fetchAll();   
            return $paResult;                 
        }
        catch(PDOException $e) {
            echo "Thất bại, Lỗi: " . $e->getMessage();
            return null;
        }       
    }
    function closeDB($paPDO)
    {
        // Ngắt kết nối
        $paPDO = null;
    }
    function getGeoEagleToAjax($paPDO,$paSRID,$paPoint)
    {
        //echo $paPoint;
        //echo "<br>";
        $paPoint = str_replace(',', ' ', $paPoint);
        //echo $paPoint;
        //echo "<br>";
        $strDistance = "ST_Distance('".$paPoint."',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('".$paPoint."',ST_AsText(geom))) from eagle";
        $mySQLStr = "SELECT ST_AsGeoJson(geom) as geo from eagle where ".$strDistance." = (".$strMinDistance.") and ".$strDistance." < 0.05";
        //echo $mySQLStr;
        //echo "<br><br>";
        $result = query($paPDO, $mySQLStr);
        
        if ($result != null)
        {
            // Lặp kết quả
            foreach ($result as $item){
                return $item['geo'];
            }
        }
        else
            return "null";
    }
    function getInfoEagleToAjax($paPDO,$paSRID,$paPoint)
    {
        //echo $paPoint;
        //echo "<br>";
        $paPoint = str_replace(',', ' ', $paPoint);
        //echo $paPoint;
        //echo "<br>";
        $strDistance = "ST_Distance('".$paPoint."',ST_AsText(geom))";
        $strMinDistance = "SELECT min(ST_Distance('".$paPoint."',ST_AsText(geom))) from eagle";
        $mySQLStr = "SELECT gid, long_x_dd, lat_y_dd from eagle where ".$strDistance." = (".$strMinDistance.") and ".$strDistance." < 0.05";
        //echo $mySQLStr;
        //echo "<br><br>";
        $result = query($paPDO, $mySQLStr);
        
        if ($result != null)
        {
            $resFin = '<table>';
            // Lặp kết quả
            foreach ($result as $item){
                $resFin = $resFin.'<tr><td>Mã đối tương: '.$item['gid'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Kinh độ: '.$item['long_x_dd'].'</td></tr>';
                $resFin = $resFin.'<tr><td>Vĩ độ: '.$item['lat_y_dd'].'</td></tr>';
                break;
            }
            $resFin = $resFin.'</table>';
            return $resFin;
        }
        else
            return "null";
    }
?>