<div class="table-responsive">
<table class="table table-hover text-left">
    <tbody>
        <tr>
            <td><b> Place </b></td>
            <td><b> Address </b></td>
            <td></td>
        </tr>
        
    <?php foreach ($burritos as $burrito): ?>
   
        <tr>
        <td> <?= $burrito["place"] ?> </td>
        <td> <?= $burrito["street"] . ", " . $burrito["city"] . ", " . $burrito["state"] . " " . $burrito["zip"] ?> </td>
        <td> 
            <form action="myburritos.php" method="POST"> 
            <input type="hidden" name="function" value="remove" />
            <input type="hidden" name="id" value="<?= $burrito['id'] ?>" />
            <button class="btn-sm" type="submit"> Remove </button> 
            </form>
        </td>
        </tr>
            
    <?php endforeach ?>
        
    </tbody>
</table>
</div>
