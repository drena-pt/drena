<?php
echo "uploads/".$_FILES['filo']['name'];
if (move_uploaded_file($_FILES['filo']['tmp_name'], "uploads/".basename($_FILES['filo']['name']))){
    echo "\nSucesso!";
} else {
    echo "\nNão deu";
}
?>