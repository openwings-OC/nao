# nao
## How To Issues
### Liste des tags
1. intégration
> Tous ce qui concerne la mise en forme de la page
2. users
> Tous ce qui concerne FOSUSerBundle et les pages utilisateurs
3. taxref
> Tous ce qui concerne la base de données TAXREF
4. observations
>Tous ce qui concerne les observations
5. forms
> Les formulaires
6. css
> Le css
7. js
> Le javascript
8. php
> Le code php

Importation de la TAXREF :
1. S'assurer de bien disposer du fichier TAXREF renommé 'TAXREF10.0.csv' dans le dossier nao
2. Avoir auparavant créé la base et l'avoir mise à jour (doctrine:schema:update --force)
3. Lancer la commande php bin/console import:csv
4. Et voila!