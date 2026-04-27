# gestion_licence
Projet final symfony de BTS2 'Gestion licence"

Contexte : Cette application est notre projet final de deuxième année de BTS. L’application a pour objectif de centraliser et faciliter la gestion du planning des interventions pédagogiques d’une promotion de licence. Elle est destinée à l’équipe pédagogique et administrative chargée d’organiser les enseignements, de planifier les cours et de coordonner les intervenants qui interviennent tout au long de l’année scolaire.
L’application : Cette application permet globalement de structurer l’ensemble des enseignements sous forme de blocs, modules et périodes de cours, gérer les intervenants et leurs disponibilités, planifier les différentes interventions (cours, ateliers, projets, conférences, évaluations, etc.) sur l’année, offrir une vision claire, cohérente et mise à jour du planning de la seule promotion concernée, éviter les conflits d’horaires ou les chevauchements d’interventions, remplacer ou compléter les outils traditionnels (tableurs, échanges e-mail) par une solution centralisée, fiable et plus efficace.
L’application comporte donc plusieurs pages dont une page de connexion, des pages de calendrier, de gestion d’interventions et du corps enseignant, et des pages de gestion de modules, de blocs d’enseignement, de l’année scolaire et des types d’interventions.

Fonctionnement/Equipe : 
Nous étions en groupe de 3, nous avons utilisé Discord pour communiquer.
Notre méthode de travail est la méthode AGILE, nous avions réparti nos tâches en sprints où chaque semaine, nous nous concentrions sur les tâches du sprint et à la fin nous faisions un bilan du sprint
Technologies :
PHP/ CSS / SQL / Symfony / Tailwind

Installation du projet et Démarrage :
1.Prérequis
PHP ( version 8.5 ou supérieure recommandée )
Composer 
Node.js  
Symphony CLI
Base de donnée (service MySQL via WAMP ou XAMPP)

Installation du projet et des dépendances
Clonez le dépôt et installez les dépendances PHP :
Récupérer le code via git clone  https://github.com/votre-utilisateur/votre-projet.git
Lancer composer install pour installer toutes les bibliothèques Symfony
lancer npm install puis npm run dev pour compiler le CSS et le JavaScript.

Configuration de la base de donnée
Assurez-vous que MySQL  est lancé sur votre WAMP/XAMPP.
Créer ou modifier  .env.local puis modifiez la ligne  DATABASE_URL = " mysql://root:@127.0.0.1:3306/nom_de_votre_bd?serverVersion=8.0&charset=utf8mb4" 

Générez la structure de la base de donnée :
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
Si vous avez des données de test : php bin/console doctrine:fixtures:load

Lancement de l’application :
 Lancer la commande symfony server:start
ouvrir le navigateur à l’adresse http://127.0.0.1:8000.