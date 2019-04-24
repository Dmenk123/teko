<table border="1" width="100%" style="border-collapse: collapse;font-size:8px;">
    <thead>
        <tr>
            <th width="2%">No</th>
            <th>Nama</th>

            <?php for($i=1;$i<=$hari;$i++) 
            { 
                ?>
      
                <th width="2%"><?=$i?></th>
                
                <?php 
            } 
            ?>

            <th width="5%">Jumlah Hari</th>
        </tr>
    </thead>
    <tbody>
    
    <?php
    $no = 1;

    for ($i = 0; $i < count($datanya); $i++) 
    {    
        $a = 1;
        if($i == 0 || $datanya[$i] == '|')   
        {
            ?>
        <tr>
            <th><?= $a ?></th>
            <?php
        } 

        if($datanya[$i] != '|')  
        {
            ?>
            <td><?= $datanya[$i] ?></td>
            <?php
        }
        else if($datanya[$i] == '|')  
        {
            ?>
        </tr>
            <?php
        }
        $a++;
    }
    ?>
    
    </tbody>
</table>