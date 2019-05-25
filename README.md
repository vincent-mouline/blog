# blog
Symfony Quests

# 15 - Symfony : Introduction aux “Services”
 

Appeler la génération de Slug
Dans ce challenge, tu dois compléter l'algo de la méthode generate() pour que tous les caractères spéciaux soient traités :

à, ç, etc. deviennent a, c, etc.;
!, apostrophes et autres ponctuations sont supprimées;
les espaces en début et fin de chaînes sont supprimés;
il n'y a pas plusieurs - successifs;
....
Une fois que l'algo de ton service est complet, fais en sorte que celui-ci soit utilisé à toutes les étapes de création et d'édition d'un article :

Lors de l'ajout d'un article depuis un formulaire,
Lors de l'ajout d'un article depuis les fixtures,
Lors de l'édition d'un article.
Critères de validation
Le service est appelé à chaque niveau de l’application où il y a un ajout/modification d'article.
Le service Slugify créé auparavant avec une méthode generate(), permet de générer un slug à partir d'une chaîne de caractères.
L'ajout de l'article : "PHPStorm, l'éditeur de code pour PHP à tester !" donne le slug "phpstorm-lediteur-de-code-pour-php-a-tester".
Le changement du titre de l'article précédent en "PHPStorm, l'éditeur de code pour PHP idéal !" donne "phpstorm-lediteur-de-code-pour-php-ideal".
