
<h1> Article management </h1>

<table>
    <tr>
       <th>Article</th>
       <th>Id</th>
       <th>Command</th>
    </tr>
    <?php foreach($data as $article) {?>
    <tr>
        <td><?= $article->title()->valueToString() ?></td>
        <td><?= $article->title()->valueToString() ?></td>
        <td>
            button 1
            
        </td>
    </tr>
    <?php } ?>
</table>

