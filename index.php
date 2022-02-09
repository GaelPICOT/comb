<?php
session_start();
require "combination.php";
require "base.php";
print(pageHeader());

if (!array_key_exists("base", $_POST) && !array_key_exists("value1", $_POST)):
?>
<h2>Paramètre du jeu</h2>
<form action="" method="POST">
    <div>
        <label for="base">Valeur maximum de chaque élément:</label>
        <input type="number" name="base" value="10" id="base" min="2" max="10">
    </div>
    <div>
        <label for="elementNumber">Nombre d'éléments:</label>
        <input type="number" name="elementNumber" value="6" id="elementNumber" min="2" max="9">
    </div>
    <button type="submit">jouer</button>
</form>
<?php
else:
    if (array_key_exists("base", $_POST) && array_key_exists("elementNumber", $_POST)) {
        $secretCombination = new IntCombination($_POST['elementNumber'], $_POST['base']);
        $_SESSION['secretCombination'] = serialize($secretCombination);
    } else {
        $secretCombination = unserialize($_SESSION['secretCombination']);
        $guess = $secretCombination->extractValue($_POST);
        $score = $secretCombination->compare($guess);
    }

    if ($score==$secretCombination->getWinningScore()) :
        session_destroy();
        $_SESSION['startingGame']=false;
    ?>
    <h2>Vous avez gagner</h2>
    Bravo!
    <?php
    else:
    ?>
    <?php if (array_key_exists("value1", $_POST)): ?>
    <h2>nombre de valeur exact = <?= $score ?></h2>
    <?php endif; ?>
    <form action="" method="POST">
        <?php
        for($i=0; $i<$secretCombination->getElementNumber(); $i++):
        ?>
        <div>
            <label for="value<?= $i ?>">Valeur <?= $i+1 ?>:</label>
            <input type="number" name="value<?= $i ?>" value="<?= $_POST["value".$i]?$_POST["value".$i]:0 ?>" id="value<?= $i ?>" min="0" max="<?= $secretCombination->getBase()-1 ?>">
        </div>
        <?php endfor; ?>
    <button type="submit">tester</button>
    </form>

    <?php
    endif;
endif;
print(pageFooter());
