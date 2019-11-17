# Arène de combattants

L'objectif est de réaliser une arène de combattants.


## Fonctionnalités

- [x] Consultation des combattants
- [ ] Création d’un combattant
- [ ] Consultation d’un combattant
- [x] Modification/suppression d’un combattant
- [ ] Lancement d'un tour de combat

### Consultation des combattants

Un visiteur qui consulte la page d’accueil du site peut consulter la liste des combattantsde l'arène, ordonnés par date de création.Un combattant a au moins : un nom, une race,une force, une intelligence, un nombre de points de vie,une date de création, une date de modification, une date de décès.Les races possibles sont : Troll, Nain, Elfe

### Création d’un combattant

Sur la page d’accueil, un bouton redirige vers une page de création d’un nouveau combattant.

### Consultation d’un combattant

Depuis la liste des combattants, un clic sur le nom de l'un d'entre eux permet de l’afficher.Àlasuite de celui-ci, la liste de ses combats est aussi visible, classée par ordre de date.

### Modification/suppression d’un combattant

Sur la page de consultation d’un combattant, il est possible d’accéder àsa modification ou àsa suppression. Lorsque l'ontente de supprimer un combattant, un pop-updemande de confirmer l'action

### Lancement d'un tour de combat

Vous pouvez à tout moment démarrer un tour. Lors de ce tour, chaque combattant se voit attribuer un adversaire aléatoirement. Un type de zone est également tiré aléatoirement au sort (désert, forêt, prairie). Une phase de résolution descombatsest lancée et résumée à l'écran. Par exemple :

```
Combat n°456, opposant Thorin (14PV) à Helmut (15PV) :
* Helmut frappe le premier, il inflige 6 PV à Thorin
* Thorin inflige 3PV à Helmut
* ....
* Helmut tue Thorin, en lui infligeant 5 PV. Il remporte le match
```

Les caractéristiques des personnages ont une incidence sur la résolution des combats :

_**La Force**_

La force sert à connaître le nombre de points de vie que perd l'adversaire lorsd'une attaque, à savoir Force/4. Par exemple, un nain ayant une force de 16 infligera 4 points de vie à chaque attaque.
La force de base est fixée à 10. Des multiplicateurs sont attribués :
* Aux Nains : random entre 1,5 et 2.
Prendre systématiquement l'entier supérieur

_**L'intelligence**_

Elle sert à savoir qui attaquera le premier. En cas d'égalité, le sort, toujours cruel désignera celui qui ouvrira les hostilités.
L'intelligence de base est fixée à 10. Des multiplicateurs sont attribués :
* Aux Elfes : random entre 1,5 et 2.
Prendre systématiquement l'entier supérieur

_**Les points de vie (ou PV)**_

Jauge de vie des Combattants. Un combattant à 0 est définitivement décédé.
Les PV de base sont fixés à 50. Des multiplicateurs sont attribués :
* Aux Elfes : random entre 1,5 et 2,5
* Aux Trolls : random entre 2,3 et 3
Prendre systématiquement l'entier supérieur

Les types de zonesont une influence sur les combats :
* Les Trolls perdent 20% de leurs PV dans les déserts
* Les Elfes ont un bonus de Force (+3) dans la forêt, tandis que les nains ont un malus de Force (-2).
* Dans la prairie, lesnains ont un bonus de Force (+4)et les Trolls également (+2)

Un combattant mort ne peut plus participer aux combats suivants. Les survivants se voient régénérer leur stock de PV. Si à la fin d'un combat, un seul combattant vivant reste, il est affublé de l'étiquette "Vainqueur de tournoi" et voit son stock de PV augmenté de 10.

### Consultation des combats

De même que pour les combattants, on peut voir la liste des combats passés, qui ils impliquaient et leur déroulé...

### API

Vous construirezune api qui permettrade retourner en JSON :
* La liste des combattants
* La liste des combats effectués
* Un combattant en fonction de son id
* Un combat en fonction de son id

Attention, certaines informations ne sont pas utiles à renvoyer(le slug par exemple)

### SEO(Search Engine Optimization)

Pour améliorer le référencement, il est recommandéde faire les modifications suivantessur l'affichage des combattants:

* Chaque combattantest accessible non pas via son id, mais via son slug. Le slugd’un combattantest le nomde celui-ci mis sous forme d’url. Par exemple pourle combattant ayant pour nom"Thorin Oakenshield, fils de Balin"auraitpour slug quelque chose comme : "thorin-oakenshield-fils-de-balin". Ilfaudra donc aussi vous assurer que chaque combattanta un slug différent ;
* Le titre (balise title) de la page d’un combattanta pour valeur le nom du combattant.

### Image associée au combattants

Ajoutez une image aux combattants.

### Bonus

Prendre aussi en compte les caractères accentués dans le nomdes combattants.
