# Chronos

Chronos est une application open source permettant de calculer le temps passer sur une tache d'un projet. 

# Démarrer Chronos 
## Pré-requis 

- symfony 4.15.0
- php 7.4
- mariadb 
- docker 19.03

## Installation

Faire un git clone : ``` git clone https://github.com/e-guyot/tpViewer ```
Lancer la commande : ```docker-compose up --build -d```
Cet commande permet de lancer le projet avec sa base et son serveur local.

## Démarrage

Ensuite vous pouvez aller sur n'importe quelle navigateur et tapez : http://localhost:8080/
Accéder à la bdd en ligne de commande : ``` docker exec -it tpviewer_db_1 mysql --user=docker -p docker ```

## Développé avec 

* [Php Storm](https://www.jetbrains.com/fr-fr/phpstorm/) - Editeur de textes
* [Bootstrap](https://getbootstrap.com/) - Framework CSS (front-end)
* [Symfony](https://symfony.com/) - Framework PHP (back-end)

# Contributions
Si vous souhaitez contribuer, lisez le fichier [CONTRIBUTING.md](https://github.com/e-guyot/tpViewer/blob/master/CONTRIBUTING.md) pour savoir comment le faire.

# Auteurs

* **DIABY Lamine** _alias_ [@diabylamine](https://github.com/diabylamine)
* **GUYOT Estelle** _alias_ [@e-guyot](https://github.com/e-guyot)
* **JAEGLY Allan** _alias_ [@allanJGL](https://github.com/allanJGL)

Liste des [contributeurs](https://github.com/e-guyot/tpViewer/contributors) 

# License

It is placed under the terms of the MIT License.

Copyright (c) 2020 allanJGL, diabylamine, e-guyot

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
