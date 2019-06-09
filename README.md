# blog
Symfony Quests

# 18  Validation d’un article

Dans la quête, tu as vu des exemples pour configurer une validation sur les catégories. Maintenant, il faut que tu prennes le réflexe d’ajouter systématiquement des contraintes de validation sur tous les champs de toutes tes entités qui en ont besoin. Dans le cadre du challenge, tu vas configurer ton entité Article afin qu’elle suive ces règles simples de validation :

Pour tester les cas d’erreur côté serveur, il faut enlever les attributs bloquants dans le HTML du formulaire (les maxLength et autre required) afin de “forcer” l’erreur. Utilise la console du navigateur pour faire cela ;-)

Une erreur appropriée apparaît au niveau de ton formulaire si un titre vide ou un titre > 255 caractères est envoyé au serveur.

Le message “ce titre existe déjà” doit apparaître dans ton formulaire, si le titre saisi existe déjà pour un autre article
Une erreur appropriée apparaît au niveau de ton formulaire si un contenu d’article vide est envoyé au serveur.

Si le content de l’article contient le mot “digital”, le message d’erreur “en français, il faut dire numérique” apparaît à la place.
